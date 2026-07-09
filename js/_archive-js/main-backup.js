/**
 * Arcade Hub Theme JavaScript
 * Handles game emulation, video playback, and interactive features
 */

jQuery(document).ready(function($) {
    // Initialize theme
    initializeArcadeHub();
    
    // Add retro loading effects
    addRetroEffects();
    
    // Initialize game modal
    initializeGameModal();
});

/**
 * Initialize the Arcade Hub theme
 */
function initializeArcadeHub() {
    console.log('🕹️ Arcade Hub initialized!');
    
    // Add some retro startup sounds (optional)
    // playStartupSound();
    
    // Initialize any existing games
    updateGameStats();
}

/**
 * Add retro visual effects
 */
function addRetroEffects() {
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
    
}



/**
 * Play a game in the retro TV emulator using RetroArch
 */
function playGame(romUrl, emulatorType) {
    console.log('🎮 Loading game:', romUrl, 'Type:', emulatorType);
    
    const modal = document.getElementById('game-modal');
    const modalContent = modal.querySelector('.game-modal-content');
    
    if (!modal) {
        console.error('Game modal elements not found');
        return;
    }
    
    // Clear existing content
    modalContent.innerHTML = '';
    
    // Create retro TV container
    const tvContainer = document.createElement('div');
    tvContainer.className = 'retro-tv-container';
    tvContainer.innerHTML = `
        <div class="retro-tv">
            <div class="tv-screen">
                <div class="tv-static"></div>
                <div class="tv-loading">
                    <div class="loading-text">LOADING RETROARCH...</div>
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
    
    // Add close button
    const closeButton = document.createElement('button');
    closeButton.className = 'close-modal';
    closeButton.innerHTML = '×';
    closeButton.onclick = closeGameModal;
    
    modalContent.appendChild(tvContainer);
    modalContent.appendChild(closeButton);
    
    // Show modal
    modal.classList.add('active');
    
    // Initialize RetroArch emulator
    setTimeout(() => {
        initializeRetroArch(romUrl, emulatorType);
    }, 1000);
}

/**
 * Initialize RetroArch Web Player
 */
function initializeRetroArch(romUrl, emulatorType) {
    const canvas = document.getElementById('retroarch-canvas');
    const loadingDiv = document.querySelector('.tv-loading');
    const staticDiv = document.querySelector('.tv-static');
    
    if (!canvas) return;
    
    // Core mapping for different console types
    const coreMap = {
        'nes': 'fceumm',
        'snes': 'snes9x',
        'gb': 'gambatte',
        'gbc': 'gambatte', 
        'gba': 'mgba',
        'genesis': 'genesis_plus_gx',
        'arcade': 'mame2003_plus',
        'n64': 'mupen64plus_next',
        'psx': 'pcsx_rearmed'
    };
    
    const core = coreMap[emulatorType] || 'fceumm';
    
    console.log('🎮 Initializing RetroArch with:', { romUrl, emulatorType, core });
    
    // Determine which emulator to use based on ROM URL type
    if (romUrl && romUrl.trim() !== '') {
        // Check for unsupported file types first
        if (romUrl.match(/\.(zip|rar|7z)(\?|$)/i)) {
            console.log('📦 Compressed file detected - not supported for direct emulation');
            showErrorMessage(loadingDiv, 'Compressed files (.zip, .rar, .7z) cannot be emulated directly. Please extract the ROM file first and upload the .nes, .smc, .gb, etc. file instead.', romUrl);
            return;
        }
        // Check if it's a direct ROM file
        else if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
            console.log('📁 Direct ROM file detected, using fallback emulator');
            loadFallbackEmulator(canvas, romUrl, core, loadingDiv, staticDiv);
        }
        // Check if it's already an embedded game URL
        else if (romUrl.includes('embed') || romUrl.includes('emulator') || romUrl.includes('retrogames.cc')) {
            console.log('🌐 Embedded game URL detected');
            loadEmbeddedEmulator(canvas, romUrl, emulatorType, loadingDiv, staticDiv);
        }
        // Try RetroArch Web Player for other URLs
        else {
            console.log('🎯 Attempting RetroArch Web Player');
            loadRetroArchPlayer(canvas, romUrl, core, loadingDiv, staticDiv);
        }
    } else {
        // No ROM URL provided, use demo games
        console.log('🎮 No ROM URL, using demo games');
        loadEmbeddedEmulator(canvas, romUrl, emulatorType, loadingDiv, staticDiv);
    }
}

/**
 * Load actual RetroArch Web Player
 */
function loadRetroArchPlayer(canvas, romUrl, core, loadingDiv, staticDiv) {
    // Update loading text
    loadingDiv.querySelector('.loading-text').textContent = 'LOADING RETROARCH CORE...';
    
    console.log('🕹️ Loading RetroArch with ROM:', romUrl, 'Core:', core);
    
    // Check if RetroArch Web Player is available
    if (typeof window.RetroArch !== 'undefined') {
        // Real RetroArch Web Player integration
        const retroarchConfig = {
            canvas: canvas,
            core: core,
            rom: romUrl,
            onload: function() {
                console.log('🕹️ RetroArch loaded successfully');
                hideLoadingShowGame(canvas, loadingDiv, staticDiv);
                addGameInstructions();
            },
            onerror: function(error) {
                console.error('❌ RetroArch loading error:', error);
                showErrorMessage(loadingDiv, 'Failed to load RetroArch core', romUrl);
            }
        };
        
        window.RetroArch.init(retroarchConfig);
    } else {
        // Fallback: Try to load ROM directly in iframe or use EmulatorJS
        console.log('📺 RetroArch not available, using fallback emulator');
        loadFallbackEmulator(canvas, romUrl, core, loadingDiv, staticDiv);
    }
}

/**
 * Fallback emulator for when RetroArch isn't available
 */
function loadFallbackEmulator(canvas, romUrl, core, loadingDiv, staticDiv) {
    // Check if the ROM URL is a direct file
    if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)$/i)) {
        // Direct ROM file - try to load with EmulatorJS or similar
        loadDirectROM(canvas, romUrl, core, loadingDiv, staticDiv);
    } else {
        // Assume it's an embedded emulator URL
        loadEmbeddedEmulator(canvas, romUrl, core, loadingDiv, staticDiv);
    }
}

/**
 * Load direct ROM file using EmulatorJS-style approach
 */
function loadDirectROM(canvas, romUrl, core, loadingDiv, staticDiv) {
    console.log('🎮 Loading direct ROM file:', romUrl);
    
    // Check if this is a ZIP file or unsupported format
    if (romUrl.match(/\.(zip|rar|7z)$/i)) {
        showErrorMessage(loadingDiv, 'ZIP files are not supported for direct emulation. Please extract the ROM file first.', romUrl);
        return;
    }
    
    // Check if this is a local development environment (but allow Archive.org)
    const isLocal = window.location.hostname === 'localhost' || 
                   window.location.hostname.includes('.local') || 
                   window.location.hostname === '127.0.0.1';
    
    if (isLocal && !romUrl.includes('archive.org')) {
        // For local development, show a message about HTTPS/local limitations
        showLocalDevelopmentMessage(loadingDiv, romUrl);
        return;
    }
    
    // Create iframe for EmulatorJS or similar web-based emulator
    const iframe = document.createElement('iframe');
    iframe.className = 'retro-emulator';
    iframe.style.width = '100%';
    iframe.style.height = '100%';
    iframe.style.border = 'none';
    iframe.frameBorder = '0';
    iframe.allow = 'autoplay; fullscreen';
    
    // Use a more reliable emulator service for direct ROM files
    const system = getEmulatorJSSystem(core);
    
    // Try multiple emulator services in order of preference
    const emulatorServices = [
        `https://www.retrogames.cc/upload-rom/?system=${system}&url=${encodeURIComponent(romUrl)}`,
        `https://demo.emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`,
        `https://emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`
    ];
    
    let currentServiceIndex = 0;
    
    function tryNextEmulator() {
        if (currentServiceIndex >= emulatorServices.length) {
            showErrorMessage(loadingDiv, 'Unable to load ROM file with any emulator service. Try using an embedded game URL instead.', romUrl);
            return;
        }
        
        const emulatorUrl = emulatorServices[currentServiceIndex];
        console.log(`🎯 Trying emulator service ${currentServiceIndex + 1}:`, emulatorUrl);
        
        iframe.src = emulatorUrl;
        currentServiceIndex++;
    }
    
    iframe.onload = function() {
        console.log('🕹️ Direct ROM emulator loaded successfully');
        hideLoadingShowGame(iframe, loadingDiv, staticDiv);
        addGameInstructions();
    };
    
    iframe.onerror = function() {
        console.error('❌ Emulator service failed, trying next...');
        tryNextEmulator();
    };
    
    // Replace canvas with iframe
    canvas.style.display = 'none';
    canvas.parentNode.appendChild(iframe);
    
    // Start with the first emulator service
    tryNextEmulator();
}

