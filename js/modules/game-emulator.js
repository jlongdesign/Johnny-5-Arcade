/**
 * Game Emulator Module
 * Handles all game loading, ROM processing, and emulator management
 */

const GameEmulator = {
    /**
     * Demo games for different systems
     */
    demoGames: {
        'nes': 'https://archive.org/embed/smb_nes_2',
        'snes': 'https://archive.org/embed/super-mario-world-super-nintendo',
        'gb': 'https://archive.org/embed/tetris_gb',
        'gbc': 'https://archive.org/embed/PokemonRedVersion',
        'gba': 'https://archive.org/embed/PokemonEmeraldVersion',
        'genesis': 'https://archive.org/embed/SegaGenesisSonicTheHedgehog',
        'arcade': 'https://archive.org/embed/MAME_pacman',
        'n64': 'https://archive.org/embed/SuperMario64',
        'psx': 'https://archive.org/embed/CrashBandicoot_201904'
    },

    /**
     * Initialize the game emulator module
     */
    init() {
        this.addEventListeners();
        console.log('🎮 Game Emulator initialized');
    },

    /**
     * Add event listeners for game emulator
     */
    addEventListeners() {
        // ESC key handler for game modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const gameModal = document.getElementById('game-modal');
                if (gameModal && gameModal.classList.contains('active')) {
                    this.closeGameModal();
                }
            }
        });
    },

    // ========================================
    // MAIN GAME LOADING METHODS
    // ========================================

    /**
     * Play a game in modal (main entry point)
     */
    playGame(romUrl, emulatorType) {
        console.log('🎮 Loading game in modal:', romUrl, 'Type:', emulatorType);
        
        const modal = document.getElementById('game-modal');
        const modalContent = modal?.querySelector('.game-modal-content');
        
        if (!modal || !modalContent) {
            console.error('Game modal elements not found');
            return;
        }
        
        // Clear existing content
        modalContent.innerHTML = '';
        
        // Create TV container and close button
        const tvContainer = this.createTVContainer(emulatorType);
        const closeButton = this.createCloseButton();
        
        modalContent.appendChild(tvContainer);
        modalContent.appendChild(closeButton);
        
        // Show modal
        modal.classList.add('active');
        
        // Initialize emulator after delay
        setTimeout(() => {
            this.initializeRetroArch(romUrl, emulatorType);
        }, 1000);
    },

    /**
     * Load game directly in TV (for single game pages)
     */
    loadGameInTV(romUrl, emulatorType) {
        console.log('📺 Loading game in TV:', romUrl, emulatorType);
        
        const tvEmulator = document.getElementById('tv-emulator');
        const loadingDiv = document.querySelector('.tv-loading');
        const staticDiv = document.querySelector('.tv-static');
        
        if (!tvEmulator || !loadingDiv) {
            console.error('TV elements not found');
            return;
        }
        
        // Add fullscreen button
        this.addFullscreenButton();
        
        // Update loading display
        this.updateLoadingDisplay(loadingDiv, emulatorType);
        
        // Process ROM URL
        const processedUrl = this.processRomUrl(romUrl, emulatorType);
        
        // Load game with delay and error handling
        setTimeout(() => {
            this.loadGameWithFallback(tvEmulator, processedUrl, emulatorType, loadingDiv, staticDiv);
        }, 1500);
    },

    // ========================================
    // ROM URL PROCESSING
    // ========================================

    /**
 * Process and validate ROM URL (UPDATED)
 */
