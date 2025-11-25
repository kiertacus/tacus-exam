@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div class="min-h-screen bg-black">
        <div class="flex h-screen">
            <!-- Left Sidebar (Desktop Only) -->
            <div class="hidden lg:flex w-64 bg-black border-r border-gray-700 flex-col fixed left-0 top-0 h-screen">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-700">
                    <a href="{{ route('tweets.index') }}" class="text-3xl font-bold text-white">
                        𝕏
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-4">
                    <a href="{{ route('tweets.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-500 hover:text-white hover:bg-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h2v8H3zm4-8h2v16H7zm4-2h2v18h-2zm4 4h2v14h-2zm4-1h2v15h-2z"></path></svg>
                        <span class="hidden xl:block">Feed</span>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl font-bold text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"></path></svg>
                        <span class="hidden xl:block">Notifications</span>
                    </a>

                    <a href="{{ route('messages.conversations') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-500 hover:text-white hover:bg-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path></svg>
                        <span class="hidden xl:block">Messages</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-500 hover:text-white hover:bg-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                        <span class="hidden xl:block">Profile</span>
                    </a>
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-full text-gray-500 hover:text-white hover:bg-gray-900 text-2xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 5H5v12h14V6z"></path></svg>
                            <span class="hidden xl:block">Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 lg:ml-64 max-w-2xl mx-auto w-full border-r border-gray-700">
                <!-- Header -->
                <div class="sticky top-0 z-40 bg-black border-b border-gray-700 px-3 sm:px-4 py-2 sm:py-3 backdrop-blur-sm bg-opacity-80">
                    <h1 class="text-xl sm:text-2xl font-bold text-white">Notifications</h1>
                </div>

                <!-- Notifications List -->
                @if($notifications->isEmpty())
                    <div class="p-6 sm:p-12 text-center">
                        <div class="text-4xl sm:text-6xl mb-4">🔔</div>
                        <p class="text-gray-300 text-base sm:text-lg font-semibold mb-2">No notifications yet</p>
                        <p class="text-gray-500 text-xs sm:text-base">When someone likes or comments on your posts, you'll see it here</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-700">
                        @foreach($notifications as $notification)
                            <div class="px-3 sm:px-4 py-3 sm:py-4 hover:bg-gray-900 transition cursor-pointer">
                                <div class="flex items-start gap-2 sm:gap-4">
                                    <!-- User Avatar -->
                                    <a href="{{ route('profile.show', $notification->fromUser) }}" class="flex-shrink-0">
                                        @if($notification->fromUser->profilePicture)
                                            <img src="{{ asset('storage/profile-pictures/' . $notification->fromUser->profilePicture->path) }}" alt="{{ $notification->fromUser->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                                                {{ strtoupper(substr($notification->fromUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </a>

                                    <!-- Notification Content -->
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('profile.show', $notification->fromUser) }}" class="font-bold text-white hover:text-blue-400 text-xs sm:text-base">
                                            {{ $notification->fromUser->name }}
                                        </a>
                                        <p class="text-gray-200 text-xs sm:text-base break-words">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>

                                    <!-- Icon based on notification type -->
                                    <div class="flex-shrink-0 text-xl sm:text-2xl">
                                        @switch($notification->type)
                                            @case('like')
                                                ❤️
                                                @break
                                            @case('comment')
                                                💬
                                                @break
                                            @case('follow')
                                                👤
                                                @break
                                            @default
                                                🔔
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