/**
 * Show local development message
 */
function showLocalDevelopmentMessage(loadingDiv, romUrl) {
    loadingDiv.innerHTML = `
        <div class="loading-text" style="color: #ffaa00;">🏠 LOCAL DEVELOPMENT</div>
        <div style="color: #ffcc66; font-size: 0.9rem; margin-top: 10px; text-align: left;">
            Direct ROM emulation doesn't work in local development due to CORS restrictions.
        </div>
        <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left;">
            <strong>For local testing, try:</strong><br>
            • Use Archive.org download URLs (they have CORS headers)<br>
            • Use embedded game URLs instead<br>
            • Deploy to your live Bluehost site<br>
            • Use demo games (leave ROM URL empty)
        </div>
        <div style="color: #666; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
            ROM URL: ${romUrl}<br>
            This will work on your live website!
        </div>
    `;
}

/**
 * Map RetroArch cores to EmulatorJS systems
 */
function getEmulatorJSSystem(core) {
    const coreMap = {
        'fceumm': 'nes',
        'snes9x': 'snes',
        'gambatte': 'gb',
        'mgba': 'gba',
        'genesis_plus_gx': 'segaMD',
        'mame2003_plus': 'arcade',
        'mupen64plus_next': 'n64',
        'pcsx_rearmed': 'psx'
    };
    
    return coreMap[core] || 'nes';
}