processRomUrl(romUrl, emulatorType) {
    // Handle empty/missing ROM URL
    if (!romUrl || romUrl.trim() === '') {
        console.log('🎮 No ROM URL provided, using demo game');
        return this.demoGames[emulatorType] || this.demoGames['nes'];
    }

    // NEW: Check if this is a local file that won't work with external emulators
    if (this.isLocalFile(romUrl)) {
        console.warn('🏠 Local ROM file detected - external emulators cannot access local files');
        console.warn('📝 Recommendation: Upload to your server or use Archive.org URLs');
        
        // Show user-friendly message and fallback to demo
        if (window.ThemeEffects?.showTVMessage) {
            window.ThemeEffects.showTVMessage('Local ROM files not supported. Using demo game.', 4000, '#ffaa00');
        }
        return this.demoGames[emulatorType] || this.demoGames['nes'];
    }

    // Use AJAX handler to test ROM accessibility
    if (window.AjaxHandlers && typeof window.AjaxHandlers.testRomUrl === 'function') {
        window.AjaxHandlers.testRomUrl(romUrl).then(result => {
            if (!result.accessible) {
                console.warn('🚨 ROM URL not accessible:', result.message);
            }
        });
    }

    // Handle compressed files (not supported)
    if (romUrl.match(/\.(zip|rar|7z)(\?|$)/i)) {
        console.warn('📦 Compressed file detected - not supported');
        return null;
    }
    
    // Handle Archive.org download URLs -> convert to embed
    if (romUrl.includes('archive.org/download/') && romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
        return this.convertArchiveDownloadToEmbed(romUrl, emulatorType);
    }
    
    // Handle direct ROM files -> wrap with emulator (only if not local)
    if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
        return this.wrapRomWithEmulator(romUrl, emulatorType);
    }
    
    // Return as-is for embed URLs and other formats
    return romUrl;
},

/**
 * Check if ROM URL is a local file that won't work with external emulators
 */
isLocalFile(romUrl) {
    // Check for local development indicators
    if (this.isLocalDevelopment()) {
        // Local relative paths
        if (romUrl.startsWith('/') && !romUrl.startsWith('//')) {
            return true;
        }
        
        // Local absolute URLs
        if (romUrl.includes('localhost') || 
            romUrl.includes('.local') || 
            romUrl.includes('127.0.0.1') ||
            romUrl.includes('192.168.') ||
            romUrl.includes('10.0.') ||
            romUrl.includes('172.')) {
            return true;
        }
    }
    
    // WordPress uploads that might be local
    if (romUrl.includes('/wp-content/uploads/') && 
        (this.isLocalDevelopment() || !romUrl.startsWith('http'))) {
        return true;
    }
    
    return false;
},

    /**
     * Convert Archive.org download URL to embed URL
     */
    convertArchiveDownloadToEmbed(romUrl, emulatorType) {
        const urlParts = romUrl.split('/');
        const collectionIndex = urlParts.indexOf('download') + 1;
        
        if (collectionIndex > 0 && urlParts[collectionIndex]) {
            const collection = urlParts[collectionIndex];
            const embedUrl = `https://archive.org/embed/${collection}`;
            console.log('🏛️ Converted Archive.org download to embed:', embedUrl);
            return embedUrl;
        }
        
        // Fallback to demo game if conversion fails
        console.warn('🎮 Archive.org URL parsing failed, using demo game');
        return this.demoGames[emulatorType] || this.demoGames['nes'];
    },

    /**
     * Wrap ROM file with emulator service
     */
    wrapRomWithEmulator(romUrl, emulatorType) {
        const systemMap = {
            'nes': 'nes', 'snes': 'snes', 'gb': 'gb', 'gbc': 'gbc',
            'gba': 'gba', 'genesis': 'segaMD', 'arcade': 'arcade',
            'n64': 'n64', 'psx': 'psx'
        };
        
        const system = systemMap[emulatorType] || 'nes';
        const wrappedUrl = `https://demo.emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`;
        
        console.log('🕹️ Wrapping ROM with EmulatorJS:', wrappedUrl);
        return wrappedUrl;
    },

    // ========================================
    // EMULATOR INITIALIZATION
    // ========================================

    /**
     * Initialize emulator (main router)
     */
    initializeRetroArch(romUrl, emulatorType) {
        const canvas = document.getElementById('retroarch-canvas');
        const loadingDiv = document.querySelector('.tv-loading');
        const staticDiv = document.querySelector('.tv-static');
        
        if (!canvas) {
            console.error('Canvas element not found');
            return;
        }
        
        console.log('🎮 Initializing emulator:', { romUrl, emulatorType });
        
        // Route to appropriate loader
        if (!romUrl || romUrl.trim() === '') {
            this.loadEmbeddedEmulator(canvas, romUrl, emulatorType, loadingDiv, staticDiv);
        } else if (romUrl.match(/\.(zip|rar|7z)(\?|$)/i)) {
            this.showCompressedFileError(loadingDiv, romUrl);
        } else if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
            this.loadDirectROM(canvas, romUrl, emulatorType, loadingDiv, staticDiv);
        } else {
            this.loadEmbeddedEmulator(canvas, romUrl, emulatorType, loadingDiv, staticDiv);
        }
    },

    /**
 * Load direct ROM file (UPDATED)
 */
