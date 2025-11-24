<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50 py-8">
        <div class="container mx-auto max-w-2xl px-4">
            <!-- Page Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-black">Messages</h1>
                        <p class="text-gray-600 mt-2">View your conversations and chat with users</p>
                    </div>
                    <div class="text-5xl">💬</div>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="space-y-4">
                @if($conversations->isEmpty())
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-blue-200">
                        <div class="text-6xl mb-4">📭</div>
                        <p class="text-gray-600 text-lg font-semibold">No conversations yet</p>
                        <p class="text-gray-500">Start a conversation by visiting someone's profile</p>
                    </div>
                @else
                    @foreach($conversations as $conversation)
                        <a href="{{ route('messages.show', $conversation['user']) }}" class="block">
                            <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-200 hover:border-blue-400 hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                                <div class="flex items-center gap-4">
                                    <!-- Avatar -->
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $conversation['user']->getAvatarColors() }} flex items-center justify-center text-white font-bold text-xl shadow-lg flex-shrink-0">
                                        {{ strtoupper(substr($conversation['user']->name, 0, 1)) }}
                                    </div>
                                    
                                    <!-- Conversation Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h3 class="font-bold text-black text-lg">{{ $conversation['user']->name }}</h3>
                                            <span class="text-sm text-gray-500">{{ $conversation['last_message']->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm truncate">
                                            @if($conversation['last_message']->sender_id === auth()->id())
                                                You: 
                                            @endif
                                            {{ $conversation['last_message']->content }}
                                        </p>
                                    </div>

                                    <!-- Unread Badge -->
                                    @if($conversation['unread_count'] > 0)
                                        <div class="bg-blue-500 text-white text-xs font-bold px-3 py-2 rounded-full flex-shrink-0">
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