/**
 * Show RetroArch placeholder (until actual integration)
 */
function showRetroArchPlaceholder(canvas, loadingDiv, staticDiv, core) {
    const ctx = canvas.getContext('2d');
    canvas.width = 640;
    canvas.height = 480;
    
    // Hide loading, show canvas
    hideLoadingShowGame(canvas, loadingDiv, staticDiv);
    
    // Draw RetroArch-style placeholder
    ctx.fillStyle = '#000';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Draw RetroArch logo style
    ctx.fillStyle = '#ff6600';
    ctx.font = 'bold 24px Courier New';
    ctx.textAlign = 'center';
    ctx.fillText('RETROARCH WEB PLAYER', canvas.width / 2, canvas.height / 2 - 60);
    
    ctx.fillStyle = '#00ff00';
    ctx.font = '16px Courier New';
    ctx.fillText(`Core: ${core.toUpperCase()}`, canvas.width / 2, canvas.height / 2 - 20);
    
    ctx.fillStyle = '#00ffff';
    ctx.font = '14px Courier New';
    ctx.fillText('Game would load here with actual ROM file', canvas.width / 2, canvas.height / 2 + 20);
    
    ctx.fillStyle = '#ffff00';
    ctx.font = '12px Courier New';
    ctx.fillText('Controls: Arrow Keys, Z/X for buttons, Enter for Start', canvas.width / 2, canvas.height / 2 + 60);
    
    // Add animated scanline effect
    animateRetroDisplay(ctx, canvas);
}

/**
 * Animate retro display effects
 */
function animateRetroDisplay(ctx, canvas) {
    let scanlineY = 0;
    
    function drawScanline() {
        // Clear previous scanline
        ctx.fillStyle = '#000';
        ctx.fillRect(0, scanlineY - 2, canvas.width, 4);
        
        // Draw new scanline
        ctx.fillStyle = 'rgba(0, 255, 0, 0.3)';
        ctx.fillRect(0, scanlineY, canvas.width, 2);
        
        scanlineY += 4;
        if (scanlineY > canvas.height) {
            scanlineY = 0;
        }
        
        requestAnimationFrame(drawScanline);
    }
    
    drawScanline();
}

/**
 * Load embedded emulator for demo games
 */
function loadEmbeddedEmulator(canvas, romUrl, emulatorType, loadingDiv, staticDiv) {
    console.log('🎮 Loading embedded emulator for:', emulatorType, 'URL:', romUrl);
    
    const iframe = document.createElement('iframe');
    iframe.className = 'retro-emulator';
    iframe.style.width = '100%';
    iframe.style.height = '100%';
    iframe.style.border = 'none';
    iframe.frameBorder = '0';
    iframe.allow = 'autoplay; fullscreen';
    
    let gameUrl = romUrl;
    
    // If no ROM URL provided, use demo games
    if (!romUrl || romUrl === '') {
        const demoGames = {
            'nes': 'https://archive.org/embed/msdos_Super_Mario_Bros_1985',
            'snes': 'https://archive.org/embed/SuperMarioWorld_201805',
            'gb': 'https://archive.org/embed/TetrisGameBoy',
            'gbc': 'https://archive.org/embed/PokemonRedVersion',
            'gba': 'https://archive.org/embed/PokemonEmeraldVersion',
            'genesis': 'https://archive.org/embed/SegaGenesisSonicTheHedgehog',
            'arcade': 'https://archive.org/embed/MAME_pacman',
            'n64': 'https://archive.org/embed/SuperMario64',
            'psx': 'https://archive.org/embed/CrashBandicoot_201904'
        };
        gameUrl = demoGames[emulatorType] || demoGames['nes'];
    }
    // If ROM URL is a direct file, try to create an EmulatorJS URL
    else if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)$/i)) {
        // Try to use EmulatorJS for direct ROM files
        const systemMap = {
            'nes': 'nes',
            'snes': 'snes', 
            'gb': 'gb',
            'gbc': 'gbc',
            'gba': 'gba',
            'genesis': 'segaMD',
            'arcade': 'arcade',
            'n64': 'n64',
            'psx': 'psx'
        };
        
        const system = systemMap[emulatorType] || 'nes';
        
        // For Archive.org URLs, use EmulatorJS which can handle direct ROM files
        if (romUrl.includes('archive.org')) {
            gameUrl = `https://demo.emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`;
            console.log('🏛️ Archive.org ROM detected, using EmulatorJS:', gameUrl);
        } else {
            gameUrl = `https://demo.emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`;
            console.log('🎯 Generated EmulatorJS URL:', gameUrl);
        }
    }
    
    iframe.onload = function() {
        console.log('✅ Emulator iframe loaded successfully');
        hideLoadingShowGame(iframe, loadingDiv, staticDiv);
        addGameInstructions();
    };
    
    iframe.onerror = function() {
        console.error('❌ Failed to load emulator iframe');
        showErrorMessage(loadingDiv, 'Failed to load game emulator. Please check the ROM URL.', romUrl);
    };
    
    // Insert iframe into TV screen (replace canvas)
    canvas.style.display = 'none';
    const tvScreen = canvas.parentElement;
    tvScreen.appendChild(iframe);
    
    // Load the game with a slight delay for TV effect
    setTimeout(() => {
        console.log('🚀 Loading game URL:', gameUrl);
        iframe.src = gameUrl;
    }, 1500);
}

