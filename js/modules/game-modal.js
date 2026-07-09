/**
 * Game Modal Module
 * Handles the game modal functionality for launching games in overlay
 */

const GameModal = {
    /**
     * Initialize game modal
     */
    init() {
        this.createModal();
        this.addEventListeners();
        console.log('🎮 Game Modal initialized');
    },

    /**
     * Create modal HTML if it doesn't exist
     */
    createModal() {
        // Check if modal already exists
        if (document.getElementById('game-modal')) {
            return;
        }

        const modal = document.createElement('div');
        modal.id = 'game-modal';
        modal.className = 'game-modal';
        modal.innerHTML = `
            <div class="game-modal-overlay"></div>
            <div class="game-modal-content">
                <!-- Content will be dynamically added by GameEmulator -->
            </div>
        `;
        
        document.body.appendChild(modal);
        this.addModalStyles();
    },

    /**
     * Add modal styles
     */
    addModalStyles() {
        if (document.getElementById('game-modal-styles')) {
            return;
        }

        const style = document.createElement('style');
        style.id = 'game-modal-styles';
        style.textContent = `
            .game-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10000;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .game-modal.active {
                display: flex;
                opacity: 1;
                align-items: center;
                justify-content: center;
            }
            
            .game-modal-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                backdrop-filter: blur(5px);
            }
            
            .game-modal-content {
                position: relative;
                width: 95%;
                height: 95%;
                max-width: 1200px;
                max-height: 800px;
                background: #1a1a1a;
                border: 3px solid #00ff00;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 0 30px rgba(0, 255, 0, 0.3);
                animation: modalSlideIn 0.5s ease-out;
            }
            
            .close-modal {
                position: absolute;
                top: 15px;
                right: 15px;
                background: #ff0000;
                color: white;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                font-size: 1.5rem;
                font-weight: bold;
                cursor: pointer;
                z-index: 10001;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                font-family: 'Courier New', monospace;
            }
            
            .close-modal:hover {
                background: #cc0000;
                transform: scale(1.1);
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
            }
            
            @keyframes modalSlideIn {
                from {
                    transform: scale(0.8) translateY(-50px);
                    opacity: 0;
                }
                to {
                    transform: scale(1) translateY(0);
                    opacity: 1;
                }
            }
            
            /* Responsive design */
            @media (max-width: 768px) {
                .game-modal-content {
                    width: 98%;
                    height: 98%;
                    border-radius: 10px;
                }
                
                .close-modal {
                    top: 10px;
                    right: 10px;
                    width: 35px;
                    height: 35px;
                    font-size: 1.3rem;
                }
            }
            
            /* Prevent body scroll when modal is open */
            body.modal-open {
                overflow: hidden;
            }
            
            /* Loading state for modal content */
            .game-modal-content.loading {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .game-modal-content.loading::before {
                content: "🎮 LOADING GAME...";
                color: #00ff00;
                font-family: 'Courier New', monospace;
                font-size: 1.5rem;
                font-weight: bold;
                text-align: center;
                animation: pulse 1.5s infinite;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
        `;
        
        document.head.appendChild(style);
    },

    /**
     * Add event listeners
     */
    addEventListeners() {
        // Close modal on overlay click
        jQuery(document).on('click', '.game-modal-overlay', () => {
            this.closeModal();
        });

        // Close modal on close button click
        jQuery(document).on('click', '.close-modal', () => {
            this.closeModal();
        });

        // Close modal on ESC key
        jQuery(document).on('keydown', (e) => {
            if (e.key === 'Escape') {
                const gameModal = document.getElementById('game-modal');
                if (gameModal && gameModal.classList.contains('active')) {
                    this.closeModal();
                }
            }
        });

        // Prevent modal content clicks from closing modal
        jQuery(document).on('click', '.game-modal-content', (e) => {
            e.stopPropagation();
        });

        // Handle window resize
        jQuery(window).on('resize', () => {
            this.handleResize();
        });
    },

    /**
     * Open modal with game content
     */
    openModal(content = null) {
        const modal = document.getElementById('game-modal');
        const modalContent = modal.querySelector('.game-modal-content');
        
        if (!modal) {
            console.error('Game modal not found');
            return;
        }

        // Clear existing content
        modalContent.innerHTML = '';
        
        // Add content if provided
        if (content) {
            if (typeof content === 'string') {
                modalContent.innerHTML = content;
            } else {
                modalContent.appendChild(content);
            }
        } else {
            // Show loading state
            modalContent.classList.add('loading');
        }

        // Show modal
        modal.classList.add('active');
        document.body.classList.add('modal-open');
        
        // Focus trap
        this.setFocusTrap(modal);
        
        console.log('🎮 Game modal opened');
    },

    /**
     * Close modal
     */
    closeModal() {
        const modal = document.getElementById('game-modal');
        if (!modal) return;

        // Hide modal
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
        
        // Clear content after animation
        setTimeout(() => {
            const modalContent = modal.querySelector('.game-modal-content');
            if (modalContent) {
                modalContent.innerHTML = '';
                modalContent.classList.remove('loading');
            }
        }, 300);
        
        // Remove focus trap
        this.removeFocusTrap();
        
        console.log('🎮 Game modal closed');
        
        // Trigger custom event
        jQuery(document).trigger('gameModalClosed');
    },

    /**
     * Check if modal is open
     */
    isOpen() {
        const modal = document.getElementById('game-modal');
        return modal && modal.classList.contains('active');
    },

    /**
     * Set loading state
     */
    setLoading(show = true) {
        const modal = document.getElementById('game-modal');
        const modalContent = modal?.querySelector('.game-modal-content');
        
        if (modalContent) {
            if (show) {
                modalContent.classList.add('loading');
                modalContent.innerHTML = '';
            } else {
                modalContent.classList.remove('loading');
            }
        }
    },

    /**
     * Add content to modal
     */
    setContent(content) {
        const modal = document.getElementById('game-modal');
        const modalContent = modal?.querySelector('.game-modal-content');
        
        if (modalContent) {
            modalContent.classList.remove('loading');
            modalContent.innerHTML = '';
            
            if (typeof content === 'string') {
                modalContent.innerHTML = content;
            } else {
                modalContent.appendChild(content);
            }
        }
    },

    /**
     * Handle window resize
     */
    handleResize() {
        if (this.isOpen()) {
            // Adjust modal size for mobile
            const modal = document.getElementById('game-modal');
            const modalContent = modal?.querySelector('.game-modal-content');
            
            if (modalContent && window.innerWidth <= 768) {
                modalContent.style.width = '98%';
                modalContent.style.height = '98%';
            }
        }
    },

    /**
     * Set focus trap for accessibility
     */
    setFocusTrap(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            // Focus first element
            firstElement.focus();
            
            // Trap focus
            this.focusTrapHandler = (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            };
            
            modal.addEventListener('keydown', this.focusTrapHandler);
        }
    },

    /**
     * Remove focus trap
     */
    removeFocusTrap() {
        const modal = document.getElementById('game-modal');
        if (modal && this.focusTrapHandler) {
            modal.removeEventListener('keydown', this.focusTrapHandler);
            this.focusTrapHandler = null;
        }
    },

    /**
     * Toggle fullscreen mode
     */
    toggleFullscreen() {
        const modal = document.getElementById('game-modal');
        
        if (!modal) return;
        
        if (!document.fullscreenElement) {
            modal.requestFullscreen().then(() => {
                modal.classList.add('fullscreen');
                console.log('🎮 Modal entered fullscreen');
            }).catch(err => {
                console.warn('Fullscreen request failed:', err);
            });
        } else {
            document.exitFullscreen().then(() => {
                modal.classList.remove('fullscreen');
                console.log('🎮 Modal exited fullscreen');
            });
        }
    },

    /**
     * Add custom CSS for specific games
     */
    addGameSpecificStyles(gameId, styles) {
        const existingStyle = document.getElementById(`game-${gameId}-styles`);
        if (existingStyle) {
            existingStyle.remove();
        }
        
        const style = document.createElement('style');
        style.id = `game-${gameId}-styles`;
        style.textContent = styles;
        document.head.appendChild(style);
    },

    /**
     * Remove game-specific styles
     */
    removeGameSpecificStyles(gameId) {
        const existingStyle = document.getElementById(`game-${gameId}-styles`);
        if (existingStyle) {
            existingStyle.remove();
        }
    }
};

// Make globally available
window.GameModal = GameModal;