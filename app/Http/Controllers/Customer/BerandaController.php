<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\Produk;
use App\Models\Testimoni;
use App\Models\Slider;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman beranda
     */
    public function index()
    {
        // Ambil data user dari session (jika ada login customer)
        $user = session()->get('user');

        // Slider untuk beranda
        $sliders = Slider::where('status', true)
                        ->orderBy('urutan', 'asc')
                        ->get();

        // Artikel terbaru untuk beranda (hanya yang aktif)
        $artikelTerbaru = Artikel::aktif()
            ->terbaru()
            ->take(6)
            ->get();

        // Artikel populer (berdasarkan dilihat)
        $artikelPopuler = Artikel::aktif()
            ->orderBy('dilihat', 'desc')
            ->take(4)
            ->get();

        // Produk terbaru (jika ada model Produk)
        $produkTerbaru = [];
        if (class_exists('App\Models\Produk')) {
            $produkTerbaru = Produk::where('status', true)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
        }

        // Testimoni (jika ada)
        $testimonis = [];
        if (class_exists('App\Models\Testimoni')) {
            $testimonis = Testimoni::where('status', true)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }

        // Kategori artikel (untuk menu/filter)
        $kategoriArtikel = KategoriArtikel::whereHas('artikels', function($query) {
                $query->aktif();
            })
            ->withCount(['artikels' => function($query) {
                $query->aktif();
            }])
            ->get();

        return view('customer.beranda', compact(
            'user',
            'sliders',
            'artikelTerbaru',
            'artikelPopuler',
            'produkTerbaru',
            'testimonis',
            'kategoriArtikel'
        ));
    }

    /**
     * Pencarian artikel
     */
    public function search(Request $request)
    {
        $user = session()->get('user');
        $keyword = $request->get('q');

        $artikel = Artikel::aktif()
            ->where(function($query) use ($keyword) {
                $query->where('judul', 'like', '%' . $keyword . '%')
                      ->orWhere('konten', 'like', '%' . $keyword . '%')
                      ->orWhere('kategori', 'like', '%' . $keyword . '%')
                      ->orWhere('penulis', 'like', '%' . $keyword . '%');
            })
            ->terbaru()
            ->paginate(9);

        return view('customer.artikel_search', compact('user', 'artikel', 'keyword'));
    }

    /**
     * Tentang kami
     */
    public function tentang()
    {
        $user = session()->get('user');
        return view('customer.tentang', compact('user'));
    }

    /**
     * Kontak
     */
    public function kontak()
    {
        $user = session()->get('user');
        return view('customer.kontak', compact('user'));
    }

    /**
     * FAQ
     */
    public function faq()
    {
        $user = session()->get('user');
        return view('customer.faq', compact('user'));
    }
}