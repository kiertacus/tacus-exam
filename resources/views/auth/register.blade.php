<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-black mb-2">Create account</h1>
        <p class="text-gray-600 text-sm">Join Chirper to share your thoughts</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-black font-semibold" />
            <x-text-input id="name" class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-black placeholder-gray-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-black font-semibold" />
            <x-text-input id="email" class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-black placeholder-gray-400" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="your@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-black font-semibold" />

            <x-text-input id="password" class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-black placeholder-gray-400"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Create a strong password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-black font-semibold" />

            <x-text-input id="password_confirmation" class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-black placeholder-gray-400"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-full transition-all duration-200 text-sm sm:text-base">
            {{ __('Create Account') }}
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

    <!-- Sign In Link -->
    <div class="text-center">
        <p class="text-gray-700 text-sm">
            Already have an account? 
            <a class="text-blue-600 hover:text-blue-700 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('login') }}">
                Sign In
            </a>
        </p>
    </div>
</x-guest-layout>

