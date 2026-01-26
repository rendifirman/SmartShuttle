<?php

namespace App\Http\Controllers\Admin;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ArtikelController extends Controller
{
    /**
     * Menampilkan daftar artikel untuk ADMIN
     * Gunakan view customer yang sudah ada
     */
  public function index(Request $request)
{
    // Query dengan filter
    $query = Artikel::query();
    
    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }
    
    if ($request->filled('penulis')) {
        $query->where('penulis', 'like', '%' . $request->penulis . '%');
    }
    
    if ($request->filled('status')) {
        if ($request->status == 'publik') {
            $query->where('status', true);
        } elseif ($request->status == 'draft') {
            $query->where('status', false);
        }
    }
    
    if ($request->filled('tanggal_dari')) {
        $query->whereDate('tanggal_publikasi', '>=', $request->tanggal_dari);
    }
    
    if ($request->filled('tanggal_sampai')) {
        $query->whereDate('tanggal_publikasi', '<=', $request->tanggal_sampai);
    }
    
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('judul', 'like', '%' . $search . '%')
              ->orWhere('konten', 'like', '%' . $search . '%')
              ->orWhere('penulis', 'like', '%' . $search . '%');
        });
    }
    
    $artikels = $query->orderBy('created_at', 'desc')->paginate(9);
    
    // Filter lists untuk dropdown
    $kategoriList = Artikel::select('kategori')->distinct()->pluck('kategori')->toArray();
    $penulisList = Artikel::select('penulis')->distinct()->pluck('penulis')->toArray();
    
    // Hitung statistik
    $totalArtikel = Artikel::count();
    $artikelAktif = Artikel::where('status', true)->count();
    $artikelDraft = Artikel::where('status', false)->count();
    
    // ❌ SALAH: Arahkan ke customer view
    // return view('customer.artikel_index', [
    //     'articles' => $artikels,
    //     'kategoriList' => $kategoriList,
    //     'isAdmin' => true
    // ]);
    
    // ✅ BENAR: Arahkan ke admin view
    return view('admin.artikel', [  // atau 'admin.artikel.index' jika sesuai struktur
        'artikels' => $artikels,
        'kategoriList' => $kategoriList,
        'penulisList' => $penulisList,
        'totalArtikel' => $totalArtikel,
        'artikelAktif' => $artikelAktif,
        'artikelDraft' => $artikelDraft
    ]);
}

    /**
     * Menampilkan form create
     */
    public function create()
    {
        // Pastikan Anda punya view ini
        return view('admin.artikel-create');
    }

    /**
     * Menyimpan artikel baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'kategori' => 'required',
            'penulis' => 'required',
            'tanggal_publikasi' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Konversi status
        $status = $request->has('status') ? true : false;
        
        // Upload gambar
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikel', 'public');
        }
        
        // Generate slug
        $slug = Str::slug($request->judul) . '-' . time();
        
        Artikel::create([
            'judul' => $request->judul,
            'slug' => $slug,
            'konten' => $request->konten,
            'gambar' => $gambarPath,
            'kategori' => $request->kategori,
            'penulis' => $request->penulis,
            'tanggal_publikasi' => $request->tanggal_publikasi,
            'status' => $status,
            'dilihat' => 0,
        ]);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        // Pastikan Anda punya view ini
        return view('admin.artikel-edit', compact('artikel'));
    }

    /**
     * Update artikel
     */
    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'kategori' => 'required',
            'penulis' => 'required',
            'tanggal_publikasi' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Konversi status
        $status = $request->has('status') ? true : false;
        
        // Data yang akan diupdate
        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'penulis' => $request->penulis,
            'tanggal_publikasi' => $request->tanggal_publikasi,
            'status' => $status,
        ];
        
        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            
            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('artikel', 'public');
            $data['gambar'] = $gambarPath;
        }
        
        // Handle hapus gambar jika checkbox dicentang
        if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
            if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            $data['gambar'] = null;
        }
        
        // Update slug jika judul berubah
        if ($artikel->judul != $request->judul) {
            $data['slug'] = Str::slug($request->judul) . '-' . time();
        }
        
        // Update artikel dengan data yang sudah disiapkan
        $artikel->update($data);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menampilkan detail artikel (untuk admin)
     */
    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        // Jika tidak punya view admin.artikel.show, gunakan customer view
        // return view('customer.artikel_detail', compact('artikel'));
        
        // Atau buat view sederhana untuk admin
        return view('admin.artikel.show', compact('artikel'));
    }

    /**
     * Menghapus artikel
     */
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($artikel->gambar) {
            Storage::disk('public')->delete($artikel->gambar);
        }
        
        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}