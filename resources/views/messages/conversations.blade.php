<x-app-layout>
    <div class="min-h-screen bg-white py-2 sm:py-4 lg:py-8">
        <div class="w-full max-w-4xl mx-auto px-2 sm:px-4 lg:px-0">
            <!-- Page Header -->
            <div class="bg-white border-b border-gray-300 p-3 sm:p-6 lg:p-8 mb-3 sm:mb-6 lg:mb-8">
                <div class="flex items-center justify-between gap-3 sm:gap-4">
                    <div>
                        <h1 class="text-xl sm:text-3xl lg:text-4xl font-bold text-black">Messages</h1>
                        <p class="text-gray-600 mt-1 sm:mt-2 text-xs sm:text-sm lg:text-base">Your conversations</p>
                    </div>
                    <div class="text-2xl sm:text-4xl lg:text-5xl flex-shrink-0">💬</div>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="space-y-0 border border-gray-300 rounded-lg overflow-hidden">
                @if($conversations->isEmpty())
                    <div class="bg-white border border-gray-300 p-6 sm:p-8 lg:p-12 text-center">
                        <div class="text-3xl sm:text-5xl lg:text-6xl mb-3 sm:mb-4">📭</div>
                        <p class="text-gray-800 text-base sm:text-lg lg:text-lg font-semibold">No conversations yet</p>
                        <p class="text-gray-600 text-xs sm:text-sm lg:text-base">Start a conversation by visiting someone's profile</p>
                    </div>
                @else
                    @foreach($conversations as $conversation)
                        <a href="{{ route('messages.show', $conversation['user']) }}" class="block border-b border-gray-300 last:border-b-0">
                            <div class="bg-white hover:bg-gray-50 p-3 sm:p-4 lg:p-6 transition-colors duration-200">
                                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
                                    <!-- Avatar -->
                                    @if($conversation['user']->profilePicture)
                                        <img src="{{ asset('storage/profile-pictures/' . $conversation['user']->profilePicture->path) }}" alt="{{ $conversation['user']->name }}" class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm sm:text-base lg:text-xl flex-shrink-0">
                                            {{ strtoupper(substr($conversation['user']->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    <!-- Conversation Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h3 class="font-bold text-black text-xs sm:text-base lg:text-lg truncate">{{ $conversation['user']->name }}</h3>
                                            <span class="text-xs sm:text-sm text-gray-600 flex-shrink-0">{{ $conversation['last_message']->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 text-xs sm:text-sm truncate">
                                            @if($conversation['last_message']->sender_id === auth()->id())
                                                You: 
                                            @endif
                                            {{ $conversation['last_message']->content }}
                                        </p>
                                    </div>

                                    <!-- Unread Badge -->
                                    @if($conversation['unread_count'] > 0)
                                        <div class="bg-blue-600 text-white text-xs font-bold px-2 sm:px-3 py-1 rounded-full flex-shrink-0">
                                            {{ $conversation['unread_count'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
