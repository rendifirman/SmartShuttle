<?php
// app/Http/Controllers/Admin/JadwalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.jadwal', [
            'title' => 'Master Data - Jadwal',
            'pageTitle' => 'Jadwal'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jadwal', [
            'title' => 'Tambah Jadwal',
            'pageTitle' => 'Tambah Jadwal'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Simpan data jadwal ke database
        // Di sini Anda bisa menambahkan logika penyimpanan
        // return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
        
        // Untuk sementara, redirect ke index
        return redirect()->route('admin.jadwal');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil data jadwal berdasarkan ID
        // return view('admin.jadwal.show', compact('jadwal'));
        
        // Untuk sementara, redirect ke index
        return redirect()->route('admin.jadwal');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil data jadwal berdasarkan ID
        // return view('admin.jadwal.edit', compact('jadwal'));
        
        // Untuk sementara, redirect ke index
        return redirect()->route('admin.jadwal');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Update data jadwal
        // return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
        
        // Untuk sementara, redirect ke index
        return redirect()->route('admin.jadwal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Hapus data jadwal
        // return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus');
        
        // Untuk sementara, redirect ke index
        return redirect()->route('admin.jadwal');
    }
}