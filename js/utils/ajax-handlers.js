/**
 * AJAX Handlers Module
 * Handles all AJAX requests for the arcade theme
 */

const AjaxHandlers = {
    /**
     * Initialize AJAX handlers
     */
    init() {
        this.setupGlobalAjaxSettings();
        this.addEventListeners();
        console.log('📡 AJAX Handlers initialized');
    },

    /**
     * Setup global AJAX settings
     */
    setupGlobalAjaxSettings() {
        // Set default AJAX settings
        jQuery.ajaxSetup({
            timeout: 10000, // 10 second timeout
            beforeSend: (xhr, settings) => {
                // Add nonce to all AJAX requests
                if (window.arcade_ajax && window.arcade_ajax.nonce) {
                    xhr.setRequestHeader('X-WP-Nonce', window.arcade_ajax.nonce);
                }
            }
        });
    },

    /**
     * Add AJAX event listeners
     */
    addEventListeners() {
        // Game filtering AJAX
        jQuery(document).on('change', '.game-filter-select', (e) => {
            this.filterGames(e.target.value, e.target.dataset.filterType);
        });

        // ROM testing AJAX (for admin)
        jQuery(document).on('click', '#test_rom_accessibility', (e) => {
            e.preventDefault();
            this.testRomAccessibility();
        });

        // Game search AJAX
        jQuery(document).on('input', '.game-search-input', (e) => {
            this.debounce(() => {
                this.searchGames(e.target.value);
            }, 300)();
        });

        // Load more games AJAX
        jQuery(document).on('click', '.load-more-games', (e) => {
            e.preventDefault();
            this.loadMoreGames();
        });
    },

    // ========================================
    // GAME FILTERING & SEARCH
    // ========================================

    /**
     * Filter games via AJAX
     */
    filterGames(value, filterType) {
        console.log('🎯 Filtering games:', filterType, value);
        
        const data = {
            action: 'arcade_filter_games',
            filter_type: filterType,
            filter_value: value,
            nonce: window.arcade_ajax?.nonce
        };

        this.showLoadingState('.game-grid');

        jQuery.ajax({
            url: window.arcade_ajax?.ajax_url,
            type: 'POST',
            data: data,
            success: (response) => {
                if (response.success) {
                    this.updateGameGrid(response.data.html);
                    this.updateFilterCounts(response.data.counts);
                } else {
                    this.showError('Failed to filter games', response.data?.message);
                }
            },
            error: (xhr, status, error) => {
                this.showError('Filter request failed', error);
            },
            complete: () => {
                this.hideLoadingState('.game-grid');
            }
        });
    },

    /**
     * Search games via AJAX
     */
    searchGames(searchTerm) {
        console.log('🔍 Searching games:', searchTerm);
        
        if (searchTerm.length < 2) {
            this.clearSearch();
            return;
        }

        const data = {
            action: 'arcade_search_games',
            search_term: searchTerm,
            nonce: window.arcade_ajax?.nonce
        };

        this.showLoadingState('.game-grid');

        jQuery.ajax({
            url: window.arcade_ajax?.ajax_url,
            type: 'POST',
            data: data,
            success: (response) => {
                if (response.success) {
                    this.updateGameGrid(response.data.html);
                    this.showSearchResults(response.data.count, searchTerm);
                } else {
                    this.showError('Search failed', response.data?.message);
                }
            },
            error: (xhr, status, error) => {
                this.showError('Search request failed', error);
            },
            complete: () => {
                this.hideLoadingState('.game-grid');
            }
        });
    },

    /**
     * Load more games (pagination)
     */
    loadMoreGames() {
        const loadMoreBtn = jQuery('.load-more-games');
        const currentPage = parseInt(loadMoreBtn.data('page') || 1);
        const nextPage = currentPage + 1;

        console.log('📄 Loading more games, page:', nextPage);

        const data = {
            action: 'arcade_load_more_games',
            page: nextPage,
            filters: this.getCurrentFilters(),
            nonce: window.arcade_ajax?.nonce
        };

        loadMoreBtn.text('Loading...').prop('disabled', true);

        jQuery.ajax({
            url: window.arcade_ajax?.ajax_url,
            type: 'POST',
            data: data,
            success: (response) => {
                if (response.success) {
                    this.appendGames(response.data.html);
                    loadMoreBtn.data('page', nextPage);
                    
                    if (!response.data.has_more) {
                        loadMoreBtn.hide();
                        this.showMessage('All games loaded!', 'info');
                    }
                } else {
                    this.showError('Failed to load more games', response.data?.message);
                }
            },
            error: (xhr, status, error) => {
                this.showError('Load more request failed', error);
            },
            complete: () => {
                loadMoreBtn.text('Load More Games').prop('disabled', false);
            }
        });
    },

    // ========================================
    // ROM TESTING (ADMIN)
    // ========================================

    /**
     * Test ROM file accessibility (admin only)
     */
    testRomAccessibility() {
        const romUrl = jQuery('#_game_rom_url').val();
        const resultsDiv = jQuery('#rom_test_results');

        if (!romUrl) {
            resultsDiv.html('<div class="notice notice-error"><p>Please enter a ROM URL first.</p></div>');
            return;
        }

        console.log('🧪 Testing ROM accessibility:', romUrl);

        resultsDiv.html('<div class="notice notice-info"><p>🔍 Testing ROM file accessibility...</p></div>');

        const data = {
            action: 'arcade_test_rom_access',
            rom_url: romUrl,
            nonce: window.arcade_ajax?.nonce
        };

        jQuery.ajax({
            url: window.arcade_ajax?.ajax_url,
            type: 'POST',
            data: data,
            timeout: 15000, // Longer timeout for ROM testing
            success: (response) => {
                if (response.success) {
                    this.displayRomTestResults(response.data);
                } else {
                    resultsDiv.html(`<div class="notice notice-error"><p>❌ Test failed: ${response.data?.message || 'Unknown error'}</p></div>`);
                }
            },
            error: (xhr, status, error) => {
                let errorMsg = 'Test request failed';
                if (status === 'timeout') {
                    errorMsg = 'Test timed out - ROM file may be too large or inaccessible';
                } else if (xhr.status) {
                    errorMsg = `HTTP ${xhr.status}: ${error}`;
                }
                resultsDiv.html(`<div class="notice notice-error"><p>❌ ${errorMsg}</p></div>`);
            }
        });
    },

    /**
     * Display ROM test results
     */
    displayRomTestResults(data) {
        const resultsDiv = jQuery('#rom_test_results');
        let resultHtml = '<div class="rom-test-results">';

        // Status
        if (data.accessible) {
            resultHtml += '<div class="notice notice-success"><p>✅ ROM file accessible</p></div>';
        } else {
            resultHtml += '<div class="notice notice-error"><p>❌ ROM file not accessible</p></div>';
        }

        // Details
        resultHtml += '<div class="rom-test-details">';
        resultHtml += `<p><strong>URL:</strong> ${data.url}</p>`;
        resultHtml += `<p><strong>Status Code:</strong> ${data.status_code}</p>`;
        resultHtml += `<p><strong>Content Type:</strong> ${data.content_type || 'Unknown'}</p>`;
        resultHtml += `<p><strong>File Size:</strong> ${data.file_size || 'Unknown'}</p>`;

        // CORS check
        if (data.cors_headers) {
            resultHtml += '<p><strong>CORS Headers:</strong> ✅ Present</p>';
        } else {
            resultHtml += '<p><strong>CORS Headers:</strong> ⚠️ Missing (may cause loading issues)</p>';
        }

        // Recommendations
        if (data.recommendations && data.recommendations.length > 0) {
            resultHtml += '<div class="recommendations"><h4>💡 Recommendations:</h4><ul>';
            data.recommendations.forEach(rec => {
                resultHtml += `<li>${rec}</li>`;
            });
            resultHtml += '</ul></div>';
        }

        resultHtml += '</div></div>';
        resultsDiv.html(resultHtml);
    },

    // ========================================
    // GAME STATS & ANALYTICS
    // ========================================

    /**
     * Track game play
     */
    trackGamePlay(gameId, romUrl, emulatorType) {
        const data = {
            action: 'arcade_track_game_play',
            game_id: gameId,
            rom_url: romUrl,
            emulator_type: emulatorType,
            nonce: window.arcade_ajax?.nonce
        };

        jQuery.ajax({
            url: window.arcade_ajax?.ajax_url,
            type: 'POST',
            data: data,
            success: (response) => {
                if (response.success) {
                    console.log('📊 Game play tracked');
                }
            }
        });
    },

    /**
     * Get game statistics
     */
    getGameStats(gameId) {
        return new Promise((resolve, reject) => {
            const data = {
                action: 'arcade_get_game_stats',
                game_id: gameId,
                nonce: window.arcade_ajax?.nonce
            };

            jQuery.ajax({
                url: window.arcade_ajax?.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    if (response.success) {
                        resolve(response.data);
                    } else {
                        reject(response.data?.message || 'Failed to get stats');
                    }
                },
                error: (xhr, status, error) => {
                    reject(error);
                }
            });
        });
    },

    // ========================================
    // USER INTERACTIONS
    // ========================================

    /**
     * Save game to favorites
     */
    toggleGameFavorite(gameId) {
        const data = {
            action: 'arcade_toggle_favorite',
            game_id: gameId,
            nonce: window.arcade_ajax?.nonce
        };

        jQuery.ajax({
            url: window.arcade_ajax?.ajax_url,
            type: 'POST',
            data: data,
            success: (response) => {
                if (response.success) {
                    this.updateFavoriteButton(gameId, response.data.is_favorite);
                    this.showMessage(response.data.message, 'success');
                } else {
                    this.showError('Failed to update favorite', response.data?.message);
                }
            },
            error: (xhr, status, error) => {
                this.showError('Favorite request failed', error);
            }
        });
    },

    /**
     * Submit game rating
     */
    submitGameRating(gameId, rating) {
        const data = {
            action: 'arcade_submit_rating',
            game_id: gameId,
            rating: rating,
            nonce: window.arcade_ajax?.nonce
        };

        jQuery.ajax({
            url: window.arcade_ajax?.ajax_url,
            type: 'POST',
            data: data,
            success: (response) => {
                if (response.success) {
                    this.updateRatingDisplay(gameId, response.data);
                    this.showMessage('Rating submitted!', 'success');
                } else {
                    this.showError('Failed to submit rating', response.data?.message);
                }
            },
            error: (xhr, status, error) => {
                this.showError('Rating request failed', error);
            }
        });
    },

    // ========================================
    // UTILITY METHODS
    // ========================================

    /**
     * Get current filter values
     */
    getCurrentFilters() {
        const filters = {};
        jQuery('.game-filter-select').each(function() {
            const filterType = jQuery(this).data('filter-type');
            const value = jQuery(this).val();
            if (value) {
                filters[filterType] = value;
            }
        });
        return filters;
    },

    /**
     * Update game grid with new content
     */
    updateGameGrid(html) {
        const gameGrid = jQuery('.game-grid');
        gameGrid.fadeOut(200, () => {
            gameGrid.html(html).fadeIn(200);
            // Re-initialize any effects on new content
            if (window.ThemeEffects) {
                window.ThemeEffects.addRetroEffects();
            }
        });
    },

    /**
     * Append games to existing grid (for pagination)
     */
    appendGames(html) {
        const gameGrid = jQuery('.game-grid');
        const newGames = jQuery(html);
        newGames.hide().appendTo(gameGrid).fadeIn(400);
        
        // Re-initialize effects on new games
        if (window.ThemeEffects) {
            window.ThemeEffects.addRetroEffects();
        }
    },

    /**
     * Show loading state
     */
    showLoadingState(selector) {
        const element = jQuery(selector);
        element.addClass('loading').append('<div class="ajax-loading">🎮 Loading...</div>');
    },

    /**
     * Hide loading state
     */
    hideLoadingState(selector) {
        const element = jQuery(selector);
        element.removeClass('loading').find('.ajax-loading').remove();
    },

    /**
     * Show error message
     */
    showError(title, message = '') {
        const errorHtml = `
            <div class="arcade-notice error">
                <strong>❌ ${title}</strong>
                ${message ? `<br>${message}` : ''}
            </div>
        `;
        this.showNotification(errorHtml, 'error');
    },

    /**
     * Show success/info message
     */
    showMessage(message, type = 'info') {
        const icon = type === 'success' ? '✅' : 'ℹ️';
        const messageHtml = `
            <div class="arcade-notice ${type}">
                ${icon} ${message}
            </div>
        `;
        this.showNotification(messageHtml, type);
    },

    /**
     * Show notification
     */
    showNotification(html, type) {
        const notification = jQuery(html);
        notification.css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            zIndex: 9999,
            padding: '15px',
            borderRadius: '4px',
            maxWidth: '300px'
        });

        jQuery('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut(400, () => notification.remove());
        }, 4000);
    },

    /**
     * Debounce function for search
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Clear search results
     */
    clearSearch() {
        // Reset to original game grid
        this.filterGames('', 'all');
    },

    /**
     * Update filter counts
     */
    updateFilterCounts(counts) {
        Object.keys(counts).forEach(filterType => {
            const countElement = jQuery(`.filter-count[data-filter="${filterType}"]`);
            if (countElement.length) {
                countElement.text(`(${counts[filterType]})`);
            }
        });
    },

    /**
     * Show search results info
     */
    showSearchResults(count, searchTerm) {
        const searchInfo = jQuery('.search-results-info');
        if (searchInfo.length) {
            searchInfo.html(`Found ${count} games matching "${searchTerm}"`);
        }
    },

    /**
     * Update favorite button state
     */
    updateFavoriteButton(gameId, isFavorite) {
        const button = jQuery(`.favorite-btn[data-game-id="${gameId}"]`);
        if (button.length) {
            button.toggleClass('is-favorite', isFavorite);
            button.html(isFavorite ? '❤️ Favorited' : '🤍 Add to Favorites');
        }
    },

    /**
     * Update rating display
     */
    updateRatingDisplay(gameId, ratingData) {
        const ratingElement = jQuery(`.game-rating[data-game-id="${gameId}"]`);
        if (ratingElement.length) {
            ratingElement.find('.average-rating').text(ratingData.average);
            ratingElement.find('.rating-count').text(`(${ratingData.count} ratings)`);
        }
    }
};

// Make globally available
window.AjaxHandlers = AjaxHandlers;