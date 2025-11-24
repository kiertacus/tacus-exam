<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50 py-8 flex flex-col">
        <div class="container mx-auto max-w-2xl px-4 flex-1 flex flex-col">
            <!-- Chat Header -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('messages.conversations') }}" class="text-blue-600 hover:text-blue-700 text-2xl">←</a>
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $user->getAvatarColors() }} flex items-center justify-center text-white font-bold shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-black">{{ $user->name }}</h1>
                            <p class="text-sm text-gray-500">Active now</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.show', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-full transition">
                        View Profile
                    </a>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-blue-200 flex-1 overflow-y-auto flex flex-col gap-4">
                @if($messages->isEmpty())
                    <div class="flex items-center justify-center h-full text-center">
                        <div>
                            <div class="text-6xl mb-4">👋</div>
                            <p class="text-gray-600 text-lg font-semibold">Start the conversation!</p>
                            <p class="text-gray-500">Send the first message to {{ $user->name }}</p>
                        </div>
                    </div>
                @else
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="flex gap-3 max-w-xs {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                @if($message->sender_id !== auth()->id())
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $user->getAvatarColors() }} flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div class="bg-gradient-to-br {{ $message->sender_id === auth()->id() ? 'from-blue-500 to-blue-600 text-white' : 'from-gray-100 to-gray-200 text-gray-800' }} rounded-2xl p-4">
                                    <p class="break-words">{{ $message->content }}</p>
                                    <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-2">
                                        {{ $message->created_at->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Message Input -->
            <form action="{{ route('messages.store', $user) }}" method="POST" class="flex gap-3">
                @csrf
                <input 
                    type="text" 
                    name="content" 
                    placeholder="Type a message... 💭"
                    class="flex-1 px-5 py-3 bg-white border-2 border-blue-200 rounded-full focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                    required
                    maxlength="1000"
                >
                <button 
                    type="submit"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-8 rounded-full transition-all transform hover:scale-105 active:scale-95 shadow-lg"
                >
                    Send 🚀
                </button>
            </form>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom
        document.querySelector('.overflow-y-auto').scrollTop = document.querySelector('.overflow-y-auto').scrollHeight;
    </script>
</x-app-layout>