loadDirectROM(canvas, romUrl, emulatorType, loadingDiv, staticDiv) {
    console.log('🎮 Loading direct ROM file:', romUrl);
    
    // Enhanced local development check
    if (this.isLocalFile(romUrl)) {
        this.showLocalFileError(loadingDiv, romUrl);
        return;
    }
    
    const iframe = this.createGameIframe();
    const system = this.getEmulatorJSSystem(emulatorType);
    
    // Emulator services in order of preference
    const emulatorServices = [
        `https://www.retrogames.cc/upload-rom/?system=${system}&url=${encodeURIComponent(romUrl)}`,
        `https://demo.emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`,
        `https://emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`
    ];
    
    this.tryEmulatorServices(iframe, emulatorServices, loadingDiv, staticDiv, canvas);
},

/**
 * Show local file error with helpful instructions
 */
showLocalFileError(loadingDiv, romUrl) {
    loadingDiv.innerHTML = `
        <div class="loading-text" style="color: #ff6600;">🏠 LOCAL FILE ERROR</div>
        <div style="color: #ffcc66; font-size: 0.9rem; margin-top: 10px; text-align: left;">
            Local ROM files cannot be loaded by external emulators.
        </div>
        <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left;">
            <strong>Solutions:</strong><br>
            • Upload ROM to your web server<br>
            • Use publicly accessible URLs<br>
            • Use Archive.org embed URLs<br>
            • Use demo games (leave ROM URL empty)
        </div>
        <div style="color: #666; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
            File: ${romUrl}<br>
            This will work when deployed to a live server.
        </div>
        <button onclick="window.GameEmulator.loadDemoGame('${romUrl}')" style="
            background: #006600; color: white; border: 1px solid #00ff00;
            padding: 8px 15px; border-radius: 4px; margin-top: 15px;
            cursor: pointer; font-family: 'Courier New', monospace;
        ">🎮 Load Demo Game Instead</button>
    `;
},

/**
 * Load demo game as fallback
 */
loadDemoGame(originalUrl) {
    console.log('🎮 Loading demo game as fallback for:', originalUrl);
    
    // Try to guess system type from original URL
    const emulatorType = this.guessSystemFromUrl(originalUrl) || 'nes';
    const demoUrl = this.demoGames[emulatorType] || this.demoGames['nes'];
    
    // Reload with demo URL
    const tvEmulator = document.getElementById('tv-emulator');
    const loadingDiv = document.querySelector('.tv-loading');
    const staticDiv = document.querySelector('.tv-static');
    
    if (tvEmulator && loadingDiv) {
        this.updateLoadingDisplay(loadingDiv, emulatorType);
        this.loadGameWithFallback(tvEmulator, demoUrl, emulatorType, loadingDiv, staticDiv);
    }
},

/**
 * Guess system type from ROM URL
 */
