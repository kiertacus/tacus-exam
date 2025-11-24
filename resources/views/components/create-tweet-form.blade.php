<div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 mb-8 border border-white/50 hover:shadow-3xl transition-all duration-500 animate-fadeInDown">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center transform rotate-6 hover:rotate-12 transition-transform duration-300">
            <span class="text-2xl">✨</span>
        </div>
        <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Share Your Thoughts</h2>
    </div>
    
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl mb-4 animate-shake backdrop-blur-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @auth
        <form action="{{ route('tweets.store') }}" method="POST" enctype="multipart/form-data" id="tweetForm">
            @csrf
            <div class="relative">
                <textarea 
                    name="content" 
                    placeholder="What's happening? Share your story... 💭" 
                    rows="4"
                    class="w-full p-5 bg-gradient-to-br from-blue-50 to-purple-50 border-2 border-transparent rounded-2xl focus:outline-none focus:border-purple-400 focus:ring-4 focus:ring-purple-200 resize-none transition-all duration-300 font-medium text-gray-800 placeholder-gray-400 hover:shadow-lg"
                    required
                    maxlength="280"
                ></textarea>
                <div class="absolute bottom-3 right-3 opacity-30 pointer-events-none text-4xl">
                    💬
                </div>
            </div>

            <!-- Media Upload Section -->
            <div class="mt-4 p-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl">
                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-3 rounded-xl transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Add image or video</span>
                    <input 
                        type="file" 
                        name="media" 
                        class="hidden" 
                        accept="image/*,video/*"
                        id="mediaInput"
                    >
                </label>
                <div id="mediaPreview" class="mt-3 flex gap-3 flex-wrap"></div>
            </div>

            <div class="mt-5 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold text-gray-600 bg-gray-100 px-4 py-2 rounded-full">
                        <span class="char-count text-purple-600">0</span><span class="text-gray-400">/280</span>
                    </span>
                </div>
                <button 
                    type="submit"
                    class="relative bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 text-white font-bold py-3 px-10 rounded-full transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-xl hover:shadow-2xl overflow-hidden group"
                >
                    <span class="relative z-10 flex items-center gap-2">
                        <span>Tweet</span>
                        <span class="group-hover:translate-x-1 transition-transform">🚀</span>
                    </span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </div>
        </form>
    @else
        <div class="text-center py-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl">
            <p class="text-gray-700 text-lg font-medium mb-4">Join the conversation! 🎉</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-2 px-6 rounded-full hover:scale-105 transition-transform shadow-lg">Login</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold py-2 px-6 rounded-full hover:scale-105 transition-transform shadow-lg">Register</a>
            </div>
        </div>
    @endauth
</div>
