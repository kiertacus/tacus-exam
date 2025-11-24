@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50 py-8">
        <div class="container mx-auto max-w-2xl px-4">
            <!-- Profile Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-blue-200">
                <div class="flex items-center gap-6">
                    <div class="w-28 h-28 bg-gradient-to-br {{ $user->getAvatarColors() }} rounded-full flex items-center justify-center shadow-lg border-4 border-blue-200">
                        <span class="text-6xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold text-black">{{ $user->name }}</h1>
                        <p class="text-gray-600 text-sm mt-1">{{ $user->email }}</p>
                        <div class="mt-4 flex gap-6 items-center">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $tweets->count() }}</p>
                                <p class="text-gray-600 text-sm">Tweets</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $user->following()->count() }}</p>
                                <p class="text-gray-600 text-sm">Following</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $user->followers()->count() }}</p>
                                <p class="text-gray-600 text-sm">Followers</p>
                            </div>
                            @auth
                                @if(auth()->user()->id !== $user->id)
                                    <div class="flex gap-2">
                                        @if(auth()->user()->isFollowing($user))
                                            <form action="{{ route('follow.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-full transition">
                                                    Following
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('follow.store', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-6 rounded-full transition-all transform hover:scale-105">
                                                    Follow
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('messages.show', $user) }}" class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-bold py-2 px-6 rounded-full transition-all transform hover:scale-105">
                                            💬 Message
                                        </a>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->id === $user->id)
                        <!-- Avatar Selection -->
                        <div class="mt-6 pt-6 border-t border-blue-200">
                            <p class="text-gray-700 font-semibold mb-3">Choose Avatar Color:</p>
                            <div class="flex gap-3 flex-wrap">
                                @php
                                    $avatarOptions = [
                                        'blue' => 'from-blue-400 to-blue-600',
                                        'purple' => 'from-purple-400 to-purple-600',
                                        'pink' => 'from-pink-400 to-pink-600',
                                        'cyan' => 'from-cyan-400 to-cyan-600',
                                        'green' => 'from-green-400 to-green-600',
                                        'orange' => 'from-orange-400 to-orange-600',
                                        'red' => 'from-red-400 to-red-600',
                                        'indigo' => 'from-indigo-400 to-indigo-600',
                                    ];
                                @endphp
                                @foreach($avatarOptions as $color => $gradient)
                                    <form action="{{ route('profile.update') }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="avatar" value="{{ $color }}">
                                        <button type="submit" class="w-12 h-12 rounded-full bg-gradient-to-br {{ $gradient }} hover:scale-110 transition-transform transform {{ $user->avatar === $color ? 'ring-4 ring-blue-600' : 'ring-2 ring-blue-300' }} shadow-lg"></button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- User's Tweets -->
            <div>
                @if ($tweets->isEmpty())
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-blue-200">
                        <div class="text-5xl mb-3">🌟</div>
                        <p class="text-gray-600 text-lg font-medium">{{ $user->name }} hasn't shared any tweets yet.</p>
                    </div>
                @else
                    <h2 class="text-2xl font-bold mb-6 text-blue-900">Tweets</h2>
                    <div class="space-y-4">
                        @foreach ($tweets as $tweet)
                            <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-200 hover:border-blue-300 transition-all duration-300 transform hover:scale-102 hover:-translate-y-1 group">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="font-bold text-lg text-black">{{ $tweet->user->name }}</p>
                                        <p class="text-gray-500 text-sm">{{ $tweet->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    @can('update', $tweet)
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('tweets.edit', $tweet) }}" class="text-blue-600 hover:text-blue-700 hover:bg-blue-100 px-3 py-1 rounded-lg text-sm font-medium transition">✏️ Edit</a>
                                            <form action="{{ route('tweets.destroy', $tweet) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 hover:bg-red-100 px-3 py-1 rounded-lg text-sm font-medium transition" onclick="return confirm('Delete this tweet?')">🗑️ Delete</button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>

                                <p class="text-black mb-6 text-base leading-relaxed">{{ $tweet->content }}</p>

                                <!-- Media Display -->
                                @if($tweet->media->count() > 0)
                                    <div class="grid grid-cols-2 gap-3 mb-6 rounded-xl overflow-hidden">
                                        @foreach($tweet->media as $media)
                                            @if($media->type === 'image')
                                                <img 
                                                    src="{{ Storage::url($media->path) }}" 
                                                    alt="Tweet media"
                                                    class="rounded-lg w-full h-auto object-cover max-h-96 hover:scale-105 transition-transform duration-300 cursor-pointer"
                                                >
                                            @else
                                                <video 
                                                    controls 
                                                    class="rounded-lg w-full h-auto max-h-96 bg-black"
                                                >
                                                    <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex items-center gap-6 text-gray-500 border-t border-blue-200 pt-4">
                                    @auth
                                        <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="flex items-center gap-2 group/like cursor-pointer">
                                            @csrf
                                            <button type="submit" class="group-hover/like:scale-110 transition-transform duration-300">
                                                @if ($tweet->isLikedBy(auth()->user()))
                                                    <span class="text-2xl animate-pulse">❤️</span>
                                                @else
                                                    <span class="text-2xl group-hover/like:text-red-500 transition-colors duration-300">🤍</span>
                                                @endif
                                            </button>
                                            <span class="font-semibold text-gray-600 group-hover/like:text-red-500 transition-colors">{{ $tweet->likes_count }}</span>
                                        </form>
                                    @else
                                        <div class="flex items-center gap-2 cursor-not-allowed">
                                            <span class="text-2xl">🤍</span>
                                            <span class="font-semibold text-gray-600">{{ $tweet->likes_count }}</span>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('tweets.index') }}" class="inline-block text-blue-600 font-bold hover:text-blue-700 transition transform hover:scale-110">
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

        div[class*="bg-white"] {
            animation: slideIn 0.5s ease-out;
        }

        .hover\:scale-102:hover {
            transform: scale(1.02);
        }
    </style>
</x-app-layout>
