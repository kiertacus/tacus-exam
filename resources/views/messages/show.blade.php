<x-app-layout>
    <div class="min-h-screen bg-white py-2 sm:py-4 lg:py-8 flex flex-col">
        <!-- Call Modal -->
        <div id="callModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg p-6 sm:p-8 max-w-sm w-full text-center">
                <div class="mb-4">
                    <div id="callTitle" class="text-2xl font-bold text-black mb-2">Calling...</div>
                    <p id="callType" class="text-gray-600 text-lg mb-4">📞 Voice Call</p>
                </div>
                <div class="mb-6">
                    @if($user->profilePicture)
                        <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover mx-auto mb-3">
                    @else
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-3xl sm:text-5xl mx-auto mb-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <p class="text-black text-xl font-semibold">{{ $user->name }}</p>
                    <p id="callDuration" class="text-gray-600 text-sm">Calling...</p>
                </div>
                <div class="flex gap-3 justify-center">
                    <button onclick="declineCall()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 sm:px-8 rounded-full transition-all">
                        Decline
                    </button>
                    <button onclick="acceptCall()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 sm:px-8 rounded-full transition-all">
                        Accept
                    </button>
                </div>
            </div>
        </div>

        <div class="w-full max-w-4xl mx-auto px-2 sm:px-4 lg:px-0 flex-1 flex flex-col">
            <!-- Chat Header -->
            <div class="bg-white border-b border-gray-300 p-3 sm:p-4 lg:p-6 mb-3 sm:mb-4 lg:mb-6 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 min-w-0">
                        <a href="{{ route('messages.conversations') }}" class="text-blue-600 hover:text-blue-700 text-lg sm:text-xl lg:text-2xl flex-shrink-0 font-bold">←</a>
                        @if($user->profilePicture)
                            <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full object-cover flex-shrink-0">
                        @else
                            <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm sm:text-base lg:text-lg flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h1 class="text-base sm:text-xl lg:text-2xl font-bold text-black truncate">{{ $user->name }}</h1>
                            <p class="text-xs sm:text-sm text-gray-600">Active now</p>
                        </div>
                    </div>
                    <div class="flex gap-1 sm:gap-2 flex-shrink-0 flex-wrap">
                        <a href="{{ route('profile.show', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 sm:px-4 lg:px-6 rounded-full transition text-xs sm:text-sm lg:text-base whitespace-nowrap">
                            Profile
                        </a>
                        <!-- Voice Call Button -->
                        <button 
                            type="button"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-2 sm:px-3 lg:px-4 rounded-full transition text-xs sm:text-sm lg:text-base"
                            onclick="initiateCall('voice')"
                            title="Start Voice Call"
                        >
                            📞
                        </button>
                        <!-- Video Call Button -->
                        <button 
                            type="button"
                            class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-2 sm:px-3 lg:px-4 rounded-full transition text-xs sm:text-sm lg:text-base"
                            onclick="initiateCall('video')"
                            title="Start Video Call"
                        >
                            📹
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messagesContainer" class="bg-white border border-gray-300 p-3 sm:p-4 lg:p-6 mb-3 sm:mb-4 lg:mb-6 flex-1 overflow-y-auto flex flex-col gap-2 sm:gap-3 lg:gap-4 rounded-lg">
                @if($messages->isEmpty())
                    <div class="flex items-center justify-center h-full text-center">
                        <div>
                            <div class="text-3xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4">👋</div>
                            <p class="text-gray-800 text-base sm:text-lg lg:text-lg font-semibold">Start the conversation!</p>
                            <p class="text-gray-600 text-xs sm:text-sm lg:text-base">Send the first message to {{ $user->name }}</p>
                        </div>
                    </div>
                @else
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="flex gap-2 sm:gap-3 max-w-xs sm:max-w-md lg:max-w-lg {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                @if($message->sender_id !== auth()->id())
                                    @if($user->profilePicture)
                                        <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-7 h-7 sm:w-8 sm:h-8 lg:w-10 lg:h-10 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 lg:w-10 lg:h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                @endif
                                
                                <div class="{{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-black' }} rounded-2xl p-3 sm:p-4 break-words">
                                    <p class="text-xs sm:text-sm lg:text-base">{{ $message->content }}</p>
                                    <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-600' }} mt-1 sm:mt-2">
                                        {{ $message->created_at->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Message Input -->
            <form action="{{ route('messages.store', $user) }}" method="POST" class="flex gap-2 sm:gap-3" id="messageForm">
                @csrf
                <input 
                    type="text" 
                    name="content" 
                    placeholder="Send a message..."
                    class="flex-1 px-3 sm:px-4 lg:px-6 py-2 sm:py-3 bg-white border border-gray-300 text-gray-900 rounded-full focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600 placeholder-gray-600 transition-all text-xs sm:text-sm lg:text-base font-medium"
                    required
                    maxlength="1000"
                    autocomplete="off"
                    id="messageInput"
                >
                <button 
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 lg:px-8 rounded-full transition-all text-xs sm:text-sm lg:text-base flex-shrink-0 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    id="messageSendBtn"
                >
                    <span id="messageSendText">Send</span>
                </button>
            </form>
        </div>
    </div>

    <audio id="ringtone" loop>
        <source src="data:audio/wav;base64,UklGRiYAAABXQVZFZm10IBAAAAABAAEAQB8AAAB9AAACABAAZGF0YQIAAAAAAA==" type="audio/wav">
    </audio>

    <script>
        let callActive = false;
        let callStartTime = null;
        let callDurationInterval = null;

        // Message Form Submission with Visible Feedback
        const messageForm = document.getElementById('messageForm');
        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                const sendBtn = document.getElementById('messageSendBtn');
                const sendText = document.getElementById('messageSendText');
                const messageInput = document.getElementById('messageInput');
                
                // Show loading state - keep text visible
                sendBtn.disabled = true;
                sendText.textContent = '📤 Sending...';
                sendBtn.classList.add('bg-blue-700');
                sendBtn.classList.remove('bg-blue-600');
                messageInput.disabled = true;
            });
        }

        // Auto-scroll to bottom
        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
        
        setTimeout(scrollToBottom, 100);

        // Initiate call function
        function initiateCall(callType) {
            const modal = document.getElementById('callModal');
            const callTypeDisplay = callType === 'voice' ? '📞 Voice Call' : '📹 Video Call';
            document.getElementById('callType').textContent = callTypeDisplay;
            document.getElementById('callTitle').textContent = `Calling {{ $user->name }}...`;
            document.getElementById('callDuration').textContent = 'Calling...';
            modal.classList.remove('hidden');
            
            callActive = true;
            callStartTime = Date.now();
            playRingtone();
            updateCallDuration();
            
            callDurationInterval = setInterval(updateCallDuration, 1000);

            // Actually initiate the call via API
            fetch('{{ route("calls.initiate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    recipient_id: {{ $user->id }},
                    call_type: callType,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Call initiated:', data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                closeCall();
            });
        }

        function playRingtone() {
            const ringtone = document.getElementById('ringtone');
            // Create a simple ringing tone using Web Audio API
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 2);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
            
            // Repeat ringing every 2 seconds
            if (callActive) {
                setTimeout(playRingtone, 2000);
            }
        }

        function updateCallDuration() {
            if (callStartTime) {
                const elapsed = Math.floor((Date.now() - callStartTime) / 1000);
                const minutes = Math.floor(elapsed / 60);
                const seconds = elapsed % 60;
                document.getElementById('callDuration').textContent = 
                    `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
        }

        function acceptCall() {
            document.getElementById('callTitle').textContent = 'Call Connected';
            document.getElementById('callDuration').textContent = 'Connected';
            callActive = false;
            
            // Stop ringing
            // In a real implementation, establish the connection
            setTimeout(() => {
                alert('Call connected with {{ $user->name }}! (Real video/audio would start here)');
            }, 500);
        }

        function declineCall() {
            closeCall();
        }

        function closeCall() {
            callActive = false;
            if (callDurationInterval) {
                clearInterval(callDurationInterval);
            }
            document.getElementById('callModal').classList.add('hidden');
            
            // Notify about decline via API
            fetch('{{ route("calls.initiate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
            }).catch(e => {});
        }
    </script>
</x-app-layout>
