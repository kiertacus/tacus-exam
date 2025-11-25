@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div class="min-h-screen bg-white py-4 sm:py-8">
        <div class="w-full max-w-2xl mx-auto px-2 sm:px-4 md:px-0">
            <!-- Profile Header -->
            <div class="bg-white border-b border-gray-300 p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                    <!-- Profile Picture with Upload -->
                    <div class="flex-shrink-0 relative group">
                        <!-- Profile Image -->
                        @if($user->profilePicture)
                            <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-20 h-20 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-blue-500">
                        @else
                            <div class="w-20 h-20 sm:w-28 sm:h-28 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center border-4 border-blue-500">
                                <span class="text-3xl sm:text-5xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        
                        <!-- Upload Overlay (Only for logged-in user's own profile) -->
                        @auth
                            @if(auth()->user()->id === $user->id)
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="document.getElementById('profilePictureInput').click()">
                                    <span class="text-white text-2xl">📷</span>
                                </div>
                                
                                <!-- Hidden File Input -->
                                <form id="profilePictureForm" action="{{ route('profile-picture.store') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                    @csrf
                                    <input 
                                        type="file" 
                                        id="profilePictureInput"
                                        name="profile_picture" 
                                        accept="image/*"
                                        onchange="uploadProfilePicture()"
                                        class="hidden"
                                    >
                                </form>
                            @endif
                        @endauth
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl sm:text-4xl font-bold text-black">{{ $user->name }}</h1>
                            <x-verified-badge :user="$user" />
                        </div>
                        <p class="text-gray-600 text-xs sm:text-sm mt-1">{{ $user->email }}</p>
                        
                        <!-- Bio and Location -->
                        @if($user->bio)
                            <p class="text-gray-800 text-sm sm:text-base mt-2">{{ $user->bio }}</p>
                        @endif
                        
                        <div class="flex gap-4 mt-2 text-gray-600 text-xs sm:text-sm justify-center sm:justify-start">
                            @if($user->location)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"></path></svg>
                                    {{ $user->location }}
                                </span>
                            @endif
                            @if($user->website)
                                <a href="{{ $user->website }}" target="_blank" class="flex items-center gap-1 text-blue-400 hover:text-blue-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path></svg>
                                    {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
                                </a>
                            @endif
                        </div>
                        
                        <!-- Stats -->
                        <div class="mt-3 sm:mt-4 flex gap-3 sm:gap-6 items-center justify-center sm:justify-start flex-wrap border-b border-gray-300 pb-4">
                            <div class="text-center">
                                <p class="text-xl sm:text-3xl font-bold text-black">{{ $tweets->count() }}</p>
                                <p class="text-gray-600 text-xs sm:text-sm">Posts</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xl sm:text-3xl font-bold text-black">{{ $user->following()->count() }}</p>
                                <p class="text-gray-600 text-xs sm:text-sm">Following</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xl sm:text-3xl font-bold text-black">{{ $user->followers()->count() }}</p>
                                <p class="text-gray-600 text-xs sm:text-sm">Followers</p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        @auth
                            @if(auth()->user()->id !== $user->id)
                                <div class="flex gap-2 justify-center sm:justify-start mt-3 sm:mt-4 flex-wrap">
                                    @if(auth()->user()->isFollowing($user))
                                        <form action="{{ route('follow.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-gray-900 hover:bg-gray-800 active:bg-gray-700 text-white font-bold py-2 px-4 sm:px-6 rounded-full border border-gray-700 transition text-sm sm:text-base shadow-md hover:shadow-lg">
                                                Following
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow.store', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2 px-4 sm:px-6 rounded-full transition-all text-sm sm:text-base shadow-md hover:shadow-lg">
                                                Follow
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('messages.show', $user) }}" class="bg-gray-900 hover:bg-gray-800 active:bg-gray-700 text-white font-bold py-2 px-4 sm:px-6 rounded-full border border-gray-700 transition text-sm sm:text-base shadow-md hover:shadow-lg inline-flex items-center gap-2">
                                        💬 Message
                                    </a>
                                </div>
                            @else
                                <!-- Edit Profile Button for Own Profile -->
                                <div class="flex gap-2 justify-center sm:justify-start mt-3 sm:mt-4 flex-wrap">
                                    <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2 px-4 sm:px-6 rounded-full transition-all text-sm sm:text-base shadow-md hover:shadow-lg inline-flex items-center gap-2">
                                        ✏️ Edit Profile
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- User's Tweets -->
            <div>
                    @if($tweets->isEmpty())
                    <div class="bg-white border border-gray-300 rounded p-12 text-center">
                        <div class="text-5xl mb-3">🌟</div>
                        <p class="text-gray-600 text-lg font-medium">{{ $user->name }} hasn't shared any posts yet.</p>
                    </div>
                @else
                    <h2 class="text-2xl font-bold mb-6 text-black px-4">Posts</h2>
                    <div class="space-y-0">
                        @foreach ($tweets as $tweet)
                            <div class="bg-white border-b border-gray-300 p-4 sm:p-6 hover:bg-gray-50 transition-colors cursor-pointer group">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="font-bold text-lg text-white">{{ $tweet->user->name }}</p>
                                        <p class="text-gray-500 text-sm">{{ $tweet->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    @can('update', $tweet)
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('tweets.edit', $tweet) }}" class="text-blue-400 hover:text-blue-300 px-3 py-1 rounded text-sm font-medium transition">Edit</a>
                                            <form action="{{ route('tweets.destroy', $tweet) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 px-3 py-1 rounded text-sm font-medium transition" onclick="return confirm('Delete this post?')">Delete</button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>

                                <p class="text-white mb-4 text-base leading-relaxed">{{ $tweet->content }}</p>

                                <!-- Media Display -->
                                @if($tweet->media->count() > 0)
                                    <div class="grid grid-cols-2 gap-3 mb-4 rounded overflow-hidden">
                                        @foreach($tweet->media as $media)
                                            @if($media->type === 'image')
                                                <img 
                                                    src="{{ Storage::url($media->path) }}" 
                                                    alt="Post media"
                                                    class="rounded w-full h-auto object-cover max-h-96 hover:opacity-80 transition-opacity cursor-pointer"
                                                >
                                            @else
                                                <video 
                                                    controls 
                                                    class="rounded w-full h-auto max-h-96 bg-black"
                                                >
                                                    <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex items-center gap-6 text-gray-500 border-t border-gray-700 pt-4">
                                    @auth
                                        <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="flex items-center gap-2 group/like cursor-pointer hover:text-red-500 transition-colors">
                                            @csrf
                                            <button type="submit" class="text-lg">
                                                @if ($tweet->isLikedBy(auth()->user()))
                                                    <svg class="w-5 h-5 fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5 fill-gray-500 group-hover/like:fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                                @endif
                                            </button>
                                            @if($tweet->likes_count > 0)<span class="text-sm">{{ $tweet->likes_count }}</span>@endif
                                        </form>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 fill-gray-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                                            @if($tweet->likes_count > 0)<span class="text-sm">{{ $tweet->likes_count }}</span>@endif
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('tweets.index') }}" class="inline-block text-blue-400 font-bold hover:text-blue-300 transition">
                    ← Back to Feed
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        div[class*="bg-black"] {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-spinner {
            animation: spin 1s linear infinite;
        }
    </style>

    <script>
        function uploadProfilePicture() {
            const form = document.getElementById('profilePictureForm');
            const fileInput = document.getElementById('profilePictureInput');
            
            // Validate file
            const file = fileInput.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file (JPEG, PNG, GIF)');
                return;
            }

            // Show toast notification
            showToast('📤 Uploading profile picture...', 'loading');

            // Submit form via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                if (response.ok) {
                    showToast('✅ Profile picture updated successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast('❌ Error uploading profile picture', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('❌ Error uploading profile picture', 'error');
            });
        }

        function showToast(message, type = 'info') {
            // Remove existing toast
            const existing = document.getElementById('toast-notification');
            if (existing) existing.remove();

            // Create toast element
            const toast = document.createElement('div');
            toast.id = 'toast-notification';
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white font-semibold shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'loading' ? 'bg-blue-500' :
                'bg-gray-800'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);

            // Auto remove after 3 seconds
            if (type !== 'loading') {
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }
    </script>
</x-app-layout>
