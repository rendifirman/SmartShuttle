// app/Http/Controllers/ArtikelController.php
<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Menampilkan daftar artikel
     */
    public function index(Request $request)
    {
        $user = session()->get('user');

        // Filter berdasarkan kategori jika ada
        $query = Artikel::aktif()->terbaru();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('konten', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        $articles = $query->paginate(9);

        // Ambil semua kategori untuk filter
        $kategoriList = Artikel::aktif()
            ->select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori')
            ->toArray();

        return view('customer.artikel_index', compact('user', 'articles', 'kategoriList'));
    }

    /**
     * Menampilkan detail artikel
     */
    public function show($slug)
    {
        $user = session()->get('user');

        $article = Artikel::aktif()->where('slug', $slug)->firstOrFail();

        // Tambah jumlah dilihat
        $article->increment('dilihat');

        // Artikel terkait (dengan kategori yang sama)
        $relatedArticles = Artikel::aktif()
            ->where('kategori', $article->kategori)
            ->where('id', '!=', $article->id)
            ->terbaru()
            ->limit(3)
            ->get();

        return view('customer.artikel_detail', compact('user', 'article', 'relatedArticles'));
    }

    /**
     * Menampilkan artikel berdasarkan kategori
     */
    public function kategori($kategori)
    {
        $user = session()->get('user');

        $articles = Artikel::aktif()
            ->where('kategori', $kategori)
            ->terbaru()
            ->paginate(9);

        $kategoriList = Artikel::aktif()
            ->select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori')
            ->toArray();

        return view('customer.artikel_kategori', compact('user', 'articles', 'kategoriList', 'kategori'));
    }
}
