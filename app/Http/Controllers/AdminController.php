<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Master Data Methods
    public function profilePerusahaan()
    {
        return view('admin.profileperusahaan');
    }

    public function pusat()
    {
        return view('admin.pusat');
    }

    public function cabangPerusahaan()
    {
        return view('admin.cabangperusahaan');
    }

    public function armada()
    {
        return view('admin.armada');
    }

    public function driver()
    {
        return view('admin.driver');
    }

    public function pegawai()
    {
        return view('admin.pegawai');
    }

    public function rute()
    {
        return view('admin.rute');
    }

    // Transaksi Methods
    public function tiketPerjalanan()
    {
        return view('admin.tiket-perjalanan');
    }

    public function tiketArmada()
    {
        return view('admin.tiket-armada');
    }

    // SmartSend Methods
    public function smartsendTiket()
    {
        return view('admin.smartsend-tiket');
    }

    public function smartsendPerjalanan()
    {
        return view('admin.smartsend-perjalanan');
    }

    public function smartsendArmada()
    {
        return view('admin.smartsend-armada');
    }

    // SmartRent Method
    public function smartrent()
    {
        return view('admin.smartrent');
    }

    // Laporan Method
    public function laporan()
    {
        return view('admin.laporan');
    }

    // Pengaturan Methods
    public function user()
    {
        return view('admin.user');
    }

    public function menu()
    {
        return view('admin.menu');
    }
}
