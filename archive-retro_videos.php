<?php get_header(); ?>

<div class="videos-archive-page container mt-5">
    <section class="arcade-section">
        <div class="text-center mb-5">
            <h1 class="mb-3" style="color: #ff00ff; font-size: 2.5rem;">
                📺 Retro Gaming TV Gallery
            </h1>
            <p class="text-white fs-5">
                Classic gaming commercials, reviews, and nostalgic videos from the 90s!
            </p>
        </div>
        
        <!-- Video Type Filter -->
        <div id="game-filters" class="card p-4 mb-4 glass glass-border rounded-4" style="border-color: #ff00ff;">
            <div class="card-body">
                <h3 class="card-title mb-3 text-magenta">📼 Filter by Type</h3>
                <div class="row g-3">
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <label for="video-type-filter" class="form-label text-bold" style="color: #ffff00;">📺 Video Type:</label>
                        <select id="video-type-filter" name="video_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="commercial">🎬 Commercials</option>
                            <option value="gameplay">🎮 Gameplay</option>
                            <option value="review">⭐ Reviews</option>
                            <option value="documentary">📚 Documentaries</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 d-flex align-items-end">
                        <button id="filter-videos" class="btn retro-button w-100">
                            🔍 Filter Videos
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Videos Grid -->
        <div id="videos-container" class="row g-4">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $youtube_id = get_post_meta(get_the_ID(), '_video_youtube_id', true);
                    $video_type = get_post_meta(get_the_ID(), '_video_type', true);
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card h-100 bg-dark glass-border" data-type="<?php echo esc_attr($video_type); ?>">
                            <div class="position-relative">
                                <?php if ($youtube_id) : ?>
                                    <img class="card-img-top video-thumbnail" 
                                         src="https://img.youtube.com/vi/<?php echo esc_attr($youtube_id); ?>/maxresdefault.jpg" 
                                         alt="<?php the_title(); ?>"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#videoModal" 
                                         data-youtube-id="<?php echo esc_attr($youtube_id); ?>"
                                         data-video-title="<?php echo esc_attr(get_the_title()); ?>"
                                         style="height: 200px; object-fit: cover; cursor: pointer;">
                                    <div class="position-absolute top-50 start-50 translate-middle" style="font-size: 3rem; color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.8); pointer-events: none;">
                                        ▶️
                                    </div>
                                <?php elseif (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top video-thumbnail', 'style' => 'height: 200px; object-fit: cover;')); ?>
                                    </a>
                                <?php else : ?>
                                    <div class="card-img-top d-flex align-items-center justify-content-center text-muted" style="height: 200px; background: linear-gradient(45deg, #333, #555); font-size: 2rem;">
                                        📺
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column p-3">
                                <h5 class="card-title video-title mb-3">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none" style="color: #ff8000;">
                                        <?php the_title(); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-white small flex-grow-1">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge" style="color: #ff00ff; background: rgba(255, 0, 255, 0.1); border: 1px solid #ff00ff;">
                                        <?php 
                                        $type_icons = array(
                                            'commercial' => '🎬',
                                            'gameplay' => '🎮',
                                            'review' => '⭐',
                                            'documentary' => '📚'
                                        );
                                        echo ($type_icons[$video_type] ?? '📺') . ' ' . esc_html($video_type ?: 'Video'); 
                                        ?>
                                    </span>
                                    <small class="text-muted">
                                        <?php echo get_the_date(); ?>
                                    </small>
                                </div>
                                
                                <?php if ($youtube_id) : ?>
                                    <div class="text-center mt-auto">
                                        <a href="<?php the_permalink(); ?>" class="btn play-button w-100">
                                            ▶️ Watch Now
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <h3 class="mb-4" style="color: #ff00ff;">📺 No videos yet!</h3>
                        <p class="text-muted mb-4">
                            Start building your retro gaming video collection with classic commercials and gameplay footage.
                        </p>
                        <a href="<?php echo admin_url('post-new.php?post_type=retro_videos'); ?>" class="btn retro-button">
                            📼 Add First Video
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (have_posts()) : ?>
            <div class="text-center mt-5">
                <?php
                echo paginate_links(array(
                    'prev_text' => '← Previous Videos',
                    'next_text' => 'Next Videos →',
                    'type' => 'list'
                ));
                ?>
            </div>
        <?php endif; ?>
    </section>
    
    <!-- Video Stats Section -->
    <section class="arcade-section mt-5" style="border-color: #00ffff;">
        <h3 class="text-center mb-4" style="color: #00ffff;">
            📊 Video Collection Stats
        </h3>
        
        <div class="row g-4">
            <?php
            $video_types = array('commercial', 'gameplay', 'review', 'documentary');
            $type_names = array(
                'commercial' => '🎬 Commercials',
                'gameplay' => '🎮 Gameplay',
                'review' => '⭐ Reviews',
                'documentary' => '📚 Documentaries'
            );
            $colors = array(
                'commercial' => '#ff8000',
                'gameplay' => '#00ff00',
                'review' => '#ffff00',
                'documentary' => '#ff00ff'
            );
            
            foreach ($video_types as $type) :
                $count = new WP_Query(array(
                    'post_type' => 'retro_videos',
                    'meta_query' => array(
                        array(
                            'key' => '_video_type',
                            'value' => $type,
                            'compare' => '='
                        )
                    ),
                    'posts_per_page' => -1
                ));
                ?>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center h-100" style="background: #2a2a2a; border: 2px solid <?php echo $colors[$type]; ?>;">
                        <div class="card-body">
                            <h4 class="card-title mb-2" style="color: <?php echo $colors[$type]; ?>; font-size: 1.8rem;">
                                <?php echo $count->found_posts; ?>
                            </h4>
                            <p class="card-text text-white small">
                                <?php echo $type_names[$type]; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
            endforeach;
            ?>
        </div>
    </section>
</div>

<!-- Bootstrap Video Modal -->
<div class="modal fade my-5" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: #1a1a1a; border: 2px solid #ff00ff;">
            <div class="modal-header" style="border-bottom: 1px solid #ff00ff; background: rgba(255, 0, 255, 0.1);">
                <h5 class="modal-title" id="videoModalLabel" style="color: #ff00ff;">
                    📺 Video Player
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="bootstrap-youtube-frame" src="" frameborder="0" allowfullscreen allow="autoplay"></iframe>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #ff00ff; background: rgba(255, 0, 255, 0.1);">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span id="video-title-display" class="text-muted"></span>
                    <div>
                        <a id="youtube-link" href="#" target="_blank" class="btn btn-sm me-2" style="background: #ff0000; color: white; border: none;">
                            📺 Watch on YouTube
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap Modal Event Handlers
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    const iframe = document.getElementById('bootstrap-youtube-frame');
    const modalTitle = document.getElementById('videoModalLabel');
    const videoTitleDisplay = document.getElementById('video-title-display');
    const youtubeLink = document.getElementById('youtube-link');

    if (videoModal) {
        // When modal is about to show
        videoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const youtubeId = button.getAttribute('data-youtube-id');
            const videoTitle = button.getAttribute('data-video-title');
            
            console.log('Loading video:', youtubeId, videoTitle);
            
            // Update iframe source with autoplay
            iframe.src = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1&rel=0';
            
            // Update modal title and footer
            modalTitle.textContent = '📺 ' + videoTitle;
            videoTitleDisplay.textContent = videoTitle;
            youtubeLink.href = 'https://www.youtube.com/watch?v=' + youtubeId;
        });

        // When modal is hidden, stop the video
        videoModal.addEventListener('hidden.bs.modal', function() {
            iframe.src = '';
            modalTitle.textContent = '📺 Video Player';
            videoTitleDisplay.textContent = '';
            youtubeLink.href = '#';
        });
    }
});

// Filter functionality
jQuery(document).ready(function($) {
    $('#filter-videos').on('click', function() {
        const selectedType = $('#video-type-filter').val();
        const videoCards = $('.video-card').closest('.col-lg-4, .col-md-6, .col-sm-12');
        
        if (selectedType === '') {
            videoCards.show();
        } else {
            videoCards.hide();
            videoCards.find('.video-card[data-type="' + selectedType + '"]').closest('.col-lg-4, .col-md-6, .col-sm-12').show();
        }
    });
});
</script>



<?php get_footer(); ?>
