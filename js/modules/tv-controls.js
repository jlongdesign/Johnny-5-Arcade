/**
 * TV Controls Module
 * Handles TV fullscreen mode, audio controls, and TV-specific functionality
 */

const TVControls = {
    /**
     * Initialize TV controls
     */
    init() {
        this.addEventListeners();
        this.addFullscreenButton();
        console.log('📺 TV Controls initialized');
    },

    /**
     * Add event listeners for TV controls
     */
    addEventListeners() {
        // ESC key to exit fullscreen
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.exitFullscreen();
            }
        });

        // TV knob click handlers
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('tv-knob')) {
                if (e.target.title === 'Volume') {
                    this.toggleGameAudio();
                } else if (e.target.title === 'Channel') {
                    this.resetGame();
                }
            }
        });
    },

    /**
     * Add fullscreen button to TV
     */
    addFullscreenButton() {
        const tvScreen = document.querySelector('.tv-screen');
        if (!tvScreen || tvScreen.querySelector('.tv-fullscreen-btn')) return;
        
        const fullscreenBtn = document.createElement('button');
        fullscreenBtn.className = 'tv-fullscreen-btn';
        fullscreenBtn.innerHTML = '⛶ FULLSCREEN';
        fullscreenBtn.onclick = () => this.toggleFullscreen();
        
        tvScreen.appendChild(fullscreenBtn);
    },

    /**
     * Toggle TV fullscreen mode
     */
    toggleFullscreen() {
        const retroTV = document.querySelector('.retro-tv');
        const fullscreenBtn = document.querySelector('.tv-fullscreen-btn');
        
        if (!retroTV) return;
        
        if (retroTV.classList.contains('tv-fullscreen-mode')) {
            this.exitFullscreen();
        } else {
            this.enterFullscreen();
        }
    },

    /**
     * Enter fullscreen mode
     */
    enterFullscreen() {
        const retroTV = document.querySelector('.retro-tv');
        const fullscreenBtn = document.querySelector('.tv-fullscreen-btn');
        
        if (!retroTV) return;
        
        retroTV.classList.add('tv-fullscreen-mode');
        fullscreenBtn.innerHTML = '⛶ EXIT FULLSCREEN';
        document.body.style.overflow = 'hidden';
        
        // Optimize for fullscreen
        const iframe = retroTV.querySelector('iframe');
        if (iframe) {
            setTimeout(() => this.optimizeGameDisplay(iframe, true), 100);
        }
        
        this.showTVMessage('FULLSCREEN ON - Press ESC to exit', 2000);
    },

    /**
     * Exit fullscreen mode
     */
    exitFullscreen() {
        const retroTV = document.querySelector('.retro-tv');
        const fullscreenBtn = document.querySelector('.tv-fullscreen-btn');
        
        if (!retroTV || !retroTV.classList.contains('tv-fullscreen-mode')) return;
        
        retroTV.classList.remove('tv-fullscreen-mode');
        if (fullscreenBtn) fullscreenBtn.innerHTML = '⛶ FULLSCREEN';
        document.body.style.overflow = '';
        
        // Restore original scaling
        const iframe = retroTV.querySelector('iframe');
        if (iframe) {
            setTimeout(() => this.optimizeGameDisplay(iframe), 100);
        }
        
        this.showTVMessage('FULLSCREEN OFF', 1000);
    },

    /**
     * Toggle game audio
     */
    toggleGameAudio() {
        const iframe = document.querySelector('.tv-screen iframe');
        if (!iframe) return;

        const audioButton = document.querySelector('.tv-knob[title="Volume"]');
        
        if (iframe.style.opacity === '0.5') {
            // Unmute
            iframe.style.opacity = '1';
            if (audioButton) audioButton.style.background = '';
            this.showTVMessage('🔊 AUDIO ON', 1000);
        } else {
            // Mute
            iframe.style.opacity = '0.5';
            if (audioButton) audioButton.style.background = '#ff0000';
            this.showTVMessage('🔇 AUDIO MUTED', 1000);
        }
    },

    /**
     * Reset/reload current game
     */
    resetGame() {
        const iframe = document.querySelector('.tv-screen iframe');
        if (iframe && iframe.src) {
            this.showTVMessage('🔄 RESETTING GAME...', 2000);
            setTimeout(() => {
                iframe.src = iframe.src; // Reload iframe
            }, 500);
        }
    },

    /**
     * Optimize game display for different screen modes
     */
    optimizeGameDisplay(gameElement, isFullscreen = false) {
        if (!gameElement) return;

        const src = gameElement.src || '';
        let transform = 'translate(-50%, -50%)';

        // Service-specific scaling
        if (src.includes('archive.org')) {
            const scale = isFullscreen ? 1.10 : 1.05;
            transform += ` scale(${scale})`;
        } else if (src.includes('emulatorjs')) {
            const scale = isFullscreen ? 1.02 : 1.01;
            transform += ` scale(${scale})`;
        } else if (src.includes('retrogames.cc')) {
            const scale = isFullscreen ? 1.15 : 1.08;
            transform += ` scale(${scale})`;
        }

        gameElement.style.transform = transform;
        gameElement.style.transformOrigin = 'center center';
        
        console.log(`🎮 Optimized display: ${isFullscreen ? 'fullscreen' : 'normal'} mode`);
    },

    /**
     * Show temporary message on TV screen
     */
    showTVMessage(message, duration = 2000, color = '#00ff00') {
        // Use ThemeEffects if available, otherwise create our own
        if (window.ThemeEffects && window.ThemeEffects.showTVMessage) {
            window.ThemeEffects.showTVMessage(message, duration, color);
            return;
        }

        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = `
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: ${color};
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            z-index: 10;
            animation: fadeInOut ${duration}ms ease-in-out forwards;
        `;
        messageDiv.textContent = message;
        
        const tvScreen = document.querySelector('.tv-screen');
        if (tvScreen) {
            tvScreen.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, duration);
        }
    }
};

// Make globally available for backwards compatibility
window.TVControls = TVControls;
window.toggleTVFullscreen = TVControls.toggleFullscreen.bind(TVControls);
window.toggleGameAudio = TVControls.toggleGameAudio.bind(TVControls);
window.resetGame = TVControls.resetGame.bind(TVControls);