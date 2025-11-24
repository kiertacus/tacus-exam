@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div class="min-h-screen bg-white">
        <div class="flex h-screen">
            <!-- Left Sidebar (Desktop Only) -->
            <div class="hidden lg:flex w-64 bg-white border-r border-gray-200 flex-col fixed left-0 top-0 h-screen">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-200">
                    <a href="{{ route('tweets.index') }}" class="text-3xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-transparent bg-clip-text">
                        Chirper
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-4">
                    <a href="{{ route('tweets.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-700 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h2v8H3zm4-8h2v16H7zm4-2h2v18h-2zm4 4h2v14h-2zm4-1h2v15h-2z"></path></svg>
                        <span class="hidden xl:block">Feed</span>
                    </a>

                    <a href="{{ route('search.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-700 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h9v14z"></path></svg>
                        <span class="hidden xl:block">Explore</span>
                    </a>

                    <a href="{{ route('messages.conversations') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-700 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path></svg>
                        <span class="hidden xl:block">Messages</span>
                    </a>

                    @auth
                        <a href="{{ route('notifications.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-700 hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"></path></svg>
                            <span class="hidden xl:block">Notifications</span>
                        </a>
                    @endauth

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-700 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                        <span class="hidden xl:block">Profile</span>
                    </a>
                </nav>

                <!-- Logout -->
                @auth
                    <div class="p-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-full text-gray-700 hover:bg-gray-100 text-2xl">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 5H5v12h14V6z"></path></svg>
                                <span class="hidden xl:block">Logout</span>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            <!-- Main Content -->
            <div class="flex-1 lg:ml-64 max-w-2xl mx-auto w-full border-r border-gray-200">
                <!-- Hashtag Header -->
                <div class="sticky top-0 z-40 bg-white border-b border-gray-200 px-4 py-4">
                    <h1 class="text-3xl font-bold text-gray-900">#{{ $hashtag->name }}</h1>
                    <p class="text-gray-600 text-sm mt-1">{{ $hashtag->count }} posts</p>
                </div>

                <!-- Posts -->
                @if($tweets->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-gray-600 text-lg">No posts with this hashtag yet</p>
                    </div>
                @else
                    @foreach($tweets as $tweet)
                        <!-- Post Card (Instagram Style) -->
                        <div class="border-b border-gray-200 bg-white">
                            <!-- Post Header -->
                            <div class="p-4 flex items-center justify-between">
                                <a href="{{ route('profile.show', $tweet->user) }}" class="flex items-center gap-3 flex-1 group">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $tweet->user->getAvatarColors() }} flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-blue-600">{{ $tweet->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $tweet->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            </div>

                            <!-- Post Image/Video -->
                            @if($tweet->media->count() > 0)
                                <div class="bg-black">
                                    @foreach($tweet->media as $media)
                                        @if($media->type === 'image')
                                            <img 
                                                src="{{ Storage::url($media->path) }}" 
                                                alt="Post"
                                                class="w-full aspect-square object-cover"
                                            >
                                        @else
                                            <video 
                                                controls 
                                                class="w-full aspect-square object-cover"
                                            >
                                                <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <!-- Post Actions -->
                            <div class="p-4 space-y-4">
                                <!-- Like, Comment, Share Buttons -->
                                <div class="flex gap-4">
                                    @auth
                                        <form action="{{ route('likes.toggle', $tweet) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="hover:text-gray-500 transition">
                                                @if ($tweet->isLikedBy(auth()->user()))
                                                    <svg class="w-6 h-6 fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                @else
                                                    <svg class="w-6 h-6 hover:fill-gray-300" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endauth
                                </div>

                                <!-- Like Count -->
                                <p class="font-bold text-gray-900">{{ $tweet->likes_count }} {{ Str::plural('like', $tweet->likes_count) }}</p>

                                <!-- Caption -->
                                <div>
                                    <p class="text-gray-900">
                                        <a href="{{ route('profile.show', $tweet->user) }}" class="font-bold hover:text-blue-600">{{ $tweet->user->name }}</a>
                                        <span class="ml-2">
                                            {!! preg_replace('/(#\w+)/i', '<a href="' . route('hashtags.show', ['tag' => '']) . '\1" class="text-blue-600 hover:text-blue-700">\1</a>', $tweet->content) !!}
                                        </span>
                                    </p>
                                </div>

                                <!-- Timestamp -->
                                <p class="text-xs text-gray-500 uppercase">{{ $tweet->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
