@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div class="min-h-screen bg-white">
        <div class="w-full max-w-2xl mx-auto border-r border-gray-300 min-h-screen">
            <!-- Header -->
            <div class="bg-white border-b border-gray-300 sticky top-0 z-50">
                <div class="p-4 sm:p-6">
                    <h1 class="text-2xl font-bold text-black">Bookmarks</h1>
                    <p class="text-gray-600 text-sm">{{ $bookmarks->count() }} saved tweets</p>
                </div>
            </div>

            <!-- Bookmarked Tweets -->
            @if($bookmarks->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 px-4">
                    <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-black mb-2">No bookmarks yet</h2>
                    <p class="text-gray-500 text-center">When you bookmark a post, it'll show up here. Bookmark posts to come back to them later.</p>
                </div>
            @else
                <div class="space-y-0 divide-y divide-gray-300">
                    @foreach($bookmarks as $bookmark)
                        <div class="bg-white p-4 sm:p-6 hover:bg-gray-50 transition-colors cursor-pointer group border-b border-gray-300">
                            <div class="flex gap-3 sm:gap-4">
                                <!-- Profile Picture -->
                                <a href="{{ route('profile.show', $bookmark->tweet->user) }}" class="flex-shrink-0">
                                    @if($bookmark->tweet->user->profilePicture)
                                        <img src="{{ asset('storage/profile-pictures/' . $bookmark->tweet->user->profilePicture->path) }}" alt="{{ $bookmark->tweet->user->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                                            {{ strtoupper(substr($bookmark->tweet->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </a>

                                <!-- Tweet Content -->
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('profile.show', $bookmark->tweet->user) }}" class="inline-flex items-center gap-2 group/header">
                                        <span class="font-bold text-black group-hover/header:text-blue-500 text-sm sm:text-base">{{ $bookmark->tweet->user->name }}</span>
                                        <x-verified-badge :user="$bookmark->tweet->user" />
                                        <span class="text-gray-500 text-xs sm:text-sm">{{ $bookmark->tweet->created_at->diffForHumans() }}</span>
                                    </a>

                                    <p class="text-black text-sm sm:text-base mt-2 break-words">{{ $bookmark->tweet->content }}</p>

                                    <!-- Media Display -->
                                    @if($bookmark->tweet->media->count() > 0)
                                        <div class="grid grid-cols-2 gap-3 mt-3 rounded overflow-hidden">
                                            @foreach($bookmark->tweet->media as $media)
                                                @if($media->type === 'image')
                                                    <img 
                                                        src="{{ Storage::url($media->path) }}" 
                                                        alt="Post media"
                                                        class="rounded w-full h-auto object-cover max-h-96 hover:opacity-80 transition-opacity cursor-pointer"
                                                    >
                                                @else
                                                    <video 
                                                        controls 
                                                        class="rounded w-full h-auto max-h-96 bg-gray-200"
                                                    >
                                                        <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Post Actions -->
                                    <div class="flex items-center gap-2 sm:gap-4 mt-4 text-gray-500 border-t border-gray-300 pt-4">
                                        <!-- Like Button -->
                                        @auth
                                            <form action="{{ route('likes.toggle', $bookmark->tweet) }}" method="POST" class="flex items-center gap-2 group/like cursor-pointer hover:text-red-500 transition-colors">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2">
                                                    @if($bookmark->tweet->isLikedBy(auth()->user()))
                                                        <svg class="w-5 h-5 fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5 fill-gray-500 group-hover/like:fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                    @endif
                                                    @if($bookmark->tweet->likes_count > 0)<span class="text-sm">{{ $bookmark->tweet->likes_count }}</span>@endif
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 fill-gray-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                @if($bookmark->tweet->likes_count > 0)<span class="text-sm">{{ $bookmark->tweet->likes_count }}</span>@endif
                                            </div>
                                        @endauth

                                        <!-- Retweet Button -->
                                        @auth
                                            <form action="{{ $bookmark->tweet->isRetweetedBy(auth()->user()) ? route('retweets.destroy', $bookmark->tweet) : route('retweets.store', $bookmark->tweet) }}" method="POST" class="flex items-center gap-2 group/retweet cursor-pointer hover:text-green-500 transition-colors">
                                                @csrf
                                                @if($bookmark->tweet->isRetweetedBy(auth()->user()))
                                                    @method('DELETE')
                                                @endif
                                                <button type="submit" class="flex items-center gap-2">
                                                    @if($bookmark->tweet->isRetweetedBy(auth()->user()))
                                                        <svg class="w-5 h-5 fill-green-500" viewBox="0 0 24 24"><path d="M17.07 12.83c-.33 0-.65.06-.94.17.31-.66.76-1.27 1.33-1.77.34-.3.66-.62.95-.97.7-.82 1.12-1.89 1.12-3.08 0-2.48-2.02-4.5-4.5-4.5-2.28 0-4.18 1.71-4.46 3.9h2.04c.26-1.31 1.45-2.3 2.93-2.3 1.64 0 2.96 1.32 2.96 2.96 0 .84-.36 1.6-.97 2.16-.29.27-.62.53-.96.79-.65.53-1.4 1.13-1.87 1.88-.7 1.13-.73 2.5-.08 3.65l-.02.03c.27.55.7 1.04 1.24 1.4.5.32 1.07.5 1.68.5.07 0 .15 0 .22-.01.33 0 .65-.06.94-.17-.31.66-.76 1.27-1.33 1.77-.34.3-.66.62-.95.97-.7.82-1.12 1.89-1.12 3.08 0 2.48 2.02 4.5 4.5 4.5 2.28 0 4.18-1.71 4.46-3.9h-2.04c-.26 1.31-1.45 2.3-2.93 2.3-1.64 0-2.96-1.32-2.96-2.96 0-.84.36-1.6.97-2.16.29-.27.62-.53.96-.79.65-.53 1.4-1.13 1.87-1.88.7-1.13.73-2.5.08-3.65l.02-.03c-.27-.55-.7-1.04-1.24-1.4-.5-.32-1.07-.5-1.68-.5-.07 0-.15 0-.22.01z"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5 fill-gray-500 group-hover/retweet:fill-green-500" viewBox="0 0 24 24"><path d="M17.07 12.83c-.33 0-.65.06-.94.17.31-.66.76-1.27 1.33-1.77.34-.3.66-.62.95-.97.7-.82 1.12-1.89 1.12-3.08 0-2.48-2.02-4.5-4.5-4.5-2.28 0-4.18 1.71-4.46 3.9h2.04c.26-1.31 1.45-2.3 2.93-2.3 1.64 0 2.96 1.32 2.96 2.96 0 .84-.36 1.6-.97 2.16-.29.27-.62.53-.96.79-.65.53-1.4 1.13-1.87 1.88-.7 1.13-.73 2.5-.08 3.65l-.02.03c.27.55.7 1.04 1.24 1.4.5.32 1.07.5 1.68.5.07 0 .15 0 .22-.01.33 0 .65-.06.94-.17-.31.66-.76 1.27-1.33 1.77-.34.3-.66.62-.95.97-.7.82-1.12 1.89-1.12 3.08 0 2.48 2.02 4.5 4.5 4.5 2.28 0 4.18-1.71 4.46-3.9h-2.04c-.26 1.31-1.45 2.3-2.93 2.3-1.64 0-2.96-1.32-2.96-2.96 0-.84.36-1.6.97-2.16.29-.27.62-.53.96-.79.65-.53 1.4-1.13 1.87-1.88.7-1.13.73-2.5.08-3.65l.02-.03c-.27-.55-.7-1.04-1.24-1.4-.5-.32-1.07-.5-1.68-.5-.07 0-.15 0-.22.01z"></path></svg>
                                                    @endif
                                                    @if($bookmark->tweet->retweets_count > 0)<span class="text-sm">{{ $bookmark->tweet->retweets_count }}</span>@endif
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 fill-gray-500" viewBox="0 0 24 24"><path d="M17.07 12.83c-.33 0-.65.06-.94.17.31-.66.76-1.27 1.33-1.77.34-.3.66-.62.95-.97.7-.82 1.12-1.89 1.12-3.08 0-2.48-2.02-4.5-4.5-4.5-2.28 0-4.18 1.71-4.46 3.9h2.04c.26-1.31 1.45-2.3 2.93-2.3 1.64 0 2.96 1.32 2.96 2.96 0 .84-.36 1.6-.97 2.16-.29.27-.62.53-.96.79-.65.53-1.4 1.13-1.87 1.88-.7 1.13-.73 2.5-.08 3.65l-.02.03c.27.55.7 1.04 1.24 1.4.5.32 1.07.5 1.68.5.07 0 .15 0 .22-.01.33 0 .65-.06.94-.17-.31.66-.76 1.27-1.33 1.77-.34.3-.66.62-.95.97-.7.82-1.12 1.89-1.12 3.08 0 2.48 2.02 4.5 4.5 4.5 2.28 0 4.18-1.71 4.46-3.9h-2.04c-.26 1.31-1.45 2.3-2.93 2.3-1.64 0-2.96-1.32-2.96-2.96 0-.84.36-1.6.97-2.16.29-.27.62-.53.96-.79.65-.53 1.4-1.13 1.87-1.88.7-1.13.73-2.5.08-3.65l.02-.03c-.27-.55-.7-1.04-1.24-1.4-.5-.32-1.07-.5-1.68-.5-.07 0-.15 0-.22.01z"></path></svg>
                                                @if($bookmark->tweet->retweets_count > 0)<span class="text-sm">{{ $bookmark->tweet->retweets_count }}</span>@endif
                                            </div>
                                        @endauth

                                        <!-- Bookmark Button -->
                                        @auth
                                            <form action="{{ $bookmark->tweet->isBookmarkedBy(auth()->user()) ? route('bookmarks.destroy', $bookmark->tweet) : route('bookmarks.store', $bookmark->tweet) }}" method="POST" class="flex items-center gap-2 group/bookmark cursor-pointer hover:text-yellow-500 transition-colors ml-auto">
                                                @csrf
                                                @if($bookmark->tweet->isBookmarkedBy(auth()->user()))
                                                    @method('DELETE')
                                                @endif
                                                <button type="submit" title="Remove from bookmarks" class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 {{ $bookmark->tweet->isBookmarkedBy(auth()->user()) ? 'fill-yellow-500' : 'fill-gray-500 group-hover/bookmark:fill-yellow-500' }}" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v16l7-3 7 3V5c0-1.1.89-2 2-2h-2z"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex items-center gap-2 ml-auto">
                                                <svg class="w-5 h-5 fill-gray-500" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v16l7-3 7 3V5c0-1.1.89-2 2-2h-2z"></path></svg>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 text-center pb-8">
                <a href="{{ route('tweets.index') }}" class="inline-block text-blue-400 font-bold hover:text-blue-300 transition">
                    ← Back to Feed
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
