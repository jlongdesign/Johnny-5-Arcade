/**
 * Arcade Hub Theme - Main Coordinator
 * Initializes all modules and coordinates theme functionality
 * 
 * Dependencies: All module files should be loaded before this file
 */

jQuery(document).ready(function($) {
    console.log('🎮 Arcade Hub Theme Loading...');
    
    // Initialize all theme modules in proper order
    initializeArcadeHub();
});

// ========================================
// MAIN INITIALIZATION
// ========================================

/**
 * Main theme initialization coordinator
 */
function initializeArcadeHub() {
    console.log('🕹️ Initializing Johnny 5 Arcade Theme...');
    
    // 1. Initialize utility modules first
    initializeUtilities();
    
    // 2. Initialize core theme modules
    initializeCoreModules();
    
    // 3. Initialize feature modules
    initializeFeatureModules();
    
    // 4. Initialize page-specific functionality
    initializePageSpecific();
    
    // 5. Final setup and event bindings
    finalizeInitialization();
    
    console.log('✅ Johnny 5 Arcade Theme initialized successfully!');
}

// ========================================
// MODULE INITIALIZATION
// ========================================

/**
 * Initialize utility modules
 */
function initializeUtilities() {
    console.log('🔧 Initializing utilities...');
    
    // Initialize AJAX handlers
    if (typeof AjaxHandlers !== 'undefined') {
        AjaxHandlers.init();
    } else {
        console.warn('⚠️ AjaxHandlers module not found');
    }
    
    // Initialize console utilities (no init needed - just mappings)
    if (typeof ConsoleUtils === 'undefined') {
        console.warn('⚠️ ConsoleUtils module not found');
    }
    
    // Initialize UI helpers (no init needed - just helper functions)
    if (typeof UIHelpers === 'undefined') {
        console.warn('⚠️ UIHelpers module not found');
    }
}

/**
 * Initialize core theme modules
 */
function initializeCoreModules() {
    console.log('🎨 Initializing core modules...');
    
    // Initialize theme effects (visual effects, animations, typing)
    if (typeof ThemeEffects !== 'undefined') {
        ThemeEffects.init();
    } else {
        console.warn('⚠️ ThemeEffects module not found - falling back to legacy effects');
        initializeLegacyEffects();
    }
    
    // Initialize game emulator
    if (typeof GameEmulator !== 'undefined') {
        GameEmulator.init();
    } else {
        console.warn('⚠️ GameEmulator module not found');
    }
}

/**
 * Initialize feature modules
 */
function initializeFeatureModules() {
    console.log('🎯 Initializing feature modules...');
    
    // Initialize game modal
    if (typeof GameModal !== 'undefined') {
        GameModal.init();
    } else {
        console.warn('⚠️ GameModal module not found - falling back to legacy modal');
        initializeLegacyGameModal();
    }
    
    // Initialize TV controls
    if (typeof TVControls !== 'undefined') {
        TVControls.init();
    } else {
        console.warn('⚠️ TVControls module not found');
    }
    
    // Initialize video player
    if (typeof VideoPlayer !== 'undefined') {
        VideoPlayer.init();
    } else {
        console.warn('⚠️ VideoPlayer module not found');
    }
    
    // Initialize game filters
    if (typeof GameFilters !== 'undefined') {
        GameFilters.init();
    } else {
        console.log('ℹ️ GameFilters module not found (optional)');
    }
    
    // Initialize navigation
    if (typeof Navigation !== 'undefined') {
        Navigation.init();
    } else {
        console.log('ℹ️ Navigation module not found (optional)');
    }
    
    // Initialize easter eggs
    if (typeof EasterEggs !== 'undefined') {
        EasterEggs.init();
    } else {
        console.log('ℹ️ EasterEggs module not found (optional)');
    }
}

/**
 * Initialize page-specific functionality
 */
function initializePageSpecific() {
    console.log('📄 Initializing page-specific features...');
    
    // Check what type of page we're on
    const body = document.body;
    
    // Single game page
    if (body.classList.contains('single-games')) {
        initializeSingleGamePage();
    }
    
    // Game archive page
    if (body.classList.contains('post-type-archive-games') || body.classList.contains('tax-console') || body.classList.contains('tax-game_genre')) {
        initializeGameArchivePage();
    }
    
    // Mod archive page
    if (body.classList.contains('post-type-archive-game_mods')) {
        initializeModArchivePage();
    }
    
    // Home page
    if (body.classList.contains('home')) {
        initializeHomePage();
    }
    
    // Admin pages
    if (body.classList.contains('wp-admin')) {
        initializeAdminPage();
    }
}

/**
 * Final initialization tasks
 */
function finalizeInitialization() {
    console.log('🏁 Finalizing initialization...');
    
    // Update game statistics
    updateGameStats();
    
    // Add global event listeners
    addGlobalEventListeners();
    
    // Initialize any legacy compatibility
    initializeLegacyCompatibility();
    
    // Optional: Play startup sound
    if (shouldPlayStartupSound()) {
        playStartupSound();
    }
    
    // Set theme as fully loaded
    document.body.classList.add('arcade-hub-loaded');
    
    // Trigger custom event for other scripts
    jQuery(document).trigger('arcadeHubLoaded');
}

