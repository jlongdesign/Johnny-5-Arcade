<?php get_header(); ?>

<div class="single-video-page container mt-5">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $youtube_id = get_post_meta(get_the_ID(), '_video_youtube_id', true);
        $video_type = get_post_meta(get_the_ID(), '_video_type', true);
        ?>
        
        <section class="arcade-section">
            <div class="container">
                <div class="text-center">
                    <a href="<?php echo get_post_type_archive_link('retro_videos'); ?>" class="text-warning text-decoration-none d-inline-block mb-4">
                        ← Back to Retro Videos
                    </a>
                </div>
                <h1 class="text-center mb-4 color-magenta text-shadow-magenta">
                    <?php the_title(); ?>
                </h1>
            
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-12">
                        <!-- YouTube Video Player -->
                        <?php if ($youtube_id) : ?>
                            <div class="text-center mb-4">
                                <div class="ratio ratio-16x9" style="border: 3px solid #ff00ff; border-radius: 10px; overflow: hidden;">
                                    <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($youtube_id); ?>" 
                                            frameborder="0" 
                                            allowfullscreen
                                            class="rounded"></iframe>
                                </div>
                            </div>
                        <?php elseif (has_post_thumbnail()) : ?>
                            <div class="text-center mb-4">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded', 'style' => 'border: 3px solid #ff00ff; max-width: 100%; height: auto;')); ?>
                            </div>
                        <?php endif; ?>
                
                        <!-- Video Info -->
                        <div class="card mb-4" style="background: #2a2a2a; border: 2px solid #ff8000;">
                            <div class="card-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <strong class="d-block" style="color: #ff8000;">📼 Video Type:</strong>
                                        <span class="text-white text-capitalize">
                                            <?php 
                                            $type_icons = array(
                                                'commercial' => '🎬 Commercial',
                                                'gameplay' => '🎮 Gameplay',
                                                'review' => '⭐ Review',
                                                'documentary' => '📚 Documentary'
                                            );
                                            echo $type_icons[$video_type] ?? '📺 Video'; 
                                            ?>
                                        </span>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <strong class="d-block" style="color: #ff8000;">📅 Published:</strong>
                                        <span class="text-white"><?php echo get_the_date(); ?></span>
                                    </div>
                                    <?php if ($youtube_id) : ?>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <strong class="d-block" style="color: #ff8000;">🔗 YouTube:</strong>
                                        <a href="https://www.youtube.com/watch?v=<?php echo esc_attr($youtube_id); ?>" 
                                        target="_blank" 
                                        class="text-decoration-none"
                                        style="color: #00ffff;">
                                            Watch on YouTube →
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <strong class="d-block" style="color: #ff8000;">👤 Added by:</strong>
                                        <span class="text-white"><?php the_author(); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Video Description -->
                        <div class="video-description text-white mb-4" style="line-height: 1.8;">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Share Section -->
                        <div class="card" style="background: linear-gradient(135deg, #1a1a1a, #2a1a2a); border: 2px solid #00ffff;">
                            <div class="card-body text-center">
                                <h3 class="card-title mb-3" style="color: #00ffff;">📤 Share This Video</h3>
                                <div class="d-flex gap-3 justify-content-center flex-wrap">
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode('Check out this retro gaming video: ' . get_the_title()); ?>" 
                                    target="_blank" 
                                    class="btn retro-button btn-sm">
                                        🐦 Twitter
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                    target="_blank" 
                                    class="btn retro-button btn-sm">
                                        📘 Facebook
                                    </a>
                                    <button onclick="copyToClipboard('<?php echo esc_js(get_permalink()); ?>')" 
                                            class="btn retro-button btn-sm">
                                        📋 Copy Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        
        <!-- Related Videos -->
        <section class="my-5 arcade-section">
            <div class="container">
                <h2 class="text-center mb-4" style="color: #ff00ff; font-size: 2rem; text-shadow: 0 0 10px #ff00ff;">
                    📺 More Retro Videos
                </h2>
                
                <div class="row g-4">
                    <?php
                    $related_videos = new WP_Query(array(
                        'post_type' => 'retro_videos',
                        'posts_per_page' => 4,
                        'post__not_in' => array(get_the_ID()),
                        'meta_query' => array(
                            array(
                                'key' => '_video_type',
                                'value' => $video_type,
                                'compare' => '='
                            )
                        )
                    ));
                    
                    // If no videos of the same type, get random videos
                    if (!$related_videos->have_posts()) {
                        $related_videos = new WP_Query(array(
                            'post_type' => 'retro_videos',
                            'posts_per_page' => 4,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand'
                        ));
                    }
                    
                    if ($related_videos->have_posts()) :
                        while ($related_videos->have_posts()) : $related_videos->the_post();
                            $related_youtube_id = get_post_meta(get_the_ID(), '_video_youtube_id', true);
                            $related_video_type = get_post_meta(get_the_ID(), '_video_type', true);
                            ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card h-100" style="background: #2a2a2a; border: 2px solid #ff00ff; position: relative; overflow: hidden;">
                                    <?php if ($related_youtube_id) : ?>
                                        <img class="card-img-top" 
                                             src="https://img.youtube.com/vi/<?php echo esc_attr($related_youtube_id); ?>/maxresdefault.jpg" 
                                             alt="<?php the_title(); ?>"
                                             style="height: 200px; object-fit: cover; cursor: pointer;"
                                             onclick="playVideo('<?php echo esc_js($related_youtube_id); ?>')">
                                        <div class="position-absolute top-50 start-50 translate-middle" style="font-size: 3rem; color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.8); pointer-events: none; z-index: 2;">
                                            ▶️
                                        </div>
                                    <?php elseif (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'card-img-top', 'style' => 'height: 200px; object-fit: cover;')); ?>
                                        </a>
                                    <?php else : ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(45deg, #333, #555); color: #999; font-size: 2rem;">
                                            📺
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h4 class="card-title">
                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none" style="color: #ff8000;">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <p class="card-text text-white flex-grow-1">
                                            <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <span class="badge" style="background-color: #ff00ff; color: white; font-size: 0.7rem;">
                                                <?php 
                                                $type_icons = array(
                                                    'commercial' => '🎬',
                                                    'gameplay' => '🎮',
                                                    'review' => '⭐',
                                                    'documentary' => '📚'
                                                );
                                                echo ($type_icons[$related_video_type] ?? '📺') . ' ' . esc_html($related_video_type ?: 'Video'); 
                                                ?>
                                            </span>
                                            <small class="text-white">
                                                <?php echo get_the_date(); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <div class="col-12">
                            <p class="text-center text-white">No related videos found.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?php echo get_post_type_archive_link('retro_videos'); ?>" class="btn btn-lg btn-retro btn-magenta">
                        📼 Browse All Videos
                    </a>
                </div>
            </div>
        </section>
        
    <?php endwhile; ?>
</div>

<!-- YouTube Player Modal -->
<div id="youtube-modal" class="game-modal">
    <div class="game-modal-content">
        <button class="close-modal" onclick="closeYouTubeModal()">&times;</button>
        <iframe id="youtube-frame" class="game-frame" src="" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<script>
function playVideo(youtubeId) {
    const modal = document.getElementById('youtube-modal');
    const iframe = document.getElementById('youtube-frame');
    iframe.src = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1';
    modal.classList.add('active');
}

function closeYouTubeModal() {
    const modal = document.getElementById('youtube-modal');
    const iframe = document.getElementById('youtube-frame');
    iframe.src = '';
    modal.classList.remove('active');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copied to clipboard! 📋');
    });
}

// Close modal when clicking outside
document.getElementById('youtube-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeYouTubeModal();
    }
});
</script>

<?php get_footer(); ?>
