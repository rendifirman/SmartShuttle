<?php

namespace App\Services;

use App\Models\DriverJadwal;
use App\Models\Jadwal;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class JadwalSearchService
{
    protected $flowMode;

    public function __construct()
    {
        $this->flowMode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    }

    /**
     * Unified search which returns a paginated result (DriverJadwal-shaped items)
     * Params keys supported: asal, tujuan, tanggal, penumpang, rute, search, harga_min, harga_max, waktu_keberangkatan
     */
    public function searchPaginated(array $params = [], int $perPage = 10)
    {
        $page = Paginator::resolveCurrentPage() ?: 1;

        $query = $this->buildBaseQuery($params);

        // Apply common filters
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function($q) use ($search) {
                $q->where('rute', 'like', '%' . $search . '%')
                  ->orWhere('armada', 'like', '%' . $search . '%');
            });
        }

        if (!empty($params['rute'])) {
            $query->where('rute', 'like', '%' . $params['rute'] . '%');
        }

        if (!empty($params['waktu_keberangkatan'])) {
            $query->whereTime('waktu_keberangkatan', '>=', $params['waktu_keberangkatan']);
        }

        if (!empty($params['harga_min'])) {
            if ($this->flowMode === 'driver_confirmation') {
                $query->where('harga', '>=', $params['harga_min']);
            } else {
                $query->where('harga_total', '>=', $params['harga_min']);
            }
        }
        if (!empty($params['harga_max'])) {
            if ($this->flowMode === 'driver_confirmation') {
                $query->where('harga', '<=', $params['harga_max']);
            } else {
                $query->where('harga_total', '<=', $params['harga_max']);
            }
        }

        // Origin / Destination strict, case-insensitive
        if (!empty($params['asal']) || !empty($params['tujuan'])) {
            $query->where(function($q) use ($params) {
                if (!empty($params['asal'])) {
                    $val = mb_strtolower(trim($params['asal']));
                    $q->whereRaw('LOWER(rutes.kota_asal) = ?', [$val]);
                }
                if (!empty($params['tujuan'])) {
                    $val = mb_strtolower(trim($params['tujuan']));
                    $q->whereRaw('LOWER(rutes.kota_tujuan) = ?', [$val]);
                }
            });
        }

        // Date
        if (!empty($params['tanggal'])) {
            if ($this->flowMode === 'driver_confirmation') {
                $query->whereDate('driver_jadwals.tanggal', $params['tanggal']);
            } else {
                $query->whereDate('jadwals.tanggal_keberangkatan', $params['tanggal']);
            }
        }

        // Passenger count
        $penumpang = (int) ($params['penumpang'] ?? ($params['passenger_count'] ?? 1));
        if ($penumpang > 1) {
            if ($this->flowMode === 'driver_confirmation') {
                $query->whereRaw('(driver_jadwals.total_kursi - driver_jadwals.kursi_terisi) >= ?', [$penumpang]);
            } else {
                $query->whereRaw('(jadwals.kursi_tersedia) >= ?', [$penumpang]);
            }
        }

        // Order
        $dateCol = $this->flowMode === 'driver_confirmation' ? 'driver_jadwals.tanggal' : 'jadwals.tanggal_keberangkatan';
        $timeCol = $this->flowMode === 'driver_confirmation' ? 'driver_jadwals.waktu_keberangkatan' : 'jadwals.waktu_keberangkatan';

        $query->orderBy($dateCol, 'asc')->orderBy($timeCol, 'asc');

        // Paginate
        $items = $query->paginate($perPage);

        // Normalize items to DriverJadwal-shaped objects
        if ($this->flowMode !== 'driver_confirmation') {
            $items->getCollection()->transform(function($jadwal) {
                $rute = $jadwal->rutes->first();

                return new class($jadwal) {
                    protected $jadwal;
                    public function __construct($jadwal) { $this->jadwal = $jadwal; }
                    public function getDetailRute() {
                        $r = $this->jadwal->rutes->first();
                        return [
                            'kota_asal' => $r->kota_asal ?? null,
                            'kota_tujuan' => $r->kota_tujuan ?? null,
                            'nama_rute' => $r->nama_rute ?? null,
                        ];
                    }
                    public function __get($name) {
                        switch ($name) {
                            case 'id_jadwal_driver': return null;
                            case 'jadwal_id': return $this->jadwal->id;
                            case 'rute':
                                $r = $this->jadwal->rutes->first();
                                return $this->jadwal->rute ?? (($r->kota_asal ?? '') . ' - ' . ($r->kota_tujuan ?? ''));
                            case 'tanggal': return \Carbon\Carbon::parse($this->jadwal->tanggal_keberangkatan);
                            case 'waktu_keberangkatan': return $this->jadwal->waktu_keberangkatan;
                            case 'waktu_kedatangan': return $this->jadwal->waktu_kedatangan;
                            case 'harga': return $this->jadwal->harga_total ?? 0;
                            case 'armada': return $this->jadwal->shuttle?->nama_shuttle ?? 'Smart Shuttle';
                            case 'jadwal': return $this->jadwal;
                            case 'driver': return null;
                            case 'total_kursi': return $this->jadwal->shuttle?->total_kursi ?? null;
                            case 'kursi_terisi': return ($this->jadwal->shuttle?->total_kursi ?? 0) - ($this->jadwal->kursi_tersedia ?? 0);
                            case 'sisa_kursi': return $this->jadwal->kursi_tersedia ?? 0;
                            case 'status': return $this->jadwal->status;
                        }
                        return null;
                    }
                };
            });
        }

        return $items;
    }

    /**
     * Build base query depending on mode
     */
    protected function buildBaseQuery(array $params = [])
    {
        if ($this->flowMode === 'driver_confirmation') {
            $query = DriverJadwal::query()
                ->join('jadwals', 'driver_jadwals.id_jadwal', '=', 'jadwals.id')
                ->join('rute_jadwals', function($join) {
                    $join->on('jadwals.id', '=', 'rute_jadwals.jadwal_id');
                })
                ->join('rutes', 'rute_jadwals.rute_id', '=', 'rutes.id')
                ->with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])
                ->where('driver_jadwals.status', 'aktif')
                ->where('driver_jadwals.tanggal', '>=', now()->toDateString())
                ->where('rute_jadwals.status', 'active')
                ->select('driver_jadwals.*')
                ->distinct('driver_jadwals.id_jadwal_driver');
        } else {
            $query = Jadwal::query()
                ->join('rute_jadwals', 'jadwals.id', '=', 'rute_jadwals.jadwal_id')
                ->join('rutes', 'rute_jadwals.rute_id', '=', 'rutes.id')
                ->with(['shuttle', 'rutes'])
                ->where('jadwals.status', 'active')
                ->where('jadwals.tanggal_keberangkatan', '>=', now()->toDateString())
                ->where('rute_jadwals.status', 'active')
                ->select('jadwals.*')
                ->distinct('jadwals.id');
        }

        return $query;
    }

    /**
     * Get rute list for filters
     */
    public function getRuteList()
    {
        if ($this->flowMode === 'driver_confirmation') {
            return DriverJadwal::select('rute')
                ->distinct()
                ->tersediaUntukCustomer()
                ->pluck('rute')
                ->filter()
                ->values();
        }

        return Jadwal::with('rutes')
            ->where('jadwals.status', 'active')
            ->where('jadwals.tanggal_keberangkatan', '>=', now()->toDateString())
            ->get()
            ->flatMap(function($jadwal) {
                return $jadwal->rutes->map(fn($r) => $r->nama_rute ?? ($r->kota_asal . ' - ' . $r->kota_tujuan));
            })
            ->filter()
            ->unique()
            ->values();
    }

    public function getDateRange()
    {
        if ($this->flowMode === 'driver_confirmation') {
            return DriverJadwal::selectRaw('MIN(tanggal) as min_date, MAX(tanggal) as max_date')
                ->tersediaUntukCustomer()
                ->first();
        }

        return Jadwal::selectRaw('MIN(tanggal_keberangkatan) as min_date, MAX(tanggal_keberangkatan) as max_date')
            ->where('jadwals.status', 'active')
            ->first();
    }

    public function getPriceRange()
    {
        if ($this->flowMode === 'driver_confirmation') {
            return DriverJadwal::selectRaw('MIN(harga) as min_harga, MAX(harga) as max_harga')
                ->tersediaUntukCustomer()
                ->first();
        }

        return Jadwal::selectRaw('MIN(harga_total) as min_harga, MAX(harga_total) as max_harga')
            ->where('jadwals.status', 'active')
            ->first();
    }
}
