<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-init="$watch('dark', val => localStorage.setItem('theme', val ? 'dark' : 'light')); if(dark) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark');" x-effect="dark ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFCHECK | Guest Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            background: linear-gradient(120deg, #dbeafe 0%, #f0fdfa 100%);
        }
        .dark body {
            background: linear-gradient(120deg, #0f172a 0%, #071226 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans dark:text-gray-100">
    <header class="w-full bg-white/80 dark:bg-slate-800/80 shadow sticky top-0 z-20 backdrop-blur">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/images/ravinallogo.jpg') }}" alt="logo" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <div class="text-xl font-extrabold text-blue-700 dark:text-blue-300">PROFCHECK</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Faculty RFID Attendance</div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm rounded-md text-blue-700 border border-blue-100 hover:bg-blue-50">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white">Register</a>
                    @endif
                @endauth

                <!-- Dark mode toggle -->
                <button @click="dark = !dark" class="p-2 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600" :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="!dark" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5" />
                    </svg>
                    <svg x-show="dark" class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-1">
        <!-- Hero -->
        <section class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-blue-800 dark:text-blue-300 leading-tight">Smart RFID Attendance for Faculty</h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-xl">Automate faculty time-in/time-out with RFID tags, generate daily reports, and keep accurate attendance records — all in a simple, secure interface.</p>

                <div class="mt-8 flex flex-wrap gap-4">
                    @guest
                    <a href="{{ route('login') }}" class="px-5 py-3 rounded-md bg-blue-600 text-white shadow hover:bg-blue-700">Get started</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-3 rounded-md bg-white text-blue-600 border border-blue-100 hover:bg-blue-50">Create account</a>
                    @endif
                    @else
                    <a href="{{ route('dashboard') }}" class="px-5 py-3 rounded-md bg-blue-600 text-white shadow hover:bg-blue-700">Open dashboard</a>
                    @endguest

                    <a href="#features" class="px-5 py-3 rounded-md bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200">Learn more</a>
                </div>

                <div class="mt-6 flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span>Secure & reliable</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
                        <span>Easy reporting</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Today's summary</div>
                        <div class="text-3xl font-bold text-blue-700 dark:text-blue-300 mt-1">{{ \App\Models\Attendance::whereDate('date', now()->toDateString())->count() ?? 0 }}</div>
                        <div class="text-xs text-gray-500">records today</div>
                    </div>
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-200 dark:from-slate-700 dark:to-slate-600 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4"/></svg>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-300">
                    <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded">
                        <div class="font-medium">Faculties</div>
                        <div class="text-xl font-semibold">{{ \App\Models\Faculty::count() }}</div>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded">
                        <div class="font-medium">Schedules</div>
                        <div class="text-xl font-semibold">{{ \App\Models\ClassSchedule::count() }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <div class="md:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">How it works</h2>
                    <ol class="mt-4 space-y-4 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">1</div>
                            <div>
                                <div class="font-semibold">Register faculty accounts</div>
                                <div class="text-sm">Admins add faculty members or faculty register and complete their profile with RFID tag.</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">2</div>
                            <div>
                                <div class="font-semibold">Link schedules</div>
                                <div class="text-sm">Assign class schedules so the scheduler can detect active classes and mark absences automatically.</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">3</div>
                            <div>
                                <div class="font-semibold">Scan RFID</div>
                                <div class="text-sm">Faculty scan their RFID tag when entering/exiting — the system records time-in/time-out.</div>
                            </div>
                        </li>
                    </ol>
                </div>

                <div class="p-6 bg-white dark:bg-slate-800 rounded-lg shadow">
                    <h3 class="font-semibold">Get started</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Create an account or contact your admin to get an RFID tag.</p>
                    <div class="mt-4">
                        @guest
                        <a href="{{ route('register') }}" class="block text-center px-4 py-2 rounded-md bg-blue-600 text-white">Register</a>
                        <a href="{{ route('login') }}" class="block mt-3 text-center px-4 py-2 rounded-md bg-white text-blue-600 border border-blue-100">Log in</a>
                        @else
                        <a href="{{ route('dashboard') }}" class="block text-center px-4 py-2 rounded-md bg-blue-600 text-white">Open dashboard</a>
                        @endguest
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="mt-16 border-t border-blue-100 dark:border-slate-700 pt-8 text-gray-500 dark:text-gray-400 text-sm w-full">
        <div class="max-w-7xl mx-auto px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="font-semibold text-blue-700 dark:text-blue-300">PROFCHECK</div>
                <div class="text-xs mt-2">Faculty RFID Attendance System — built to simplify attendance tracking.</div>
            </div>
            <div>
                <div class="font-semibold">Quick links</div>
                <ul class="mt-2 space-y-1">
                    <li><a href="{{ route('login') }}" class="hover:underline">Login</a></li>
                    @if(Route::has('register'))<li><a href="{{ route('register') }}" class="hover:underline">Register</a></li>@endif
                    <li><a href="{{ url('/contact') }}" class="hover:underline">Contact</a></li>
                </ul>
            </div>
            <div>
                <div class="font-semibold">Contact</div>
                <div class="mt-2 text-xs">Email: support@profcheck.local</div>
                <div class="mt-1 text-xs">Phone: +63 912 345 6789</div>
            </div>
        </div>
        <div class="text-center py-4 text-xs text-gray-400">&copy; {{ date('Y') }} PROFCHECK. All rights reserved.</div>
    </footer>
</body>
</html>
