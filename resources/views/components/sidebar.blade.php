<aside class="hidden lg:block w-64 bg-white border-r">
    <div class="p-4">
        <div class="text-sm font-semibold text-gray-700 mb-4">Navigation</div>
        <ul class="space-y-2 text-sm">
            <li><a href="{{ Route::has('dashboard') ? route('dashboard') : url('/dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Dashboard</a></li>
            @can('viewAny', \App\Models\Faculty::class)
                <li><a href="{{ url('admin/dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Admin</a></li>
            @endcan
            <li><a href="{{ url('faculty/dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Faculty</a></li>
            <li><a href="{{ url('admin/attendance/attendanceReport') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Attendance Report</a></li>
        </ul>
    </div>
</aside>
