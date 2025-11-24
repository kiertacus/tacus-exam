@extends('layouts.app')

@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Search</h1>
            
            <!-- Search Form -->
            <form action="{{ route('search.index') }}" method="GET" class="flex gap-2">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Search people and posts..." 
                    value="{{ $query }}"
                    class="flex-1 px-4 py-3 rounded-full border-2 border-blue-300 focus:outline-none focus:border-blue-500 bg-white"
                >
                <button 
                    type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition"
                >
                    Search
                </button>
            </form>
        </div>

        @if(strlen($query) >= 2)
            <!-- Users Section -->
            @if($users->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">People</h2>
                    <div class="space-y-4">
                        @foreach($users as $user)
                            <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $user->getAvatarColors() }} flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $user) }}" class="font-semibold text-gray-900 hover:text-blue-600">
                                                {{ $user->name }}
                                            </a>
                                            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    @auth
                                        @if(auth()->user()->id !== $user->id)
                                            @if(auth()->user()->isFollowing($user))
                                                <form action="{{ route('follow.destroy', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-full font-semibold hover:bg-blue-50 transition">
                                                        Following
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('follow.store', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition">
                                                        Follow
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tweets Section -->
            @if($tweets->count() > 0)
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Posts</h2>
                    <div class="space-y-4">
                        @foreach($tweets as $tweet)
                            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition border-l-4 border-blue-500">
                                <!-- Tweet Author -->
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $tweet->user->getAvatarColors() }} flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <a href="{{ route('profile.show', $tweet->user) }}" class="font-semibold text-gray-900 hover:text-blue-600">
                                            {{ $tweet->user->name }}
                                        </a>
                                        <p class="text-gray-500 text-sm">{{ $tweet->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <!-- Tweet Content -->
                                <p class="text-gray-800 mb-4">{{ $tweet->content }}</p>

                                <!-- Tweet Media -->
                                @if($tweet->media->count() > 0)
                                    <div class="grid grid-cols-2 gap-2 mb-4">
                                        @foreach($tweet->media as $media)
                                            @if($media->type === 'image')
                                                <img 
                                                    src="{{ Storage::url($media->path) }}" 
                                                    alt="Tweet media"
                                                    class="rounded-lg w-full h-auto object-cover"
                                                >
                                            @else
                                                <video 
                                                    controls 
                                                    class="rounded-lg w-full h-auto"
                                                >
                                                    <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Tweet Actions -->
                                <div class="flex items-center justify-between text-gray-600 pt-3 border-t">
                                    <div class="flex gap-4">
                                        <span class="text-sm">
                                            <i class="text-blue-500">❤️ {{ $tweet->likes->count() }}</i>
                                        </span>
                                    </div>

                                    @auth
                                        @if($tweet->isLikedBy(auth()->user()))
                                            <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-700 font-semibold transition">
                                                    Unlike
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-gray-600 hover:text-blue-600 transition">
                                                    Like
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(strlen($query) >= 2)
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No results found for "{{ $query }}"</p>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Enter at least 2 characters to search</p>
            </div>
        @endif
    </div>
</div>
@endsection