/**
 * Hide loading screen and show game
 */
function hideLoadingShowGame(gameElement, loadingDiv, staticDiv) {
    loadingDiv.style.display = 'none';
    staticDiv.style.display = 'none';
    gameElement.style.display = 'block';
    
    // Add TV turn-on effect
    gameElement.style.opacity = '0';
    gameElement.style.transform = 'scaleY(0.1)';
    gameElement.style.transition = 'all 0.5s ease-out';
    
    setTimeout(() => {
        gameElement.style.opacity = '1';
        gameElement.style.transform = 'scaleY(1)';
    }, 100);
}

/**
 * Show error message in TV
 */
function showErrorMessage(loadingDiv, message, romUrl = '') {
    console.error('🚨 Game loading error:', message, 'ROM URL:', romUrl);
    
    let troubleshooting = '';
    
    // Check for local development issues
    const isLocal = window.location.hostname === 'localhost' || 
                   window.location.hostname.includes('.local') || 
                   window.location.hostname === '127.0.0.1';
    
    if (isLocal && romUrl) {
        troubleshooting = `
            <div style="color: #ff9900; font-size: 0.8rem; margin-top: 10px; background: #332200; padding: 10px; border-radius: 4px;">
                <strong>🏠 LOCAL DEVELOPMENT DETECTED</strong><br>
                ROM emulation has limitations in local development:
                <br>• CORS restrictions prevent loading external ROM files
                <br>• Mixed HTTP/HTTPS content issues
                <br>• Limited emulator service compatibility
                <br><br>
                <strong>Solutions:</strong><br>
                • Deploy to your live Bluehost site for full functionality
                <br>• Use embedded game URLs like: archive.org/embed/...
                <br>• Leave ROM URL empty to test with demo games
            </div>
        `;
    }
    // Provide specific troubleshooting based on ROM URL type
    else if (romUrl) {
        if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)$/i)) {
            troubleshooting = `
                <div style="color: #ffaa00; font-size: 0.8rem; margin-top: 10px;">
                    💡 Direct ROM file detected. Troubleshooting:
                    <br>• Check if file is publicly accessible (try opening URL in new tab)
                    <br>• Ensure CORS headers allow cross-origin access
                    <br>• Try using an embedded game URL instead
                    <br>• Make sure it's not a ZIP file (extract first)
                </div>
            `;
        } else if (romUrl.includes('http')) {
            troubleshooting = `
                <div style="color: #ffaa00; font-size: 0.8rem; margin-top: 10px;">
                    💡 External URL detected. Troubleshooting:
                    <br>• Check if URL is accessible in a new tab
                    <br>• Verify the emulator service is working
                    <br>• Try a different ROM URL or emulator service
                    <br>• Ensure HTTPS if your site uses HTTPS
                </div>
            `;
        }
    } else {
        troubleshooting = `
            <div style="color: #00ffaa; font-size: 0.8rem; margin-top: 10px;">
                💡 No ROM URL provided - using demo games
                <br>• Add a ROM URL in the game editor
                <br>• Or use embedded game URLs from archive.org
                <br>• Extract ZIP files before uploading ROM files
            </div>
        `;
    }
    
    loadingDiv.innerHTML = `
        <div class="loading-text" style="color: #ff0000;">⚠️ LOADING ERROR</div>
        <div style="color: #ff6666; font-size: 0.9rem; margin-top: 10px;">
            ${message}
        </div>
        ${troubleshooting}
        <div style="color: #ccc; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
            <strong>Quick Fixes:</strong><br>
            • Try refreshing the page<br>
            • Use embedded game URLs (recommended)<br>
            • Check browser console for detailed errors<br>
            • Deploy to live site for full ROM support
        </div>
    `;
}

