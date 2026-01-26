<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    /**
     * Menampilkan daftar artikel untuk customer
     */
    public function index(Request $request)
    {
        $user = session()->get('user');
        
        $query = Artikel::where('status', true)
            ->whereNotNull('tanggal_publikasi')
            ->where('tanggal_publikasi', '<=', now());

        // Filter berdasarkan kategori jika ada
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('konten', 'like', '%' . $search . '%')
                  ->orWhere('penulis', 'like', '%' . $search . '%');
            });
        }

        $articles = $query->orderBy('tanggal_publikasi', 'desc')->paginate(9);
        
        $kategoriList = Artikel::where('status', true)
            ->whereNotNull('tanggal_publikasi')
            ->where('tanggal_publikasi', '<=', now())
            ->select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->toArray();
        
        return view('customer.artikel_index', compact('articles', 'user', 'kategoriList'));
    }

    /**
     * Menampilkan detail artikel untuk customer
     */
    public function show($slug)
{
    $user = session()->get('user');
    
    $article = Artikel::where('slug', $slug)  // Ubah dari $artikel menjadi $article
        ->where('status', true)
        ->whereNotNull('tanggal_publikasi')
        ->where('tanggal_publikasi', '<=', now())
        ->firstOrFail();
    
    // Tambah jumlah dilihat
    $article->increment('dilihat');
    
    // Artikel terkait
    $relatedArticles = Artikel::where('kategori', $article->kategori)
        ->where('id', '!=', $article->id)
        ->where('status', true)
        ->whereNotNull('tanggal_publikasi')
        ->where('tanggal_publikasi', '<=', now())
        ->orderBy('tanggal_publikasi', 'desc')
        ->take(3)
        ->get();
    
    return view('customer.artikel_detail', compact('user', 'article', 'relatedArticles')); // Ubah di sini juga
}
    /**
     * Menampilkan artikel berdasarkan kategori
     */
    public function kategori($kategori)
    {
        $user = session()->get('user');
        
        $articles = Artikel::where('kategori', $kategori)
            ->where('status', true)
            ->whereNotNull('tanggal_publikasi')
            ->where('tanggal_publikasi', '<=', now())
            ->orderBy('tanggal_publikasi', 'desc')
            ->paginate(9);
        
        $kategoriNama = $kategori;
        
        // Kategori yang tersedia
        $kategoriList = Artikel::where('status', true)
            ->whereNotNull('tanggal_publikasi')
            ->where('tanggal_publikasi', '<=', now())
            ->select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->toArray();
        
        return view('customer.artikel_index', compact('user', 'articles', 'kategoriNama', 'kategoriList'));
    }
}