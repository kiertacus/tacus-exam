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
                    <a href="{{ route('tweets.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl {{ request()->routeIs('tweets.index') ? 'font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h2v8H3zm4-8h2v16H7zm4-2h2v18h-2zm4 4h2v14h-2zm4-1h2v15h-2z"></path></svg>
                        <span class="hidden xl:block">Feed</span>
                    </a>

                    <a href="{{ route('search.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl {{ request()->routeIs('search.index') ? 'font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h9v14z"></path></svg>
                        <span class="hidden xl:block">Explore</span>
                    </a>

                    <a href="{{ route('messages.conversations') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl {{ request()->routeIs('messages.*') ? 'font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path></svg>
                        <span class="hidden xl:block">Messages</span>
                    </a>

                    @auth
                        <a href="{{ route('notifications.index') }}" class="relative flex items-center gap-4 px-4 py-3 rounded-full text-2xl {{ request()->routeIs('notifications.*') ? 'font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"></path></svg>
                            <span class="hidden xl:block">Notifications</span>
                            @php
                                $unreadCount = auth()->user()->getUnreadNotificationsCount();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    @endauth

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3 rounded-full text-2xl text-gray-700 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                        <span class="hidden xl:block">Profile</span>
                    </a>
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-full text-gray-700 hover:bg-gray-100 text-2xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 5H5v12h14V6z"></path></svg>
                            <span class="hidden xl:block">Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Feed -->
            <div class="flex-1 lg:ml-64 max-w-2xl mx-auto w-full border-r border-gray-200 min-h-screen">
                <!-- Stories Section (Instagram Style) -->
                <div class="border-b border-gray-200 bg-white sticky top-0 z-40">
                    <div class="p-4 overflow-x-auto">
                        <div class="flex gap-4">
                            @auth
                                <!-- Your Story -->
                                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-2 group cursor-pointer">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br {{ auth()->user()->getAvatarColors() }} p-1 border-2 border-gray-300 group-hover:border-blue-500 transition">
                                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                            <span class="text-2xl">+</span>
                                        </div>
                                    </div>
                                    <span class="text-xs text-center">Your Story</span>
                                </a>
                            @endauth

                            <!-- Other Users Stories (Suggested) -->
                            @php
                                $suggestedUsers = \App\Models\User::inRandomOrder()->where('id', '!=', auth()->id())->limit(15)->get();
                            @endphp
                            @foreach($suggestedUsers->take(10) as $user)
                                <a href="{{ route('profile.show', $user) }}" class="flex flex-col items-center gap-2 group cursor-pointer">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br {{ $user->getAvatarColors() }} flex items-center justify-center text-white font-bold text-xl border-2 border-gray-300 group-hover:border-blue-500 transition">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-center truncate w-16">{{ explode(' ', $user->name)[0] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Create Post Form (Always Show for Auth) -->
                @auth
                    <div class="p-4 bg-white border-b border-gray-200">
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ auth()->user()->getAvatarColors() }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            
                            <!-- Form -->
                            <form action="{{ route('tweets.store') }}" method="POST" enctype="multipart/form-data" class="flex-1" id="create-tweet-form">
                                @csrf
                                <div class="space-y-3">
                                    <!-- Textarea -->
                                    <textarea
                                        name="content"
                                        placeholder="What's on your mind?"
                                        maxlength="280"
                                        class="w-full border-0 outline-none text-lg placeholder-gray-500 resize-none focus:ring-0"
                                        rows="3"
                                    ></textarea>

                                    <!-- Media Upload Preview -->
                                    <div id="media-preview" class="hidden">
                                        <div class="grid grid-cols-3 gap-2" id="preview-grid"></div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                        <div class="flex gap-2">
                                            <!-- Image Upload -->
                                            <label class="cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"></path></svg>
                                                <input type="file" name="media[]" accept="image/*,video/*" multiple hidden id="media-input" onchange="previewMedia()">
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <button
                                            type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full transition disabled:opacity-50"
                                            id="submit-btn"
                                        >
                                            Post
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
                    <div class="p-8 bg-white border-b border-gray-200 text-center">
                        <p class="text-gray-600 mb-4">Sign in to post and connect with others</p>
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
                            <p class="text-gray-600 text-lg font-semibold mb-2">No posts yet</p>
                            <p class="text-gray-500">Start following people to see their posts here</p>
                        </div>
                    @else
                        @foreach ($tweets as $tweet)
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

                                    @can('update', $tweet)
                                        <button type="button" onclick="toggleMenu('menu-{{ $tweet->id }}')" class="text-gray-500 hover:text-gray-900">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                                        </button>
                                        <div id="menu-{{ $tweet->id }}" class="hidden absolute right-4 mt-8 bg-white rounded-lg shadow-lg border border-gray-200 z-20">
                                            <a href="{{ route('tweets.edit', $tweet) }}" class="block px-4 py-2 text-blue-600 hover:bg-gray-100 rounded-t-lg">Edit</a>
                                            <form action="{{ route('tweets.destroy', $tweet) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-b-lg" onclick="return confirm('Delete this post?')">Delete</button>
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
                                <div class="p-4 space-y-4">
                                    <!-- Like, Comment, Share Buttons -->
                                    <div class="flex gap-4">
                                        @auth
                                            <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="group">
                                                @csrf
                                                <button type="submit" class="hover:text-gray-500 transition">
                                                    @if ($tweet->isLikedBy(auth()->user()))
                                                        <svg class="w-6 h-6 fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                    @else
                                                        <svg class="w-6 h-6 hover:fill-gray-300" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="hover:text-gray-500 transition">
                                                <svg class="w-6 h-6" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                            </a>
                                        @endauth

                                        <a href="{{ route('profile.show', $tweet->user) }}" class="text-gray-900 hover:text-gray-500 transition">
                                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 12H6v-2h14v2zm0-3H6V9h14v2zm0-3H6V6h14v2z"></path></svg>
                                        </a>

                                        <button class="text-gray-900 hover:text-gray-500 transition">
                                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"></path></svg>
                                        </button>
                                    </div>

                                    <!-- Like Count -->
                                    <p class="font-bold text-gray-900">{{ $tweet->likes_count }} {{ Str::plural('like', $tweet->likes_count) }}</p>

                                    <!-- Caption -->
                                    <div>
                                        <p class="text-gray-900">
                                            <a href="{{ route('profile.show', $tweet->user) }}" class="font-bold hover:text-blue-600">{{ $tweet->user->name }}</a>
                                            <span class="ml-2">
                                                {!! preg_replace_callback(
                                                    '/(#\w+)/i',
                                                    function($matches) {
                                                        $tag = $matches[1];
                                                        return '<a href="' . route('hashtags.show', ['tag' => substr($tag, 1)]) . '" class="text-blue-600 hover:text-blue-700">' . $tag . '</a>';
                                                    },
                                                    $tweet->content
                                                ) !!}
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Timestamp -->
                                    <p class="text-xs text-gray-500 uppercase">{{ $tweet->created_at->diffForHumans() }}</p>

                                    <!-- Comments Section -->
                                    <div class="border-t border-gray-200 pt-4">
                                        <!-- Comments List -->
                                        @if($tweet->comments->count() > 0)
                                            <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                                                @foreach($tweet->comments->take(5) as $comment)
                                                    <div class="flex gap-2 text-sm">
                                                        <a href="{{ route('profile.show', $comment->user) }}" class="font-bold text-gray-900 hover:text-blue-600">
                                                            {{ $comment->user->name }}
                                                        </a>
                                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                                        @can('delete', $comment)
                                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="ml-auto">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs" onclick="return confirm('Delete this comment?')">Delete</button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if($tweet->comments->count() > 5)
                                                <p class="text-xs text-gray-500 mb-4">
                                                    <a href="#" onclick="alert('Viewing {{ $tweet->comments->count() }} comments')" class="text-blue-600 hover:text-blue-700">
                                                        View all {{ $tweet->comments->count() }} comments
                                                    </a>
                                                </p>
                                            @endif
                                        @endif

                                        <!-- Add Comment Form -->
                                        @auth
                                            <form action="{{ route('comments.store', $tweet) }}" method="POST" class="flex gap-2">
                                                @csrf
                                                <input 
                                                    type="text"
                                                    name="content"
                                                    placeholder="Add a comment..."
                                                    class="flex-1 border-0 text-sm px-0 py-2 focus:ring-0 placeholder-gray-500"
                                                >
                                                <button type="submit" class="text-blue-600 font-bold text-sm hover:text-blue-700">Post</button>
                                            </form>
                                        @else
                                            <p class="text-xs text-gray-500"><a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Sign in</a> to comment</p>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Right Sidebar (Suggested Users) - Desktop Only -->
            <div class="hidden xl:block w-80 bg-white border-l border-gray-200 p-6">
                <!-- Search Bar -->
                <div class="mb-6">
                    <form action="{{ route('search.index') }}" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="q"
                            placeholder="Search..."
                            class="w-full bg-gray-100 border-0 rounded-full py-2 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                        >
                    </form>
                </div>

                <!-- Suggested For You -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-900">Suggested for you</h3>
                        <a href="{{ route('search.index') }}" class="text-blue-600 text-sm hover:text-blue-700">See All</a>
                    </div>

                    <div class="space-y-4">
                        @php
                            $suggestedForYou = \App\Models\User::inRandomOrder()
                                ->where('id', '!=', auth()->id() ?? 0)
                                ->limit(10)
                                ->get();
                        @endphp
                        @foreach($suggestedForYou->take(5) as $user)
                            @if(!auth()->check() || auth()->user()->id !== $user->id && !auth()->user()->isFollowing($user))
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('profile.show', $user) }}" class="flex items-center gap-2 flex-1">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $user->getAvatarColors() }} flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-bold text-sm text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">Popular</p>
                                        </div>
                                    </a>

                                    @auth
                                        <form action="{{ route('follow.store', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-blue-600 font-bold text-sm hover:text-blue-700">Follow</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="text-blue-600 font-bold text-sm hover:text-blue-700">Follow</a>
                                    @endauth
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-8 text-xs text-gray-500 space-y-2">
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
    </script>

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