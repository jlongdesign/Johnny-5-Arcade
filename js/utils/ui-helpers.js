/**
 * UI Helpers Module
 * Provides utility functions for creating UI elements and interactions
 */

const UIHelpers = {
    /**
     * Create game instructions overlay
     */
    createGameInstructions() {
        const instructions = document.createElement('div');
        instructions.className = 'game-instructions';
        instructions.innerHTML = `
            <div style="
                position: absolute;
                bottom: 20px;
                left: 20px;
                right: 20px;
                background: rgba(0, 0, 0, 0.9);
                color: #00ff00;
                padding: 15px;
                border-radius: 8px;
                border: 2px solid #00ff00;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                z-index: 10;
                opacity: 0.9;
                transition: opacity 0.3s ease;
            ">
                <div style="text-align: center; margin-bottom: 10px; color: #00ffff; font-weight: bold;">
                    🎮 GAME CONTROLS
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; font-size: 0.8rem;">
                    <div><strong>Arrow Keys:</strong> Move/Navigate</div>
                    <div><strong>Z Key:</strong> Action/Jump</div>
                    <div><strong>X Key:</strong> Secondary Action</div>
                    <div><strong>Enter:</strong> Start/Pause</div>
                    <div><strong>Space:</strong> Select</div>
                    <div><strong>Shift:</strong> Run/Turbo</div>
                </div>
                <div style="text-align: center; margin-top: 10px; font-size: 0.7rem; color: #ffff00;">
                    Click anywhere in the game to focus controls • Press ESC to exit fullscreen
                </div>
            </div>
        `;
        return instructions;
    },

    /**
     * Create loading spinner
     */
    createLoadingSpinner(text = 'Loading...', color = '#00ff00') {
        const spinner = document.createElement('div');
        spinner.className = 'ui-loading-spinner';
        spinner.innerHTML = `
            <div style="
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 15px;
                color: ${color};
                font-family: 'Courier New', monospace;
            ">
                <div class="loading" style="
                    width: 30px;
                    height: 30px;
                    border: 3px solid ${color};
                    border-top: 3px solid transparent;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                "></div>
                <div style="font-size: 1rem; font-weight: bold;">
                    ${text}
                </div>
            </div>
        `;
        return spinner;
    },

    /**
     * Create notification toast
     */
    createNotification(message, type = 'info', duration = 4000) {
        const notification = document.createElement('div');
        notification.className = 'ui-notification';
        
        const colors = {
            'info': { bg: '#0066cc', border: '#0099ff', icon: 'ℹ️' },
            'success': { bg: '#006600', border: '#00ff00', icon: '✅' },
            'warning': { bg: '#cc6600', border: '#ff9900', icon: '⚠️' },
            'error': { bg: '#cc0000', border: '#ff0000', icon: '❌' }
        };
        
        const config = colors[type] || colors.info;
        
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${config.bg};
                color: white;
                border: 2px solid ${config.border};
                border-radius: 8px;
                padding: 15px 20px;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                z-index: 10000;
                max-width: 300px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                animation: slideInRight 0.3s ease-out;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 1.2rem;">${config.icon}</span>
                    <span>${message}</span>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after duration
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, duration);
        
        return notification;
    },

    /**
     * Create confirmation dialog
     */
    createConfirmDialog(title, message, onConfirm, onCancel) {
        const dialog = document.createElement('div');
        dialog.className = 'ui-confirm-dialog';
        dialog.innerHTML = `
            <div style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10001;
                font-family: 'Courier New', monospace;
            ">
                <div style="
                    background: #1a1a1a;
                    border: 3px solid #00ff00;
                    border-radius: 10px;
                    padding: 30px;
                    max-width: 400px;
                    width: 90%;
                    text-align: center;
                ">
                    <h3 style="color: #00ff00; margin-bottom: 15px; font-size: 1.3rem;">
                        ${title}
                    </h3>
                    <p style="color: #ccc; margin-bottom: 25px; line-height: 1.6;">
                        ${message}
                    </p>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <button class="confirm-yes" style="
                            background: #006600;
                            color: white;
                            border: 2px solid #00ff00;
                            padding: 10px 20px;
                            border-radius: 5px;
                            cursor: pointer;
                            font-family: 'Courier New', monospace;
                            font-weight: bold;
                        ">✅ Yes</button>
                        <button class="confirm-no" style="
                            background: #660000;
                            color: white;
                            border: 2px solid #ff0000;
                            padding: 10px 20px;
                            border-radius: 5px;
                            cursor: pointer;
                            font-family: 'Courier New', monospace;
                            font-weight: bold;
                        ">❌ No</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(dialog);
        
        // Event listeners
        dialog.querySelector('.confirm-yes').addEventListener('click', () => {
            document.body.removeChild(dialog);
            if (onConfirm) onConfirm();
        });
        
        dialog.querySelector('.confirm-no').addEventListener('click', () => {
            document.body.removeChild(dialog);
            if (onCancel) onCancel();
        });
        
        // Close on background click
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) {
                document.body.removeChild(dialog);
                if (onCancel) onCancel();
            }
        });
        
        return dialog;
    },

    /**
     * Create rating stars
     */
    createRatingStars(currentRating = 0, maxRating = 5, interactive = false, onRate = null) {
        const container = document.createElement('div');
        container.className = 'ui-rating-stars';
        container.style.cssText = `
            display: inline-flex;
            gap: 2px;
            cursor: ${interactive ? 'pointer' : 'default'};
        `;
        
        for (let i = 1; i <= maxRating; i++) {
            const star = document.createElement('span');
            star.style.cssText = `
                font-size: 1.2rem;
                color: ${i <= currentRating ? '#ffaa00' : '#666'};
                transition: color 0.2s ease;
            `;
            star.textContent = '★';
            star.dataset.rating = i;
            
            if (interactive) {
                star.addEventListener('mouseenter', () => {
                    this.highlightStars(container, i);
                });
                
                star.addEventListener('click', () => {
                    if (onRate) onRate(i);
                    this.setStarRating(container, i);
                });
                
                container.addEventListener('mouseleave', () => {
                    this.setStarRating(container, currentRating);
                });
            }
            
            container.appendChild(star);
        }
        
        return container;
    },

    /**
     * Helper: Highlight stars on hover
     */
    highlightStars(container, rating) {
        const stars = container.querySelectorAll('span');
        stars.forEach((star, index) => {
            star.style.color = (index < rating) ? '#ffaa00' : '#666';
        });
    },

    /**
     * Helper: Set star rating
     */
    setStarRating(container, rating) {
        const stars = container.querySelectorAll('span');
        stars.forEach((star, index) => {
            star.style.color = (index < rating) ? '#ffaa00' : '#666';
        });
    },

    /**
     * Create progress bar
     */
    createProgressBar(progress = 0, showPercentage = true) {
        const container = document.createElement('div');
        container.className = 'ui-progress-bar';
        container.innerHTML = `
            <div style="
                background: #333;
                border: 2px solid #00ff00;
                border-radius: 10px;
                overflow: hidden;
                position: relative;
                height: 20px;
                width: 100%;
            ">
                <div class="progress-fill" style="
                    background: linear-gradient(90deg, #00ff00, #00cc00);
                    height: 100%;
                    width: ${progress}%;
                    transition: width 0.3s ease;
                    position: relative;
                "></div>
                ${showPercentage ? `
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        color: ${progress > 50 ? '#000' : '#fff'};
                        font-size: 0.8rem;
                        font-weight: bold;
                        font-family: 'Courier New', monospace;
                        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
                    ">${Math.round(progress)}%</div>
                ` : ''}
            </div>
        `;
        return container;
    },

    /**
     * Update progress bar
     */
    updateProgressBar(progressBar, newProgress) {
        const fill = progressBar.querySelector('.progress-fill');
        const text = progressBar.querySelector('div:last-child');
        
        if (fill) {
            fill.style.width = `${newProgress}%`;
        }
        
        if (text && text.textContent.includes('%')) {
            text.textContent = `${Math.round(newProgress)}%`;
            text.style.color = newProgress > 50 ? '#000' : '#fff';
        }
    },

    /**
     * Create modal backdrop
     */
    createModalBackdrop(onClose = null) {
        const backdrop = document.createElement('div');
        backdrop.className = 'ui-modal-backdrop';
        backdrop.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            backdrop-filter: blur(3px);
            animation: fadeIn 0.3s ease-out;
        `;
        
        if (onClose) {
            backdrop.addEventListener('click', onClose);
        }
        
        return backdrop;
    },

    /**
     * Create responsive grid
     */
    createResponsiveGrid(items, minColumnWidth = '250px') {
        const grid = document.createElement('div');
        grid.className = 'ui-responsive-grid';
        grid.style.cssText = `
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(${minColumnWidth}, 1fr));
            gap: 20px;
            width: 100%;
        `;
        
        items.forEach(item => {
            grid.appendChild(item);
        });
        
        return grid;
    },

    /**
     * Create search input with icon
     */
    createSearchInput(placeholder = 'Search...', onSearch = null) {
        const container = document.createElement('div');
        container.className = 'ui-search-input';
        container.innerHTML = `
            <div style="
                position: relative;
                display: inline-block;
                width: 100%;
            ">
                <input type="text" placeholder="${placeholder}" style="
                    background: #1a1a1a;
                    border: 2px solid #00ff00;
                    color: #00ff00;
                    padding: 10px 40px 10px 15px;
                    border-radius: 25px;
                    font-family: 'Courier New', monospace;
                    font-size: 0.9rem;
                    width: 100%;
                    outline: none;
                    transition: all 0.3s ease;
                " />
                <span style="
                    position: absolute;
                    right: 15px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #00ff00;
                    font-size: 1rem;
                    pointer-events: none;
                ">🔍</span>
            </div>
        `;
        
        const input = container.querySelector('input');
        
        // Add focus effects
        input.addEventListener('focus', () => {
            input.style.borderColor = '#00ffff';
            input.style.boxShadow = '0 0 10px rgba(0, 255, 255, 0.3)';
        });
        
        input.addEventListener('blur', () => {
            input.style.borderColor = '#00ff00';
            input.style.boxShadow = 'none';
        });
        
        // Add search functionality
        if (onSearch) {
            input.addEventListener('input', (e) => onSearch(e.target.value));
        }
        
        return container;
    },

    /**
     * Add animation classes
     */
    addAnimationCSS() {
        if (!document.getElementById('ui-helpers-animations')) {
            const style = document.createElement('style');
            style.id = 'ui-helpers-animations';
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                @keyframes fadeOut {
                    from { opacity: 1; }
                    to { opacity: 0; }
                }
                
                @keyframes pulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                }
                
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    75% { transform: translateX(5px); }
                }
            `;
            document.head.appendChild(style);
        }
    }
};

// Auto-add animation CSS on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => UIHelpers.addAnimationCSS());
} else {
    UIHelpers.addAnimationCSS();
}

// Make globally available
window.UIHelpers = UIHelpers;