// ========================================
// PAGE-SPECIFIC INITIALIZATION
// ========================================

/**
 * Initialize single game page features
 */
function initializeSingleGamePage() {
    console.log('🎮 Initializing single game page...');
    
    // Add game-specific functionality
    const gameId = getGameIdFromPage();
    if (gameId) {
        // Track page view
        if (window.AjaxHandlers && typeof AjaxHandlers.trackGameView === 'function') {
            AjaxHandlers.trackGameView(gameId);
        }
        
        // Initialize game rating if present
        initializeGameRating(gameId);
        
        // Initialize social sharing
        initializeGameSharing(gameId);
    }
    
    // Auto-focus on play button
    const playButton = document.querySelector('.play-button, [onclick*="loadGameInTV"]');
    if (playButton) {
        setTimeout(() => playButton.focus(), 1000);
    }
}

/**
 * Initialize game archive page features
 */
function initializeGameArchivePage() {
    console.log('🎯 Initializing game archive page...');
    
    // Initialize infinite scroll if enabled
    if (document.querySelector('.load-more-games')) {
        initializeInfiniteScroll();
    }
    
    // Initialize filter persistence
    initializeFilterState();
    
    // Add keyboard navigation
    addArchiveKeyboardNavigation();
}

/**
 * Initialize mod archive page features
 */
function initializeModArchivePage() {
    console.log('🔧 Initializing mod archive page...');
    
    // Initialize mod-specific filters
    initializeModFilters();
    
    // Initialize load order display
    initializeLoadOrderDisplay();
}

/**
 * Initialize home page features
 */
function initializeHomePage() {
    console.log('🏠 Initializing home page...');
    
    // Initialize featured games carousel
    initializeFeaturedGames();
    
    // Initialize recent games
    initializeRecentGames();
    
    // Add entrance animations
    addHomePageAnimations();
}

/**
 * Initialize admin page features
 */
function initializeAdminPage() {
    console.log('⚙️ Initializing admin features...');
    
    // Initialize ROM testing if on game edit page
    if (document.getElementById('test_rom_accessibility')) {
        initializeROMTesting();
    }
    
    // Initialize mod management if on mod edit page
    if (document.querySelector('.mod-load-order')) {
        initializeModManagement();
    }
}

// ========================================
// LEGACY COMPATIBILITY & FALLBACKS
// ========================================


/**
 * Initialize legacy game modal if GameModal module not available
 */
function initializeLegacyGameModal() {
    console.log('🎮 Initializing legacy game modal...');
    
    // Basic modal functionality
    jQuery(document).on('click', '.close-modal', function() {
        jQuery('#game-modal').removeClass('active');
    });
    
    // ESC key to close modal
    jQuery(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            jQuery('#game-modal').removeClass('active');
            jQuery('#youtube-modal').removeClass('active');
        }
    });
}

/**
 * Initialize legacy compatibility for global functions
 */
function initializeLegacyCompatibility() {
    // Ensure all global functions are available for inline onclick handlers
    
    // Game functions
    if (typeof window.playGame === 'undefined') {
        window.playGame = function(romUrl, emulatorType) {
            console.log('🎮 Legacy playGame called:', romUrl, emulatorType);
            if (window.GameEmulator) {
                window.GameEmulator.playGame(romUrl, emulatorType);
            }
        };
    }
    
    if (typeof window.loadGameInTV === 'undefined') {
        window.loadGameInTV = function(romUrl, emulatorType) {
            console.log('📺 Legacy loadGameInTV called:', romUrl, emulatorType);
            if (window.GameEmulator) {
                window.GameEmulator.loadGameInTV(romUrl, emulatorType);
            }
        };
    }
    
    // Modal functions
    if (typeof window.closeGameModal === 'undefined') {
        window.closeGameModal = function() {
            jQuery('#game-modal').removeClass('active');
        };
    }
    
    // Video functions
    if (typeof window.playVideo === 'undefined') {
        window.playVideo = function(youtubeId) {
            console.log('📺 Legacy playVideo called:', youtubeId);
            if (window.VideoPlayer) {
                window.VideoPlayer.playVideo(youtubeId);
            }
        };
    }
    
    if (typeof window.closeYouTubeModal === 'undefined') {
        window.closeYouTubeModal = function() {
            jQuery('#youtube-modal').removeClass('active');
        };
    }
    
    // TV control functions
    if (typeof window.toggleTVFullscreen === 'undefined') {
        window.toggleTVFullscreen = function() {
            if (window.TVControls) {
                window.TVControls.toggleFullscreen();
            }
        };
    }
    
    if (typeof window.toggleGameAudio === 'undefined') {
        window.toggleGameAudio = function() {
            if (window.TVControls) {
                window.TVControls.toggleGameAudio();
            }
        };
    }
    
    if (typeof window.resetGame === 'undefined') {
        window.resetGame = function() {
            if (window.TVControls) {
                window.TVControls.resetGame();
            }
        };
    }
    
    // Utility functions
    if (typeof window.shareGame === 'undefined') {
        window.shareGame = function(gameId) {
            console.log('📤 Legacy shareGame called:', gameId);
            // Basic sharing functionality
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            }
        };
    }
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