/**
 * Add game control instructions to TV
 */
function addGameInstructions() {
    const modal = document.getElementById('game-modal');
    const existingInstructions = modal.querySelector('.game-instructions');
    
    if (existingInstructions) {
        existingInstructions.remove();
    }
    
    const instructions = document.createElement('div');
    instructions.className = 'game-instructions';
    instructions.style.cssText = `
        position: absolute;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.9);
        color: #00ff00;
        padding: 15px 25px;
        border: 2px solid #00ff00;
        border-radius: 8px;
        font-size: 0.9rem;
        text-align: center;
        max-width: 600px;
        white-space: nowrap;
        z-index: 10002;
    `;
    
    instructions.innerHTML = `
        <strong>🎮 RETRO CONTROLS:</strong>
        Arrow Keys: D-Pad • Z: A Button • X: B Button • Enter: Start • Shift: Select
    `;
    
    const tvContainer = modal.querySelector('.retro-tv-container');
    tvContainer.appendChild(instructions);
    
    // Auto-hide instructions after 10 seconds
    setTimeout(() => {
        instructions.style.opacity = '0';
        instructions.style.transition = 'opacity 1s ease';
        setTimeout(() => instructions.remove(), 1000);
    }, 10000);
}

/**
 * Close the game modal and cleanup
 */
function closeGameModal() {
    const modal = document.getElementById('game-modal');
    const modalContent = modal.querySelector('.game-modal-content');
    
    if (!modal || !modalContent) return;
    
    // Add TV turn-off effect
    const tvScreen = modal.querySelector('.tv-screen');
    if (tvScreen) {
        const gameElement = tvScreen.querySelector('.retro-emulator, #retroarch-canvas');
        if (gameElement) {
            gameElement.style.transition = 'all 0.3s ease-out';
            gameElement.style.opacity = '0';
            gameElement.style.transform = 'scaleY(0.1)';
        }
    }
    
    // Close modal after animation
    setTimeout(() => {
        // Clear all content
        modalContent.innerHTML = '';
        modal.classList.remove('active');
        
        // Reset modal content for next use
        modalContent.innerHTML = `
            <iframe id="game-frame" class="game-frame" src="" frameborder="0"></iframe>
        `;
    }, 300);
}

/**
 * Initialize game modal functionality
 */
function initializeGameModal() {
    // Close modal when clicking the X button
    jQuery(document).on('click', '.close-modal', function() {
        closeGameModal();
    });
    
    // Close modal when clicking outside
    jQuery('#game-modal').on('click', function(e) {
        if (e.target === this) {
            closeGameModal();
        }
    });
    
    // Close modal with ESC key
    jQuery(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closeGameModal();
        }
    });
}

/**
 * Play YouTube video in modal
 */
