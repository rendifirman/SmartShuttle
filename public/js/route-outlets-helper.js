/**
 * Helper untuk menampilkan outlets pemberhentian dari API
 * Usage: loadRouteOutlets(jadwalId, containerSelector)
 */

async function loadRouteOutlets(jadwalId, containerSelector) {
    try {
        const container = document.querySelector(containerSelector);
        if (!container) {
            console.warn(`Container not found: ${containerSelector}`);
            return;
        }

        // Check if already loaded
        if (container.dataset.loaded === 'true') {
            return;
        }

        // Fetch schedule details dengan outlets
        const response = await fetch(`/api/v1/schedules/${jadwalId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });

        if (!response.ok) {
            console.error('Failed to fetch schedule details:', response.statusText);
            return;
        }

        const data = await response.json();
        if (!data.success || !data.data) {
            console.warn('Invalid response format');
            return;
        }

        const schedule = data.data;

        // Build outlets HTML from routes
        let htmlContent = '';

        if (schedule.rutes && Array.isArray(schedule.rutes)) {
            schedule.rutes.forEach((rute, ruteIdx) => {
                // Add route header
                htmlContent += `
                    <div style="margin: 16px 0; padding: 12px; background: #f8f9fa; border-radius: 8px;">
                        <h5 style="color: var(--primary-color); margin: 0 0 12px 0; font-size: 14px;">
                            <i class="fas fa-bus"></i> ${rute.nama_rute || 'Rute'}
                        </h5>
                `;

                // Check if outlets_pemberhentian exists
                if (rute.outlets_pemberhentian && Array.isArray(rute.outlets_pemberhentian) && rute.outlets_pemberhentian.length > 0) {
                    htmlContent += '<div style="padding-left: 16px;">';

                    rute.outlets_pemberhentian.forEach((outlet, idx) => {
                        const stopType = [];
                        if (outlet.is_pickup_point) stopType.push('📍 PICKUP');
                        if (outlet.is_drop_point) stopType.push('🎯 DROP');

                        htmlContent += `
                            <div style="display: flex; align-items: flex-start; margin-bottom: 12px; padding: 8px; border-radius: 6px; background: white;">
                                <div style="width: 24px; height: 24px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; margin-right: 12px; flex-shrink: 0;">
                                    ${outlet.urutan}
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #333; margin-bottom: 4px;">
                                        ${outlet.nama_outlet || 'Unknown'}
                                    </div>
                                    ${outlet.alamat ? `<div style="font-size: 12px; color: #666; margin-bottom: 4px;"><i class="fas fa-map-marker-alt"></i> ${outlet.alamat}</div>` : ''}
                                    ${outlet.telepon ? `<div style="font-size: 12px; color: #666; margin-bottom: 4px;"><i class="fas fa-phone"></i> ${outlet.telepon}</div>` : ''}
                                    ${outlet.estimasi_waktu ? `<div style="font-size: 11px; color: #999;">⏱ ${outlet.estimasi_waktu}</div>` : ''}
                                    ${stopType.length > 0 ? `<div style="font-size: 11px; margin-top: 4px; color: #666;">${stopType.join(' • ')}</div>` : ''}
                                </div>
                            </div>
                        `;
                    });

                    htmlContent += '</div>';
                } else {
                    htmlContent += '<div style="color: #999; font-size: 13px; padding: 8px; font-style: italic;">Tidak ada data outlet pemberhentian</div>';
                }

                htmlContent += '</div>';
            });
        } else {
            htmlContent = '<div style="color: #999; font-size: 13px;">Tidak ada informasi rute tersedia</div>';
        }

        container.innerHTML = htmlContent;
        container.dataset.loaded = 'true';

    } catch (error) {
        console.error('Error loading route outlets:', error);
        const container = document.querySelector(containerSelector);
        if (container) {
            container.innerHTML = '<div style="color: #d32f2f; font-size: 13px;">Gagal memuat detail rute</div>';
        }
    }
}

/**
 * Toggle Rincian Perjalanan dengan loading dari API
 */
function toggleRouteDetailsWithOutlets(jadwalId, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const isHidden = container.style.display === 'none';
    container.style.display = isHidden ? 'block' : 'none';

    if (isHidden && container.dataset.loaded !== 'true') {
        // Load outlets ketika dibuka pertama kali
        const outletsContainer = container.querySelector('.outlets-details-content') || container;
        loadRouteOutlets(jadwalId, `.outlets-details-content[data-jadwal-id="${jadwalId}"]`);
    }
}

/**
 * Initialize semua route detail toggles di halaman
 */
document.addEventListener('DOMContentLoaded', function() {
    // Find semua outlets-details-content dan load mereka ketika dibutuhkan
    const handleInitialLoad = () => {
        // Ini optional - outlets akan diload ketika user klik toggle button
    };

    handleInitialLoad();
});
