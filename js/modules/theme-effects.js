/**
 * Theme Effects Module
 * Handles visual effects, animations, and typing effects
 */

const ThemeEffects = {
    /**
     * Initialize theme effects
     */
    init() {
        this.addRetroEffects();
        this.addTypingEffect();
        console.log('🎨 Theme effects initialized');
    },

    /**
     * Add retro visual effects
     */
    addRetroEffects() {
        // Add hover effects to game cards
        jQuery('.game-card').hover(
            function() {
                jQuery(this).addClass('retro-hover');
            },
            function() {
                jQuery(this).removeClass('retro-hover');
            }
        );
        
        // Add click effects to buttons
        jQuery('.retro-button, .play-button').on('click', function() {
            const button = jQuery(this);
            button.addClass('button-clicked');
            setTimeout(() => {
                button.removeClass('button-clicked');
            }, 200);
        });
    },

    /**
     * Add typing effect to the site title
     */
    addTypingEffect() {
        const title = jQuery('.site-title');
        if (title.length && title.text().trim().length > 0) {
            const originalText = title.text().trim();
            title.text('');
            title.css('border-right', '2px solid #00ffff');
            
            // Add delay before typing starts
            setTimeout(() => {
                let i = 0;
                const typeInterval = setInterval(() => {
                    title.text(originalText.substring(0, i + 1));
                    i++;
                    if (i >= originalText.length) {
                        clearInterval(typeInterval);
                        setTimeout(() => {
                            title.css('border-right', 'none');
                        }, 1000);
                    }
                }, 100); // Typing speed
            }, 0); // Delay before starting
        }
    },

    /**
     * Show temporary message on TV screen
     */
    showTVMessage(message, duration = 2000, color = '#00ff00') {
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

// Make globally available
window.ThemeEffects = ThemeEffects;