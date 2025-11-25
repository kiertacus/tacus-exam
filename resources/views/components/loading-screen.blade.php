<style>
    .loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 50%, #1e3a8a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeOut 0.8s ease-in-out forwards;
        animation-delay: 12s;
    }

    .loader-container.hide {
        display: none;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            visibility: visible;
        }
        to {
            opacity: 0;
            visibility: hidden;
        }
    }

    .loader {
        text-align: center;
        width: 100%;
        max-width: 400px;
        padding: 20px;
    }

    .loader-logo {
        font-size: 5rem;
        font-weight: bold;
        color: white;
        margin-bottom: 30px;
        animation: bounce 1.5s ease-in-out infinite;
        text-shadow: 0 0 30px rgba(59, 130, 246, 0.5);
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
            opacity: 1;
        }
        50% {
            transform: translateY(-30px);
            opacity: 0.8;
        }
    }

    .progress-container {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        margin: 30px 0;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #60a5fa, #3b82f6);
        border-radius: 10px;
        width: 0%;
        animation: none;
        transition: width 0.3s ease;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.8);
    }

    .loader-text {
        color: white;
        font-size: 1.3rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .loader-subtext {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        margin-top: 15px;
        font-weight: 400;
    }

    .progress-text {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
        margin-top: 8px;
        font-weight: 500;
    }

    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.2);
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1.2s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div id="loadingScreen" class="loader-container">
    <div class="loader">
        <div class="loader-logo">𝕏</div>
        <div class="spinner"></div>
        <div class="loader-text">Chirper</div>
        
        <!-- Progress Bar -->
        <div class="progress-container">
            <div id="progressBar" class="progress-bar"></div>
        </div>
        
        <div class="progress-text"><span id="progressText">0</span>%</div>
        <div class="loader-subtext">Loading your feed...</div>
    </div>
</div>

<script>
    let progress = 0;
    let loadingStartTime = Date.now();
    const loadingDuration = 12000; // 12 seconds total (within 10-20 range)
    
    // Simulate progressive loading with variable speed
    const progressInterval = setInterval(() => {
        const elapsed = Date.now() - loadingStartTime;
        const percentage = (elapsed / loadingDuration) * 100;
        
        // Let progress reach 100%
        progress = Math.min(percentage, 100);
        
        updateProgress(progress);
        
        if (elapsed >= loadingDuration) {
            clearInterval(progressInterval);
            progress = 100;
            updateProgress(100);
        }
    }, 50);
    
    function updateProgress(value) {
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        if (progressBar && progressText) {
            progressBar.style.width = value + '%';
            progressText.textContent = Math.floor(value);
        }
    }

    // Auto hide loading screen after 12 seconds
    setTimeout(() => {
        const loadingScreen = document.getElementById('loadingScreen');
        if (loadingScreen) {
            loadingScreen.classList.add('hide');
        }
    }, 12000);

    // Or hide when page fully loads (whichever comes first)
    window.addEventListener('load', () => {
        const loadingScreen = document.getElementById('loadingScreen');
        if (loadingScreen && !loadingScreen.classList.contains('hide')) {
            // Complete the progress bar
            updateProgress(100);
            // Then hide after a short delay
            setTimeout(() => {
                loadingScreen.classList.add('hide');
            }, 300);
        }
    });
</script>

