<!-- Latest Reels Section -->
<section class="reels-section mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="text-magenta text-shadow-magenta">🎬 Latest Reels</h2>
                <p class="text-white">
                    Quick gaming content and arcade highlights!
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            // Get homepage ID (assuming this is on the homepage)
            $homepage_id = get_option('page_on_front');
            if (!$homepage_id) {
                $homepage_id = get_the_ID(); // Fallback to current page
            }
            
            $reels_to_display = array();

            for ($i = 1; $i <= 8; $i++) {
                $url = get_option("reel_url_$i", '');
                $title = get_option("reel_title_$i", '');
                $type = get_option("reel_type_$i", 'youtube');
                $manual_thumbnail = get_option("reel_thumbnail_$i", '');
                
                if (!empty($url) && !empty($title)) {
                    $video_id = extract_video_id($url, $type);
                    if ($video_id) {
                        $thumbnail = null;
                        if ($type === 'instagram') {
                            // Use manual thumbnail if provided, otherwise try to fetch
                            $thumbnail = !empty($manual_thumbnail) ? $manual_thumbnail : get_instagram_thumbnail($video_id);
                        }
                        
                        $reels_to_display[] = array(
                            'type' => $type,
                            'id' => $video_id,
                            'title' => $title,
                            'url' => $url,
                            'thumbnail' => $thumbnail
                        );
                    }
                }
            }
            
            // Display reels
            if (!empty($reels_to_display)) :
                foreach ($reels_to_display as $reel) :
                    ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="reel-card glass-card glass-border rounded-4 h-100 position-relative overflow-hidden">
                            <div class="reel-thumbnail position-relative" style="aspect-ratio: 9/16;">
                                <?php if ($reel['type'] === 'youtube') : ?>
                                    <!-- YouTube Short -->
                                    <img src="https://img.youtube.com/vi/<?php echo esc_attr($reel['id']); ?>/maxresdefault.jpg" 
                                         alt="<?php echo esc_attr($reel['title']); ?>"
                                         class="w-100 h-100"
                                         style="object-fit: cover; cursor: pointer;"
                                         onclick="openReelModal('https://www.youtube.com/embed/<?php echo esc_js($reel['id']); ?>?autoplay=1&rel=0')">
                                    
                                    <!-- YouTube Play Button -->
                                    <div class="position-absolute top-50 start-50 translate-middle" 
                                        style="cursor: pointer;"
                                        onclick="openReelModal('https://www.youtube.com/embed/<?php echo esc_js($reel['id']); ?>?autoplay=1&rel=0')">
                                        <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-play text-white"></i>
                                        </div>
                                    </div>
                                    
                                <?php elseif ($reel['type'] === 'instagram') : ?>
                                    <!-- Instagram Reel -->
                                    <?php if (!empty($reel['thumbnail'])) : ?>
                                        <img src="<?php echo esc_url($reel['thumbnail']); ?>" 
                                             alt="<?php echo esc_attr($reel['title']); ?>"
                                             class="w-100 h-100"
                                             style="object-fit: cover; cursor: pointer;"
                                             onclick="openInstagramReelModal('<?php echo esc_js($reel['id']); ?>')"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <?php endif; ?>
                                    
                                    <!-- Fallback for failed image load or no thumbnail -->
                                    <div class="w-100 h-100 align-items-center justify-content-center bg-dark text-white" 
                                         style="<?php echo !empty($reel['thumbnail']) ? 'display: none;' : 'display: flex;'; ?> cursor: pointer;"
                                         onclick="openInstagramReelModal('<?php echo esc_js($reel['id']); ?>')">
                                        <div class="text-center">
                                            <i class="fab fa-instagram fa-3x mb-2"></i>
                                            <p class="small mb-0">Click to Watch</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Instagram Play Button -->
                                    <div class="position-absolute top-50 start-50 translate-middle" style="pointer-events: none;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: linear-gradient(45deg, #f09433, #bc1888);">
                                            <i class="fas fa-play text-white"></i>
                                        </div>
                                    </div>
                                    
                                <?php endif; ?>
                                
                                <!-- Platform Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    <?php if ($reel['type'] === 'youtube') : ?>
                                        <span class="badge bg-danger">📺 YouTube</span>
                                    <?php else : ?>
                                        <span class="badge text-white" style="background: linear-gradient(45deg, #f09433, #bc1888);">📸 Instagram</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="p-3">
                                <h4 class="h6 text-white mb-2"><?php echo esc_html($reel['title']); ?></h4>
                                <small class="text-white">Latest <?php echo esc_html($reel['type']); ?> Reel</small>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
            else :
                ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No reels configured yet. Add some in your page settings!</p>
                </div>
                <?php
            endif;
            ?>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="https://www.youtube.com/@johnny5arcade" target="_blank" class="btn btn-danger me-2 mb-2">
                    <i class="fab fa-youtube me-1"></i> More on YouTube
                </a>
                <a href="https://www.instagram.com/johnny5arcade" target="_blank" class="btn mb-2" style="background: linear-gradient(45deg, #f09433, #bc1888); color: white;">
                    <i class="fab fa-instagram me-1"></i> Follow on Instagram
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center mt-4">
            <h3>Follow Our Other Socials!</h3>
            <div class="footer-text social-links d-flex flex-wrap gap-3 justify-content-center">
                <a href="https://www.facebook.com/johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.youtube.com/@johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://www.tikTok.com/@johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://www.twitch.tv/johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-twitch"></i>
                </a>
                <a href="https://johnny-5-arcade-shop.creator-spring.com/" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Reel Modal -->
<div class="modal fade" id="reelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 p-2">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="reel-video-container" style="position: relative; width: 100%; padding-bottom: 177.78%; /* 9:16 aspect ratio */">
                    <iframe id="reel-iframe" 
                            src="" 
                            frameborder="0" 
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openReelModal(videoUrl) {
    var reelIframe = document.getElementById('reel-iframe');
    var reelModalEl = document.getElementById('reelModal');
    
    if (!reelIframe || !reelModalEl) {
        console.error('Reel modal elements not found');
        return;
    }
    
    reelIframe.src = videoUrl;
    var reelModal = new bootstrap.Modal(reelModalEl);
    reelModal.show();
}

function openInstagramReelModal(reelId) {
    var reelIframe = document.getElementById('reel-iframe');
    var reelModalEl = document.getElementById('reelModal');
    
    if (!reelIframe || !reelModalEl) {
        console.error('Reel modal elements not found');
        return;
    }
    
    // Use Instagram embed URL
    var embedUrl = 'https://www.instagram.com/reel/' + reelId + '/embed/';
    reelIframe.src = embedUrl;
    
    var reelModal = new bootstrap.Modal(reelModalEl);
    reelModal.show();
    
    // Load Instagram embed script if not already loaded
    if (!window.instgrm) {
        var script = document.createElement('script');
        script.src = 'https://www.instagram.com/embed.js';
        script.async = true;
        script.defer = true;
        document.body.appendChild(script);
    } else {
        // If script already loaded, process embeds
        window.instgrm.Embeds.process();
    }
}

// Clear iframe when modal closes
document.addEventListener('DOMContentLoaded', function() {
    var reelModalEl = document.getElementById('reelModal');
    if (reelModalEl) {
        reelModalEl.addEventListener('hidden.bs.modal', function () {
            var reelIframe = document.getElementById('reel-iframe');
            if (reelIframe) {
                reelIframe.src = '';
            }
        });
    }
});
</script>