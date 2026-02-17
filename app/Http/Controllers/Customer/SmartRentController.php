<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MProfilePerusahaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SmartRentController extends Controller
{
    /**
     * Halaman utama SmartRent
     */
    public function index()
    {
        $profile = MProfilePerusahaan::first();
        
        $vehicles = [
            [
                'id' => 1,
                'name' => 'Toyota Hiace Commuter',
                'type' => 'Shuttle',
                'image' => asset('images/shuttle1.jpeg'),
                'seats' => '12 Seat',
                'luggage' => '4 Koper',
                'fuel' => 'Bensin',
                'transmission' => 'Manual',
                'ac' => 'AC Double',
                'price' => 1200000,
                'price_formatted' => '1.200.000',
                'period' => '/hari',
                'available' => true,
                'description' => 'Mobil shuttle dengan kapasitas 12 penumpang, cocok untuk keluarga besar atau rombongan kecil.',
                'facilities' => ['AC', 'Audio System', 'Kursi Empuk', 'Safety Belt', 'P3K']
            ],
            [
                'id' => 2,
                'name' => 'Isuzu Elf Long',
                'type' => 'Shuttle',
                'image' => asset('images/shuttle2.jpeg'),
                'seats' => '18 Seat',
                'luggage' => '6 Koper',
                'fuel' => 'Solar',
                'transmission' => 'Manual',
                'ac' => 'AC Double',
                'price' => 1500000,
                'price_formatted' => '1.500.000',
                'period' => '/hari',
                'available' => true,
                'description' => 'Armada besar untuk rombongan besar dengan kenyamanan maksimal.',
                'facilities' => ['AC Double', 'Audio System', 'Kursi Reclining', 'Bagasi Luas', 'P3K', 'Karpet']
            ],
            [
                'id' => 3,
                'name' => 'Mitsubishi L300',
                'type' => 'Shuttle',
                'image' => asset('images/shuttle3.jpeg'),
                'seats' => '8 Seat',
                'luggage' => 'Besar',
                'fuel' => 'Solar',
                'transmission' => 'Manual',
                'ac' => 'AC',
                'price' => 800000,
                'price_formatted' => '800.000',
                'period' => '/hari',
                'available' => true,
                'description' => 'Ekonomis dan tangguh, cocok untuk kebutuhan sehari-hari dengan kapasitas barang besar.',
                'facilities' => ['AC', 'Audio', 'Kursi Standard', 'Bagasi Luas']
            ],
        ];
        
        return view('customer.smartrent', compact('profile', 'vehicles'));
    }
    
    /**
     * Halaman detail kendaraan (TIDAK PERLU LOGIN)
     */
    public function detail($id)
    {
        $vehicles = [
            1 => [
                'id' => 1,
                'name' => 'Toyota Hiace Commuter',
                'type' => 'Shuttle | Manual',
                'category' => 'Shuttle Bus',
                'image' => asset('images/shuttle1.jpeg'),
                'images' => [
                    asset('images/shuttle1.jpeg'),
                    asset('images/shuttle2.jpg'),
                    asset('images/shuttle3.jpg'),
                ],
                'seats' => '12 Seat',
                'luggage' => '4 Koper',
                'fuel' => 'Bensin',
                'transmission' => 'Manual',
                'ac' => 'Dual AC',
                'year' => '2022',
                'insurance' => 'All Risk',
                'driver' => 'Termasuk Sopir',
                'features' => ['AC', 'Audio System', 'LCD TV', 'Reclining Seats', 'Cool Box', 'USB Charger', 'WiFi', 'Safety Belt'],
                'price' => 1200000,
                'driver_price' => 150000,
                'price_formatted' => '1.200.000',
                'period' => '/hari',
                'min_rent' => '2',
                'available' => true,
                'description' => 'Toyota Hiace Commuter dengan kapasitas 12 penumpang sangat cocok untuk perjalanan grup atau keluarga. Dilengkapi dengan AC ganda, kursi yang nyaman, dan bagasi yang luas. Mobil ini dalam kondisi prima dan siap untuk perjalanan jarak jauh dengan kenyamanan maksimal.',
                'specifications' => [
                    ['label' => 'Merek', 'value' => 'Toyota'],
                    ['label' => 'Model', 'value' => 'Hiace Commuter'],
                    ['label' => 'Tahun', 'value' => '2022'],
                    ['label' => 'Warna', 'value' => 'Putih'],
                    ['label' => 'Plat Nomor', 'value' => 'B 1234 ABC'],
                    ['label' => 'Kapasitas Mesin', 'value' => '2500 cc'],
                    ['label' => 'Transmisi', 'value' => 'Manual'],
                    ['label' => 'AC', 'value' => 'Dual AC'],
                    ['label' => 'Sistem Audio', 'value' => 'Touchscreen + Bluetooth'],
                ]
            ],
            2 => [
                'id' => 2,
                'name' => 'Isuzu Elf Long',
                'type' => 'MPV | Manual',
                'category' => 'Shuttle Bus',
                'image' => asset('images/shuttle1.jpeg'),
                'images' => [
                    asset('images/shuttle1.jpeg'),
                ],
                'seats' => '18 Seat',
                'luggage' => '6 Koper',
                'fuel' => 'Solar',
                'transmission' => 'Manual',
                'ac' => 'Triple AC',
                'year' => '2021',
                'insurance' => 'All Risk',
                'driver' => 'Termasuk Sopir',
                'features' => ['AC', 'Audio System', 'LCD TV', 'Reclining Seats', 'Cool Box', 'USB Charger', 'WiFi', 'Safety Belt', 'Emergency Exit', 'First Aid Kit'],
                'price' => 1500000,
                'driver_price' => 200000,
                'price_formatted' => '1.500.000',
                'period' => '/hari',
                'min_rent' => '2',
                'available' => true,
                'description' => 'Isuzu Elf Long dengan kapasitas 18 penumpang, ideal untuk transportasi kelompok besar. Dilengkapi dengan 3 unit AC untuk kenyamanan seluruh penumpang. Mobil ini sangat cocok untuk perjalanan wisata, perusahaan, atau acara keluarga besar.',
                'specifications' => [
                    ['label' => 'Merek', 'value' => 'Isuzu'],
                    ['label' => 'Model', 'value' => 'Elf Long'],
                    ['label' => 'Tahun', 'value' => '2021'],
                    ['label' => 'Warna', 'value' => 'Silver'],
                    ['label' => 'Plat Nomor', 'value' => 'B 5678 DEF'],
                    ['label' => 'Kapasitas Mesin', 'value' => '3000 cc'],
                    ['label' => 'Transmisi', 'value' => 'Manual'],
                    ['label' => 'AC', 'value' => 'Triple AC'],
                    ['label' => 'Bagasi', 'value' => 'Ekstra Luas'],
                ]
            ],
            3 => [
                'id' => 3,
                'name' => 'Mitsubishi L300',
                'type' => 'Shuttle | Manual',
                'category' => 'Shuttle',
                'image' => asset('images/shuttle1.jpeg'),
                'images' => [
                    asset('images/shuttle1.jpeg'),
                ],
                'seats' => '8 Seat',
                'luggage' => 'Besar',
                'fuel' => 'Solar',
                'transmission' => 'Manual',
                'ac' => 'Single AC',
                'year' => '2020',
                'insurance' => 'TLO',
                'driver' => 'Termasuk Sopir',
                'features' => ['AC', 'Audio System', 'Bagasi Luas', 'Safety Belt', 'Power Steering'],
                'price' => 800000,
                'driver_price' => 100000,
                'price_formatted' => '800.000',
                'period' => '/hari',
                'min_rent' => '1',
                'available' => true,
                'description' => 'Mitsubishi L300 yang tangguh dan ekonomis, cocok untuk perjalanan dengan bagasi besar. Dengan kapasitas 8 penumpang, sangat ideal untuk keluarga atau tim kecil. Mobil ini sangat hemat bahan bakar dan mudah dalam perawatan.',
                'specifications' => [
                    ['label' => 'Merek', 'value' => 'Mitsubishi'],
                    ['label' => 'Model', 'value' => 'L300'],
                    ['label' => 'Tahun', 'value' => '2020'],
                    ['label' => 'Warna', 'value' => 'Hitam'],
                    ['label' => 'Plat Nomor', 'value' => 'B 9012 GHI'],
                    ['label' => 'Kapasitas Mesin', 'value' => '2400 cc'],
                    ['label' => 'Transmisi', 'value' => 'Manual'],
                    ['label' => 'AC', 'value' => 'Single AC'],
                    ['label' => 'Konsumsi BBM', 'value' => '10 km/liter'],
                ]
            ],
        ];
        
        $vehicle = $vehicles[$id] ?? null;
        
        if (!$vehicle) {
            abort(404);
        }
        
        $profile = MProfilePerusahaan::first();
        
        return view('customer.smartrent-detail', compact('profile', 'vehicle'));
    }
    
    /**
     * Halaman booking dengan filter (PERLU LOGIN)
     */
    public function booking(Request $request)
    {
        // CEK APAKAH USER SUDAH LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman booking.')
                ->with('redirect_url', route('smartrent.booking', $request->all()));
        }
        
        // Ambil parameter dari URL untuk filter
        $filterData = [
            'city' => $request->input('city', ''),
            'vehicle_type' => $request->input('vehicle_type', ''),
            'rent_date' => $request->input('rent_date', date('Y-m-d')),
            'duration' => $request->input('duration', 1),
            'capacity' => $request->input('capacity', ''),
            'vehicle_id' => $request->input('vehicle_id', '')
        ];
        
        $profile = MProfilePerusahaan::first();
        
        $allVehicles = [
            [
                'id' => 1,
                'name' => 'Toyota Hiace Commuter',
                'type' => 'Shuttle | Manual',
                'image' => asset('images/shuttle1.jpeg'),
                'seats' => '12 Seat',
                'luggage' => '4 Koper',
                'fuel' => 'Bensin',
                'price' => 1200000,
                'driver_price' => 150000,
                'price_formatted' => '1.200.000',
                'period' => '/hari',
                'available' => true,
                'description' => 'Mobil shuttle dengan kapasitas 12 penumpang, cocok untuk keluarga besar atau rombongan kecil. Dilengkapi dengan AC dan audio system.',
                'facilities' => ['AC', 'Audio System', 'Kursi Empuk', 'Safety Belt', 'P3K'],
                'driver_included_price' => 150000,
                'driver_included' => true
            ],
            [
                'id' => 2,
                'name' => 'Isuzu Elf Long',
                'type' => 'MPV | Manual',
                'image' => asset('images/shuttle1.jpeg'),
                'seats' => '18 Seat',
                'luggage' => '6 Koper',
                'fuel' => 'Solar',
                'price' => 1500000,
                'driver_price' => 200000,
                'price_formatted' => '1.500.000',
                'period' => '/hari',
                'available' => true,
                'description' => 'Armada besar untuk rombongan besar dengan kenyamanan maksimal. Cocok untuk tour, arisan, atau acara keluarga.',
                'facilities' => ['AC Double', 'Audio System', 'Kursi Reclining', 'Bagasi Luas', 'P3K', 'Karpet'],
                'driver_included_price' => 200000,
                'driver_included' => true
            ],
            [
                'id' => 3,
                'name' => 'Mitsubishi L300',
                'type' => 'Shuttle | Manual',
                'image' => asset('images/shuttle1.jpeg'),
                'seats' => '8 Seat',
                'luggage' => 'Besar',
                'fuel' => 'Solar',
                'price' => 800000,
                'driver_price' => 100000,
                'price_formatted' => '800.000',
                'period' => '/hari',
                'available' => true,
                'description' => 'Ekonomis dan tangguh, cocok untuk kebutuhan sehari-hari dengan kapasitas barang yang besar.',
                'facilities' => ['AC', 'Audio', 'Kursi Standard', 'Bagasi Luas'],
                'driver_included_price' => 100000,
                'driver_included' => true
            ],
        ];
        
        $filteredVehicles = $allVehicles;
        
        // Filter berdasarkan kota (jika ada)
        if ($filterData['city']) {
            $filteredVehicles = array_filter($filteredVehicles, function($vehicle) use ($filterData) {
                return true; // Untuk sementara selalu true, bisa ditambahkan logika filter kota nanti
            });
        }
        
        // Filter berdasarkan tipe kendaraan
        if ($filterData['vehicle_type']) {
            $filteredVehicles = array_filter($filteredVehicles, function($vehicle) use ($filterData) {
                return stripos($vehicle['type'], $filterData['vehicle_type']) !== false;
            });
        }
        
        // Filter berdasarkan kapasitas
        if ($filterData['capacity']) {
            $filteredVehicles = array_filter($filteredVehicles, function($vehicle) use ($filterData) {
                $capacityRange = $filterData['capacity'];
                $seats = intval(preg_replace('/\D/', '', $vehicle['seats']));
                
                if ($capacityRange === '1-4') return $seats >= 1 && $seats <= 4;
                if ($capacityRange === '5-7') return $seats >= 5 && $seats <= 7;
                if ($capacityRange === '8-12') return $seats >= 8 && $seats <= 12;
                if ($capacityRange === '13+') return $seats >= 13;
                return true;
            });
        }
        
        $filteredVehicles = array_values($filteredVehicles);
        
        // Cari kendaraan yang dipilih (jika ada vehicle_id)
        $selectedVehicle = null;
        if ($filterData['vehicle_id']) {
            foreach ($allVehicles as $vehicle) {
                if ($vehicle['id'] == $filterData['vehicle_id']) {
                    $selectedVehicle = $vehicle;
                    break;
                }
            }
        }
        
        // Return view dengan semua data
        return view('customer.smartrent-booking', [
            'filterData' => $filterData,
            'vehicles' => $filteredVehicles,
            'selectedVehicle' => $selectedVehicle,
            'profile' => $profile
        ]);
    }
    
    /**
     * Proses order langsung (PERLU LOGIN)
     */
    public function order(Request $request)
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pemesanan.')
                ->with('redirect_url', route('smartrent.booking'));
        }

        $request->validate([
            'vehicle_id' => 'required|integer',
            'service' => 'required|in:self-drive,with-driver',
            'duration' => 'required|integer|min:1|max:30',
            'rent_date' => 'required|date|after_or_equal:today',
        ]);

        $vehicle = $this->getVehicleById($request->vehicle_id);
        if (!$vehicle) {
            return redirect()->route('smartrent.booking')->with('error', 'Kendaraan tidak ditemukan.');
        }

        $vehiclePrice = $vehicle['price'] * $request->duration;
        $driverPrice = ($request->service == 'with-driver') ?
            ($vehicle['driver_included_price'] ?? 0) * $request->duration : 0;
        $totalPrice = $vehiclePrice + $driverPrice;

        session()->put('smartrent_order', [
            'vehicle_id' => $request->vehicle_id,
            'vehicle_name' => $vehicle['name'],
            'vehicle_type' => $vehicle['type'],
            'rent_date' => $request->rent_date,
            'duration' => $request->duration,
            'service' => $request->service,
            'vehicle_price' => $vehiclePrice,
            'driver_price' => $driverPrice,
            'total_price' => $totalPrice,
            'booking_code' => 'SR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6)),
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);

        $profile = MProfilePerusahaan::first();

        return view('customer.pesanan-smartrent', [
            'profile' => $profile,
            'order' => session('smartrent_order')
        ]);
    }

    /**
     * Proses checkout dari halaman detail (POST) - PERLU LOGIN
     */
    public function processDetailCheckout(Request $request)
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.')
                ->with('redirect_url', route('smartrent.detail', $request->vehicle_id))
                ->with('form_data', $request->except('_token'));
        }
        
        $validated = $request->validate([
            'vehicle_id' => 'required|integer',
            'vehicle_name' => 'required|string',
            'vehicle_price' => 'required|numeric',
            'vehicle_image' => 'required|string',
            'vehicle_type' => 'required|string',
            'pickup_location' => 'required|string',
            'rent_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:1|max:30',
            'service_type' => 'required|in:with_driver,self_drive',
            'driver_price' => 'required|numeric',
        ]);
        
        $vehicleData = $this->getVehicleById($validated['vehicle_id']);
        
        $vehiclePrice = $validated['vehicle_price'] * $validated['duration'];
        $driverPrice = 0;
        
        if ($validated['service_type'] == 'with_driver') {
            $driverPricePerDay = $validated['driver_price'];
            $driverPrice = $driverPricePerDay * $validated['duration'];
        }
        
        $totalPrice = $vehiclePrice + $driverPrice;
        
        $checkoutData = [
            'vehicle_id' => $validated['vehicle_id'],
            'vehicle_name' => $validated['vehicle_name'],
            'vehicle_price' => $validated['vehicle_price'],
            'vehicle_image' => $validated['vehicle_image'],
            'vehicle_type' => $validated['vehicle_type'],
            'pickup_location' => $validated['pickup_location'],
            'rent_date' => $validated['rent_date'],
            'duration' => $validated['duration'],
            'service_type' => $validated['service_type'],
            'vehicle_total' => $vehiclePrice,
            'driver_total' => $driverPrice,
            'driver_price_per_day' => $driverPricePerDay ?? 0,
            'total_price' => $totalPrice,
            'booking_code' => 'SR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6)),
            'created_at' => now()->format('Y-m-d H:i:s')
        ];
        
        session(['smartrent_checkout' => $checkoutData]);
        
        return redirect()->route('smartrent.checkout');
    }
    
    /**
     * Proses checkout dari halaman booking (GET dengan query parameters) - PERLU LOGIN
     */
    public function processBookingCheckout(Request $request)
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.')
                ->with('redirect_url', route('smartrent.booking', $request->all()));
        }
        
        $validated = $request->validate([
            'vehicle_id' => 'required|integer',
            'service' => 'required|in:self-drive,with-driver',
            'duration' => 'required|integer|min:1|max:30',
            'rent_date' => 'required|date|after_or_equal:today',
        ]);
        
        $vehicle = $this->getVehicleById($validated['vehicle_id']);
        if (!$vehicle) {
            return redirect()->route('smartrent.booking')->with('error', 'Kendaraan tidak ditemukan.');
        }
        
        $serviceType = $validated['service'] == 'with-driver' ? 'with_driver' : 'self_drive';
        
        $vehiclePrice = $vehicle['price'] * $validated['duration'];
        $driverPrice = 0;
        $driverPricePerDay = 0;
        
        if ($serviceType == 'with_driver') {
            $driverPricePerDay = $vehicle['driver_price'] ?? 200000;
            $driverPrice = $driverPricePerDay * $validated['duration'];
        }
        
        $totalPrice = $vehiclePrice + $driverPrice;
        
        $checkoutData = [
            'vehicle_id' => $validated['vehicle_id'],
            'vehicle_name' => $vehicle['name'],
            'vehicle_price' => $vehicle['price'],
            'vehicle_image' => $vehicle['image'] ?? asset('images/shuttle1.jpeg'),
            'vehicle_type' => $vehicle['type'],
            'pickup_location' => '',
            'rent_date' => $validated['rent_date'],
            'duration' => $validated['duration'],
            'service_type' => $serviceType,
            'vehicle_total' => $vehiclePrice,
            'driver_total' => $driverPrice,
            'driver_price_per_day' => $driverPricePerDay,
            'total_price' => $totalPrice,
            'booking_code' => 'SR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6)),
            'created_at' => now()->format('Y-m-d H:i:s')
        ];
        
        session(['smartrent_checkout' => $checkoutData]);
        
        return redirect()->route('smartrent.checkout');
    }
    
    /**
     * Halaman form checkout (GET) - PERLU LOGIN
     */
    public function showCheckoutForm()
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.')
                ->with('redirect_url', route('smartrent.index'));
        }
        
        $checkout = session('smartrent_checkout');
        
        if (!$checkout) {
            return redirect()->route('smartrent.index')
                ->with('error', 'Sesi pemesanan tidak ditemukan. Silakan ulangi pemesanan.');
        }
        
        $profile = MProfilePerusahaan::first();
        
        return view('customer.smartrent-checkout', compact('profile', 'checkout'));
    }
    
    /**
     * Finalisasi checkout dengan data customer (POST) - PERLU LOGIN
     */
    public function finalizeCheckout(Request $request)
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.')
                ->with('redirect_url', route('smartrent.checkout'));
        }
        
        // Validasi input
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'pickup_location' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'ktp_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sim_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'other_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);
        
        // Ambil data checkout dari session
        $checkout = session('smartrent_checkout');
        
        if (!$checkout) {
            return redirect()->route('smartrent.index')
                ->with('error', 'Sesi pemesanan telah berakhir. Silakan ulangi pemesanan.');
        }
        
        // Proses upload file
        $ktpPath = $request->file('ktp_file')->store('documents/ktp', 'public');
        $simPath = $request->file('sim_file')->store('documents/sim', 'public');
        $otherPath = null;
        
        if ($request->hasFile('other_document')) {
            $otherPath = $request->file('other_document')->store('documents/other', 'public');
        }
        
        // Gabungkan semua data
        $completeCheckout = array_merge($checkout, [
            'customer_data' => [
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ],
            'rental_details' => [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'pickup_location' => $validated['pickup_location'],
                'notes' => $validated['notes'] ?? '',
            ],
            'documents' => [
                'ktp_path' => $ktpPath,
                'sim_path' => $simPath,
                'other_document' => $otherPath,
            ],
            'invoice_number' => 'INV-SR-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'booking_code' => 'BOOK-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        
        // Simpan ke session dengan nama yang BENAR
        session(['smartrent_complete_checkout' => $completeCheckout]);
        
        // Hapus session checkout awal
        session()->forget('smartrent_checkout');
        
        // Log untuk debugging
        Log::info('Checkout data saved to session:', $completeCheckout);
        
        // Redirect ke halaman pembayaran
        return redirect()->route('smartrent.payment')
            ->with('success', 'Data pemesanan berhasil disimpan. Silakan lanjutkan pembayaran.');
    }
    
    /**
     * Halaman konfirmasi booking - PERLU LOGIN
     */
    public function confirmation()
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat konfirmasi.')
                ->with('redirect_url', route('smartrent.index'));
        }
        
        $payment = session()->get('smartrent_payment');
        
        if (!$payment) {
            return redirect()->route('smartrent.index')->with('error', 'Data pembayaran tidak ditemukan.');
        }
        
        $profile = MProfilePerusahaan::first();
        
        return view('customer.smartrent-confirmation', compact('profile', 'payment'));
    }
    
    /**
     * API: Get vehicle by ID (AJAX)
     */
    public function getVehicle($id)
    {
        $vehicles = [
            1 => [
                'id' => 1,
                'name' => 'Toyota Hiace Commuter',
                'type' => 'Shuttle',
                'price' => 1200000,
                'driver_price' => 150000,
                'price_formatted' => '1.200.000',
                'driver_included_price' => 150000,
                'seats' => '12 Seat',
                'description' => 'Mobil shuttle dengan kapasitas 12 penumpang...',
                'image' => asset('images/shuttle1.jpeg')
            ],
            2 => [
                'id' => 2,
                'name' => 'Isuzu Elf Long',
                'type' => 'MPV',
                'price' => 1500000,
                'driver_price' => 200000,
                'price_formatted' => '1.500.000',
                'driver_included_price' => 200000,
                'seats' => '18 Seat',
                'description' => 'Armada besar untuk rombongan besar...',
                'image' => asset('images/shuttle1.jpeg')
            ],
            3 => [
                'id' => 3,
                'name' => 'Mitsubishi L300',
                'type' => 'Shuttle',
                'price' => 800000,
                'driver_price' => 100000,
                'price_formatted' => '800.000',
                'driver_included_price' => 100000,
                'seats' => '8 Seat',
                'description' => 'Ekonomis dan tangguh, cocok untuk kebutuhan sehari-hari...',
                'image' => asset('images/shuttle1.jpeg')
            ],
        ];
        
        $vehicle = $vehicles[$id] ?? null;
        
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'vehicle' => $vehicle
        ]);
    }
    
    /**
     * API: Cek ketersediaan tanggal
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|integer',
            'rent_date' => 'required|date',
            'duration' => 'required|integer|min:1'
        ]);
        
        $available = true;
        
        return response()->json([
            'success' => true,
            'available' => $available,
            'message' => $available ? 'Kendaraan tersedia pada tanggal tersebut' : 'Kendaraan tidak tersedia'
        ]);
    }
    
    /**
     * Helper: Get vehicle by ID
     */
    private function getVehicleById($id)
    {
        $vehicles = [
            1 => [
                'id' => 1,
                'name' => 'Toyota Hiace Commuter',
                'type' => 'Shuttle',
                'price' => 1200000,
                'driver_price' => 150000,
                'price_formatted' => '1.200.000',
                'driver_included_price' => 150000,
                'seats' => '12 Seat',
                'description' => 'Mobil shuttle dengan kapasitas 12 penumpang...',
                'image' => asset('images/shuttle1.jpeg')
            ],
            2 => [
                'id' => 2,
                'name' => 'Isuzu Elf Long',
                'type' => 'MPV',
                'price' => 1500000,
                'driver_price' => 200000,
                'price_formatted' => '1.500.000',
                'driver_included_price' => 200000,
                'seats' => '18 Seat',
                'description' => 'Armada besar untuk rombongan besar...',
                'image' => asset('images/shuttle1.jpeg')
            ],
            3 => [
                'id' => 3,
                'name' => 'Mitsubishi L300',
                'type' => 'Shuttle',
                'price' => 800000,
                'driver_price' => 100000,
                'price_formatted' => '800.000',
                'driver_included_price' => 100000,
                'seats' => '8 Seat',
                'description' => 'Ekonomis dan tangguh, cocok untuk kebutuhan sehari-hari...',
                'image' => asset('images/shuttle1.jpeg')
            ],
        ];
        
        return $vehicles[$id] ?? null;
    }
    
    /**
     * Halaman pembayaran - PERLU LOGIN
     */
    public function payment()
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan pembayaran.')
                ->with('redirect_url', route('smartrent.checkout'));
        }
        
        $checkout = session('smartrent_complete_checkout');
        
        if (!$checkout) {
            return redirect()->route('smartrent.checkout')
                ->with('error', 'Data pemesanan tidak ditemukan. Silakan ulangi proses checkout.');
        }
        
        $profile = MProfilePerusahaan::first();
        
        return view('customer.pembayaran-smartrent', compact('profile', 'checkout'));
    }
    
    /**
     * Proses pembayaran - PERLU LOGIN
     */
    public function processPayment(Request $request)
    {
        // CEK LOGIN
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pembayaran.')
                ->with('redirect_url', route('smartrent.payment'));
        }
        
        $request->validate([
            'payment_method' => 'required|in:transfer,cash,qris',
            'payment_proof' => 'required_if:payment_method,transfer|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        $checkout = session('smartrent_complete_checkout');
        
        if (!$checkout) {
            return redirect()->route('smartrent.checkout')
                ->with('error', 'Data pemesanan tidak ditemukan. Silakan ulangi proses checkout.');
        }
        
        // Simpan bukti pembayaran jika transfer
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }
        
        $paymentData = [
            'checkout_data' => $checkout,
            'payment_method' => $request->payment_method,
            'payment_proof' => $paymentProofPath,
            'payment_date' => now()->format('Y-m-d H:i:s'),
            'payment_status' => $request->payment_method === 'cash' ? 'pending' : 'pending_verification',
            'transaction_id' => 'TRX-' . date('YmdHis') . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
        ];
        
        // Simpan ke session
        session(['smartrent_payment' => $paymentData]);
        
        // Hapus session checkout
        session()->forget('smartrent_complete_checkout');
        
        // Redirect ke halaman konfirmasi
        return redirect()->route('smartrent.confirmation')
            ->with('success', 'Pembayaran berhasil diproses. Menunggu verifikasi.');
    }
}