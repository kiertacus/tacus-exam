<div class="hidden xl:block w-80 bg-black border-l border-gray-700 p-4 max-h-screen overflow-y-auto sticky top-0">
    <!-- Trending Header -->
    <div class="mb-4 pb-4 border-b border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-3">What's happening</h2>
        <div class="relative">
            <input 
                type="text" 
                placeholder="Search..."
                class="w-full bg-gray-900 border border-gray-700 rounded-full py-2 px-4 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 text-sm"
            >
        </div>
    </div>

    <!-- Trending Topics -->
    <div class="space-y-3">
        @php
            $trendingHashtags = \App\Models\Hashtag::withCount('tweets')
                ->orderByDesc('tweets_count')
                ->limit(5)
                ->get();
        @endphp

        @if($trendingHashtags->count() > 0)
            @foreach($trendingHashtags as $hashtag)
                <a href="{{ route('hashtags.show', ['tag' => $hashtag->name]) }}" class="block p-3 hover:bg-gray-900 rounded-lg transition border border-transparent hover:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-xs font-medium">Trending Worldwide</p>
                            <h3 class="text-white font-bold text-sm hover:text-blue-400">#{{ $hashtag->name }}</h3>
                        </div>
                        <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 6v12h12V6H6z M7 7h10v10H7V7z M8.5 8v8h3v-8h-3z M12.5 8v8h3v-8h-3z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">{{ $hashtag->tweets_count }} {{ Str::plural('post', $hashtag->tweets_count) }}</p>
                </a>
            @endforeach
        @else
            <div class="p-4 text-center text-gray-500">
                <p class="text-sm">No trending topics yet</p>
            </div>
        @endif
    </div>

    <!-- Show More -->
    <div class="mt-4 pt-4 border-t border-gray-700">
        <a href="{{ route('search.index') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">Show more</a>
    </div>
</div>
