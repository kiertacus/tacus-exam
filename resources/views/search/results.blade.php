<x-app-layout>
    <div class="min-h-screen bg-white py-2 sm:py-4 lg:py-8">
        <div class="w-full max-w-4xl mx-auto px-2 sm:px-4 lg:px-0">
            <!-- Search Header -->
            <div class="bg-white border-b border-gray-300 p-3 sm:p-6 lg:p-8 mb-3 sm:mb-6 lg:mb-8 rounded-lg">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    @if($query)
                        Search Results for "<span class="text-blue-600">{{ $query }}</span>"
                    @else
                        Search
                    @endif
                </h1>
                <p class="text-gray-600 mt-2">Found {{ $users->count() + $tweets->count() }} results</p>

                <!-- Search Form -->
                <form action="{{ route('search.index') }}" method="GET" class="mt-4">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search tweets & users..." class="w-full bg-gray-50 border border-gray-300 rounded-lg py-3 px-4 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </form>
            </div>

            @if($query && $users->count() === 0 && $tweets->count() === 0)
                <div class="bg-white border border-gray-300 p-8 sm:p-12 text-center rounded-lg">
                    <div class="text-5xl sm:text-6xl mb-4">🔍</div>
                    <p class="text-gray-800 text-lg font-semibold">No results found</p>
                    <p class="text-gray-600">Try searching with different keywords</p>
                </div>
            @else
                <!-- Users Section -->
                @if($users->count() > 0)
                    <div class="mb-6 sm:mb-8 lg:mb-10">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 px-3 sm:px-0">Users</h2>
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($users as $user)
                                <a href="{{ route('profile.show', $user) }}" class="block bg-white border border-gray-300 p-3 sm:p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        @if($user->profilePicture)
                                            <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover flex-shrink-0">
                                        @else
                                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm sm:text-base flex-shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate">{{ $user->name }}</h3>
                                                @if($user->is_verified)
                                                    <span class="text-blue-600 text-lg flex-shrink-0">✓</span>
                                                @endif
                                            </div>
                                            <p class="text-gray-600 text-xs sm:text-sm truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tweets Section -->
                @if($tweets->count() > 0)
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 px-3 sm:px-0">Tweets</h2>
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($tweets as $tweet)
                                <div class="bg-white border border-gray-300 p-3 sm:p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex gap-3">
                                        <a href="{{ route('profile.show', $tweet->user) }}" class="flex-shrink-0">
                                            @if($tweet->user->profilePicture)
                                                <img src="{{ asset('storage/profile-pictures/' . $tweet->user->profilePicture->path) }}" alt="{{ $tweet->user->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                                    {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </a>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <a href="{{ route('profile.show', $tweet->user) }}" class="font-bold text-gray-900 text-sm sm:text-base hover:underline">
                                                    {{ $tweet->user->name }}
                                                </a>
                                                @if($tweet->user->is_verified)
                                                    <span class="text-blue-600 text-lg flex-shrink-0">✓</span>
                                                @endif
                                                <span class="text-gray-600 text-xs sm:text-sm">{{ $tweet->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-800 text-sm sm:text-base break-words">{{ $tweet->content }}</p>
                                            <div class="mt-3 text-xs sm:text-sm text-gray-600 flex gap-4">
                                                <span>❤️ {{ $tweet->likes->count() }}</span>
                                                <span>💬 {{ $tweet->comments_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
