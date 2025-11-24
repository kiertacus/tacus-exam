<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 py-8 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-40 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="container mx-auto max-w-2xl px-4 relative z-10">
            <!-- Create Tweet Section -->
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
                    <form action="{{ route('tweets.store') }}" method="POST">
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

            <!-- Tweets Feed -->
            <div class="space-y-6">
                @if ($tweets->isEmpty())
                    <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-16 text-center border border-white/50 animate-fadeInUp">
                        <div class="text-7xl mb-4 animate-bounce">📝</div>
                        <p class="text-gray-600 text-xl font-semibold mb-2">No tweets yet</p>
                        <p class="text-gray-500">Be the first to share something amazing!</p>
                    </div>
                @else
                    @foreach ($tweets as $tweet)
                        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-xl p-6 border border-white/50 hover:border-purple-300 transition-all duration-500 transform hover:scale-[1.02] hover:-translate-y-2 group animate-fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-start mb-5">
                                <div class="flex-1">
                                    <a href="{{ route('profile.show', $tweet->user) }}" class="inline-block">
                                        <div class="flex items-center gap-4">
                                            <!-- Enhanced Avatar with Multiple Styles -->
                                            <div class="relative">
                                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $tweet->user->getAvatarColors() }} flex items-center justify-center text-white font-bold text-xl shadow-lg group-hover:shadow-2xl transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3 relative overflow-hidden">
                                                    <!-- Avatar Letter -->
                                                    <span class="relative z-10">{{ strtoupper(substr($tweet->user->name, 0, 1)) }}</span>
                                                    <!-- Shine Effect -->
                                                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                                </div>
                                                <!-- Status Indicator -->
                                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-3 border-white rounded-full animate-pulse"></div>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 text-lg group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-purple-600 group-hover:bg-clip-text transition-all duration-300">{{ $tweet->user->name }}</p>
                                                <p class="text-gray-500 text-sm flex items-center gap-2">
                                                    <span>{{ $tweet->created_at->diffForHumans() }}</span>
                                                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                                    <span class="text-xs">🌍</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                @can('update', $tweet)
                                    <div class="relative">
                                        <button type="button" 
                                                onclick="toggleMenu('menu-{{ $tweet->id }}')" 
                                                class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-xl transition-all duration-300 transform hover:scale-105">
                                            <span class="text-xl">⋯</span>
                                        </button>
                                        <!-- Dropdown Menu -->
                                        <div id="menu-{{ $tweet->id }}" class="hidden absolute right-0 top-12 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden z-20 min-w-[180px] animate-slideDown">
                                            <a href="{{ route('tweets.edit', $tweet) }}" class="flex items-center gap-3 px-5 py-3 text-blue-600 hover:bg-blue-50 transition-colors duration-200 font-medium">
                                                <span class="text-lg">✏️</span>
                                                <span>Edit Tweet</span>
                                            </a>
                                            <form action="{{ route('tweets.destroy', $tweet) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 text-red-600 hover:bg-red-50 transition-colors duration-200 font-medium border-t border-gray-100" onclick="return confirm('Delete this tweet?')">
                                                    <span class="text-lg">🗑️</span>
                                                    <span>Delete Tweet</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endcan
                            </div>

                            <div class="bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-2xl p-5 mb-5 relative overflow-hidden">
                                <p class="text-gray-800 text-base leading-relaxed relative z-10">{{ $tweet->content }}</p>
                                <div class="absolute top-0 right-0 text-6xl opacity-5 pointer-events-none">💭</div>
                            </div>

                            <div class="flex items-center gap-8 text-gray-500 border-t border-gray-200 pt-4">
                                @auth
                                    <!-- Like Button -->
                                    <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="flex items-center gap-3 group/like cursor-pointer">
                                        @csrf
                                        <button type="submit" class="relative group-hover/like:scale-125 transition-all duration-300 transform">
                                            @if ($tweet->isLikedBy(auth()->user()))
                                                <span class="text-3xl animate-heartBeat">❤️</span>
                                            @else
                                                <span class="text-3xl group-hover/like:text-red-500 transition-colors duration-300">🤍</span>
                                            @endif
                                            <span class="absolute inset-0 rounded-full bg-red-400 opacity-0 group-hover/like:opacity-20 group-hover/like:scale-150 transition-all duration-500"></span>
                                        </button>
                                        <span class="font-bold text-lg text-gray-700 group-hover/like:text-red-500 group-hover/like:scale-110 transition-all duration-300">
                                            {{ $tweet->likes_count }}
                                        </span>
                                    </form>

                                    <!-- Comment Button -->
                                    <button onclick="toggleComments('comments-{{ $tweet->id }}')" class="flex items-center gap-3 cursor-pointer hover:text-blue-500 transition-colors duration-300 group/comment">
                                        <span class="text-2xl group-hover/comment:scale-110 transition-transform">💬</span>
                                        <span class="font-semibold">Comment</span>
                                    </button>

                                    <!-- Share Button -->
                                    <button onclick="shareModal('share-{{ $tweet->id }}')" class="flex items-center gap-3 cursor-pointer hover:text-green-500 transition-colors duration-300 group/share ml-auto">
                                        <span class="text-2xl group-hover/share:scale-110 group-hover/share:rotate-12 transition-all">🔄</span>
                                        <span class="font-semibold">Share</span>
                                    </button>
                                @else
                                    <div class="flex items-center gap-3 cursor-not-allowed opacity-60">
                                        <span class="text-3xl">🤍</span>
                                        <span class="font-bold text-lg text-gray-700">{{ $tweet->likes_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 cursor-not-allowed opacity-60">
                                        <span class="text-2xl">💬</span>
                                        <span class="font-semibold">Comment</span>
                                    </div>
                                    <div class="flex items-center gap-3 cursor-not-allowed opacity-60 ml-auto">
                                        <span class="text-2xl">🔄</span>
                                        <span class="font-semibold">Share</span>
                                    </div>
                                @endauth
                            </div>

                            @auth
                                <!-- Comments Section -->
                                <div id="comments-{{ $tweet->id }}" class="hidden mt-6 pt-6 border-t border-gray-200 animate-slideDown">
                                    <form action="{{ route('comments.store', $tweet) }}" method="POST" class="mb-6">
                                        @csrf
                                        <div class="flex gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ auth()->user()->getAvatarColors() }} flex items-center justify-center text-white font-bold shadow-lg flex-shrink-0">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1">
                                                <textarea 
                                                    name="content" 
                                                    placeholder="Write a comment... 💭" 
                                                    rows="2"
                                                    class="w-full p-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-200 resize-none transition-all duration-300 text-sm"
                                                    required
                                                ></textarea>
                                                <button type="submit" class="mt-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold py-2 px-6 rounded-full hover:scale-105 transition-transform duration-300 shadow-lg text-sm">
                                                    Post Comment
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Comments List -->
                                    <div class="space-y-4">
                                        @foreach ($tweet->comments as $comment)
                                            <div class="flex gap-3 bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition-colors duration-300">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $comment->user->getAvatarColors() }} flex items-center justify-center text-white font-bold shadow-md flex-shrink-0">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <p class="font-bold text-gray-800 text-sm">{{ $comment->user->name }}</p>
                                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-gray-700 text-sm">{{ $comment->content }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Share Modal -->
                                <div id="share-{{ $tweet->id }}" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center animate-fadeIn" onclick="closeModal('share-{{ $tweet->id }}')">
                                    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 animate-scaleIn" onclick="event.stopPropagation()">
                                        <div class="flex justify-between items-center mb-6">
                                            <h3 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">Share Tweet</h3>
                                            <button onclick="closeModal('share-{{ $tweet->id }}')" class="text-gray-400 hover:text-gray-600 text-2xl hover:rotate-90 transition-transform duration-300">×</button>
                                        </div>
                                        <div class="space-y-3">
                                            <button onclick="copyToClipboard('{{ route('tweets.show', $tweet) }}')" class="w-full flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-purple-50 hover:from-blue-100 hover:to-purple-100 rounded-2xl transition-all duration-300 group">
                                                <span class="text-3xl group-hover:scale-110 transition-transform">🔗</span>
                                                <div class="text-left">
                                                    <p class="font-bold text-gray-800">Copy Link</p>
                                                    <p class="text-xs text-gray-600">Share link to this tweet</p>
                                                </div>
                                            </button>
                                            <button onclick="shareVia('twitter', '{{ $tweet->content }}')" class="w-full flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-2xl transition-all duration-300 group">
                                                <span class="text-3xl group-hover:scale-110 transition-transform">🐦</span>
                                                <div class="text-left">
                                                    <p class="font-bold text-gray-800">Share on Twitter</p>
                                                    <p class="text-xs text-gray-600">Post to your timeline</p>
                                                </div>
                                            </button>
                                            <button onclick="shareVia('facebook', '{{ $tweet->content }}')" class="w-full flex items-center gap-4 p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 rounded-2xl transition-all duration-300 group">
                                                <span class="text-3xl group-hover:scale-110 transition-transform">📘</span>
                                                <div class="text-left">
                                                    <p class="font-bold text-gray-800">Share on Facebook</p>
                                                    <p class="text-xs text-gray-600">Post to your feed</p>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        // Character counter
        document.querySelector('textarea')?.addEventListener('input', function() {
            const count = this.value.length;
            const counter = document.querySelector('.char-count');
            counter.textContent = count;
            
            // Color change based on character count
            if (count > 250) {
                counter.classList.add('text-red-600');
                counter.classList.remove('text-purple-600');
            } else {
                counter.classList.add('text-purple-600');
                counter.classList.remove('text-red-600');
            }
        });

        // Add stagger animation to tweets
        document.querySelectorAll('.animate-fadeInUp').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });
    </script>

    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes blob {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            25% {
                transform: translate(20px, -50px) scale(1.1);
            }
            50% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            75% {
                transform: translate(50px, 50px) scale(1.05);
            }
        }

        @keyframes heartBeat {
            0%, 100% {
                transform: scale(1);
            }
            10%, 30% {
                transform: scale(1.2);
            }
            20%, 40% {
                transform: scale(1.1);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .animate-fadeInDown {
            animation: fadeInDown 0.6s ease-out forwards;
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animate-heartBeat {
            animation: heartBeat 1s ease-in-out;
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        .shadow-3xl {
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #e0e7ff, #fce7f3);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #8b5cf6, #ec4899);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #7c3aed, #db2777);
        }
    </style>
</x-app-layout>