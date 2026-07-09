<!-- Retro TV Preview Section -->
    <section class="retro-tv-section p-lg-4 p-2 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="text-magenta text-shadow-magenta">📺 Retro TV Gallery</h2>
                    <p class="text-white text-shadow-magenta">
                        Take a trip down memory lane with classic gaming commercials and videos!
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <?php
                $retro_videos = new WP_Query(array(
                    'post_type' => 'retro_videos',
                    'posts_per_page' => 4,
                    'orderby' => 'rand'
                ));

                if ($retro_videos->have_posts()) :
                    while ($retro_videos->have_posts()) : $retro_videos->the_post();
                        $youtube_id = get_post_meta(get_the_ID(), '_video_youtube_id', true);
                        $video_type = get_post_meta(get_the_ID(), '_video_type', true);
                        ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <!-- Video Card -->
                            <div class="video-card card glass-card glass-border rounded-4 h-100 d-flex flex-column">
                                <div class="video-thumbnail-container">
                                    <?php if ($youtube_id) : ?>
                                        <div class="video-thumbnail rounded mb-3 overflow-hidden">
                                            <img class="img-fluid rounded w-100 ratio ratio-16x9" 
                                             src="https://img.youtube.com/vi/<?php echo esc_attr($youtube_id); ?>/maxresdefault.jpg" 
                                             alt="<?php the_title(); ?>"
                                             onclick="playVideo('<?php echo esc_js($youtube_id); ?>')"
                                             style="cursor: pointer; aspect-ratio: 16/9; object-fit: cover;">
                                        </div>
                                    <?php elseif (has_post_thumbnail()) : ?>
                                        <div class="video-thumbnail crt-overlay crt--flicker ratio ratio-16x9 rounded mb-3 overflow-hidden">
                                            <?php the_post_thumbnail('medium', array('class' => 'video-thumbnail img-fluid rounded w-100')); ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="video-thumbnail bg-secondary rounded d-flex align-items-center justify-content-center text-muted" style="aspect-ratio: 16/9;">
                                            📺 No Preview
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Video Info -->
                                <div id="video-info" class="flex-grow-1 d-flex flex-column px-3">
                                    <h4 class="video-title h6 mb-2"><?php the_title(); ?></h4>
                                    <p class="video-description text-white small mb-2 flex-grow-1">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                    </p>
                                    <span class="badge text-uppercase small" style="color: #ff8000; background: rgba(255, 128, 0, 0.2);">
                                        <?php echo esc_html($video_type ?: 'Video'); ?>
                                    </span>
                                </div>
                                
                                <div class="card-footer border-0 p-3">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-retro btn-magenta w-100">
                                        📼 Watch Now
                                    </a>  
                                </div><!-- end card-footer -->
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="col-12"><p class="text-center text-muted">No retro videos available yet. Start building your nostalgic collection!</p></div>';
                endif;
                ?>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="<?php echo get_post_type_archive_link('retro_videos'); ?>" class="btn btn-lg btn-retro btn-magenta">
                        📼 Watch More Videos
                    </a>
                </div>
            </div>
        </div>
    </section>