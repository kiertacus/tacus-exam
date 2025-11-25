@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <!-- Notification Toast Component -->
    <x-notification-toast />

    <div class="min-h-screen bg-white flex flex-col">
        <div class="flex flex-col lg:flex-row flex-1">
            <!-- Main Feed -->
            <div class="flex-1 w-full lg:max-w-2xl border-r border-gray-300 min-h-screen">
                <!-- Stories Section (Instagram Style) -->
                <div class="border-b border-gray-300 bg-white sticky top-16 z-40">
                    <div class="p-4 overflow-x-auto scrollbar-hide">
                        <div class="flex gap-4 min-w-max pb-2">
                            @auth
                                <!-- Your Story (Click to add) -->
                                <button onclick="openStoryModal()" class="flex flex-col items-center gap-2 group cursor-pointer flex-shrink-0" title="Add your story">
                                    @if(auth()->user()->profilePicture)
                                        <img src="{{ asset('storage/profile-pictures/' . auth()->user()->profilePicture->path) }}" alt="{{ auth()->user()->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-4 border-blue-500 group-hover:border-blue-600 transition">
                                    @else
                                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 p-1 border-4 border-blue-500 group-hover:border-blue-600 transition flex items-center justify-center">
                                            <span class="text-xl sm:text-2xl text-white font-bold">+</span>
                                        </div>
                                    @endif
                                    <span class="text-xs text-center text-gray-500 whitespace-nowrap">Your Story</span>
                                </button>
                            @endauth

                            <!-- Users with Active Stories -->
                            @php
                                $storiesUsers = \App\Models\User::whereHas('stories', function($q) {
                                    $q->where('expires_at', '>', now());
                                })->where('id', '!=', auth()->id())
                                ->inRandomOrder()
                                ->limit(15)
                                ->get();
                            @endphp
                            
                            @forelse($storiesUsers as $user)
                                <button onclick="viewStories({{ $user->id }})" class="flex flex-col items-center gap-2 group cursor-pointer flex-shrink-0" title="View {{ $user->name }}'s story">
                                    @if($user->profilePicture)
                                        <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-4 border-gradient-to-br from-pink-500 to-purple-500 group-hover:opacity-80 transition" style="border-image: linear-gradient(135deg, #ec4899, #a855f7) 1;">
                                    @else
                                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg border-4 group-hover:opacity-80 transition" style="border-image: linear-gradient(135deg, #ec4899, #a855f7) 1;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-xs text-center text-gray-700 whitespace-nowrap">{{ $user->name }}</span>
                                </button>
                            @empty
                                <div class="text-center py-6 text-gray-500 text-sm">
                                    No active stories. Be the first! 📸
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Create Post Form (Always Show for Auth) -->
                @auth
                    <div class="p-3 sm:p-4 bg-white border-b border-gray-300">
                        <div class="flex gap-3 sm:gap-4">
                            <!-- Profile Picture -->
                            @if(auth()->user()->profilePicture)
                                <img src="{{ asset('storage/profile-pictures/' . auth()->user()->profilePicture->path) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            
                            <!-- Form -->
                            <form action="{{ route('tweets.store') }}" method="POST" enctype="multipart/form-data" class="flex-1" id="create-tweet-form">
                                @csrf
                                <div class="space-y-2 sm:space-y-3">
                                    <!-- Textarea -->
                                    <textarea
                                        name="content"
                                        placeholder="What's on your mind?"
                                        maxlength="280"
                                        class="w-full border-0 outline-none text-sm sm:text-lg text-black placeholder-gray-500 resize-none focus:ring-0 bg-white"
                                        rows="3"
                                    ></textarea>

                                    <!-- Media Upload Preview -->
                                    <div id="media-preview" class="hidden">
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="preview-grid"></div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                        <div class="flex gap-1 sm:gap-2">
                                            <!-- Image Upload -->
                                            <label class="cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"></path></svg>
                                                <input type="file" name="media[]" accept="image/*,video/*" multiple hidden id="media-input" onchange="previewMedia()">
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <button
                                            type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-1.5 px-4 sm:py-2 sm:px-6 rounded-full transition disabled:opacity-50 text-sm sm:text-base shadow-md hover:shadow-lg"
                                            id="submit-btn"
                                        >
                                            <span id="submit-text">Post</span>
                                        </button>
                                    </div>

                                    <!-- Character Count -->
                                    <div class="text-xs text-gray-500 text-right">
                                        <span id="char-count">0</span>/280
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Not Logged In -->
                    <div class="p-8 bg-white border-b border-gray-300 text-center">
                        <p class="text-gray-700 mb-4">Sign in to post and connect with others</p>
                        <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full">
                            Sign In
                        </a>
                    </div>
                @endauth

                <!-- Feed Posts -->
                <div>
                    @if ($tweets->isEmpty())
                        <div class="p-12 text-center">
                            <div class="text-6xl mb-4">📸</div>
                            <p class="text-gray-800 text-lg font-semibold mb-2">No posts yet</p>
                            <p class="text-gray-700">Start following people to see their posts here</p>
                        </div>
                    @else
                        @foreach ($tweets as $tweet)
                            <!-- Post Card (Twitter Style) -->
                            <div class="border-b border-gray-300 bg-white hover:bg-gray-50 transition cursor-pointer">
                                <!-- Post Header -->
                                <div class="p-3 sm:p-4 flex items-center justify-between">
                                    <a href="{{ route('profile.show', $tweet->user) }}" class="flex items-center gap-2 sm:gap-3 flex-1 group">
                                        @if($tweet->user->profilePicture)
                                            <img src="{{ asset('storage/profile-pictures/' . $tweet->user->profilePicture->path) }}" alt="{{ $tweet->user->name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                                                {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-black group-hover:text-blue-600 text-sm sm:text-base">
                                                {{ $tweet->user->name }}
                                                <x-verified-badge :user="$tweet->user" />
                                            </p>
                                            <p class="text-xs text-gray-600">{{ $tweet->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>

                                    @can('update', $tweet)
                                        <button type="button" onclick="toggleMenu('menu-{{ $tweet->id }}')" class="text-gray-500 hover:text-blue-500">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                                        </button>
                                        <div id="menu-{{ $tweet->id }}" class="hidden absolute right-4 mt-8 bg-white rounded-lg shadow-lg border border-gray-300 z-20">
                                            <a href="{{ route('tweets.edit', $tweet) }}" class="block px-4 py-2 text-blue-500 hover:bg-gray-800 rounded-t-lg text-sm">Edit</a>
                                            <form action="{{ route('tweets.destroy', $tweet) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-800 rounded-b-lg text-sm" onclick="return confirm('Delete this post?')">Delete</button>
                                            </form>
                                        </div>
                                    @endcan
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
                                <div class="p-3 sm:p-4 space-y-3 sm:space-y-4 border-t border-gray-300">
                                    <!-- Like, Retweet, Bookmark, Comment Buttons -->
                                    <div class="flex gap-2 sm:gap-4 text-gray-500 text-xs sm:text-sm">
                                        @auth
                                            <!-- Like Button -->
                                            <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="group flex items-center gap-2 hover:text-red-500 transition cursor-pointer">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 hover:text-red-500 transition">
                                                    @if ($tweet->isLikedBy(auth()->user()))
                                                        <svg class="w-5 h-5 fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                        <span>{{ $tweet->likes_count }}</span>
                                                    @else
                                                        <svg class="w-5 h-5 hover:text-red-500" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                                        @if($tweet->likes_count > 0)<span>{{ $tweet->likes_count }}</span>@endif
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Comment Button -->
                                            <a href="{{ route('profile.show', $tweet->user) }}" class="flex items-center gap-2 hover:text-blue-500 transition">
                                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 12H6v-2h14v2zm0-3H6V9h14v2zm0-3H6V6h14v2z"></path></svg>
                                                @if($tweet->comments->count() > 0)<span>{{ $tweet->comments->count() }}</span>@endif
                                            </a>

                                            <!-- Retweet Button -->
                                            <form action="{{ route('retweets.store', $tweet) }}" method="POST" class="flex items-center gap-2 hover:text-green-500 transition cursor-pointer">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 hover:text-green-500 transition">
                                                    @if ($tweet->isRetweetedBy(auth()->user()))
                                                        <svg class="w-5 h-5 fill-green-500" viewBox="0 0 24 24"><path d="M23 3v6h-6M1 21v-6h6M3.51 9a9 9 0 0114.85-4.95M1.21 15H7a2 2 0 012 2v.5a.5.5 0 00.5.5H10M23 19l-1.5-1.5M20.29 15a9 9 0 10-14.68 5"/></svg>
                                                        <span>{{ $tweet->retweets->count() }}</span>
                                                    @else
                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path d="M1 4v6h6M23 20v-6h-6"/><polyline points="20.49 9 15 14.5 9.51 9"></polyline><polyline points="3.51 15 9 9.5 14.49 15"></polyline></svg>
                                                        @if($tweet->retweets->count() > 0)<span>{{ $tweet->retweets->count() }}</span>@endif
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Bookmark Button -->
                                            <form action="{{ route('bookmarks.store', $tweet) }}" method="POST" class="flex items-center gap-2 hover:text-yellow-500 transition cursor-pointer">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 hover:text-yellow-500 transition">
                                                    @if ($tweet->isBookmarkedBy(auth()->user()))
                                                        <svg class="w-5 h-5 fill-yellow-500" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                                                    @else
                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="flex items-center gap-2 hover:text-red-500 transition">
                                                <svg class="w-5 h-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                            </a>
                                        @endauth
                                    </div>

                                    <!-- Caption -->
                                    <div>
                                        <p class="text-black text-sm sm:text-base">
                                            <a href="{{ route('profile.show', $tweet->user) }}" class="font-bold hover:text-blue-600">{{ $tweet->user->name }}</a>
                                            <x-verified-badge :user="$tweet->user" />
                                            <span class="ml-2 text-gray-700">
                                                {!! preg_replace_callback(
                                                    '/(#\w+)/i',
                                                    function($matches) {
                                                        $tag = $matches[1];
                                                        return '<a href="' . route('hashtags.show', ['tag' => substr($tag, 1)]) . '" class="text-blue-400 hover:text-blue-300">' . $tag . '</a>';
                                                    },
                                                    $tweet->content
                                                ) !!}
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Comments Section -->
                                    <div class="border-t border-gray-300 pt-3 sm:pt-4">
                                        <!-- Comments List -->
                                        @if($tweet->comments->count() > 0)
                                            <div class="space-y-3 sm:space-y-4 mb-4 max-h-48 overflow-y-auto">
                                                @foreach($tweet->comments->take(5) as $comment)
                                                    <div class="flex gap-2 sm:gap-3 text-xs sm:text-sm hover:bg-gray-100 p-2 rounded transition">
                                                        <!-- Comment Author Avatar -->
                                                        <div class="flex-shrink-0">
                                                            @if($comment->user->profilePicture)
                                                                <img src="{{ asset('storage/profile-pictures/' . $comment->user->profilePicture->path) }}" alt="{{ $comment->user->name }}" class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover">
                                                            @else
                                                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <a href="{{ route('profile.show', $comment->user) }}" class="font-bold text-black hover:text-blue-600">
                                                                {{ $comment->user->name }}
                                                            </a>
                                                            <p class="text-gray-200 break-words">{{ $comment->content }}</p>
                                                        </div>
                                                        @can('delete', $comment)
                                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="flex-shrink-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-500 hover:text-red-400 text-xs" onclick="return confirm('Delete this comment?')">Delete</button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if($tweet->comments->count() > 5)
                                                <p class="text-xs text-gray-500 mb-4">
                                                    <a href="#" onclick="alert('Viewing {{ $tweet->comments->count() }} comments')" class="text-blue-400 hover:text-blue-300">
                                                        View all {{ $tweet->comments->count() }} comments
                                                    </a>
                                                </p>
                                            @endif
                                        @endif

                                        <!-- Add Comment Form -->
                                        @auth
                                            <form action="{{ route('comments.store', $tweet) }}" method="POST" class="flex gap-2 sm:gap-3 items-center">
                                                @csrf
                                                <div class="flex-shrink-0">
                                                    @if(auth()->user()->profilePicture)
                                                        <img src="{{ asset('storage/profile-pictures/' . auth()->user()->profilePicture->path) }}" alt="{{ auth()->user()->name }}" class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover">
                                                    @else
                                                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <input 
                                                    type="text"
                                                    name="content"
                                                    placeholder="Post your reply..."
                                                    class="flex-1 border-0 bg-transparent text-xs sm:text-sm px-0 py-2 focus:ring-0 placeholder-gray-500 text-black"
                                                >
                                                <button type="submit" class="text-blue-400 font-bold text-xs sm:text-sm hover:text-blue-300 flex-shrink-0">Reply</button>
                                            </form>
                                        @else
                                            <p class="text-xs text-gray-500"><a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Sign in</a> to reply</p>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Right Sidebar (Trending & Suggested Users) - Desktop Only -->
            <div class="hidden lg:block w-64 xl:w-80 bg-white border-l border-gray-300 p-4 lg:p-6 overflow-y-auto max-h-screen">
                <!-- Search Bar -->
                <div class="mb-4 lg:mb-6">
                    <form action="{{ route('search.index') }}" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="q"
                            placeholder="Search..."
                            class="w-full bg-gray-100 border border-gray-300 rounded-full py-2 px-3 lg:px-4 text-xs lg:text-sm text-black placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </form>
                </div>

                <!-- Trending Component -->
                <x-trending-sidebar />

                <!-- Suggested For You -->
                <div class="mt-6 lg:mt-8 bg-gray-50 rounded-lg border border-gray-300 p-3 lg:p-4">
                    <div class="flex justify-between items-center mb-3 lg:mb-4">
                        <h3 class="font-bold text-xs lg:text-base text-black">Suggested For You</h3>
                        <a href="{{ route('search.index') }}" class="text-blue-600 text-xs lg:text-sm hover:text-blue-700">See More</a>
                    </div>

                    <div class="space-y-3 lg:space-y-4">
                        @php
                            $suggestedForYou = \App\Models\User::inRandomOrder()
                                ->where('id', '!=', auth()->id() ?? 0)
                                ->limit(10)
                                ->get();
                        @endphp
                        @foreach($suggestedForYou->take(5) as $user)
                            @if(!auth()->check() || auth()->user()->id !== $user->id && !auth()->user()->isFollowing($user))
                                <div class="flex items-center justify-between p-3 hover:bg-gray-200 rounded transition cursor-pointer">
                                    <a href="{{ route('profile.show', $user) }}" class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                        @if($user->profilePicture)
                                            <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover flex-shrink-0">
                                        @else
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-xs sm:text-sm text-black truncate">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-600">Popular</p>
                                        </div>
                                    </a>

                                    @auth
                                        <form action="{{ route('follow.store', $user) }}" method="POST" class="flex-shrink-0">
                                            @csrf
                                            <button type="submit" class="text-white font-bold text-xs sm:text-sm bg-blue-600 hover:bg-blue-700 px-4 py-1 rounded-full">Follow</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="text-white font-bold text-xs sm:text-sm bg-blue-600 hover:bg-blue-700 px-4 py-1 rounded-full flex-shrink-0">Follow</a>
                                    @endauth
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-6 lg:mt-8 text-xs lg:text-sm text-gray-600 space-y-1 lg:space-y-2">
                    <p>About • Help • Press • API • Jobs • Privacy • Terms • Locations • Language</p>
                    <p>© 2025 Chirper from Tacus</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu(id) {
            const menu = document.getElementById(id);
            menu.classList.toggle('hidden');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[id^="menu-"]') && !event.target.closest('button[onclick*="toggleMenu"]')) {
                document.querySelectorAll('[id^="menu-"]').forEach(el => {
                    if (!el.classList.contains('hidden')) {
                        el.classList.add('hidden');
                    }
                });
            }
        });

        // Character counter
        const textarea = document.querySelector('textarea[name="content"]');
        const charCount = document.getElementById('char-count');
        const submitBtn = document.getElementById('submit-btn');

        if (textarea) {
            textarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                submitBtn.disabled = this.value.length === 0 && document.getElementById('media-input').files.length === 0;
            });
        }

        // Media preview
        function previewMedia() {
            const input = document.getElementById('media-input');
            const preview = document.getElementById('media-preview');
            const previewGrid = document.getElementById('preview-grid');
            
            previewGrid.innerHTML = '';
            
            if (input.files.length > 0) {
                preview.classList.remove('hidden');
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative bg-gray-100 rounded aspect-square overflow-hidden';
                        
                        if (file.type.startsWith('image/')) {
                            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        } else {
                            div.innerHTML = `<video src="${e.target.result}" class="w-full h-full object-cover"></video>`;
                        }
                        
                        previewGrid.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
                submitBtn.disabled = false;
            } else {
                preview.classList.add('hidden');
                submitBtn.disabled = textarea.value.length === 0;
            }
        }

        // Tweet Form Submission with Visible Feedback
        const tweetForm = document.getElementById('create-tweet-form');
        if (tweetForm) {
            tweetForm.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const originalText = submitText.textContent;
                
                // Show loading state - keep text visible
                submitBtn.disabled = true;
                submitText.textContent = '📤 Posting...';
                submitBtn.classList.add('bg-blue-700');
                submitBtn.classList.remove('bg-blue-600');
            });
        }

        // Story Modal Functions
        function openStoryModal() {
            document.getElementById('storyModal').classList.remove('hidden');
        }

        function closeStoryModal() {
            document.getElementById('storyModal').classList.add('hidden');
        }

        function validateStoryForm() {
            const fileInput = document.getElementById('storyMediaInput');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Please select an image or video for your story');
                return false;
            }
            const file = fileInput.files[0];
            const maxSize = 50 * 1024 * 1024; // 50MB
            if (file.size > maxSize) {
                alert('File is too large. Maximum size is 50MB');
                return false;
            }
            return true;
        }

        function updateStoryButtonText() {
            if (validateStoryForm()) {
                const submitText = document.getElementById('story-submit-text');
                submitText.textContent = '📤 Posting...';
            }
        }

        // Story Viewer Functions
        let currentStoryUserId = null;
        let currentStories = [];
        let currentStoryIndex = 0;

        function viewStories(userId) {
            currentStoryUserId = userId;
            currentStoryIndex = 0;
            
            // Fetch stories for this user
            fetch(`/api/stories/${userId}`)
                .then(response => response.json())
                .then(data => {
                    currentStories = data;
                    if (currentStories.length > 0) {
                        displayCurrentStory();
                        document.getElementById('storyViewerModal').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error loading stories:', error);
                    alert('Error loading stories');
                });
        }

        function displayCurrentStory() {
            if (currentStories.length === 0) return;
            
            const story = currentStories[currentStoryIndex];
            const contentDiv = document.getElementById('storyViewerContent');
            
            // Clear previous content
            contentDiv.innerHTML = '';
            
            // Display media
            if (story.type === 'image') {
                const img = document.createElement('img');
                img.src = `/storage/${story.media_path}`;
                img.className = 'w-full h-full object-contain';
                contentDiv.appendChild(img);
            } else {
                const video = document.createElement('video');
                video.src = `/storage/${story.media_path}`;
                video.className = 'w-full h-full object-contain';
                video.controls = true;
                video.autoplay = true;
                contentDiv.appendChild(video);
            }
            
            // Update user info
            const userInfoDiv = document.getElementById('storyViewerUserInfo');
            userInfoDiv.innerHTML = `
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                    ${story.user_name ? story.user_name.charAt(0).toUpperCase() : '?'}
                </div>
                <div class="flex-1">
                    <div class="font-bold text-white">${story.user_name || 'Unknown'}</div>
                    <div class="text-xs text-gray-300">${formatTime(story.created_at)}</div>
                </div>
            `;
        }

        function previousStory() {
            if (currentStories.length > 0) {
                currentStoryIndex = (currentStoryIndex - 1 + currentStories.length) % currentStories.length;
                displayCurrentStory();
            }
        }

        function nextStory() {
            if (currentStories.length > 0) {
                currentStoryIndex = (currentStoryIndex + 1) % currentStories.length;
                displayCurrentStory();
            }
        }

        function closeStoryViewer() {
            document.getElementById('storyViewerModal').classList.add('hidden');
            currentStoryUserId = null;
            currentStories = [];
            currentStoryIndex = 0;
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            
            if (diff < 60) return 'just now';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            return date.toLocaleDateString();
        }

        function previewStoryMedia() {
            const input = document.getElementById('storyMediaInput');
            const preview = document.getElementById('storyPreview');
            
            if (input.files.length > 0) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    } else {
                        preview.innerHTML = `<video src="${e.target.result}" class="w-full h-full object-cover" controls></video>`;
                    }
                    preview.parentElement.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Close modal when clicking outside
        document.getElementById('storyModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeStoryModal();
            }
        });
    </script>

    <!-- Story Viewer Modal -->
    <div id="storyViewerModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md h-screen sm:h-auto sm:max-h-screen bg-black rounded-lg overflow-hidden">
            <!-- Close Button -->
            <button onclick="closeStoryViewer()" class="absolute top-4 right-4 text-white text-2xl z-10 hover:text-gray-300">×</button>
            
            <!-- Story Image/Video -->
            <div id="storyViewerContent" class="w-full h-full sm:h-96 bg-black flex items-center justify-center">
                <div class="text-white text-center">Loading...</div>
            </div>
            
            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/50 to-transparent p-4 text-white">
                <div id="storyViewerUserInfo" class="flex items-center gap-3">
                    <!-- User info will be populated here -->
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button onclick="previousStory()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300 z-10">‹</button>
            <button onclick="nextStory()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300 z-10">›</button>
        </div>
    </div>

    <!-- Story Modal -->
    <div id="storyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Add a Story</h2>
                <button onclick="closeStoryModal()" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
            </div>
            
            <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        @foreach($errors->all() as $error)
                            <p class="text-red-700 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <!-- Media Preview -->
                <div id="storyPreviewContainer" class="hidden bg-gray-100 rounded-lg overflow-hidden h-64 flex items-center justify-center">
                    <div id="storyPreview"></div>
                </div>
                
                <!-- Media Upload -->
                <label class="block cursor-pointer">
                    <div class="border-2 border-dashed border-blue-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                        <svg class="w-8 h-8 mx-auto mb-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"></path>
                        </svg>
                        <p class="text-gray-600">Click to upload image or video</p>
                        <p class="text-xs text-gray-500 mt-1">Max 50MB - Image or Video</p>
                    </div>
                    <input 
                        type="file" 
                        name="media" 
                        id="storyMediaInput"
                        accept="image/*,video/*" 
                        class="hidden" 
                        required 
                        onchange="previewStoryMedia()"
                    >
                </label>
                
                <!-- Caption -->
                <textarea 
                    name="caption" 
                    placeholder="Add a caption (optional)..."
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="2"
                    maxlength="500"
                ></textarea>
                
                <!-- Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700">
                    <p class="font-semibold mb-1">⏰ Story expires in 24 hours</p>
                    <p>Your story will automatically disappear after 24 hours from posting</p>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeStoryModal()" 
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
                        onclick="updateStoryButtonText()"
                    >
                        <span id="story-submit-text">Post Story</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Smooth transitions */
        * {
            transition: background-color 0.2s, color 0.2s;
        }

        /* Story circle glow on hover */
        .group:hover {
            opacity: 0.7;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</x-app-layout>