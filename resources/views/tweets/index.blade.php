@use('Illuminate\Support\Facades\Storage')

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
            <x-create-tweet-form />

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

                            <!-- Media Display -->
                            @if($tweet->media->count() > 0)
                                <div class="grid grid-cols-2 gap-3 mb-5 rounded-2xl overflow-hidden">
                                    @foreach($tweet->media as $media)
                                        @if($media->type === 'image')
                                            <img 
                                                src="{{ Storage::url($media->path) }}" 
                                                alt="Tweet media"
                                                class="rounded-xl w-full h-auto object-cover max-h-96 hover:scale-105 transition-transform duration-300 cursor-pointer"
                                            >
                                        @else
                                            <video 
                                                controls 
                                                class="rounded-xl w-full h-auto max-h-96 bg-black"
                                            >
                                                <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

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
                                @else
                                    <div class="flex items-center gap-3 cursor-not-allowed opacity-60">
                                        <span class="text-3xl">🤍</span>
                                        <span class="font-bold text-lg text-gray-700">{{ $tweet->likes_count }}</span>
                                    </div>
                                @endauth
                            </div>

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

        // Media preview
        const mediaInput = document.getElementById('mediaInput');
        if (mediaInput) {
            mediaInput.addEventListener('change', function(e) {
                const preview = document.getElementById('mediaPreview');
                preview.innerHTML = '';
                
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        if (file.type.startsWith('image/')) {
                            preview.innerHTML = `
                                <div class="relative">
                                    <img src="${event.target.result}" class="max-h-48 rounded-lg">
                                    <button type="button" onclick="document.getElementById('mediaInput').value=''; this.parentElement.parentElement.innerHTML='';" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full">✕</button>
                                </div>
                            `;
                        } else if (file.type.startsWith('video/')) {
                            preview.innerHTML = `
                                <div class="relative">
                                    <video class="max-h-48 rounded-lg" controls>
                                        <source src="${event.target.result}">
                                    </video>
                                    <button type="button" onclick="document.getElementById('mediaInput').value=''; this.parentElement.parentElement.innerHTML='';" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full">✕</button>
                                </div>
                            `;
                        }
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }

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