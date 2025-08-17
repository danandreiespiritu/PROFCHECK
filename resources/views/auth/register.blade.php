<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFCHECK</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/ravinallogo.jpg') }}">
</head>
<body class="bg-gradient-to-br from-indigo-200 via-purple-200 to-pink-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex items-center justify-center relative">
    <div class="absolute inset-0 bg-white bg-opacity-30 dark:bg-black dark:bg-opacity-40 pointer-events-none"></div>
    <div id="register-card" class="relative z-10 bg-white shadow-2xl rounded-3xl px-8 py-10 w-full max-w-lg mx-4 opacity-0 translate-y-8 transition-all duration-700 dark:bg-gray-900">
        <div class="flex flex-col items-center mb-6">
            <h2 class="text-3xl font-extrabold text-center text-blue-700 dark:text-blue-300 mb-1">Create an Account</h2>
        </div>
        <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-gray-700 dark:text-gray-200" />
                <x-text-input id="name" class="block mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('name') border-red-500 @enderror" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your full name" aria-label="Name" aria-invalid="@error('name')true@enderror" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" aria-live="polite" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-200" />
                <x-text-input id="email" class="block mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('email') border-red-500 @enderror" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@email.com" aria-label="Email" aria-invalid="@error('email')true@enderror" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" aria-live="polite" />
            </div>
            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-200" />
                <div class="relative">
                    <x-text-input id="password" class="block mt-1 w-full border border-gray-300 rounded-lg p-2 pr-10 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('password') border-red-500 @enderror" type="password" name="password" required autocomplete="new-password" placeholder="Create a password" aria-label="Password" aria-invalid="@error('password')true@enderror" />
                    <button type="button" id="toggle-password" aria-label="Show password" aria-pressed="false" onclick="togglePassword('password', this)" tabindex="0" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
                        <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 0118 0 9 9 0 01-18 0z" />
                        </svg>
                        <svg id="eye-off-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7s4-7 9-7c1.657 0 3.216.417 4.563 1.138M19.07 4.93a9.953 9.953 0 012.93 5.07M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 0118 0 9 9 0 01-18 0z" />
                            <line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" aria-live="polite" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 dark:text-gray-200" />
                <div class="relative">
                    <x-text-input id="password_confirmation" class="block mt-1 w-full border border-gray-300 rounded-lg p-2 pr-10 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('password_confirmation') border-red-500 @enderror" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" aria-label="Confirm Password" aria-invalid="@error('password_confirmation')true@enderror" />
                    <button type="button" id="toggle-password-confirmation" aria-label="Show confirm password" aria-pressed="false" onclick="togglePassword('password_confirmation', this)" tabindex="0" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
                        <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 0118 0 9 9 0 01-18 0z" />
                        </svg>
                        <svg id="eye-off-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7s4-7 9-7c1.657 0 3.216.417 4.563 1.138M19.07 4.93a9.953 9.953 0 012.93 5.07M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 0118 0 9 9 0 01-18 0z" />
                            <line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" aria-live="polite" />
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" {{ old('terms') ? 'checked' : '' }} required class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-400 focus:ring-2 @error('terms') border-red-500 @enderror">
                <label for="terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    I agree to the <a href="#" class="text-blue-600 hover:underline focus:underline dark:text-blue-400">Terms & Conditions</a>
                </label>
            </div>
            @error('terms')
                <div class="mt-2 text-red-600 text-sm" aria-live="polite">{{ $message }}</div>
            @enderror

            <div class="flex flex-col sm:flex-row items-center justify-between mt-8 gap-3">
                <a class="text-sm text-blue-600 hover:underline dark:text-blue-400" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <x-primary-button class="bg-blue-600 text-white rounded-lg px-6 py-2 hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 transition-colors w-full sm:w-auto dark:bg-blue-700 dark:hover:bg-blue-800">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
    <!-- Terms & Conditions Modal -->
    <div id="terms-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl max-w-lg w-full p-8 relative border border-blue-100 dark:border-blue-900">
            <h3 class="text-xl font-bold mb-4 text-blue-700 dark:text-blue-300">Terms & Conditions</h3>
            <div class="text-gray-700 dark:text-gray-200 max-h-72 overflow-y-auto mb-6 text-sm space-y-3">
                <p>
                    By creating an account with Ravinal Store, you agree to provide accurate and complete personal information, including your name, email, and phone number. Your privacy is important to us. We collect your information solely for the purpose of account creation, order processing, and customer support.
                </p>
                <p>
                    We are committed to protecting your data and will not share your personal information with third parties except as required by law or to fulfill your orders. You have the right to access, update, or request deletion of your data at any time.
                </p>
                <p>
                    By proceeding, you consent to our collection and use of your information as described in this policy. If you do not agree, please do not register for an account.
                </p>
            </div>
            <div class="flex justify-end gap-2">
                <button onclick="closeTermsModal()" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">Close</button>
            </div>
        </div>
    </div>
    <script>
        // Show modal when "Terms & Conditions" link is clicked
        document.addEventListener('DOMContentLoaded', () => {
            const termsLink = document.querySelector('label[for="terms"] a');
            if (termsLink) {
                termsLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('terms-modal').classList.remove('hidden');
                });
            }
        });

        // Close modal function
        function closeTermsModal() {
            document.getElementById('terms-modal').classList.add('hidden');
        }
    </script>
    <script>
        // Animate form on load
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('register-card').classList.remove('opacity-0', 'translate-y-8');

            // Intercept form submission for terms checkbox
            const form = document.querySelector('form[action="{{ route('register') }}"]');
            if (!form) {
                // fallback: try to select the first form if selector fails
                const forms = document.getElementsByTagName('form');
                if (forms.length > 0) {
                    forms[0].addEventListener('submit', function(e) {
                        const terms = document.getElementById('terms');
                        if (!terms.checked) {
                            e.preventDefault();
                            document.getElementById('terms-modal').classList.remove('hidden');
                            terms.focus();
                        }
                    });
                }
            } else {
                form.addEventListener('submit', function(e) {
                    const terms = document.getElementById('terms');
                    if (!terms.checked) {
                        e.preventDefault();
                        document.getElementById('terms-modal').classList.remove('hidden');
                        terms.focus();
                    }
                });
            }
        });

        // Password visibility toggle with icon swap and aria-pressed
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const eye = document.getElementById('eye-' + id);
            const eyeOff = document.getElementById('eye-off-' + id);
            if (input.type === 'password') {
                input.type = 'text';
                btn.setAttribute('aria-pressed', 'true');
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                btn.setAttribute('aria-pressed', 'false');
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }
    </script>
</body>
</html>