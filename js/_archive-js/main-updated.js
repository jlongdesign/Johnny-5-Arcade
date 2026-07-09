/**
 * Load game directly in the TV on the single game page
 * UPDATED VERSION - Bypasses EmulatorJS for better reliability
 */
function loadGameInTV(romUrl, emulatorType) {
    const tvEmulator = document.getElementById('tv-emulator');
    const loadingDiv = document.querySelector('.tv-loading');
    const staticDiv = document.querySelector('.tv-static');
    
    if (!tvEmulator || !loadingDiv) return;
    
    console.log('📺 Loading game in TV:', romUrl, emulatorType);
    
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
    
    // Demo game URLs for different systems
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
    
    // If no ROM URL, use demo games
    if (!romUrl || romUrl === '') {
        gameUrl = demoGames[emulatorType] || demoGames['nes'];
        console.log('🎮 Using demo game:', gameUrl);
    }
    // If it's a direct Archive.org download ROM file, convert to embed URL
    else if (romUrl.includes('archive.org/download/') && romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
        // Convert Archive.org download URL to embed URL
        // From: https://archive.org/download/collection/file.nes
        // To: https://archive.org/embed/collection
        const urlParts = romUrl.split('/');
        const collectionIndex = urlParts.indexOf('download') + 1;
        if (collectionIndex > 0 && urlParts[collectionIndex]) {
            const collection = urlParts[collectionIndex];
            gameUrl = `https://archive.org/embed/${collection}`;
            console.log('🏛️ Converted Archive.org download to embed:', gameUrl);
        } else {
            // Fallback to demo game if URL parsing fails
            gameUrl = demoGames[emulatorType] || demoGames['nes'];
            console.log('🎮 Archive.org URL parsing failed, using demo game');
        }
    }
    // If it's a direct ROM file from elsewhere, show error and use demo
    else if (romUrl.match(/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|iso)(\?|$)/i)) {
        console.log('❌ Direct ROM files only work with Archive.org URLs');
        gameUrl = demoGames[emulatorType] || demoGames['nes'];
        
        // Show helpful message
        setTimeout(() => {
            showTVMessage('Direct ROM files only supported from Archive.org. Using demo game.', 4000, '#ff8800');
            
            setTimeout(() => {
                loadingDiv.innerHTML = `
                    <div class="loading-text" style="color: #ffaa00;">📁 NON-ARCHIVE.ORG ROM</div>
                    <div style="color: #ffcc66; font-size: 0.8rem; margin-top: 10px; text-align: left; line-height: 1.4;">
                        Direct ROM files work best with Archive.org URLs.
                    </div>
                    <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left; line-height: 1.4;">
                        <strong>For ROM files:</strong><br>
                        • Upload to Archive.org first<br>
                        • Use Archive.org download URLs<br>
                        • Or use embedded game URLs instead<br>
                        • Currently loading demo game
                    </div>
                    <div style="color: #666; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
                        Provided URL: ${romUrl.split('/').pop()}<br>
                        Using demo instead
                    </div>
                `;
            }, 3000);
        }, 2000);
    }
    
    // Load game with TV turn-on effect
    setTimeout(() => {
        console.log('🚀 Loading game URL in TV:', gameUrl);
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
            console.error('❌ TV emulator failed to load:', gameUrl);
            
            // If it's an Archive.org URL and it failed, try a different approach
            if (gameUrl.includes('archive.org')) {
                showTVMessage('Archive.org service unavailable. Using demo game.', 4000, '#ff8800');
                
                setTimeout(() => {
                    tvEmulator.src = demoGames[emulatorType] || demoGames['nes'];
                }, 2000);
            } else {
                showTVMessage('Failed to load game. Check console for details.', 4000, '#ff0000');
                
                setTimeout(() => {
                    loadingDiv.innerHTML = `
                        <div class="loading-text" style="color: #ff0000;">⚠️ LOADING FAILED</div>
                        <div style="color: #ff6666; font-size: 0.8rem; margin-top: 10px; text-align: left; line-height: 1.4;">
                            Could not load the game URL.
                        </div>
                        <div style="color: #ccc; font-size: 0.8rem; margin-top: 15px; text-align: left; line-height: 1.4;">
                            <strong>Quick fixes:</strong><br>
                            • Check if URL works in a new tab<br>
                            • Try Archive.org embedded URLs<br>
                            • Leave ROM URL empty for demo games<br>
                            • Check browser console for errors
                        </div>
                        <div style="color: #666; font-size: 0.7rem; margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
                            Failed URL: ${gameUrl}<br>
                            <button onclick="location.reload()" style="background: #ff6600; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-top: 10px; cursor: pointer;">Try Again</button>
                        </div>
                    `;
                }, 2000);
            }
        };
    }, 1500);
}
