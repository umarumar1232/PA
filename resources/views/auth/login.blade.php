<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
            
        </div>
    </form>
    <div class="mt-6">
    <div class="flex items-center my-4">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-4 text-gray-500 text-sm">ATAU</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>

    <a href="{{ url('/auth/google') }}"
       class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition text-sm font-medium text-gray-700">
       
        <svg class="w-5 h-5" viewBox="0 0 48 48">
            <path fill="#EA4335" d="M24 9.5c3.9 0 7.4 1.4 10.1 4.1l7.5-7.5C36.5 2.2 30.7 0 24 0 14.6 0 6.5 5.4 2.5 13.2l8.8 6.8C13.4 13.8 18.2 9.5 24 9.5z"/>
            <path fill="#34A853" d="M46.1 24.5c0-1.7-.1-3.4-.4-5H24v9.5h12.4c-.5 2.8-2 5.1-4.3 6.7l6.6 5.1c3.9-3.6 6.4-9 6.4-16.3z"/>
            <path fill="#4A90E2" d="M11.3 28c-1-2.8-1-5.8 0-8.6l-8.8-6.8C.9 16.1 0 20 0 24s.9 7.9 2.5 11.4l8.8-6.8z"/>
            <path fill="#FBBC05" d="M24 48c6.7 0 12.3-2.2 16.4-6l-6.6-5.1c-2 1.4-4.6 2.3-9.8 2.3-5.8 0-10.6-4.3-12.7-10.5l-8.8 6.8C6.5 42.6 14.6 48 24 48z"/>
        </svg>

        Login dengan Google
    </a>
    </div>
</x-guest-layout>
