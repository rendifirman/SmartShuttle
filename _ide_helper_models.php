<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $kode_cabang
 * @property string $nama_cabang
 * @property string $kota
 * @property string $alamat
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $koordinat_gps
 * @property \Illuminate\Support\Carbon|null $jam_buka
 * @property \Illuminate\Support\Carbon|null $jam_tutup
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $jam_operasional
 * @property-read mixed $jumlah_outlet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Outlet> $outlets
 * @property-read int|null $outlets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereJamBuka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereJamTutup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereKodeCabang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereKoordinatGps($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereNamaCabang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedAt($value)
 */
	class Branch extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pemesanan_id
 * @property string $nama_lengkap
 * @property string|null $nik
 * @property string|null $jenis_kelamin
 * @property string|null $tanggal_lahir
 * @property string|null $nomor_kursi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $telepon
 * @property-read mixed $jenis_kelamin_text
 * @property-read mixed $punya_kursi
 * @property-read \App\Models\KursiTerpesan|null $kursiTerpesan
 * @property-read \App\Models\Pemesanan $pemesanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereNomorKursi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang wherePemesananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPenumpang whereUpdatedAt($value)
 */
	class DetailPenumpang extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $shuttle_id
 * @property string $tanggal_keberangkatan
 * @property string $waktu_keberangkatan
 * @property string $waktu_kedatangan
 * @property numeric $harga_total
 * @property int $kursi_tersedia
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $rute_string
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rute> $rutes
 * @property-read int|null $rutes_count
 * @property-read \App\Models\Shuttle $shuttle
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereHargaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereKursiTersedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereShuttleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereTanggalKeberangkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereWaktuKeberangkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereWaktuKedatangan($value)
 */
	class Jadwal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kp_kode Contoh: kp_pengguna, kp_driver
 * @property string $kp_judul
 * @property string $kp_konten_html
 * @property string $kp_versi
 * @property \Illuminate\Support\Carbon $kp_tanggal_efektif
 * @property bool $kp_status_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereKpJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereKpKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereKpKontenHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereKpStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereKpTanggalEfektif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereKpVersi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KebijakanPrivasi whereUpdatedAt($value)
 */
	class KebijakanPrivasi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $jadwal_id
 * @property string $nomor_kursi
 * @property int|null $detail_penumpang_id
 * @property int|null $pemesanan_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DetailPenumpang|null $detailPenumpang
 * @property-read \App\Models\Jadwal $jadwal
 * @property-read \App\Models\Pemesanan|null $pemesanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan whereDetailPenumpangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan whereNomorKursi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan wherePemesananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KursiTerpesan whereUpdatedAt($value)
 */
	class KursiTerpesan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_layanan
 * @property string $kode_layanan
 * @property string $nama_layanan
 * @property string $slug
 * @property string $deskripsi_singkat
 * @property string|null $deskripsi_panjang
 * @property string|null $icon
 * @property string|null $logo
 * @property string $kategori_layanan
 * @property bool $status_aktif
 * @property int $urutan_tampilan
 * @property array<array-key, mixed>|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan kategori($kategori)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan urutan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereDeskripsiPanjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereDeskripsiSingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereIdLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereKategoriLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereKodeLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereNamaLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MLayanan whereUrutanTampilan($value)
 */
	class MLayanan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $nama_perusahaan
 * @property string|null $deskripsi_singkat
 * @property string|null $email_utama
 * @property string|null $email_dukungan
 * @property string|null $telepon_utama
 * @property string|null $telepon_dukungan
 * @property string|null $alamat_kantor_pusat
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $twitter_url
 * @property array<array-key, mixed>|null $jam_operasional
 * @property string|null $link_kebijakan_privasi
 * @property string|null $link_syarat_ketentuan
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereAlamatKantorPusat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereDeskripsiSingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereEmailDukungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereEmailUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereInstagramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereJamOperasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereLinkKebijakanPrivasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereLinkSyaratKetentuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereTeleponDukungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereTeleponUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereTwitterUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MMasterKontak whereUpdatedAt($value)
 */
	class MMasterKontak extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_profile
 * @property string $nama_perusahaan
 * @property string $nama_dagang
 * @property string|null $logo_perusahaan
 * @property string|null $deskripsi_singkat
 * @property string|null $visi
 * @property string|null $misi
 * @property string $alamat_kantor_pusat
 * @property string $telepon
 * @property string $email
 * @property string|null $website
 * @property string|null $background_website
 * @property string|null $jam_operasional
 * @property string|null $npwp
 * @property string|null $nib
 * @property string|null $siup
 * @property string|null $tdp
 * @property string|null $nomor_sertifikat_transportasi
 * @property string|null $kode_izin_penyelenggaraan
 * @property \Illuminate\Support\Carbon $tanggal_berdiri
 * @property string|null $nama_pendiri
 * @property string|null $penanggung_jawab_utama
 * @property string|null $struktur_organisasi_file
 * @property string|null $struktur_organisasi_text
 * @property string|null $brand_smartshuttle
 * @property string|null $brand_smartsent
 * @property string|null $brand_smartrent
 * @property string|null $deskripsi_unit_bisnis
 * @property string|null $sop_layanan_customer_file
 * @property string|null $sop_layanan_customer_text
 * @property string|null $kebijakan_refund_file
 * @property string|null $kebijakan_refund_text
 * @property string|null $kebijakan_privasi_file
 * @property string|null $kebijakan_privasi_text
 * @property string|null $syarat_ketentuan_file
 * @property string|null $syarat_ketentuan_text
 * @property string|null $link_kebijakan_refund
 * @property string|null $link_kebijakan_privasi
 * @property string|null $link_syarat_ketentuan
 * @property string $status
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $services_subtitle
 * @property string|null $features_title
 * @property string|null $features
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $twitter_url
 * @property string|null $footer_description
 * @property string|null $reviews
 * @property-read mixed $background_url
 * @property-read mixed $logo_url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereAlamatKantorPusat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereBackgroundWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereBrandSmartrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereBrandSmartsent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereBrandSmartshuttle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereDeskripsiSingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereDeskripsiUnitBisnis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereFeaturesTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereFooterDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereIdProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereInstagramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereJamOperasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereKebijakanPrivasiFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereKebijakanPrivasiText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereKebijakanRefundFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereKebijakanRefundText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereKodeIzinPenyelenggaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereLinkKebijakanPrivasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereLinkKebijakanRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereLinkSyaratKetentuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereLogoPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereMisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereNamaDagang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereNamaPendiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereNib($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereNomorSertifikatTransportasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan wherePenanggungJawabUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereReviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereServicesSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereSiup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereSopLayananCustomerFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereSopLayananCustomerText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereStrukturOrganisasiFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereStrukturOrganisasiText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereSyaratKetentuanFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereSyaratKetentuanText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereTanggalBerdiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereTdp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereTwitterUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereVisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MProfilePerusahaan withoutTrashed()
 */
	class MProfilePerusahaan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $kode
 * @property string $jenis
 * @property string|null $deskripsi
 * @property numeric $biaya_admin
 * @property int $estimasi_waktu
 * @property array<array-key, mixed>|null $instruksi
 * @property bool $aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $biaya_admin_formatted
 * @property-read mixed $estimasi_waktu_formatted
 * @property-read mixed $instruksi_array
 * @property-read mixed $tersedia
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran jenis($jenis)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran kode($kode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereBiayaAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereEstimasiWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereInstruksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodePembayaran whereUpdatedAt($value)
 */
	class MetodePembayaran extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_outlet
 * @property string $alamat_lengkap
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $fasilitas
 * @property string|null $jam_operasional
 * @property string|null $foto_outlet
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $branch_id
 * @property string|null $tipe_outlet
 * @property int|null $kapasitas_parkir
 * @property bool $tersedia_toilet
 * @property bool $tersedia_musholla
 * @property bool $tersedia_atm
 * @property bool $tersedia_wifi
 * @property string|null $zona_pelayanan
 * @property-read \App\Models\Branch|null $branch
 * @property-read mixed $fasilitas_array
 * @property-read mixed $foto_url
 * @property-read mixed $gambar
 * @property-read mixed $info_lengkap
 * @property-read mixed $kota
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereAlamatLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereFasilitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereFotoOutlet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereJamOperasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereKapasitasParkir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereNamaOutlet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereTersediaAtm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereTersediaMusholla($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereTersediaToilet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereTersediaWifi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereTipeOutlet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereZonaPelayanan($value)
 */
	class Outlet extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pemesanan_id
 * @property string $kode_pembayaran
 * @property numeric $jumlah
 * @property string $metode
 * @property string $status
 * @property string|null $no_virtual_account
 * @property string|null $qr_code
 * @property string|null $nama_bank
 * @property string|null $instruksi_pembayaran
 * @property \Illuminate\Support\Carbon $waktu_kadaluarsa
 * @property \Illuminate\Support\Carbon|null $waktu_pembayaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_kadaluarsa
 * @property-read mixed $status_text
 * @property-read \App\Models\Pemesanan $pemesanan
 * @property-read \App\Models\Transaksi|null $transaksi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereInstruksiPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereKodePembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereMetode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereNamaBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereNoVirtualAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran wherePemesananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereWaktuKadaluarsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereWaktuPembayaran($value)
 */
	class Pembayaran extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_booking
 * @property int|null $customer_id
 * @property int $jadwal_id
 * @property int $jumlah_penumpang
 * @property int $harga_total
 * @property int $diskon
 * @property int $total_bayar
 * @property string $nama_pemesan
 * @property string $telepon_pemesan
 * @property string $email_pemesan
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $tanggal_pembayaran
 * @property \Illuminate\Support\Carbon|null $waktu_pembayaran
 * @property string|null $metode_pembayaran
 * @property string|null $kode_promo
 * @property \Illuminate\Support\Carbon|null $waktu_kadaluarsa
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPenumpang> $detailPenumpang
 * @property-read int|null $detail_penumpang_count
 * @property-read mixed $is_kadaluarsa
 * @property-read mixed $status_text
 * @property-read mixed $tanggal_formatted
 * @property-read mixed $total_bayar_formatted
 * @property-read mixed $waktu_formatted
 * @property-read \App\Models\Jadwal $jadwal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KursiTerpesan> $kursiTerpesan
 * @property-read int|null $kursi_terpesan_count
 * @property-read \App\Models\Outlet|null $outletAsal
 * @property-read \App\Models\Outlet|null $outletTujuan
 * @property-read \App\Models\Transaksi|null $transaksi
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan riwayat($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereDiskon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereEmailPemesan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereHargaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereJumlahPenumpang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereKodeBooking($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereKodePromo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereMetodePembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereNamaPemesan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereTanggalPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereTeleponPemesan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereTotalBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereWaktuKadaluarsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemesanan whereWaktuPembayaran($value)
 */
	class Pemesanan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_pengirim
 * @property string $email_pengirim
 * @property string|null $nomor_telepon
 * @property string $pesan
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak whereEmailPengirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak whereNamaPengirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak whereNomorTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak wherePesan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanKontak whereUpdatedAt($value)
 */
	class PesanKontak extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_promo
 * @property string $nama_promo
 * @property string $jenis_diskon
 * @property numeric $nilai_diskon
 * @property numeric|null $maksimal_diskon
 * @property numeric $minimal_pembelian
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_berakhir
 * @property int|null $kuota
 * @property int $terpakai
 * @property bool $status
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_aktif
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereJenisDiskon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereKodePromo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereKuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereMaksimalDiskon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereMinimalPembelian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereNamaPromo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereNilaiDiskon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereTanggalBerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereTerpakai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereUpdatedAt($value)
 */
	class Promo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_rute
 * @property string $nama_rute
 * @property string $kota_asal
 * @property string $kota_tujuan
 * @property string $durasi Durasi dalam format HH:MM
 * @property numeric|null $jarak
 * @property numeric $harga_dasar
 * @property string|null $rute_pemberhentian
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $pemberhentian_array
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal> $jadwals
 * @property-read int|null $jadwals_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereDurasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereHargaDasar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereJarak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereKodeRute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereKotaAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereKotaTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereNamaRute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereRutePemberhentian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rute whereUpdatedAt($value)
 */
	class Rute extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $jadwal_id
 * @property int $rute_id
 * @property int $urutan
 * @property int|null $durasi_segment Durasi dalam menit untuk segment ini
 * @property numeric|null $harga_segment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Jadwal $jadwal
 * @property-read \App\Models\Rute $rute
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereDurasiSegment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereHargaSegment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereRuteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RuteJadwal whereUrutan($value)
 */
	class RuteJadwal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_shuttle
 * @property string|null $tipe_shuttle
 * @property int $kapasitas_kursi
 * @property string|null $fasilitas
 * @property string|null $nomor_polisi
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $total_kursi
 * @property string|null $layout_kursi
 * @property string|null $gambar_depan
 * @property string|null $gambar_samping
 * @property string|null $gambar_belakang
 * @property string|null $gambar_interior
 * @property-read mixed $daftar_kursi
 * @property-read mixed $fasilitas_array
 * @property-read mixed $layout_kursi_array
 * @property-read mixed $total_baris
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal> $jadwals
 * @property-read int|null $jadwals_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereFasilitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereGambarBelakang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereGambarDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereGambarInterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereGambarSamping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereKapasitasKursi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereLayoutKursi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereNamaShuttle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereNomorPolisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereTipeShuttle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereTotalKursi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shuttle whereUpdatedAt($value)
 */
	class Shuttle extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $sk_kode Contoh: sk_pengguna, sk_driver, sk_pengiriman
 * @property string $sk_judul
 * @property string $sk_konten_html
 * @property string $sk_versi
 * @property \Illuminate\Support\Carbon $sk_tanggal_efektif
 * @property bool $sk_status_aktif
 * @property string $sk_tipe pengguna, driver, mitra, pengiriman
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereSkJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereSkKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereSkKontenHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereSkStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereSkTanggalEfektif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereSkTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereSkVersi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyaratKetentuan whereUpdatedAt($value)
 */
	class SyaratKetentuan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pembayaran_id
 * @property int $pemesanan_id
 * @property string $kode_transaksi
 * @property numeric $jumlah
 * @property numeric $biaya_admin
 * @property numeric $total
 * @property string|null $catatan
 * @property string|null $bukti_pembayaran
 * @property \Illuminate\Support\Carbon|null $waktu_transaksi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pembayaran $pembayaran
 * @property-read \App\Models\Pemesanan $pemesanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereBiayaAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereBuktiPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereKodeTransaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi wherePembayaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi wherePemesananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereWaktuTransaksi($value)
 */
	class Transaksi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $phone
 * @property string|null $nik
 * @property string|null $avatar
 * @property int $member_point
 * @property int $loyalty_point
 * @property string $membership_level
 * @property bool $two_factor_enabled
 * @property string $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $avatar_url
 * @property-read mixed $membership_progress
 * @property-read mixed $next_membership_level
 * @property-read mixed $points_needed_for_next_level
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pemesanan> $pemesanan
 * @property-read int|null $pemesanan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLoyaltyPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMemberPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMembershipLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

