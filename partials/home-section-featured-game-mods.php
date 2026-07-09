    <!-- Featured Game Mods Section -->
    <section id="home-game-mods" class="mb-5">
        <div class="container">

            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="text-orange text-shadow-orange">🛠️ Featured Game Mods</h2>
                    <p class="text-white text-shadow-cyan">
                        Transform your favorite classics with these amazing community modifications!
                    </p>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <?php
                // Get featured mods first, then fill with latest if needed
                $featured_mods = new WP_Query(array(
                    'post_type' => 'game_mods',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => '_featured_mod',
                            'value' => 'yes',
                            'compare' => '='
                        )
                    )
                ));

                $mod_ids_shown = array();
                $mods_to_display = array();
                $total_mods_needed = 8;

                // Collect featured mods
                if ($featured_mods->have_posts()) {
                    while ($featured_mods->have_posts()) {
                        $featured_mods->the_post();
                        $mod_ids_shown[] = get_the_ID();
                        $mods_to_display[] = array(
                            'post' => $GLOBALS['post'],
                            'featured' => true
                        );
                    }
                    wp_reset_postdata();
                }

                // Fill remaining slots with latest mods if needed
                if (count($mods_to_display) < $total_mods_needed) {
                    $latest_mods = new WP_Query(array(
                        'post_type' => 'game_mods',
                        'posts_per_page' => $total_mods_needed - count($mods_to_display),
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'post__not_in' => $mod_ids_shown
                    ));

                    if ($latest_mods->have_posts()) {
                        while ($latest_mods->have_posts()) {
                            $latest_mods->the_post();
                            $mods_to_display[] = array(
                                'post' => $GLOBALS['post'],
                                'featured' => false
                            );
                        }
                        wp_reset_postdata();
                    }
                }

                // Display the mods
                if (!empty($mods_to_display)) :
                    foreach ($mods_to_display as $mod_data) :
                        $GLOBALS['post'] = $mod_data['post'];
                        setup_postdata($mod_data['post']);
                        
                        // Get mod meta data
                        $mod_version = get_post_meta(get_the_ID(), '_mod_version', true);
                        $mod_author = get_post_meta(get_the_ID(), '_mod_author', true);
                        $base_games = wp_get_post_terms(get_the_ID(), 'base_game');
                        $mod_categories = wp_get_post_terms(get_the_ID(), 'mod_category');
                        $download_url = get_post_meta(get_the_ID(), '_mod_download_url', true);
                        $is_featured = $mod_data['featured'];
                        ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="mod-card card position-relative glass-card glass-border rounded-3 h-100 d-flex flex-column position-relative"
                                <?php if ($is_featured) : ?>
                                    <div class="position-absolute top-0 end-0 m-2"
                                    ">⭐ FEATURED</div>
                                <?php endif; ?>
                                
                                <!-- Mod thumbnail -->
                                <div class="text-center mb-3">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="rounded w-100 ratio ratio-16x9 lazyloaded">
                                            <?php the_post_thumbnail('medium', array(
                                                'class' => 'img-fluid w-100 h-100',
                                                'style' => 'object-fit: cover; transition: transform 0.3s ease;'
                                            )); ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="bg-secondary rounded mb-3 d-flex align-items-center justify-content-center text-white" style="height: 150px; border: 2px dashed #666;">
                                            🛠️ No Image
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body d-flex flex-column p-0">
                                    <!-- Mod title -->
                                    <h3 class="h6 px-3 text-orange">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <!-- Base game info -->
                                    <?php if ($base_games && !empty($base_games)) : ?>
                                        <div class="px-3 pb-3">
                                            <span class="badge bg-success small">🎮 FOR: <?php echo esc_html($base_games[0]->name); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Mod category -->
                                    <?php if ($mod_categories && !empty($mod_categories)) : ?>
                                        <div class="px-3 pt-3">
                                            <span class="badge" style="background: rgba(255, 128, 0, 0.2); color: #ff8000;">
                                                <?php echo esc_html($mod_categories[0]->name); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Mod description -->
                                    <p class="text-white small mb-0 flex-grow-1 p-3" style="border-top: 1px solid #444; padding-top: 10px;">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                    </p>
                                    
                                    <!-- Mod meta info -->
                                    <div class="d-flex justify-content-between align-items-center small px-3 pt-3" style="border-top: 1px solid #444; padding-top: 10px;">
                                        <?php if ($mod_version) : ?>
                                            <span style="color: #00ffff;">📦 v<?php echo esc_html($mod_version); ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if ($mod_author) : ?>
                                            <span style="color: #ffff00;">👨‍💻 <?php echo esc_html($mod_author); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Action buttons -->
                                    <div class="d-flex gap-2 mt-auto p-3">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-retro btn-orange d-flex flex-grow-1 align-items-center justify-content-center">📖&nbsp;Details</a>
                                        
                                        <?php if ($download_url) : ?>
                                            <a href="<?php echo esc_url($download_url); ?>" target="_blank" class="btn btn-sm btn-retro btn-green d-flex flex-grow-1 align-items-center justify-content-center stretched-link">
                                                ⬇️ Download
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div><!-- end of mod card -->
                        </div><!-- end of column -->
                        <?php
                    endforeach;
                    wp_reset_postdata();
                else :
                    echo '<div class="col-12"><p class="text-center text-muted">No game mods available yet. Start modding those classics!</p></div>';
                endif;
                ?>
            </div><!-- end of mods row -->
            
            <div class="row">
                <div class="col-12 text-center">
                    <a href="<?php echo get_post_type_archive_link('game_mods'); ?>" class="btn btn-retro btn-orange btn-lg" style="background: linear-gradient(135deg, #ff4400, #ff8800); border-color: #ff8000;">
                        🔧 Browse All Mods
                    </a>
                </div>
            </div><!-- end of browse all mods row -->
        </div><!-- end of container -->
    </section>