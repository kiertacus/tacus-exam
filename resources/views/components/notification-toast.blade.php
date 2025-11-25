<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
    // Function to show notification toast
    function showNotification(title, message, type = 'success') {
        const container = document.getElementById('notificationContainer');
        const notification = document.createElement('div');
        
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        
        notification.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg animate-pulse max-w-sm transform transition-all`;
        notification.innerHTML = `
            <div class="font-bold">${title}</div>
            <div class="text-sm">${message}</div>
        `;
        
        container.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'fadeOut 0.5s ease-in-out forwards';
            setTimeout(() => notification.remove(), 500);
        }, 5000);
    }

    // Listen for real-time notifications (optional - requires Echo/WebSocket)
    if (typeof Echo !== 'undefined') {
        Echo.channel('notifications')
            .listen('StoryPosted', (e) => {
                showNotification('📖 New Story!', `${e.user_name} posted a new story!`, 'info');
            });
    }
</script>

<style>
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
</style>