/**
 * Update game statistics
 */
function updateGameStats() {
    const gameCards = jQuery('.game-card');
    if (gameCards.length > 0) {
        console.log(`📊 Found ${gameCards.length} games in the arcade!`);
        
        // Update any game counters on the page
        const gameCounters = jQuery('.game-count');
        gameCounters.text(gameCards.length);
    }
}

/**
 * Add global event listeners
 */
function addGlobalEventListeners() {
    // Global click tracking for analytics
    jQuery(document).on('click', '[data-track]', function() {
        const trackingData = jQuery(this).data('track');
        if (window.AjaxHandlers && typeof AjaxHandlers.trackEvent === 'function') {
            AjaxHandlers.trackEvent(trackingData);
        }
    });
    
    // Global error handling for iframes
    jQuery('iframe').on('error', function() {
        console.warn('📺 Iframe failed to load:', this.src);
    });
    
    // Prevent right-click on game iframes (optional)
    jQuery(document).on('contextmenu', '.retro-emulator', function(e) {
        e.preventDefault();
        return false;
    });
}

/**
 * Get game ID from current page
 */
function getGameIdFromPage() {
    // Try multiple methods to get game ID
    const gameIdMeta = document.querySelector('meta[name="game-id"]');
    if (gameIdMeta) return gameIdMeta.content;
    
    const bodyClass = document.body.className.match(/postid-(\d+)/);
    if (bodyClass) return bodyClass[1];
    
    return null;
}

/**
 * Check if startup sound should play
 */
function shouldPlayStartupSound() {
    // Only on home page and if user has interacted
    return document.body.classList.contains('home') && 
           localStorage.getItem('arcade_sound_enabled') !== 'false';
}

/**
 * Play startup sound
 */
function playStartupSound() {
    if (window.arcade_ajax && window.arcade_ajax.theme_url) {
        const audio = new Audio(window.arcade_ajax.theme_url + '/sounds/startup.mp3');
        audio.volume = 0.3;
        audio.play().catch(e => console.log('🔇 Sound autoplay blocked'));
    }
}

/**
 * Initialize game rating functionality
 */
function initializeGameRating(gameId) {
    const ratingElements = document.querySelectorAll('.game-rating-stars');
    ratingElements.forEach(element => {
        element.addEventListener('click', function(e) {
            if (e.target.dataset.rating) {
                const rating = parseInt(e.target.dataset.rating);
                if (window.AjaxHandlers && typeof AjaxHandlers.submitGameRating === 'function') {
                    AjaxHandlers.submitGameRating(gameId, rating);
                }
            }
        });
    });
}

/**
 * Initialize game sharing functionality
 */
function initializeGameSharing(gameId) {
    const shareButtons = document.querySelectorAll('.share-game');
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    text: 'Check out this retro game!',
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    if (window.ThemeEffects && window.ThemeEffects.showTVMessage) {
                        window.ThemeEffects.showTVMessage('Link copied to clipboard!', 2000);
                    }
                });
            }
        });
    });
}

// ========================================
// PLACEHOLDER FUNCTIONS FOR FUTURE MODULES
// ========================================

function initializeInfiniteScroll() { console.log('📜 Infinite scroll not yet implemented'); }
function initializeFilterState() { console.log('🔄 Filter state persistence not yet implemented'); }
function addArchiveKeyboardNavigation() { console.log('⌨️ Archive keyboard navigation not yet implemented'); }
function initializeModFilters() { console.log('🔧 Mod filters not yet implemented'); }
function initializeLoadOrderDisplay() { console.log('📋 Load order display not yet implemented'); }
function initializeFeaturedGames() { console.log('⭐ Featured games carousel not yet implemented'); }
function initializeRecentGames() { console.log('🕐 Recent games not yet implemented'); }
function addHomePageAnimations() { console.log('✨ Home page animations not yet implemented'); }
function initializeROMTesting() { console.log('🧪 Admin ROM testing not yet implemented'); }
function initializeModManagement() { console.log('🔧 Admin mod management not yet implemented'); }

// ========================================
// EXPOSE MAIN FUNCTION GLOBALLY
// ========================================

// Make main initialization function available globally
window.initializeArcadeHub = initializeArcadeHub;

// Legacy compatibility exports
window.updateGameStats = updateGameStats;
window.playClickSound = function() {
    if (window.arcade_ajax && window.arcade_ajax.theme_url) {
        const audio = new Audio(window.arcade_ajax.theme_url + '/sounds/click.mp3');
        audio.volume = 0.2;
        audio.play().catch(e => console.log('🔇 Click sound blocked'));
    }
};