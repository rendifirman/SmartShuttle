<?php
// Convert perjalanan.blade.php to use app-driver layout

$file = 'resources/views/driver/perjalanan.blade.php';
$content = file_get_contents($file);

// Extract just the relevant parts - we'll rebuild from scratch with the new structure
// Find CSS content (between <style> and </style>)
$cssStart = strpos($content, '<style>') + strlen('<style>');
$cssEnd = strpos($content, '</style>');
$cssContent = substr($content, $cssStart, $cssEnd - $cssStart);

// Find JavaScript (between first <script> and last </script>)
$jsStart = strpos($content, '<script>') + strlen('<script>');
$jsEnd = strrpos($content, '</script>');
$jsContent = substr($content, $jsStart, $jsEnd - $jsStart);

// Find main content (between <!-- SIDEBAR --> and <!-- MODAL UPDATE LOKASI -->)
$sidebarStart = strpos($content, '<!-- SIDEBAR -->');
$sidebarEnd = strpos($content, '<!-- MODAL UPDATE LOKASI -->');
$mainContent = substr($content, strpos($content, '<!-- HALAMAN DAFTAR PERJALANAN -->'), $sidebarEnd - strpos($content, '<!-- HALAMAN DAFTAR PERJALANAN -->'));

// Build new blade file
$newContent = <<<'BLADE'
@extends('layouts.app-driver')

@section('title', 'Perjalanan - Smart Shuttle Driver')

@push('styles')
<style>
BLADE;

$newContent .= $cssContent;

$newContent .= <<<'BLADE'
</style>
@endpush

@section('content')
BLADE;

// Extract and include just the content part (without sidebar, without main wrappers)
// Find the content area
$contentStart = strpos($content, '<!-- ========================== -->');
$contentEnd = strrpos($content, '<!-- MODAL UPDATE LOKASI -->');
$contentPart = substr($content, $contentStart, $contentEnd - $contentStart);

// Fix the profile name to be dynamic
$contentPart = str_replace('Dimas Mahendra', '{{ auth()->guard(\'driver\')->user()?->name ?? "Driver" }}', $contentPart);
// Fix the date
$contentPart = str_replace('Tanggal: 03 Des 2025', 'Tanggal: <span id="currentDateDisplay">{{ \Carbon\Carbon::today()->format(\'d M Y\') }}</span>', $contentPart);

// Fix driver name in detail page
$contentPart = str_replace('<span id="driverNameDisplay">Dimas Mahendra</span>', '<span id="driverNameDisplay">{{ auth()->guard(\'driver\')->user()?->name ?? "Driver" }}</span>', $contentPart);

$newContent .= $contentPart;

$newContent .= <<<'BLADE'
@endsection

<!-- MODAL UPDATE LOKASI -->
<div class="modal-overlay" id="updateLokasiModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Update Lokasi</h3>
            <p class="modal-subtitle">Lokasi bus akan berpindah ke titik berikutnya</p>
            <p class="modal-subtitle" style="margin-top: 10px; font-weight: 600;" id="modalNextLocation"></p>
        </div>

        <div class="modal-buttons">
            <button class="btn-cancel" id="cancelUpdateBtn">Batal</button>
            <button class="btn-update" id="confirmUpdateBtn">Update</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
BLADE;

// Fix the tripsData to use proper Blade syntax
$jsContent = str_replace(
    'const tripsData = <?php echo json_encode($tripsData ?? []); ?>;',
    'const tripsData = {!! json_encode($tripsData ?? []) !!};',
    $jsContent
);
$jsContent = str_replace(
    'const currentDriverId = <?php echo json_encode($driver->id ?? null); ?>;',
    'const currentDriverId = {!! json_encode(auth()->guard(\'driver\')->user()?->id ?? null) !!};',
    $jsContent
);
$jsContent = str_replace(
    "fetch('<?php echo e(route(\"api.driver.location.update\")); ?>'",
    "fetch('{{ route(\"api.driver.location.update\") }}'",
    $jsContent
);

$newContent .= $jsContent;

$newContent .= <<<'BLADE'
</script>
@endpush
BLADE;

// Write the new content
file_put_contents($file, $newContent);

echo "✓ Conversion complete!\n";
echo "File: " . $file . "\n";
echo "Size: " . filesize($file) . " bytes\n";
?>
