<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Live Driver Locations - Admin</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:8px;border:1px solid #ddd;text-align:left}
        th{background:#f4f4f4}
        .status{padding:6px 10px;border-radius:6px;background:#ffe38e;color:#8a6d3b}
        .status-selesai{background:#9be79b;color:#2d572c}
    </style>
</head>
<body>
    <h2>Live Driver Locations</h2>
    <p>Halaman ini menggunakan polling setiap 10 detik. Untuk real-time gunakan broadcasting (Pusher / Redis) konfigurasi tambahan.</p>

    <table id="locationsTable">
        <thead>
            <tr>
                <th>Driver</th>
                <th>Trip ID</th>
                <th>Last Location</th>
                <th>Detail</th>
                <th>Stop Index</th>
                <th>Status</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <script>
        async function fetchActiveLocations(){
            try{
                const resp = await fetch('<?php echo e(route("api.driver.location.active")); ?>', {credentials: 'same-origin'});
                const data = await resp.json();
                if(!data.success) return console.error('API error', data);

                const grouped = data.data || {};
                const tbody = document.querySelector('#locationsTable tbody');
                tbody.innerHTML = '';

                Object.keys(grouped).forEach(driverId => {
                    const locs = grouped[driverId];
                    // take latest
                    const latest = locs[0];
                    const driverName = latest.driver?.name || ('Driver ' + driverId);
                    const tripId = latest.id_jadwal_driver || latest.trip_id || '-';
                    const locName = latest.location_name || '-';
                    const locDetail = latest.location_detail || '-';
                    const stopIndex = latest.stop_index ?? '-';
                    const status = latest.status || '-';
                    const updatedAt = latest.created_at || latest.updated_at || '-';

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${driverName}</td>
                        <td>${tripId}</td>
                        <td>${locName}</td>
                        <td>${locDetail}</td>
                        <td>${stopIndex}</td>
                        <td><span class="${status === 'Selesai' || status === 'completed' ? 'status-selesai' : 'status'}">${status}</span></td>
                        <td>${updatedAt}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }catch(e){
                console.error(e);
            }
        }

        fetchActiveLocations();
        setInterval(fetchActiveLocations, 10000);
    </script>
</body>
</html>
