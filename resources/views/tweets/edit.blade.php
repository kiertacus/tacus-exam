<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50 py-8">
        <div class="container mx-auto max-w-2xl px-4">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-blue-200">
                <h2 class="text-3xl font-bold mb-6 text-blue-900">Edit Tweet</h2>
                
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tweets.update', $tweet) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <textarea 
                        name="content" 
                        placeholder="What's on your mind? 🤔" 
                        rows="6"
                        class="w-full p-4 bg-blue-50 border-2 border-blue-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 resize-none transition-all duration-300 font-medium text-black placeholder-gray-500"
                        required
                        maxlength="280"
                    >{{ $tweet->content }}</textarea>
                    
                    <div class="mt-6 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600">
                            <span id="charCount">{{ strlen($tweet->content) }}</span>/280
                        </span>
                        <div class="flex gap-3">
                            <a href="{{ route('tweets.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-8 rounded-full transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl border border-gray-300">
                                Cancel
                            </a>
                            <button 
                                type="submit"
                                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-8 rounded-full transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-2xl"
                            >
                                Update Tweet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('textarea').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });
    </script>

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
    </style>
</x-app-layout>
