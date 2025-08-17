<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFCHECK</title>
    <link rel="icon" href="<?php echo e(asset('storage/images/ravinallogo.jpg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #fff !important;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" href="<?php echo e(asset('storage/images/ravinallogo.jpg')); ?>">
</head>
<body class="bg-gradient-to-br from-indigo-300 via-purple-200 to-pink-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white/90 dark:bg-gray-800/80 rounded-3xl shadow-2xl p-10 backdrop-blur-lg border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col items-center mb-8">
                <a href="<?php echo e(url('/')); ?>" class="mb-3">
                    <img src="<?php echo e(asset('storage/images/ravinallogo.jpg')); ?>" alt="PROFCHECK" class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-indigo-400 dark:border-indigo-700" />
                </a>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">Welcome Back</h2>
                <p class="text-gray-500 dark:text-gray-300 text-base mt-1">Sign in to manage attendance and reports</p>
            </div>

            <?php if(session('status')): ?>
                <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded px-4 py-2"><?php echo e(session('status')); ?></div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded px-4 py-2">Whoops! Something went wrong.</div>
            <?php endif; ?>

            <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-6" autocomplete="on">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required autofocus value="<?php echo e(old('email')); ?>" autocomplete="email"
                        class="text-white block w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                    />
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-xs text-red-500 mt-1 block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                            class="text-white block w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                        />
                        <button type="button" @click="show = !show" :aria-pressed="show.toString()" aria-label="Toggle password visibility"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                            tabindex="-1"
                        >
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.965 9.965 0 012.244-3.434M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-xs text-red-500 mt-1 block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-400 transition">
                        <label for="remember" class="text-sm text-gray-600 dark:text-gray-300">Remember me</label>
                    </div>
                    <div>
                        <?php if(Route::has('password.request')): ?>
                            <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-indigo-600 hover:underline font-medium">Forgot password?</a>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-lg shadow-lg font-semibold transition"
                >Log in</button>
            </form>

            <p class="mt-8 text-sm text-center text-gray-600 dark:text-gray-300">
                Don't have an account?
                <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 hover:underline font-medium">Sign up</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/auth/login.blade.php ENDPATH**/ ?>