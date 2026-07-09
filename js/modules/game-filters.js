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