function playVideo(youtubeId) {
    console.log('📺 Playing video:', youtubeId);
    
    const modal = document.getElementById('youtube-modal');
    const iframe = document.getElementById('youtube-frame');
    
    if (modal && iframe) {
        iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0`;
        modal.classList.add('active');
    }
}

/**
 * Close YouTube modal
 */
function closeYouTubeModal() {
    const modal = document.getElementById('youtube-modal');
    const iframe = document.getElementById('youtube-frame');
    
    if (iframe) iframe.src = '';
    if (modal) modal.classList.remove('active');
}

/**
 * Update game statistics
 */
function updateGameStats() {
    // Count visible games and update any counters
    const gameCards = jQuery('.game-card');
    if (gameCards.length > 0) {
        console.log(`📊 Found ${gameCards.length} games in the arcade!`);
    }
}

/**
 * Filter games by criteria
 */
function filterGames(console, genre, year) {
    console.log('🔍 Filtering games:', { console, genre, year });
    
    jQuery.ajax({
        url: arcade_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'filter_games',
            console: console,
            genre: genre,
            year: year,
            nonce: arcade_ajax.nonce
        },
        beforeSend: function() {
            jQuery('#games-container').html(`
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #00ff00;">
                    <div class="loading" style="margin: 20px auto;"></div>
                    <p>Searching the arcade database...</p>
                </div>
            `);
        },
        success: function(response) {
            jQuery('#games-container').html(response);
            updateGameStats();
            
            // Add success animation
            jQuery('#games-container').hide().fadeIn(500);
        },
        error: function() {
            jQuery('#games-container').html(`
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #ff0000;">
                    <p>❌ Error loading games. Please try again.</p>
                    <button onclick="location.reload()" class="retro-button" style="margin-top: 15px;">
                        🔄 Retry
                    </button>
                </div>
            `);
        }
    });
}

/**
 * Add retro sound effects (optional)
 */
function playStartupSound() {
    // You can add actual sound files here
    // const audio = new Audio(arcade_theme.theme_url + '/sounds/startup.mp3');
    // audio.volume = 0.3;
    // audio.play().catch(e => console.log('Sound autoplay blocked'));
}

/**
 * Add button click sound effect
 */
function playClickSound() {
    // Optional click sound
    // const audio = new Audio(arcade_theme.theme_url + '/sounds/click.mp3');
    // audio.volume = 0.2;
    // audio.play().catch(e => console.log('Sound autoplay blocked'));
}

/**
 * Konami Code easter egg
 */
let konamiCode = [];
const konamiSequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // ↑↑↓↓←→←→BA

jQuery(document).on('keydown', function(e) {
    konamiCode.push(e.keyCode);
    
    if (konamiCode.length > konamiSequence.length) {
        konamiCode.shift();
    }
    
    if (konamiCode.length === konamiSequence.length && 
        konamiCode.every((code, index) => code === konamiSequence[index])) {
        
        activateKonamiCode();
        konamiCode = [];
    }
});

/**
 * Activate Konami Code easter egg
 */
function activateKonamiCode() {
    console.log('🎉 Konami Code activated!');
    
    // Add special effects
    jQuery('body').addClass('konami-active');
    
    // Show easter egg message
    const message = jQuery(`
        <div id="konami-message" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(45deg, #ff0080, #8000ff);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            z-index: 10001;
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            border: 3px solid #fff;
            animation: pulse 1s infinite;
        ">
            <h3>🎉 KONAMI CODE ACTIVATED! 🎉</h3>
            <p>You've unlocked the secret retro mode!</p>
            <p style="font-size: 0.9rem; margin-top: 15px;">
                ↑↑↓↓←→←→BA - Classic!
            </p>
            <button onclick="jQuery('#konami-message').fadeOut()" 
                    style="background: white; color: #8000ff; border: none; padding: 10px 20px; border-radius: 5px; margin-top: 15px; cursor: pointer; font-weight: bold;">
                AWESOME!
            </button>
        </div>
    `);
    
    jQuery('body').append(message);
    
    // Remove after 10 seconds
    setTimeout(() => {
        message.fadeOut();
        jQuery('body').removeClass('konami-active');
    }, 10000);
}

/**
 * Initialize responsive navigation
 */
function initMobileNav() {
    const navToggle = jQuery(`
        <button class="nav-toggle" style="
            display: none;
            background: #00ff00;
            color: #000;
            border: none;
            padding: 10px;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 4px;
        ">☰</button>
    `);
    
    jQuery('.header-content').append(navToggle);
    
    navToggle.on('click', function() {
        jQuery('.nav-menu').toggleClass('mobile-active');
    });
    
    // Show/hide mobile nav button based on screen size
    function checkMobileNav() {
        if (jQuery(window).width() <= 768) {
            navToggle.show();
        } else {
            navToggle.hide();
            jQuery('.nav-menu').removeClass('mobile-active');
        }
    }
    
    jQuery(window).on('resize', checkMobileNav);
    checkMobileNav();
}

// Initialize mobile navigation
jQuery(document).ready(function() {
    initMobileNav();
});

// Global functions for inline onclick handlers
window.playGame = playGame;
window.playVideo = playVideo;
window.closeGameModal = closeGameModal;
window.closeYouTubeModal = closeYouTubeModal;
window.loadGameInTV = loadGameInTV;
window.toggleGameAudio = toggleGameAudio;
window.resetGame = resetGame;
window.shareGame = shareGame;

/**
 * Load game directly in the TV on the single game page
 */
function loadGameInTV(romUrl, emulatorType) {
    const tvEmulator = document.getElementById('tv-emulator');
    const loadingDiv = document.querySelector('.tv-loading');
    const staticDiv = document.querySelector('.tv-static');
    
    if (!tvEmulator || !loadingDiv) return;
    
    console.log('📺 Loading game in TV:', romUrl, emulatorType);
    
    // Check for local development issues
    const isLocal = window.location.hostname === 'localhost' || 
                   window.location.hostname.includes('.local') || 
                   window.location.hostname === '127.0.0.1';
    
    // Update loading text
    loadingDiv.innerHTML = `
        <div class="loading-text">LOADING GAME...</div>
        <div class="loading"></div>
        <div style="margin-top: 15px; font-size: 0.9rem; color: #ccc;">
            Booting ${emulatorType.toUpperCase()} emulator...
        </div>
    `;
    
    // Check for problematic file types
    if (romUrl && romUrl.match(/\.(zip|rar|7z)(\?|$)/i)) {
        setTimeout(() => {
            showTVMessage('ZIP files not supported. Extract ROM first.', 4000, '#ff0000');
        }, 1000);
        return;
    }
    
    // Check for local development with direct ROM files (but allow Archive.org)
    if (isLocal && romUrl && romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i) && !romUrl.includes('archive.org')) {
        setTimeout(() => {
            loadingDiv.innerHTML = `
                <div class="loading-text" style="color: #ffaa00;">🏠 LOCAL DEVELOPMENT</div>
                <div style="color: #ffcc66; font-size: 0.8rem; margin-top: 10px; text-align: left; line-height: 1.4;">
                    Direct ROM files don't work in local development due to CORS restrictions.
                </div>
                <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left; line-height: 1.4;">
                    <strong>For local testing:</strong><br>
                    • Use Archive.org URLs (they have CORS headers)<br>
                    • Use embedded game URLs instead<br>
                    • Or deploy to your live Bluehost site<br>
                    • Or leave ROM URL empty for demo games
                </div>
                <div style="color: #666; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
                    This ROM will work on your live website!<br>
                    File: ${romUrl.split('/').pop()}
                </div>
            `;
        }, 1500);
        return;
    }
    
    // Demo game URLs for different systems (updated for better CORS compatibility)
    const demoGames = {
        'nes': 'https://archive.org/embed/msdos_Super_Mario_Bros_1985',
        'snes': 'https://archive.org/embed/SuperMarioWorld_201805',
        'gb': 'https://archive.org/embed/TetrisGameBoy',
        'gbc': 'https://archive.org/embed/PokemonRedVersion',
        'gba': 'https://archive.org/embed/PokemonEmeraldVersion',
        'genesis': 'https://archive.org/embed/SegaGenesisSonicTheHedgehog',
        'arcade': 'https://archive.org/embed/MAME_pacman',
        'n64': 'https://archive.org/embed/SuperMario64',
        'psx': 'https://archive.org/embed/CrashBandicoot_201904'
    };
    
    let gameUrl = romUrl;
    
    // If no ROM URL or it's a non-Archive.org direct ROM file on local, use demo games
    if (!romUrl || romUrl === '') {
        gameUrl = demoGames[emulatorType] || demoGames['nes'];
        console.log('🎮 Using demo game for local development:', gameUrl);
    }
    // If it's a direct ROM file (including Archive.org), wrap it with an emulator
    else if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
        const systemMap = {
            'nes': 'nes',
            'snes': 'snes', 
            'gb': 'gb',
            'gbc': 'gbc',
            'gba': 'gba',
            'genesis': 'segaMD',
            'arcade': 'arcade',
            'n64': 'n64',
            'psx': 'psx'
        };
        
        const system = systemMap[emulatorType] || 'nes';
        gameUrl = `https://demo.emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`;
        console.log('� Wrapping direct ROM with EmulatorJS:', gameUrl);
    }
    
    // Load game with TV turn-on effect
    setTimeout(() => {
        console.log('🚀 Loading game URL in TV:', gameUrl);
        tvEmulator.dataset.tryCount = '0'; // Initialize try counter
        tvEmulator.src = gameUrl;
        
        tvEmulator.onload = function() {
            console.log('✅ TV emulator loaded successfully');
            // Hide loading and static
            loadingDiv.style.display = 'none';
            staticDiv.style.display = 'none';
            
            // Show emulator with TV turn-on effect
            tvEmulator.style.display = 'block';
            tvEmulator.style.opacity = '0';
            tvEmulator.style.transform = 'scaleY(0.1)';
            tvEmulator.style.transition = 'all 0.5s ease-out';
            
            setTimeout(() => {
                tvEmulator.style.opacity = '1';
                tvEmulator.style.transform = 'scaleY(1)';
            }, 100);
            
            // Show success message briefly
            showTVMessage('GAME LOADED - ENJOY!', 2000);
        };
        
        tvEmulator.onerror = function() {
            console.error('❌ TV emulator failed to load');
            
            // Try fallback emulator services for direct ROM files
            if (romUrl && romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
                const systemMap = {
                    'nes': 'nes',
                    'snes': 'snes', 
                    'gb': 'gb',
                    'gbc': 'gbc',
                    'gba': 'gba',
                    'genesis': 'segaMD',
                    'arcade': 'arcade',
                    'n64': 'n64',
                    'psx': 'psx'
                };
                
                const system = systemMap[emulatorType] || 'nes';
                const fallbackServices = [
                    `https://emulatorjs.org/beta/?system=${system}&url=${encodeURIComponent(romUrl)}`,
                    `https://www.retrogames.cc/upload-rom/?system=${system}&url=${encodeURIComponent(romUrl)}`,
                    // Fallback to demo games if all services fail
                    demoGames[emulatorType] || demoGames['nes']
                ];
                
                let currentTry = parseInt(tvEmulator.dataset.tryCount || '0');
                
                if (currentTry < fallbackServices.length - 1) {
                    currentTry++;
                    tvEmulator.dataset.tryCount = currentTry.toString();
                    console.log(`🔄 Trying fallback service ${currentTry}:`, fallbackServices[currentTry]);
                    
                    showTVMessage(`Trying backup emulator... (${currentTry}/${fallbackServices.length})`, 2000, '#ffaa00');
                    
                    setTimeout(() => {
                        tvEmulator.src = fallbackServices[currentTry];
                    }, 1000);
                    return;
                }
            }
            
            // Check if it's a ROM URL issue
            if (romUrl && romUrl.includes('archive.org')) {
                showTVMessage('Archive.org ROM URL not found (404). Check if the file exists.', 6000, '#ff6600');
                setTimeout(() => {
                    loadingDiv.innerHTML = `
                        <div class="loading-text" style="color: #ff6600;">📁 ROM FILE NOT FOUND</div>
                        <div style="color: #ffcc66; font-size: 0.8rem; margin-top: 10px; text-align: left; line-height: 1.4;">
                            The Archive.org ROM URL returned a 404 error.
                        </div>
                        <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left; line-height: 1.4;">
                            <strong>Try these valid Archive.org ROM examples:</strong><br>
                            • https://archive.org/download/NINTENDO-NES-ROMS/Super%20Mario%20Bros.%20%281985%29%20%28World%29.nes<br>
                            • https://archive.org/download/GameBoyROMSet/Tetris%20%28World%29%20%28Rev%201%29.gb<br>
                            • Browse: archive.org/details/NINTENDO-NES-ROMS
                        </div>
                        <div style="color: #666; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
                            Invalid URL: ${romUrl}<br>
                            Or leave ROM URL empty for demo games
                        </div>
                    `;
                }, 2000);
            } else {
                showTVMessage('All emulator services failed. Using demo game.', 3000, '#ff8800');
                setTimeout(() => {
                    tvEmulator.src = demoGames[emulatorType] || demoGames['nes'];
                }, 2000);
            }
        };
    }, 1500);
}

/**
 * Show temporary message on TV screen
 */
function showTVMessage(message, duration = 2000, color = '#00ff00') {
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

/**
 * Toggle game audio (simulate)
 */
function toggleGameAudio() {
    const tvEmulator = document.getElementById('tv-emulator');
    if (tvEmulator && tvEmulator.style.display !== 'none') {
        // In a real implementation, this would control the emulator's audio
        showTVMessage('AUDIO TOGGLED', 1000, '#ffff00');
        
        // Add visual feedback to the knob
        const volumeKnob = event.target;
        volumeKnob.style.transform = 'rotate(45deg)';
        setTimeout(() => {
            volumeKnob.style.transform = 'rotate(0deg)';
        }, 200);
    }
}

/**
 * Reset the current game
 */
function resetGame() {
    const tvEmulator = document.getElementById('tv-emulator');
    if (tvEmulator && tvEmulator.src) {
        showTVMessage('RESETTING GAME...', 1500, '#ff8000');
        
        setTimeout(() => {
            // Reload the game
            const currentSrc = tvEmulator.src;
            tvEmulator.src = '';
            setTimeout(() => {
                tvEmulator.src = currentSrc;
            }, 500);
        }, 500);
        
        // Add visual feedback to the knob
        const resetKnob = event.target;
        resetKnob.style.transform = 'rotate(-45deg)';
        setTimeout(() => {
            resetKnob.style.transform = 'rotate(0deg)';
        }, 200);
    }
}

/**
 * Share the current game
 */
function shareGame() {
    const gameTitle = document.querySelector('h1') ? document.querySelector('h1').textContent : 'this retro game';
    const gameUrl = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: `Play ${gameTitle} - Arcade Hub`,
            text: `Check out this awesome retro game: ${gameTitle}`,
            url: gameUrl
        });
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(gameUrl).then(() => {
            showTVMessage('GAME LINK COPIED!', 2000, '#00ffff');
        });
    } else {
        // Fallback: show social sharing options
        const shareModal = document.createElement('div');
        shareModal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        `;
        
        shareModal.innerHTML = `
            <div style="background: #1a1a1a; border: 3px solid #00ffff; border-radius: 10px; padding: 30px; text-align: center; max-width: 400px;">
                <h3 style="color: #00ffff; margin-bottom: 20px;">Share ${gameTitle}</h3>
                <div style="display: flex; gap: 15px; justify-content: center; margin-bottom: 20px;">
                    <a href="https://twitter.com/intent/tweet?text=Playing ${encodeURIComponent(gameTitle)} on Arcade Hub!&url=${encodeURIComponent(gameUrl)}" target="_blank" class="retro-button">🐦 Twitter</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(gameUrl)}" target="_blank" class="retro-button">📘 Facebook</a>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="retro-button">Close</button>
            </div>
        `;
        
        document.body.appendChild(shareModal);
        
        // Close on background click
        shareModal.addEventListener('click', function(e) {
            if (e.target === shareModal) {
                shareModal.remove();
            }
        });
    }
}
