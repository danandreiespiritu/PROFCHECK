<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Locations Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>#map{height:80vh;width:100%}</style>
</head>
<body class="p-6 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">User Locations</h1>

    <div class="mb-4">
        <form method="GET" class="flex gap-2">
            <input type="text" name="user_id" placeholder="User ID" class="px-3 py-2 border rounded" value="<?php echo e(request('user_id')); ?>">
            <input type="text" name="faculty_id" placeholder="Faculty ID" class="px-3 py-2 border rounded" value="<?php echo e(request('faculty_id')); ?>">
            <input type="date" name="from" class="px-3 py-2 border rounded" value="<?php echo e(request('from')); ?>">
            <input type="date" name="to" class="px-3 py-2 border rounded" value="<?php echo e(request('to')); ?>">
            <button class="px-3 py-2 bg-blue-600 text-white rounded">Filter</button>
        </form>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        <?php
            $initialLocations = $locations->map(function($l){
                return [
                    'lat' => (float) $l->latitude,
                    'lng' => (float) $l->longitude,
                    'address' => $l->address,
                    'user' => $l->user?->name ?? null,
                    'faculty' => ($l->faculty ? ($l->faculty->FirstName . ' ' . $l->faculty->LastName) : null),
                    'time' => $l->created_at->toDateTimeString(),
                ];
            })->toArray();
        ?>
        const locations = <?php echo json_encode($initialLocations, 15, 512) ?>;

        const map = L.map('map').setView([14.5995, 120.9842], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        locations.forEach((loc) => {
            const marker = L.marker([loc.lat, loc.lng]).addTo(map);
            marker.bindPopup(`<b>${loc.user || loc.faculty || 'User'}</b><br>${loc.address || ''}<br><small>${loc.time}</small>`);
        });

        if (locations.length) {
            const group = L.featureGroup(locations.map(l => L.marker([l.lat, l.lng])));
            map.fitBounds(group.getBounds().pad(0.2));
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/admin/locations/index.blade.php ENDPATH**/ ?>