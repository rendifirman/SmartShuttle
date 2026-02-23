# TODO: Fix Pegawai Database Fields

## Task: Sesuaikan data pegawai database sesuai dengan form edit/create

### Fields yang akan ditambahkan:
1. **Pendidikan & Keahlian**: 
   - `pendidikan_terakhir`
   - `institusi`
   - `tahun_lulus`
   - `keahlian`
   - `pengalaman_kerja`

2. **Dokumen**: 
   - `dokumen_ktp`
   - `dokumen_ijazah`
   - `dokumen_npwp`
   - `dokumen_skck`

## Plan:

- [ ] 1. Buat migration baru untuk menambahkan field-field missing ke users table
- [ ] 2. Update User model - tambahkan field-field baru ke $fillable
- [ ] 3. Update AdminController - sesuaikan storePegawai dan updatePegawai method

## Followup steps:
- [ ] Jalankan migration: `php artisan migrate`
- [ ] Test form create dan edit employee
