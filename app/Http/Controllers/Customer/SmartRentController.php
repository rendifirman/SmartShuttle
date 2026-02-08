<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MProfilePerusahaan;

class SmartRentController extends Controller
{
    /**
     * Halaman utama SmartRent
     */
    public function index()
    {
        $profile = MProfilePerusahaan::first();
        
        // Data kendaraan
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
    
    public function showCheckoutForm(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        $service = $request->query('service');
        $duration = $request->query('duration', 1);
        $rentDate = $request->query('rent_date', date('Y-m-d'));
        
        // Cari data kendaraan
        $vehicle = Vehicle::find($vehicleId);
        
        if (!$vehicle) {
            return redirect()->route('smartrent.index')->with('error', 'Kendaraan tidak ditemukan.');
        }
        
        // Hitung total harga
        $total = $vehicle->price * $duration;
        
        return view('customer.checkout-smartrent', compact(
            'vehicle',
            'vehicleId',
            'service',
            'duration',
            'rentDate',
            'total'
        ));
    }

    public function processCheckout(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'pickup_address' => 'required|string|max:500',
            'rent_date' => 'required|date',
            'duration' => 'required|integer|min:1|max:30',
            'city' => 'required|string|max:100',
            'service_type' => 'required|in:self-drive,with-driver',
            'ktp_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sim_file' => $request->service_type === 'self-drive' ? 'required|file|mimes:jpg,jpeg,png,pdf|max:2048' : 'nullable',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        try {
            // Simpan file KTP
            $ktpPath = $request->file('ktp_file')->store('ktp_documents', 'public');
            
            // Simpan file SIM jika ada
            $simPath = null;
            if ($request->hasFile('sim_file')) {
                $simPath = $request->file('sim_file')->store('sim_documents', 'public');
            }
            
            // Hitung total harga
            $vehicle = Vehicle::find($validated['vehicle_id']);
            $totalPrice = $vehicle->price * $validated['duration'];
            
            // Tambahan biaya untuk layanan dengan sopir
            if ($validated['service_type'] === 'with-driver') {
                $driverFee = 150000 * $validated['duration']; // Rp 150,000 per hari
                $totalPrice += $driverFee;
            }
            
            // Generate order number
            $orderNumber = 'SR-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            // Simpan data pesanan
            $order = SmartRentOrder::create([
                'order_number' => $orderNumber,
                'vehicle_id' => $validated['vehicle_id'],
                'customer_name' => $validated['full_name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'pickup_address' => $validated['pickup_address'],
                'rent_date' => $validated['rent_date'],
                'duration' => $validated['duration'],
                'city' => $validated['city'],
                'service_type' => $validated['service_type'],
                'ktp_file' => $ktpPath,
                'sim_file' => $simPath,
                'notes' => $validated['notes'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);
            
            // Redirect ke halaman pembayaran dengan data order
            return redirect()->route('smartrent.payment', ['order_id' => $order->id])
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showPayment(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if (!$orderId) {
            return redirect()->route('smartrent.index')->with('error', 'Order ID tidak ditemukan.');
        }
        
        $order = SmartRentOrder::with('vehicle')->find($orderId);
        
        if (!$order) {
            return redirect()->route('smartrent.index')->with('error', 'Pesanan tidak ditemukan.');
        }
        
        // Hitung detail harga
        $vehiclePrice = $order->vehicle->price;
        $subtotal = $vehiclePrice * $order->duration;
        $driverFee = 0;
        
        if ($order->service_type === 'with-driver') {
            $driverFee = 150000 * $order->duration;
        }
        
        $total = $order->total_price;
        
        return view('customer.pembayaran-smartrent', compact('order', 'vehiclePrice', 'subtotal', 'driverFee', 'total'));
    }

    /**
     * Halaman booking dengan filter
     */
    public function booking(Request $request)
    {
        $filterData = [
            'city' => $request->input('city', ''),
            'vehicle_type' => $request->input('vehicle_type', ''),
            'rent_date' => $request->input('rent_date', date('Y-m-d')),
            'duration' => $request->input('duration', 1),
            'capacity' => $request->input('capacity', ''),
            'vehicle_id' => $request->input('vehicle_id', '')
        ];
        
        $profile = MProfilePerusahaan::first();
        
        // Data semua kendaraan
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
                'price_formatted' => '800.000',
                'period' => '/hari',
                'available' => true,
                'description' => 'Ekonomis dan tangguh, cocok untuk kebutuhan sehari-hari dengan kapasitas barang yang besar.',
                'facilities' => ['AC', 'Audio', 'Kursi Standard', 'Bagasi Luas'],
                'driver_included_price' => 100000,
                'driver_included' => true
            ],
        ];
        
        // Filter kendaraan berdasarkan input
        $filteredVehicles = $allVehicles;
        
        if ($filterData['city']) {
            // Filter berdasarkan kota (simulasi)
            $filteredVehicles = array_filter($filteredVehicles, function($vehicle) use ($filterData) {
                return true; // Untuk sementara return true semua
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
        
        // Reset array keys
        $filteredVehicles = array_values($filteredVehicles);
        
        // Cari vehicle yang dipilih
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
     * Halaman detail kendaraan
     */
    public function vehicleDetail($id)
    {
        $profile = MProfilePerusahaan::first();
        
        // Data kendaraan (contoh)
        $vehicle = [
            'id' => $id,
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
            'description' => 'Mobil shuttle dengan kapasitas 12 penumpang, cocok untuk keluarga besar atau rombongan kecil. Dilengkapi dengan AC dan audio system. Kursi empuk dan memiliki sistem keselamatan yang lengkap.',
            'specifications' => [
                ['label' => 'Kapasitas', 'value' => '12 Penumpang'],
                ['label' => 'Transmisi', 'value' => 'Manual'],
                ['label' => 'Bahan Bakar', 'value' => 'Bensin'],
                ['label' => 'AC', 'value' => 'Double AC'],
                ['label' => 'Audio', 'value' => 'System Stereo'],
                ['label' => 'Tahun', 'value' => '2023'],
            ],
            'facilities' => ['AC', 'Audio System', 'Kursi Empuk', 'Safety Belt', 'P3K', 'Karpet', 'Kursi Reclining'],
            'driver_included_price' => 150000,
            'driver_included' => true
        ];
        
        return view('customer.smartrent-detail', compact('profile', 'vehicle'));
    }
    
    /**
     * Proses order (langsung ke halaman pesanan)
     */
    public function order(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|integer',
            'service' => 'required|in:self-drive,with-driver',
            'duration' => 'required|integer|min:1|max:30',
            'rent_date' => 'required|date|after_or_equal:today',
        ]);

        // Ambil data kendaraan
        $vehicle = $this->getVehicleById($request->vehicle_id);
        if (!$vehicle) {
            return redirect()->route('smartrent.booking')->with('error', 'Kendaraan tidak ditemukan.');
        }

        // Hitung total harga
        $vehiclePrice = $vehicle['price'] * $request->duration;
        $driverPrice = ($request->service == 'with-driver') ?
            ($vehicle['driver_included_price'] ?? 0) * $request->duration : 0;
        $totalPrice = $vehiclePrice + $driverPrice;

        // Simpan data booking ke session
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
     * Proses booking (form checkout)
     */
    public function checkout(Request $request)
    {
        // Jika request GET (dari tombol Lanjutkan ke Pembayaran)
        if ($request->isMethod('get')) {
            $validated = $request->validate([
                'vehicle_id' => 'required|integer',
                'service' => 'required|in:self-drive,with-driver',
                'duration' => 'required|integer|min:1|max:30',
                'rent_date' => 'required|date|after_or_equal:today',
            ]);
            
            // Ambil data kendaraan
            $vehicle = $this->getVehicleById($validated['vehicle_id']);
            if (!$vehicle) {
                return redirect()->route('smartrent.booking')->with('error', 'Kendaraan tidak ditemukan.');
            }
            
            // Hitung harga
            $vehiclePrice = $vehicle['price'] * $validated['duration'];
            $driverPrice = ($validated['service'] == 'with-driver') ? 
                ($vehicle['driver_included_price'] ?? 0) * $validated['duration'] : 0;
            $total = $vehiclePrice + $driverPrice;
            
            $profile = MProfilePerusahaan::first();
            
            return view('customer.pesanan-smartrent', [
                'profile' => $profile,
                'vehicle' => $vehicle,
                'service' => $validated['service'],
                'duration' => $validated['duration'],
                'rentDate' => $validated['rent_date'],
                'vehicleId' => $validated['vehicle_id'],
                'vehiclePrice' => $vehiclePrice,
                'driverPrice' => $driverPrice,
                'total' => $total
            ]);
        }
        
        // Jika request POST (dari form pesanan)
        $request->validate([
            'vehicle_id' => 'required|integer',
            'rent_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:1|max:30',
            'service_type' => 'required|in:self-drive,with-driver',
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'pickup_address' => 'required|string|max:255',
            'city' => 'required|string',
            'notes' => 'nullable|string',
            'ktp_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sim_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        // Ambil data kendaraan
        $vehicle = $this->getVehicleById($request->vehicle_id);
        if (!$vehicle) {
            return redirect()->route('smartrent.booking')->with('error', 'Kendaraan tidak ditemukan.');
        }
        
        // Hitung total harga
        $vehiclePrice = $vehicle['price'] * $request->duration;
        $driverPrice = ($request->service_type == 'with-driver') ? 
            ($vehicle['driver_included_price'] ?? 0) * $request->duration : 0;
        $totalPrice = $vehiclePrice + $driverPrice;
        
        // Upload file KTP
        $ktpPath = null;
        if ($request->hasFile('ktp_file')) {
            $ktpPath = $request->file('ktp_file')->store('smartrent/documents', 'public');
        }
        
        // Upload file SIM jika ada
        $simPath = null;
        if ($request->hasFile('sim_file') && $request->service_type == 'self-drive') {
            $simPath = $request->file('sim_file')->store('smartrent/documents', 'public');
        }
        
        // Generate booking code
        $bookingCode = 'SR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        
        // Simpan data booking ke session
        session()->put('smartrent_booking', [
            'vehicle_id' => $request->vehicle_id,
            'vehicle_name' => $vehicle['name'],
            'vehicle_type' => $vehicle['type'],
            'rent_date' => $request->rent_date,
            'duration' => $request->duration,
            'service_type' => $request->service_type,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'pickup_address' => $request->pickup_address,
            'city' => $request->city,
            'notes' => $request->notes,
            'vehicle_price' => $vehiclePrice,
            'driver_price' => $driverPrice,
            'total_price' => $totalPrice,
            'ktp_file' => $ktpPath,
            'sim_file' => $simPath,
            'booking_code' => $bookingCode,
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);
        
        return redirect()->route('smartrent.payment')->with('success', 'Pesanan berhasil dibuat!');
    }
    
    /**
     * Halaman pembayaran
     */
    public function payment()
    {
        $booking = session()->get('smartrent_booking');
        
        if (!$booking) {
            return redirect()->route('smartrent.index')->with('error', 'Sesi booking tidak ditemukan.');
        }
        
        $profile = MProfilePerusahaan::first();
        
        return view('customer.pembayaran-smartrent', compact('profile', 'booking'));
    }
    
    /**
     * Proses pembayaran
     */
    public function processPayment(Request $request)
    {
        $booking = session()->get('smartrent_booking');
        
        if (!$booking) {
            return redirect()->route('smartrent.index')->with('error', 'Sesi booking tidak ditemukan.');
        }
        
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,qris,credit_card',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Generate invoice number
        $invoiceNumber = 'INV-SR-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
        
        // Simpan ke session untuk halaman konfirmasi
        session()->put('smartrent_payment', [
            'invoice_number' => $invoiceNumber,
            'booking_data' => $booking,
            'payment_method' => $request->payment_method,
            'payment_date' => now()->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);
        
        return redirect()->route('smartrent.confirmation');
    }
    
    /**
     * Halaman konfirmasi booking
     */
    public function confirmation()
    {
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
        // Data kendaraan (contoh)
        $vehicles = [
            1 => [
                'id' => 1,
                'name' => 'Toyota Hiace Commuter',
                'type' => 'Shuttle',
                'price' => 1200000,
                'price_formatted' => '1.200.000',
                'driver_included_price' => 150000,
                'seats' => '12 Seat',
                'description' => 'Mobil shuttle dengan kapasitas 12 penumpang...'
            ],
            2 => [
                'id' => 2,
                'name' => 'Isuzu Elf Long',
                'type' => 'MPV',
                'price' => 1500000,
                'price_formatted' => '1.500.000',
                'driver_included_price' => 200000,
                'seats' => '18 Seat',
                'description' => 'Armada besar untuk rombongan besar...'
            ],
            3 => [
                'id' => 3,
                'name' => 'Mitsubishi L300',
                'type' => 'Shuttle',
                'price' => 800000,
                'price_formatted' => '800.000',
                'driver_included_price' => 100000,
                'seats' => '8 Seat',
                'description' => 'Ekonomis dan tangguh, cocok untuk kebutuhan sehari-hari...'
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
        
        // Simulasi cek ketersediaan
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
                'driver_included_price' => 150000,
                'seats' => '12 Seat',
                'description' => 'Mobil shuttle dengan kapasitas 12 penumpang...'
            ],
            2 => [
                'id' => 2,
                'name' => 'Isuzu Elf Long',
                'type' => 'MPV',
                'price' => 1500000,
                'driver_included_price' => 200000,
                'seats' => '18 Seat',
                'description' => 'Armada besar untuk rombongan besar...'
            ],
            3 => [
                'id' => 3,
                'name' => 'Mitsubishi L300',
                'type' => 'Shuttle',
                'price' => 800000,
                'driver_included_price' => 100000,
                'seats' => '8 Seat',
                'description' => 'Ekonomis dan tangguh, cocok untuk kebutuhan sehari-hari...'
            ],
        ];
        
        return $vehicles[$id] ?? null;
    }
}