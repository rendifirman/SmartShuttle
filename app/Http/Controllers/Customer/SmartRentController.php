<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MProfilePerusahaan;
use App\Models\SmartRentTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
                'image' => asset('images/toyotahiace.png'),
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
                'image' => asset('images/isuzu.png'),
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
                'image' => asset('images/toyotahiace.png'),
                'images' => [
                    asset('images/toyotahiace.png'),
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
                'image' => asset('images/isuzu.png'),
                'images' => [
                    asset('images/isuzu.png'),
                    asset('images/shuttle2.jpg'),
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
                'image' => asset('images/shuttle3.jpeg'),
                'images' => [
                    asset('images/shuttle3.jpeg'),
                    asset('images/shuttle2.jpg'),
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
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman booking.')
                ->with('redirect_url', route('smartrent.booking', $request->all()));
        }
        
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
                'image' => asset('images/toyotahiace.png'),
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
                'image' => asset('images/isuzu.png'),
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
                'image' => asset('images/shuttle3.jpeg'),
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
        
        if ($filterData['city']) {
            $filteredVehicles = array_filter($filteredVehicles, function($vehicle) use ($filterData) {
                return true;
            });
        }
        
        if ($filterData['vehicle_type']) {
            $filteredVehicles = array_filter($filteredVehicles, function($vehicle) use ($filterData) {
                return stripos($vehicle['type'], $filterData['vehicle_type']) !== false;
            });
        }
        
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
        
        $selectedVehicle = null;
        if ($filterData['vehicle_id']) {
            foreach ($allVehicles as $vehicle) {
                if ($vehicle['id'] == $filterData['vehicle_id']) {
                    $selectedVehicle = $vehicle;
                    break;
                }
            }
        }
        
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
        $driverPricePerDay = 0;
        
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
            'driver_price_per_day' => $driverPricePerDay,
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
            'vehicle_image' => $vehicle['image'] ?? asset('images/toyotahiace.png'),
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
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.')
                ->with('redirect_url', route('smartrent.checkout'));
        }
        
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
        
        $checkout = session('smartrent_checkout');
        
        if (!$checkout) {
            return redirect()->route('smartrent.index')
                ->with('error', 'Sesi pemesanan telah berakhir. Silakan ulangi pemesanan.');
        }
        
        $ktpPath = $request->file('ktp_file')->store('documents/ktp', 'public');
        $simPath = $request->file('sim_file')->store('documents/sim', 'public');
        $otherPath = null;
        
        if ($request->hasFile('other_document')) {
            $otherPath = $request->file('other_document')->store('documents/other', 'public');
        }
        
        $orderNumber = 'SR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        
        try {
            $transaction = SmartRentTransaction::create([
                'order_number' => $orderNumber,
                'invoice_number' => 'INV-SR-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'user_id' => Auth::id(),
                'vehicle_id' => $checkout['vehicle_id'] ?? null,
                'vehicle_name' => $checkout['vehicle_name'] ?? null,
                'vehicle_type' => $checkout['vehicle_type'] ?? null,
                'vehicle_price' => (float) ($checkout['vehicle_price'] ?? 0),
                'duration' => $checkout['duration'] ?? 1,
                'vehicle_total' => (float) ($checkout['vehicle_total'] ?? 0),
                'service_type' => $checkout['service_type'] ?? 'self_drive',
                'driver_price_per_day' => (float) ($checkout['driver_price_per_day'] ?? 0),
                'driver_total' => (float) ($checkout['driver_total'] ?? 0),
                'total_price' => (float) ($checkout['total_price'] ?? 0),
                'customer_name' => $validated['full_name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'customer_address' => $validated['address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'pickup_location' => $validated['pickup_location'],
                'notes' => $validated['notes'] ?? null,
                'ktp_path' => $ktpPath,
                'sim_path' => $simPath,
                'other_document_path' => $otherPath,
                'payment_status' => 'unpaid',
                'status' => 'pending_payment',
            ]);
            
            session(['smartrent_order_number' => $orderNumber]);
            session(['smartrent_transaction_id' => $transaction->id]);
        } catch (\Exception $e) {
            Log::error('Error saving SmartRent transaction: ' . $e->getMessage());
            return redirect()->route('smartrent.checkout')
                ->with('error', 'Terjadi kesalahan saat menyimpan data pesanan. Silakan coba lagi.');
        }
        
        session()->forget('smartrent_checkout');
        
        Log::info('SmartRent checkout data saved to database with order_number: ' . $orderNumber);
        
        return redirect()->route('smartrent.payment', ['order_number' => $orderNumber])
            ->with('success', 'Data pesanan berhasil disimpan. Silakan lanjutkan pembayaran.');
    }
    
    /**
     * Halaman pembayaran - PERLU LOGIN
     */
    public function payment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan pembayaran.')
                ->with('redirect_url', route('smartrent.checkout'));
        }
        
        $orderNumber = $request->get('order_number') ?? session('smartrent_order_number');

        if (!$orderNumber) {
            return redirect()->route('smartrent.checkout')
                ->with('error', 'Data pemesanan tidak ditemukan. Silakan ulangi proses checkout.');
        }

        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction) {
            return redirect()->route('smartrent.checkout')
                ->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $profile = MProfilePerusahaan::first();

        return view('customer.pembayaran-smartrent', [
            'profile' => $profile,
            'transaction' => $transaction,
            'order_number' => $orderNumber,
            'order_id' => $transaction->id,
            'totalPrice' => $transaction->total_price,
            'vehicle' => [
                'id' => $transaction->vehicle_id,
                'name' => $transaction->vehicle_name,
                'type' => $transaction->vehicle_type,
            ],
            'customerData' => [
                'full_name' => $transaction->customer_name,
                'email' => $transaction->customer_email,
                'phone' => $transaction->customer_phone,
                'address' => $transaction->customer_address,
            ],
            'rentDate' => $transaction->start_date,
            'duration' => $transaction->duration,
            'service' => $transaction->service_type,
        ]);
    }

    /**
     * Proses pembayaran - PERLU LOGIN
     */
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pembayaran.')
                ->with('redirect_url', route('smartrent.payment'));
        }
        
        $orderNumber = $request->get('order_number');

        if (!$orderNumber) {
            return redirect()->route('smartrent.payment')
                ->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction) {
            return redirect()->route('smartrent.payment')
                ->with('error', 'Data pemesanan tidak ditemukan.');
        }
        
        $request->validate([
            'payment_method' => 'required|string',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        $methodMap = [
            'qris' => 'qris',
            'QRIS' => 'qris',
            'BCA Virtual Account' => 'bca_va',
            'Mandiri Virtual Account' => 'mandiri_va',
            'bca_va' => 'bca_va',
            'mandiri_va' => 'mandiri_va',
        ];
        
        $paymentMethod = $methodMap[$request->payment_method] ?? strtolower(str_replace(' ', '_', $request->payment_method));
        
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs/smartrent', 'public');
        }
        
        try {
            $transaction->update([
                'payment_method' => $paymentMethod,
                'payment_status' => 'paid',
                'payment_proof_path' => $paymentProofPath,
                'paid_at' => now(),
                'status' => 'confirmed',
            ]);

            $this->generateQrCodeForTransaction($transaction);
            
            session(['smartrent_last_order' => $transaction->order_number]);
            
        } catch (\Exception $e) {
            Log::error('Error updating SmartRent payment: ' . $e->getMessage());
            return redirect()->route('smartrent.payment', ['order_number' => $orderNumber])
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }

        return redirect()->route('smartrent.payment-success', ['order_number' => $transaction->order_number])
            ->with('success', 'Pembayaran berhasil diproses.');
    }

    /**
     * Halaman sukses pembayaran - PERLU LOGIN
     */
    public function success(Request $request, $orderNumber = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.')
                ->with('redirect_url', route('smartrent.payment-success', $orderNumber));
        }
        
        if (!$orderNumber) {
            $orderNumber = $request->get('order_number') ?? session('smartrent_last_order');
        }

        if (!$orderNumber) {
            return redirect()->route('smartrent.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction) {
            return redirect()->route('smartrent.index')
                ->with('error', 'Data pesanan tidak ditemukan.');
        }

        if ($transaction->payment_status === 'paid' && !$transaction->qr_code) {
            $this->generateQrCodeForTransaction($transaction);
            $transaction->refresh();
        }

        $profile = MProfilePerusahaan::first();

        return view('customer.smartrent-success', [
            'profile' => $profile,
            'transaction' => $transaction,
            'order_number' => $transaction->order_number,
            'vehicle_name' => $transaction->vehicle_name,
            'rent_date' => $transaction->start_date,
            'customer_info' => [
                'full_name' => $transaction->customer_name,
                'phone' => $transaction->customer_phone,
                'email' => $transaction->customer_email,
                'address' => $transaction->customer_address,
            ],
            'payment_method' => $transaction->payment_method,
            'total_price' => $transaction->total_price,
        ]);
    }

    /**
     * Menampilkan E-Ticket SmartRent - PERLU LOGIN
     */
    public function showETicket($orderNumber)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat E-Ticket.')
                ->with('redirect_url', route('smartrent.e-ticket', $orderNumber));
        }

        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($transaction->payment_status !== 'paid') {
            return redirect()->route('customer.riwayat')
                ->with('error', 'E-Ticket hanya tersedia untuk transaksi yang sudah dibayar.');
        }

        if (!$transaction->qr_code || !$transaction->qr_path) {
            $this->generateQrCodeForTransaction($transaction);
            $transaction->refresh();
        }

        $priceBreakdown = $this->getPriceBreakdown($transaction);
        $profile = MProfilePerusahaan::first();

        return view('customer.smartrent-e-ticket', compact('profile', 'transaction', 'priceBreakdown'));
    }

    /**
     * Download E-Ticket sebagai PDF - PERLU LOGIN
     */
    public function downloadETicket($orderNumber)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.')
                ->with('redirect_url', route('smartrent.e-ticket.download', $orderNumber));
        }

        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->firstOrFail();

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.smartrent-e-ticket-pdf', [
                'transaction' => $transaction,
                'priceBreakdown' => $this->getPriceBreakdown($transaction)
            ]);
            
            return $pdf->download('e-ticket-' . $transaction->order_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            
            return redirect()->route('smartrent.e-ticket', $orderNumber)
                ->with('info', 'Fitur download PDF akan segera tersedia sepenuhnya.');
        }
    }

    /**
     * Print E-Ticket - PERLU LOGIN
     */
    public function printETicket($orderNumber)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.')
                ->with('redirect_url', route('smartrent.e-ticket.print', $orderNumber));
        }

        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->firstOrFail();

        if (!$transaction->qr_code || !$transaction->qr_path) {
            $this->generateQrCodeForTransaction($transaction);
            $transaction->refresh();
        }

        $priceBreakdown = $this->getPriceBreakdown($transaction);

        return view('customer.smartrent-e-ticket-print', compact('transaction', 'priceBreakdown'));
    }

    /**
     * API: Get vehicle by ID (AJAX)
     */
    public function getVehicle($id)
    {
        $vehicle = $this->getVehicleById($id);
        
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
     * API untuk mendapatkan data E-Ticket
     */
    public function getETicketData($orderNumber)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        if (!$transaction->qr_code || !$transaction->qr_path) {
            $this->generateQrCodeForTransaction($transaction);
            $transaction->refresh();
        }

        $priceBreakdown = $this->getPriceBreakdown($transaction);

        return response()->json([
            'success' => true,
            'data' => [
                'order_number' => $transaction->order_number,
                'invoice_number' => $transaction->invoice_number,
                'customer_name' => $transaction->customer_name,
                'customer_email' => $transaction->customer_email,
                'customer_phone' => $transaction->customer_phone,
                'customer_address' => $transaction->customer_address,
                'vehicle_name' => $transaction->vehicle_name,
                'vehicle_type' => $transaction->vehicle_type,
                'service_type' => $transaction->service_type === 'with_driver' ? 'Dengan Sopir' : 'Sewa Mandiri',
                'duration' => $transaction->duration . ' Hari',
                'rental_period' => ($transaction->start_date ? $transaction->start_date->format('d M Y') : '-') . ' - ' . ($transaction->end_date ? $transaction->end_date->format('d M Y') : '-'),
                'start_date' => $transaction->start_date ? $transaction->start_date->format('d M Y') : '-',
                'end_date' => $transaction->end_date ? $transaction->end_date->format('d M Y') : '-',
                'start_time' => $transaction->start_time,
                'end_time' => $transaction->end_time,
                'pickup_location' => $transaction->pickup_location,
                'total_price' => 'Rp ' . number_format($transaction->total_price, 0, ',', '.'),
                'qr_url' => $transaction->qr_path ? asset($transaction->qr_path) : null,
                'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : '-',
                'status' => $transaction->status_label,
                'payment_status' => $transaction->payment_status_label,
                'price_breakdown' => $priceBreakdown
            ]
        ]);
    }

    /**
     * Generate QR Code untuk transaksi
     */
    private function generateQrCodeForTransaction(SmartRentTransaction $transaction)
    {
        try {
            $qrDataArray = $this->generateQrData($transaction);
            $qrData = json_encode($qrDataArray);

            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData);
            $response = Http::get($qrApiUrl);

            if ($response->ok()) {
                $fileName = 'rent/' . $transaction->order_number . '_' . time() . '.png';
                Storage::disk('public')->put('qr/' . $fileName, $response->body());

                $transaction->qr_code = $fileName;
                $transaction->qr_path = '/storage/qr/' . $fileName;
                $transaction->save();

                Log::info('QR Code generated for SmartRent (via qrserver): ' . $transaction->order_number);
            } else {
                Log::error('Failed to fetch QR Code from external service for: ' . $transaction->order_number . ' Response code: ' . $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Gagal generate QR Code untuk SmartRent: ' . $e->getMessage());
        }
    }

    /**
     * Generate data untuk QR Code
     */
    private function generateQrData(SmartRentTransaction $transaction)
    {
        $data = [
            'type' => 'smartrent',
            'order' => $transaction->order_number,
            'invoice' => $transaction->invoice_number,
            'customer' => $transaction->customer_name,
            'vehicle' => $transaction->vehicle_name,
            'service' => $transaction->service_type,
            'start' => $transaction->start_date ? $transaction->start_date->format('Y-m-d') : null,
            'end' => $transaction->end_date ? $transaction->end_date->format('Y-m-d') : null,
            'amount' => $transaction->total_price
        ];
        
        return 'SMARTRENT:' . json_encode($data);
    }

    /**
     * Dapatkan breakdown harga
     */
    private function getPriceBreakdown(SmartRentTransaction $transaction)
    {
        $breakdown = [];

        $breakdown[] = [
            'label' => 'Sewa ' . $transaction->vehicle_name . ' (' . $transaction->duration . ' hari)',
            'amount' => $transaction->vehicle_total,
            'formatted' => 'Rp ' . number_format($transaction->vehicle_total, 0, ',', '.')
        ];

        if ($transaction->service_type === 'with_driver' && $transaction->driver_total > 0) {
            $breakdown[] = [
                'label' => 'Biaya Sopir (' . $transaction->duration . ' hari)',
                'amount' => $transaction->driver_total,
                'formatted' => 'Rp ' . number_format($transaction->driver_total, 0, ',', '.')
            ];
        }

        return $breakdown;
    }

    /**
     * Hitung tanggal selesai sewa
     */
    private function calculateEndDate($startDate, $duration)
    {
        $date = \Carbon\Carbon::parse($startDate);
        $date->addDays($duration);
        return $date->format('d-m-Y');
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
                'image' => asset('images/toyotahiace.png')
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
                'image' => asset('images/isuzu.png')
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
                'image' => asset('images/shuttle3.jpeg')
            ],
        ];
        
        return $vehicles[$id] ?? null;
    }
}