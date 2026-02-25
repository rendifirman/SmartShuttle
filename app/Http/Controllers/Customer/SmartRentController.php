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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
                'driver_price' => 150000,
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
                'driver_price' => 200000,
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
                'driver_price' => 100000,
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
     * Halaman detail kendaraan
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
     * Halaman booking dengan filter
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
     * Proses order langsung
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
     * Proses checkout dari halaman detail (POST)
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
     * Proses checkout dari halaman booking (GET dengan query parameters)
     */
    public function processBookingCheckout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.')
                ->with('redirect_url', route('smartrent.booking', $request->all()));
        }
        
        $vehicle_id = $request->get('vehicle_id');
        $vehicle_name = $request->get('vehicle_name');
        $vehicle_image = $request->get('vehicle_image');
        $vehicle_price_per_day = $request->get('vehicle_price_per_day');
        $driver_price_per_day = $request->get('driver_price_per_day');
        $selected_driver_price_per_day = $request->get('selected_driver_price_per_day');
        $service = $request->get('service');
        $duration = $request->get('duration');
        $rent_date = $request->get('rent_date');
        $vehicle_total = $request->get('vehicle_total');
        $driver_total = $request->get('driver_total');
        $total_price = $request->get('total_price');
        
        Log::info('DATA DITERIMA DI processBookingCheckout:', [
            'vehicle_id' => $vehicle_id,
            'vehicle_name' => $vehicle_name,
            'vehicle_price_per_day' => $vehicle_price_per_day,
            'driver_price_per_day' => $driver_price_per_day,
            'selected_driver_price_per_day' => $selected_driver_price_per_day,
            'service' => $service,
            'duration' => $duration,
            'vehicle_total' => $vehicle_total,
            'driver_total' => $driver_total,
            'total_price' => $total_price
        ]);
        
        if (!$vehicle_id || !$vehicle_name || !$service || !$duration || !$rent_date) {
            return redirect()->route('smartrent.booking')
                ->with('error', 'Data pemesanan tidak lengkap. Silakan ulangi.');
        }
        
        $vehicle_price_per_day = (int)$vehicle_price_per_day;
        $driver_price_per_day = (int)$driver_price_per_day;
        $selected_driver_price_per_day = (int)$selected_driver_price_per_day;
        $duration = (int)$duration;
        $vehicle_total = (int)$vehicle_total;
        $driver_total = (int)$driver_total;
        $total_price = (int)$total_price;
        
        $serviceType = $service == 'with-driver' ? 'with_driver' : 'self_drive';
        
        $calculatedVehicleTotal = $vehicle_price_per_day * $duration;
        $calculatedDriverTotal = $selected_driver_price_per_day * $duration;
        $calculatedTotalPrice = $calculatedVehicleTotal + $calculatedDriverTotal;
        
        $finalVehicleTotal = ($vehicle_total == $calculatedVehicleTotal) ? $vehicle_total : $calculatedVehicleTotal;
        $finalDriverTotal = ($driver_total == $calculatedDriverTotal) ? $driver_total : $calculatedDriverTotal;
        $finalTotalPrice = ($total_price == $calculatedTotalPrice) ? $total_price : $calculatedTotalPrice;
        
        $checkoutData = [
            'vehicle_id' => $vehicle_id,
            'vehicle_name' => $vehicle_name,
            'vehicle_image' => $vehicle_image ?: asset('images/toyotahiace.png'),
            'vehicle_price_per_day' => $vehicle_price_per_day,
            'driver_price_per_day' => $driver_price_per_day,
            'selected_driver_price_per_day' => $selected_driver_price_per_day,
            'service_type' => $serviceType,
            'duration' => $duration,
            'rent_date' => $rent_date,
            'vehicle_total' => $finalVehicleTotal,
            'driver_total' => $finalDriverTotal,
            'total_price' => $finalTotalPrice,
            'booking_code' => 'SR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6)),
            'created_at' => now()->format('Y-m-d H:i:s')
        ];
        
        Log::info('DATA DISIMPAN KE SESSION:', $checkoutData);
        
        session(['smartrent_checkout' => $checkoutData]);
        
        return redirect()->route('smartrent.checkout');
    }
    
    /**
     * Halaman form checkout (GET)
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
     * Finalisasi checkout dengan data customer (POST) - FIXED VERSION
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
        
        $vehicle_id = $request->input('vehicle_id');
        $vehicle_name = $request->input('vehicle_name');
        $vehicle_image = $request->input('vehicle_image');
        $vehicle_price_per_day = (int)$request->input('vehicle_price_per_day');
        $driver_price_per_day = (int)$request->input('driver_price_per_day');
        $selected_driver_price_per_day = (int)$request->input('selected_driver_price_per_day');
        $service_type = $request->input('service_type');
        $duration = (int)$request->input('duration');
        $rent_date = $request->input('rent_date');
        $end_date = $request->input('end_date');
        $vehicle_total = (int)$request->input('vehicle_total');
        $driver_total = (int)$request->input('driver_total');
        $total_price = (int)$request->input('total_price');
        $pickup_location = $validated['pickup_location'];
        
        if (!$vehicle_id || !$vehicle_name || !$service_type || !$duration) {
            return redirect()->route('smartrent.booking')
                ->with('error', 'Data pemesanan tidak lengkap. Silakan ulangi.');
        }
        
        $ktpPath = $request->file('ktp_file')->store('documents/ktp', 'public');
        $simPath = $request->file('sim_file')->store('documents/sim', 'public');
        $otherPath = null;
        
        if ($request->hasFile('other_document')) {
            $otherPath = $request->file('other_document')->store('documents/other', 'public');
        }
        
        $orderNumber = 'SR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        
        try {
            DB::beginTransaction();
            
            $transaction = SmartRentTransaction::create([
                'order_number' => $orderNumber,
                'invoice_number' => 'INV-SR-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'user_id' => Auth::id(),
                'vehicle_id' => $vehicle_id,
                'vehicle_name' => $vehicle_name,
                'vehicle_type' => $service_type == 'with_driver' ? 'Dengan Sopir' : 'Lepas Kunci',
                'vehicle_price' => $vehicle_price_per_day,
                'duration' => $duration,
                'vehicle_total' => $vehicle_total,
                'service_type' => $service_type,
                'driver_price_per_day' => $selected_driver_price_per_day,
                'driver_total' => $driver_total,
                'total_price' => $total_price,
                'customer_name' => $validated['full_name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'customer_address' => $validated['address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'pickup_location' => $pickup_location,
                'notes' => $validated['notes'] ?? null,
                'ktp_path' => $ktpPath,
                'sim_path' => $simPath,
                'other_document_path' => $otherPath,
                'payment_status' => 'unpaid',
                'status' => 'pending_payment',
            ]);
            
            DB::commit();
            
            $pickupCity = $this->extractCityFromPickupLocation($pickup_location);
            
            session(['smartrent_payment_data' => [
                'order_number' => $orderNumber,
                'transaction_id' => $transaction->id,
                'vehicle_id' => $vehicle_id,
                'vehicle_name' => $vehicle_name,
                'vehicle_image' => $vehicle_image,
                'vehicle_price_per_day' => $vehicle_price_per_day,
                'driver_price_per_day' => $driver_price_per_day,
                'selected_driver_price_per_day' => $selected_driver_price_per_day,
                'service_type' => $service_type,
                'duration' => $duration,
                'rent_date' => $rent_date,
                'vehicle_total' => $vehicle_total,
                'driver_total' => $driver_total,
                'total_price' => $total_price,
                'customer_name' => $validated['full_name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'customer_address' => $validated['address'],
                'pickup_location' => $pickup_location,
                'city' => $pickupCity,
            ]]);
            
            session(['smartrent_order_number' => $orderNumber]);
            session(['smartrent_transaction_id' => $transaction->id]);
            
            Log::info('SmartRent transaction created with complete data', [
                'order_number' => $orderNumber,
                'pickup_location' => $pickup_location,
                'city' => $pickupCity
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving SmartRent transaction: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('smartrent.checkout')
                ->with('error', 'Terjadi kesalahan saat menyimpan data pesanan. Silakan coba lagi.');
        }
        
        session()->forget('smartrent_checkout');
        
        return redirect()->route('smartrent.payment', ['order_number' => $orderNumber])
            ->with('success', 'Data pesanan berhasil disimpan. Silakan lanjutkan pembayaran.');
    }

   /**
     * Tampilkan halaman pembayaran dengan data dari session
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

        $paymentData = session('smartrent_payment_data');
        
        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction && !$paymentData) {
            return redirect()->route('smartrent.checkout')
                ->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $profile = MProfilePerusahaan::first();

        if ($paymentData) {
            Log::info('Using payment data from session', ['order_number' => $orderNumber]);
            
            $rentDate = $paymentData['rent_date'];
            $duration = $paymentData['duration'];
            $endDate = date('Y-m-d', strtotime($rentDate . ' + ' . ($duration - 1) . ' days'));
            
            $vehicle = [
                'id' => $paymentData['vehicle_id'],
                'name' => $paymentData['vehicle_name'],
                'type' => $paymentData['service_type'] == 'with_driver' ? 'Dengan Sopir' : 'Lepas Kunci',
                'price' => $paymentData['vehicle_price_per_day'],
                'image' => $paymentData['vehicle_image'] ?? asset('images/toyotahiace.png'),
            ];
            
            $customerData = [
                'full_name' => $paymentData['customer_name'],
                'email' => $paymentData['customer_email'],
                'phone' => $paymentData['customer_phone'],
                'address' => $paymentData['customer_address'],
                'pickup_address' => $paymentData['pickup_location'],
                'city' => $paymentData['city'] ?? 'Jakarta',
            ];
            
            return view('customer.pembayaran-smartrent', [
                'profile' => $profile,
                'transaction' => $transaction,
                'order_number' => $orderNumber,
                'order_id' => $transaction->id ?? null,
                'totalPrice' => $paymentData['total_price'],
                'vehicle' => $vehicle,
                'vehicle_price' => $paymentData['vehicle_price_per_day'],
                'driver_price_per_day' => $paymentData['selected_driver_price_per_day'] ?? 0,
                'service' => $paymentData['service_type'],
                'duration' => $paymentData['duration'],
                'rentDate' => $paymentData['rent_date'],
                'endDate' => $endDate,
                'vehicle_total' => $paymentData['vehicle_total'],
                'driver_total' => $paymentData['driver_total'] ?? 0,
                'customerData' => $customerData,
                'order_time' => now(),
                'payment_deadline' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'use_session_data' => true
            ]);
        }

        Log::info('Using transaction data from database', ['order_number' => $orderNumber]);
        
        $rentDate = $transaction->start_date ? $transaction->start_date->format('Y-m-d') : date('Y-m-d');
        $duration = $transaction->duration ?? 1;
        $endDate = date('Y-m-d', strtotime($rentDate . ' + ' . ($duration - 1) . ' days'));
        
        $vehicle = [
            'id' => $transaction->vehicle_id,
            'name' => $transaction->vehicle_name,
            'type' => $transaction->vehicle_type,
            'price' => $transaction->vehicle_price,
            'image' => $this->getVehicleImage($transaction->vehicle_id) ?? asset('images/toyotahiace.png'),
        ];
        
        $pickupCity = $this->extractCityFromPickupLocation($transaction->pickup_location);
        
        $customerData = [
            'full_name' => $transaction->customer_name,
            'email' => $transaction->customer_email,
            'phone' => $transaction->customer_phone,
            'address' => $transaction->customer_address,
            'pickup_address' => $transaction->pickup_location,
            'city' => $pickupCity,
        ];

        return view('customer.pembayaran-smartrent', [
            'profile' => $profile,
            'transaction' => $transaction,
            'order_number' => $orderNumber,
            'order_id' => $transaction->id,
            'totalPrice' => $transaction->total_price,
            'vehicle' => $vehicle,
            'vehicle_price' => $transaction->vehicle_price,
            'driver_price_per_day' => $transaction->driver_price_per_day ?? 0,
            'service' => $transaction->service_type,
            'duration' => $transaction->duration,
            'rentDate' => $rentDate,
            'endDate' => $endDate,
            'vehicle_total' => $transaction->vehicle_total,
            'driver_total' => $transaction->driver_total ?? 0,
            'customerData' => $customerData,
            'order_time' => $transaction->created_at,
            'payment_deadline' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'use_session_data' => false
        ]);
    }

    /**
     * Proses pembayaran (AJAX)
     */
    public function processPayment(Request $request)
    {
        try {
            // Log data yang diterima
            Log::info('processPayment called', [
                'method' => $request->method(),
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'order_number' => 'required|string',
                'payment_method' => 'required|string',
                'total_price' => 'required|numeric',
                'full_name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|email',
                'rent_date' => 'required|date',
                'duration' => 'required|integer',
                'service_type' => 'required|string'
            ]);

            if ($validator->fails()) {
                // Log error validasi
                Log::error('Validasi gagal', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            DB::beginTransaction();
            
            // Cari transaksi yang sudah ada
            $orderNumber = $request->order_number;
            $transaction = SmartRentTransaction::where('order_number', $orderNumber)
                ->where('user_id', Auth::id())
                ->first();

            if (!$transaction) {
                // Buat transaksi baru jika belum ada
                $transaction = new SmartRentTransaction();
                $transaction->order_number = $orderNumber;
                $transaction->invoice_number = 'INV-SR-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
                $transaction->user_id = Auth::id();
            }
            
            // Hitung end_date
            $endDate = date('Y-m-d', strtotime($request->rent_date . ' + ' . ($request->duration - 1) . ' days'));
            
            // Hitung total komponen
            $vehiclePrice = (int) $request->get('vehicle_price', 1200000);
            $vehicleTotal = $vehiclePrice * $request->duration;
            $driverTotal = ($request->service_type == 'with_driver') ? (150000 * $request->duration) : 0;
            
            // Update data transaksi
            $transaction->vehicle_id = $request->get('vehicle_id');
            $transaction->vehicle_name = $request->get('vehicle_name', 'Toyota Hiace');
            $transaction->vehicle_price = $vehiclePrice;
            $transaction->duration = $request->duration;
            $transaction->vehicle_total = $vehicleTotal;
            $transaction->service_type = $request->service_type;
            $transaction->driver_price_per_day = ($request->service_type == 'with_driver') ? 150000 : 0;
            $transaction->driver_total = $driverTotal;
            $transaction->total_price = $request->total_price;
            
            $transaction->customer_name = $request->full_name;
            $transaction->customer_phone = $request->phone;
            $transaction->customer_email = $request->email;
            $transaction->pickup_location = $request->get('pickup_address');
            $transaction->customer_address = $request->get('city', 'Jakarta');
            
            $transaction->start_date = $request->rent_date;
            $transaction->end_date = $endDate;
            
            // SET STATUS LUNAS!
            $transaction->payment_status = 'paid';
            $transaction->payment_method = $this->mapPaymentMethod($request->payment_method);
            $transaction->paid_at = now();
            $transaction->status = 'confirmed';
            
            // Generate QR Code
            if (!$transaction->qr_path) {
                $this->generateQrCodeForTransaction($transaction);
            }
            
            $transaction->save();
            
            DB::commit();
            
            // Kembalikan response JSON dengan redirect_url
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses',
                'redirect_url' => route('smartrent.payment-success', [
                    'order_number' => $transaction->order_number,
                    'payment_method' => $request->payment_method,
                    'total_price' => $request->total_price
                ])
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment process error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map payment method dari frontend ke database
     */
    private function mapPaymentMethod($method)
    {
        $map = [
            'QRIS' => 'qris',
            'BCA Virtual Account' => 'bca_va',
            'Mandiri Virtual Account' => 'mandiri_va'
        ];
        
        return $map[$method] ?? 'qris';
    }

    /**
     * Halaman sukses pembayaran
     */
    public function success(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Ambil dari query parameter
        $orderNumber = $request->get('order_number') ?? $request->get('orderNumber');

        if (!$orderNumber) {
            return redirect()->route('smartrent.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $successData = session('smartrent_success_data');
        
        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        // Jika transaksi belum paid, update statusnya
        if ($transaction && !$transaction->is_paid) {
            $transaction->payment_status = 'paid';
            $transaction->paid_at = now();
            $transaction->status = 'confirmed';
            $transaction->save();
        }

        if (!$transaction && !$successData) {
            return redirect()->route('smartrent.index')
                ->with('error', 'Data pesanan tidak ditemukan.');
        }

        Log::info('SmartRent success page accessed', [
            'order_number' => $orderNumber,
            'has_success_data' => !is_null($successData),
            'has_transaction' => !is_null($transaction)
        ]);

        $profile = MProfilePerusahaan::first();

        // Jika ada data success dari session
        if ($successData) {
            Log::info('Using success data from session', [
                'order_number' => $orderNumber,
                'driver_price_per_day' => $successData['driver_price_per_day'] ?? 0,
                'selected_driver_price_per_day' => $successData['selected_driver_price_per_day'] ?? 0,
                'service' => $successData['service'] ?? 'unknown',
                'city' => $successData['city'] ?? 'Jakarta'
            ]);
            
            $transaction = (object) [
                'order_number' => $successData['order_number'],
                'invoice_number' => $successData['invoice_number'] ?? ('INV/' . $successData['order_number']),
                'vehicle_name' => $successData['vehicle_name'],
                'service_type' => $successData['service'],
                'start_date' => \Carbon\Carbon::parse($successData['rent_date']),
                'end_date' => \Carbon\Carbon::parse($successData['rent_date_end']),
                'duration' => $successData['duration'],
                'pickup_location' => $successData['pickup_location'],
                'customer_name' => $successData['customer_name'],
                'customer_email' => $successData['customer_email'],
                'customer_phone' => $successData['customer_phone'],
                'customer_address' => $successData['customer_address'],
                'payment_method' => $successData['payment_method'] == 'QRIS' ? 'qris' : 
                               ($successData['payment_method'] == 'BCA Virtual Account' ? 'bca_va' : 'mandiri_va'),
                'paid_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'is_paid' => true,
                'payment_status_label' => 'Lunas',
                'vehicle_price' => $successData['vehicle_price'],
                'driver_price_per_day' => $successData['driver_price_per_day'] ?? 0,
                'vehicle_total' => $successData['vehicle_total'],
                'driver_total' => $successData['driver_total'] ?? 0,
                'total_price' => $successData['total_price'],
                'qr_path' => $transaction->qr_path ?? null,
            ];
            
            $viewData = [
                'profile' => $profile,
                'transaction' => $transaction,
                'order_number' => $transaction->order_number,
                'vehicle_name' => $transaction->vehicle_name,
                'vehicle_price' => $transaction->vehicle_price,
                'driver_price_per_day' => $successData['driver_price_per_day'] ?? 0,
                'selected_driver_price_per_day' => $successData['selected_driver_price_per_day'] ?? $successData['driver_price_per_day'] ?? 0,
                'service' => $transaction->service_type,
                'duration' => $transaction->duration,
                'rent_date' => $successData['rent_date'],
                'vehicle_total' => $transaction->vehicle_total,
                'driver_total' => $transaction->driver_total,
                'total_price' => $transaction->total_price,
                'customer_name' => $transaction->customer_name,
                'customer_phone' => $transaction->customer_phone,
                'customer_email' => $transaction->customer_email,
                'customer_address' => $transaction->customer_address,
                'pickup_location' => $transaction->pickup_location,
                'payment_method' => $successData['payment_method'],
                'payment_time' => $successData['payment_time'],
                'rent_date_end' => $successData['rent_date_end'],
                'rent_date_formatted' => $successData['rent_date_formatted'] ?? date('d M Y', strtotime($successData['rent_date'])),
                'rent_date_end_formatted' => $successData['rent_date_end_formatted'] ?? date('d M Y', strtotime($successData['rent_date_end'])),
                'invoice_number' => $transaction->invoice_number,
                'city' => $successData['city'] ?? 'Jakarta'
            ];
            
            // Hapus session data
            session()->forget('smartrent_success_data');
            session()->forget('smartrent_last_order');
            
            return view('customer.smartrent-success', $viewData);
        }

        // Jika menggunakan data dari database
        if ($transaction && !$transaction->qr_path) {
            $this->generateQrCodeForTransaction($transaction);
            $transaction->refresh();
        }

        $rentDate = $transaction->start_date ? $transaction->start_date->format('Y-m-d') : date('Y-m-d');
        $endDate = $transaction->end_date ? $transaction->end_date->format('Y-m-d') : date('Y-m-d', strtotime($rentDate . ' + ' . ($transaction->duration - 1) . ' days'));
        $pickupCity = $this->extractCityFromPickupLocation($transaction->pickup_location);

        $viewData = [
            'profile' => $profile,
            'transaction' => $transaction,
            'order_number' => $transaction->order_number,
            'vehicle_name' => $transaction->vehicle_name,
            'vehicle_price' => $transaction->vehicle_price,
            'driver_price_per_day' => $transaction->driver_price_per_day ?? 0,
            'selected_driver_price_per_day' => $transaction->driver_price_per_day ?? 0,
            'service' => $transaction->service_type,
            'duration' => $transaction->duration,
            'rent_date' => $rentDate,
            'vehicle_total' => $transaction->vehicle_total,
            'driver_total' => $transaction->driver_total ?? 0,
            'total_price' => $transaction->total_price,
            'customer_name' => $transaction->customer_name,
            'customer_phone' => $transaction->customer_phone,
            'customer_email' => $transaction->customer_email,
            'customer_address' => $transaction->customer_address,
            'pickup_location' => $transaction->pickup_location,
            'payment_method' => $transaction->payment_method ? 
                ($transaction->payment_method == 'qris' ? 'QRIS' : 
                    ($transaction->payment_method == 'bca_va' ? 'BCA Virtual Account' : 'Mandiri Virtual Account')) 
                : 'QRIS',
            'payment_time' => $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i') . ' WIB' : now()->format('d/m/Y H:i') . ' WIB',
            'rent_date_end' => $endDate,
            'rent_date_formatted' => $transaction->start_date ? $transaction->start_date->format('d M Y') : date('d M Y'),
            'rent_date_end_formatted' => $transaction->end_date ? $transaction->end_date->format('d M Y') : date('d M Y', strtotime('+' . ($transaction->duration - 1) . ' days')),
            'invoice_number' => $transaction->invoice_number,
            'city' => $pickupCity
        ];

        return view('customer.smartrent-success', $viewData);
    }

    /**
     * Helper: Get vehicle image by ID
     */
    private function getVehicleImage($vehicleId)
    {
        $images = [
            1 => asset('images/toyotahiace.png'),
            2 => asset('images/isuzu.png'),
            3 => asset('images/shuttle3.jpeg'),
        ];
        
        return $images[$vehicleId] ?? asset('images/toyotahiace.png');
    }

    /**
     * Extract city from pickup location string
     */
    private function extractCityFromPickupLocation($pickupLocation)
    {
        if (empty($pickupLocation)) {
            return 'Jakarta';
        }
        
        $cityMap = [
            'Jakarta' => 'Jakarta',
            'Bandung' => 'Bandung',
            'Surabaya' => 'Surabaya',
            'Yogyakarta' => 'Yogyakarta',
            'Bali' => 'Bali',
            'Lainnya' => 'Jakarta'
        ];
        
        foreach ($cityMap as $key => $city) {
            if (strpos($pickupLocation, $key) !== false) {
                return $city;
            }
        }
        
        return 'Jakarta';
    }

    /**
     * Debug endpoint
     */
    public function debugPaymentStatus($orderNumber)
    {
        if (!config('app.debug')) {
            return response()->json([
                'error' => 'Debug mode is disabled'
            ], 403);
        }

        try {
            $transaction = SmartRentTransaction::where('order_number', $orderNumber)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'error' => 'Transaction not found',
                    'order_number' => $orderNumber
                ], 404);
            }

            return response()->json([
                'success' => true,
                'order_number' => $orderNumber,
                'data' => $transaction
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan E-Ticket
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

        if (!$transaction->is_paid) {
            return redirect()->route('smartrent.riwayat')
                ->with('error', 'E-Ticket hanya tersedia untuk transaksi yang sudah dibayar. Status saat ini: ' . $transaction->payment_status_label);
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
     * Download E-Ticket sebagai PDF
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
     * Print E-Ticket
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
     * API: Get vehicle by ID
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
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        if (!$transaction->is_paid) {
            return response()->json([
                'success' => false,
                'message' => 'E-Ticket hanya tersedia untuk transaksi yang sudah dibayar'
            ], 403);
        }

        if (!$transaction->qr_code || !$transaction->qr_path) {
            $this->generateQrCodeForTransaction($transaction);
            $transaction->refresh();
        }

        $priceBreakdown = $this->getPriceBreakdown($transaction);
        $pickupCity = $this->extractCityFromPickupLocation($transaction->pickup_location);

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
                'city' => $pickupCity,
                'total_price' => 'Rp ' . number_format($transaction->total_price, 0, ',', '.'),
                'qr_url' => $transaction->qr_path ? asset($transaction->qr_path) : null,
                'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : '-',
                'status' => $transaction->status_label,
                'payment_status' => $transaction->payment_status_label,
                'is_paid' => $transaction->is_paid,
                'price_breakdown' => $priceBreakdown
            ]
        ]);
    }

    /**
     * Generate QR Code untuk transaksi
     */
    private function generateQrCodeForTransaction($transaction)
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

                Log::info('QR Code generated for SmartRent: ' . $transaction->order_number);
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
    private function generateQrData($transaction)
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
    private function getPriceBreakdown($transaction)
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