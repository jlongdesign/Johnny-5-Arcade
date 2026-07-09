/**
 * Video Player Module
 * Handles YouTube video playback in modal overlay
 */

const VideoPlayer = {
    /**
     * Initialize video player
     */
    init() {
        this.createVideoModal();
        this.addEventListeners();
        console.log('📺 Video Player initialized');
    },

    /**
     * Create video modal HTML if it doesn't exist
     */
    createVideoModal() {
        // Check if modal already exists
        if (document.getElementById('youtube-modal')) {
            return;
        }

        const modal = document.createElement('div');
        modal.id = 'youtube-modal';
        modal.className = 'youtube-modal';
        modal.innerHTML = `
            <div class="youtube-modal-overlay"></div>
            <div class="youtube-modal-content">
                <button class="close-youtube-modal">×</button>
                <div class="youtube-player-container">
                    <!-- YouTube player will be inserted here -->
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        this.addVideoModalStyles();
    },

    /**
     * Add video modal styles
     */
    addVideoModalStyles() {
        if (document.getElementById('video-player-styles')) {
            return;
        }

        const style = document.createElement('style');
        style.id = 'video-player-styles';
        style.textContent = `
            .youtube-modal {
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
            
            .youtube-modal.active {
                display: flex;
                opacity: 1;
                align-items: center;
                justify-content: center;
            }
            
            .youtube-modal-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                backdrop-filter: blur(5px);
            }
            
            .youtube-modal-content {
                position: relative;
                width: 90%;
                max-width: 1200px;
                background: #1a1a1a;
                border: 3px solid #ff0000;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 0 30px rgba(255, 0, 0, 0.3);
                animation: videoModalSlideIn 0.5s ease-out;
            }
            
            .close-youtube-modal {
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
            
            .close-youtube-modal:hover {
                background: #cc0000;
                transform: scale(1.1);
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
            }
            
            .youtube-player-container {
                position: relative;
                width: 100%;
                height: 0;
                padding-bottom: 56.25%; /* 16:9 aspect ratio */
                background: #000;
            }
            
            .youtube-player-container iframe,
            .youtube-player-container video {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: none;
            }
            
            .video-loading {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: #ff0000;
                font-family: 'Courier New', monospace;
                font-size: 1.5rem;
                font-weight: bold;
                text-align: center;
            }
            
            .video-loading::before {
                content: "📺 LOADING VIDEO...";
                animation: pulse 1.5s infinite;
            }
            
            @keyframes videoModalSlideIn {
                from {
                    transform: scale(0.8) translateY(-50px);
                    opacity: 0;
                }
                to {
                    transform: scale(1) translateY(0);
                    opacity: 1;
                }
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
            
            /* Responsive design */
            @media (max-width: 768px) {
                .youtube-modal-content {
                    width: 95%;
                    border-radius: 10px;
                }
                
                .close-youtube-modal {
                    top: 10px;
                    right: 10px;
                    width: 35px;
                    height: 35px;
                    font-size: 1.3rem;
                }
            }
            
            /* Prevent body scroll when modal is open */
            body.video-modal-open {
                overflow: hidden;
            }
            
            /* Video controls styling */
            .video-controls {
                position: absolute;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 10px;
                background: rgba(0, 0, 0, 0.8);
                padding: 10px;
                border-radius: 25px;
                border: 1px solid #ff0000;
            }
            
            .video-control-btn {
                background: transparent;
                color: #ff0000;
                border: 1px solid #ff0000;
                padding: 8px 12px;
                border-radius: 20px;
                cursor: pointer;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }
            
            .video-control-btn:hover {
                background: #ff0000;
                color: white;
            }
        `;
        
        document.head.appendChild(style);
    },

    /**
     * Add event listeners
     */
    addEventListeners() {
        // Close modal on overlay click
        jQuery(document).on('click', '.youtube-modal-overlay', () => {
            this.closeVideo();
        });

        // Close modal on close button click
        jQuery(document).on('click', '.close-youtube-modal', () => {
            this.closeVideo();
        });

        // Close modal on ESC key
        jQuery(document).on('keydown', (e) => {
            if (e.key === 'Escape') {
                const videoModal = document.getElementById('youtube-modal');
                if (videoModal && videoModal.classList.contains('active')) {
                    this.closeVideo();
                }
            }
        });

        // Prevent modal content clicks from closing modal
        jQuery(document).on('click', '.youtube-modal-content', (e) => {
            e.stopPropagation();
        });

        // Handle play video clicks
        jQuery(document).on('click', '[data-youtube-id]', (e) => {
            e.preventDefault();
            const youtubeId = jQuery(e.currentTarget).data('youtube-id');
            if (youtubeId) {
                this.playVideo(youtubeId);
            }
        });
    },

    /**
     * Play YouTube video
     */
    playVideo(youtubeId, autoplay = true) {
        console.log('📺 Playing video:', youtubeId);
        
        const modal = document.getElementById('youtube-modal');
        const playerContainer = modal.querySelector('.youtube-player-container');
        
        if (!modal || !playerContainer) {
            console.error('Video modal not found');
            return;
        }

        // Show loading state
        this.showLoading(playerContainer);
        
        // Open modal
        modal.classList.add('active');
        document.body.classList.add('video-modal-open');
        
        // Create YouTube iframe
        const iframe = this.createYouTubeIframe(youtubeId, autoplay);
        
        // Clear container and add iframe
        setTimeout(() => {
            playerContainer.innerHTML = '';
            playerContainer.appendChild(iframe);
        }, 300);
        
        // Track video play
        this.trackVideoPlay(youtubeId);
    },

    /**
     * Create YouTube iframe
     */
    createYouTubeIframe(youtubeId, autoplay = true) {
        const iframe = document.createElement('iframe');
        
        // YouTube embed parameters
        const params = new URLSearchParams({
            autoplay: autoplay ? '1' : '0',
            modestbranding: '1',
            rel: '0',
            showinfo: '0',
            iv_load_policy: '3',
            color: 'red',
            theme: 'dark',
            controls: '1',
            fs: '1',
            cc_load_policy: '0',
            disablekb: '0',
            enablejsapi: '1',
            origin: window.location.origin
        });
        
        iframe.src = `https://www.youtube.com/embed/${youtubeId}?${params.toString()}`;
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        iframe.allowFullscreen = true;
        iframe.title = 'YouTube Video Player';
        
        return iframe;
    },

    /**
     * Play video from direct URL (MP4, etc.)
     */
    playVideoFile(videoUrl, title = 'Video') {
        console.log('📺 Playing video file:', videoUrl);
        
        const modal = document.getElementById('youtube-modal');
        const playerContainer = modal.querySelector('.youtube-player-container');
        
        if (!modal || !playerContainer) {
            console.error('Video modal not found');
            return;
        }

        // Show loading state
        this.showLoading(playerContainer);
        
        // Open modal
        modal.classList.add('active');
        document.body.classList.add('video-modal-open');
        
        // Create video element
        const video = document.createElement('video');
        video.src = videoUrl;
        video.controls = true;
        video.autoplay = true;
        video.title = title;
        video.style.width = '100%';
        video.style.height = '100%';
        
        // Clear container and add video
        setTimeout(() => {
            playerContainer.innerHTML = '';
            playerContainer.appendChild(video);
        }, 300);
    },

    /**
     * Close video modal
     */
    closeVideo() {
        const modal = document.getElementById('youtube-modal');
        if (!modal) return;

        // Hide modal
        modal.classList.remove('active');
        document.body.classList.remove('video-modal-open');
        
        // Stop video and clear content after animation
        setTimeout(() => {
            const playerContainer = modal.querySelector('.youtube-player-container');
            if (playerContainer) {
                playerContainer.innerHTML = '';
            }
        }, 300);
        
        console.log('📺 Video modal closed');
        
        // Trigger custom event
        jQuery(document).trigger('videoModalClosed');
    },

    /**
     * Show loading state
     */
    showLoading(container) {
        container.innerHTML = '<div class="video-loading"></div>';
    },

    /**
     * Check if video modal is open
     */
    isOpen() {
        const modal = document.getElementById('youtube-modal');
        return modal && modal.classList.contains('active');
    },

    /**
     * Track video play for analytics
     */
    trackVideoPlay(youtubeId) {
        if (window.AjaxHandlers && typeof AjaxHandlers.trackEvent === 'function') {
            AjaxHandlers.trackEvent({
                action: 'video_play',
                video_id: youtubeId,
                timestamp: Date.now()
            });
        }
    },

    /**
     * Create video gallery
     */
    createVideoGallery(videos, containerId) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('Video gallery container not found:', containerId);
            return;
        }

        const gallery = document.createElement('div');
        gallery.className = 'video-gallery';
        gallery.style.cssText = `
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px 0;
        `;

        videos.forEach(video => {
            const videoCard = this.createVideoCard(video);
            gallery.appendChild(videoCard);
        });

        container.appendChild(gallery);
    },

    /**
     * Create individual video card
     */
    createVideoCard(video) {
        const card = document.createElement('div');
        card.className = 'video-card';
        card.style.cssText = `
            background: #2a2a2a;
            border: 2px solid #ff0000;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        `;

        const thumbnail = video.youtubeId 
            ? `https://img.youtube.com/vi/${video.youtubeId}/maxresdefault.jpg`
            : video.thumbnail || 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDMyMCAxODAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMjAiIGhlaWdodD0iMTgwIiBmaWxsPSIjMzMzIi8+Cjx0ZXh0IHg9IjE2MCIgeT0iOTAiIGZpbGw9IiM2NjYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuMzVlbSI+VmlkZW88L3RleHQ+Cjwvc3ZnPg==';

        card.innerHTML = `
            <div style="position: relative; overflow: hidden;">
                <img src="${thumbnail}" alt="${video.title}" style="
                    width: 100%;
                    height: 180px;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                " />
                <div style="
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: rgba(255, 0, 0, 0.9);
                    color: white;
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.5rem;
                    transition: all 0.3s ease;
                ">▶</div>
                ${video.duration ? `
                    <div style="
                        position: absolute;
                        bottom: 10px;
                        right: 10px;
                        background: rgba(0, 0, 0, 0.8);
                        color: white;
                        padding: 2px 6px;
                        border-radius: 3px;
                        font-size: 0.8rem;
                        font-family: 'Courier New', monospace;
                    ">${video.duration}</div>
                ` : ''}
            </div>
            <div style="padding: 15px;">
                <h4 style="color: #ff0000; margin-bottom: 8px; font-size: 1rem; line-height: 1.3;">
                    ${video.title}
                </h4>
                ${video.description ? `
                    <p style="color: #ccc; font-size: 0.9rem; line-height: 1.4; margin: 0;">
                        ${video.description.length > 100 ? video.description.substring(0, 100) + '...' : video.description}
                    </p>
                ` : ''}
            </div>
        `;

        // Add hover effects
        card.addEventListener('mouseenter', () => {
            card.style.borderColor = '#ff3333';
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 10px 20px rgba(255, 0, 0, 0.2)';
            
            const img = card.querySelector('img');
            const playBtn = card.querySelector('div[style*="position: absolute"]');
            if (img) img.style.transform = 'scale(1.05)';
            if (playBtn) playBtn.style.transform = 'translate(-50%, -50%) scale(1.1)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.borderColor = '#ff0000';
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'none';
            
            const img = card.querySelector('img');
            const playBtn = card.querySelector('div[style*="position: absolute"]');
            if (img) img.style.transform = 'scale(1)';
            if (playBtn) playBtn.style.transform = 'translate(-50%, -50%) scale(1)';
        });

        // Add click handler
        card.addEventListener('click', () => {
            if (video.youtubeId) {
                this.playVideo(video.youtubeId);
            } else if (video.videoUrl) {
                this.playVideoFile(video.videoUrl, video.title);
            }
        });

        return card;
    },

    /**
     * Get video info from YouTube API (if available)
     */
    async getVideoInfo(youtubeId) {
        // This would require YouTube API key
        // For now, return basic info
        return {
            id: youtubeId,
            title: 'YouTube Video',
            thumbnail: `https://img.youtube.com/vi/${youtubeId}/maxresdefault.jpg`
        };
    }
};

// Make globally available
window.VideoPlayer = VideoPlayer;

// Legacy compatibility functions
window.playVideo = function(youtubeId) {
    if (window.VideoPlayer) {
        window.VideoPlayer.playVideo(youtubeId);
    }
};

window.closeYouTubeModal = function() {
    if (window.VideoPlayer) {
        window.VideoPlayer.closeVideo();
    }
};