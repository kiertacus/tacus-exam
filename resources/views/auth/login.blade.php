<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-black mb-2">Welcome back</h1>
        <p class="text-gray-600 text-sm">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-black font-semibold" />
            <x-text-input id="email" class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-black placeholder-gray-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="your@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-black font-semibold" />

            <x-text-input id="password" class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-black placeholder-gray-400"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Enter your password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-700 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-full transition-all duration-200 text-sm sm:text-base">
            {{ __('Sign In') }}
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">or</span>
        </div>
    </div>

    <!-- Sign Up Link -->
    <div class="text-center">
        <p class="text-gray-700 text-sm">
            Don't have an account? 
            <a class="text-blue-600 hover:text-blue-700 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('register') }}">
                Sign Up
            </a>
        </p>
    </div>
</x-guest-layout>