guessSystemFromUrl(romUrl) {
    const extensionMap = {
        '.nes': 'nes',
        '.smc': 'snes', '.sfc': 'snes',
        '.gb': 'gb',
        '.gbc': 'gbc',
        '.gba': 'gba',
        '.md': 'genesis', '.gen': 'genesis', '.bin': 'genesis',
        '.z64': 'n64', '.n64': 'n64',
        '.iso': 'psx'
    };
    
    const url = romUrl.toLowerCase();
    for (const [ext, system] of Object.entries(extensionMap)) {
        if (url.includes(ext)) {
            return system;
        }
    }
    
    return 'nes'; // Default fallback
},

    /**
     * Load embedded emulator (Archive.org, demo games, etc.)
     */
    loadEmbeddedEmulator(canvas, romUrl, emulatorType, loadingDiv, staticDiv) {
        console.log('🎮 Loading embedded emulator:', emulatorType, romUrl);
        
        const iframe = this.createGameIframe();
        let gameUrl = romUrl;
        
        // Use demo game if no URL provided
        if (!gameUrl || gameUrl === '') {
            gameUrl = this.demoGames[emulatorType] || this.demoGames['nes'];
            console.log('🎮 Using demo game:', gameUrl);
        }
        
        // Set up iframe handlers
        iframe.onload = () => {
            console.log('✅ Embedded emulator loaded successfully');
            this.hideLoadingShowGame(iframe, loadingDiv, staticDiv);
            this.addGameInstructions();
        };
        
        iframe.onerror = () => {
            console.error('❌ Failed to load embedded emulator');
            this.showEmulatorError(loadingDiv, romUrl);
        };
        
        // Replace canvas with iframe and load
        canvas.style.display = 'none';
        canvas.parentElement.appendChild(iframe);
        
        setTimeout(() => {
            console.log('🚀 Loading game URL:', gameUrl);
            iframe.src = gameUrl;
        }, 1500);
    },

    // ========================================
    // EMULATOR SERVICE FALLBACK
    // ========================================

    /**
     * Try multiple emulator services with fallback
     */
    tryEmulatorServices(iframe, services, loadingDiv, staticDiv, canvas, currentIndex = 0) {
        if (currentIndex >= services.length) {
            this.showEmulatorError(loadingDiv);
            return;
        }
        
        const emulatorUrl = services[currentIndex];
        console.log(`🎯 Trying emulator service ${currentIndex + 1}/${services.length}:`, emulatorUrl);
        
        // Update loading message
        loadingDiv.innerHTML = `
            <div class="loading-text">LOADING EMULATOR...</div>
            <div class="loading"></div>
            <div style="margin-top: 15px; font-size: 0.9rem; color: #ccc;">
                Trying service ${currentIndex + 1}/${services.length}...
            </div>
        `;
        
        iframe.onload = () => {
            console.log('🕹️ Emulator service loaded successfully');
            this.hideLoadingShowGame(iframe, loadingDiv, staticDiv);
            this.addGameInstructions();
        };
        
        iframe.onerror = () => {
            console.error('❌ Emulator service failed, trying next...');
            this.tryEmulatorServices(iframe, services, loadingDiv, staticDiv, canvas, currentIndex + 1);
        };
        
        // Replace canvas with iframe and load
        canvas.style.display = 'none';
        canvas.parentNode.appendChild(iframe);
        iframe.src = emulatorUrl;
    },

    /**
     * Load game with fallback for TV mode
     */
    loadGameWithFallback(tvEmulator, gameUrl, emulatorType, loadingDiv, staticDiv) {
        if (!gameUrl) {
            this.showCompressedFileError(loadingDiv, 'Compressed file detected');
            return;
        }
        
        console.log('🚀 Loading game URL in TV:', gameUrl);
        
        tvEmulator.dataset.tryCount = '0';
        tvEmulator.src = gameUrl;
        
        tvEmulator.onload = () => {
            console.log('✅ TV emulator loaded successfully');
            this.showTVGameLoaded(tvEmulator, loadingDiv, staticDiv);
        };
        
        tvEmulator.onerror = () => {
            console.error('❌ TV emulator failed, trying fallback');
            this.handleTVLoadError(tvEmulator, gameUrl, emulatorType, loadingDiv);
        };
    },

    // ========================================
    // UI CREATION METHODS
    // ========================================

    /**
     * Create TV container HTML
     */
    createTVContainer(emulatorType) {
        const tvContainer = document.createElement('div');
        tvContainer.className = 'retro-tv-container';
        tvContainer.innerHTML = `
            <div class="retro-tv">
                <div class="tv-screen">
                    <div class="tv-static"></div>
                    <div class="tv-loading">
                        <div class="loading-text">LOADING EMULATOR...</div>
                        <div class="loading"></div>
                        <div style="margin-top: 15px; font-size: 0.9rem; color: #ccc;">
                            Initializing ${emulatorType.toUpperCase()} core...
                        </div>
                    </div>
                    <canvas id="retroarch-canvas" class="retro-emulator" style="display: none;"></canvas>
                </div>
                <div class="tv-controls">
                    <div class="tv-knob" title="Volume"></div>
                    <div class="tv-knob" title="Channel"></div>
                </div>
                <div class="retro-tv-brand">ARCADE-HUB</div>
            </div>
        `;
        return tvContainer;
    },

    /**
     * Create close button for modal
     */
    createCloseButton() {
        const closeButton = document.createElement('button');
        closeButton.className = 'close-modal';
        closeButton.innerHTML = '×';
        closeButton.onclick = () => this.closeGameModal();
        return closeButton;
    },

    /**
     * Create game iframe
     */
    createGameIframe() {
        const iframe = document.createElement('iframe');
        iframe.className = 'retro-emulator';
        iframe.style.cssText = `
            width: 100%; height: 100%; border: none;
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            margin: 0; transform-origin: center center;
        `;
        iframe.frameBorder = '0';
        iframe.allow = 'autoplay; fullscreen';
        return iframe;
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
        fullscreenBtn.onclick = () => window.TVControls?.toggleFullscreen();
        
        tvScreen.appendChild(fullscreenBtn);
    },

    // ========================================
    // DISPLAY AND UI METHODS
    // ========================================

    /**
     * Hide loading and show game with TV effect
     */
    hideLoadingShowGame(gameElement, loadingDiv, staticDiv) {
        loadingDiv.style.display = 'none';
        staticDiv.style.display = 'none';
        gameElement.style.display = 'block';
        
        // TV turn-on effect
        gameElement.style.opacity = '0';
        gameElement.style.transform += ' scaleY(0.1)';
        gameElement.style.transition = 'all 0.5s ease-out';
        
        setTimeout(() => {
            gameElement.style.opacity = '1';
            gameElement.style.transform = gameElement.style.transform.replace('scaleY(0.1)', 'scaleY(1)');
            
            // Apply game optimization after effect
            setTimeout(() => this.optimizeGameDisplay(gameElement), 100);
        }, 100);
    },

    /**
     * Show TV game loaded with effects
     */
    showTVGameLoaded(tvEmulator, loadingDiv, staticDiv) {
        this.hideLoadingShowGame(tvEmulator, loadingDiv, staticDiv);
        
        // Show success messages
        this.showTVMessage('GAME LOADED - ENJOY!', 2000);
        setTimeout(() => {
            this.showTVMessage('Press fullscreen button for best experience!', 3000, '#00ffff');
        }, 2500);
    },

    /**
     * Update loading display
     */
    updateLoadingDisplay(loadingDiv, emulatorType) {
        loadingDiv.innerHTML = `
            <div class="loading-text">LOADING GAME...</div>
            <div class="loading"></div>
            <div style="margin-top: 15px; font-size: 0.9rem; color: #ccc;">
                Booting ${emulatorType.toUpperCase()} emulator...
            </div>
        `;
    },

    /**
     * Add game instructions
     */
    addGameInstructions() {
        if (!window.UIHelpers?.createGameInstructions) return;
        
        const modal = document.getElementById('game-modal');
        const existingInstructions = modal?.querySelector('.game-instructions');
        
        if (existingInstructions) existingInstructions.remove();
        
        const instructions = window.UIHelpers.createGameInstructions();
        const tvContainer = modal?.querySelector('.retro-tv-container');
        
        if (tvContainer) {
            tvContainer.appendChild(instructions);
            
            // Auto-hide after 10 seconds
            setTimeout(() => {
                instructions.style.opacity = '0';
                instructions.style.transition = 'opacity 1s ease';
                setTimeout(() => instructions.remove(), 1000);
            }, 10000);
        }
    },

    // ========================================
    // ERROR HANDLING
    // ========================================

    /**
     * Show compressed file error
     */
    showCompressedFileError(loadingDiv, romUrl) {
        loadingDiv.innerHTML = `
            <div class="loading-text" style="color: #ff6600;">📦 COMPRESSED FILE ERROR</div>
            <div style="color: #ffcc66; font-size: 0.9rem; margin-top: 10px; text-align: left;">
                ZIP/RAR/7Z files cannot be emulated directly.
            </div>
            <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left;">
                <strong>Please:</strong><br>
                • Extract the ROM file first<br>
                • Upload the .nes, .smc, .gb file instead<br>
                • Or use an Archive.org embed URL
            </div>
        `;
    },

    /**
     * Show local development message
     */
    showLocalDevelopmentMessage(loadingDiv, romUrl) {
        loadingDiv.innerHTML = `
            <div class="loading-text" style="color: #ffaa00;">🏠 LOCAL DEVELOPMENT</div>
            <div style="color: #ffcc66; font-size: 0.8rem; margin-top: 10px; text-align: left;">
                Direct ROM files don't work locally due to CORS restrictions.
            </div>
            <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left;">
                <strong>For local testing:</strong><br>
                • Use Archive.org URLs<br>
                • Deploy to live site<br>
                • Use demo games (empty ROM URL)
            </div>
            <div style="color: #666; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
                This will work on your live website!
            </div>
        `;
    },

    /**
     * Show general emulator error
     */
    showEmulatorError(loadingDiv, romUrl = '') {
        loadingDiv.innerHTML = `
            <div class="loading-text" style="color: #ff0000;">⚠️ LOADING ERROR</div>
            <div style="color: #ff6666; font-size: 0.9rem; margin-top: 10px;">
                Unable to load game emulator
            </div>
            <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px;">
                Try refreshing or using a different ROM URL
            </div>
        `;
    },

    /**
     * Handle TV load error with fallback
     */
    handleTVLoadError(tvEmulator, originalUrl, emulatorType, loadingDiv) {
        const currentTry = parseInt(tvEmulator.dataset.tryCount || '0');
        const fallbackServices = [
            `https://emulatorjs.org/beta/?system=${this.getEmulatorJSSystem(emulatorType)}&url=${encodeURIComponent(originalUrl)}`,
            `https://www.retrogames.cc/upload-rom/?system=${this.getEmulatorJSSystem(emulatorType)}&url=${encodeURIComponent(originalUrl)}`,
            this.demoGames[emulatorType] || this.demoGames['nes']
        ];
        
        if (currentTry < fallbackServices.length - 1) {
            const nextTry = currentTry + 1;
            tvEmulator.dataset.tryCount = nextTry.toString();
            
            console.log(`🔄 Trying fallback service ${nextTry}:`, fallbackServices[nextTry]);
            this.showTVMessage(`Trying backup emulator... (${nextTry}/${fallbackServices.length})`, 2000, '#ffaa00');
            
            setTimeout(() => {
                tvEmulator.src = fallbackServices[nextTry];
            }, 1000);
        } else {
            this.showTVMessage('All services failed. Using demo game.', 3000, '#ff8800');
            setTimeout(() => {
                tvEmulator.src = this.demoGames[emulatorType] || this.demoGames['nes'];
            }, 2000);
        }
    },

    // ========================================
    // UTILITY METHODS
    // ========================================

    /**
     * Check if running in local development
     */
    isLocalDevelopment() {
        return window.location.hostname === 'localhost' || 
               window.location.hostname.includes('.local') || 
               window.location.hostname === '127.0.0.1';
    },

    /**
     * Map emulator type to EmulatorJS system
     */
    getEmulatorJSSystem(emulatorType) {
        const systemMap = {
            'nes': 'nes', 'snes': 'snes', 'gb': 'gb', 'gbc': 'gbc',
            'gba': 'gba', 'genesis': 'segaMD', 'arcade': 'arcade',
            'n64': 'n64', 'psx': 'psx'
        };
        return systemMap[emulatorType] || 'nes';
    },

    /**
     * Optimize game display for different services
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
        
        console.log(`🎯 Optimized display: ${isFullscreen ? 'fullscreen' : 'normal'} mode`);
    },

    /**
     * Show TV message
     */
    showTVMessage(message, duration = 2000, color = '#00ff00') {
        if (window.ThemeEffects?.showTVMessage) {
            window.ThemeEffects.showTVMessage(message, duration, color);
        } else {
            console.log(`TV Message: ${message}`);
        }
    },

    /**
     * Close game modal
     */
    closeGameModal() {
        const modal = document.getElementById('game-modal');
        if (modal) {
            modal.classList.remove('active');
            
            // Clear modal content to stop games
            const modalContent = modal.querySelector('.game-modal-content');
            if (modalContent) {
                modalContent.innerHTML = '';
            }
        }
    }
};

// ========================================
// GLOBAL EXPORTS FOR BACKWARDS COMPATIBILITY
// ========================================

window.GameEmulator = GameEmulator;
window.playGame = GameEmulator.playGame.bind(GameEmulator);
window.loadGameInTV = GameEmulator.loadGameInTV.bind(GameEmulator);
window.closeGameModal = GameEmulator.closeGameModal.bind(GameEmulator);
window.optimizeGameDisplay = GameEmulator.optimizeGameDisplay.bind(GameEmulator);