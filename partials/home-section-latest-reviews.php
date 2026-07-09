<!-- Latest Reviews Section -->
    <section id="home-latest-reviews" class="reviews-section mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="mb-4 text-yellow text-shadow-yellow">
                        🌟 Latest Reviews
                    </h2>
                </div>
            </div>
            
            <div class="row g-4 mb-3">
                <?php
                $latest_reviews = new WP_Query(array(
                    'post_type' => 'reviews',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($latest_reviews->have_posts()) :
                    while ($latest_reviews->have_posts()) : $latest_reviews->the_post();
                        $rating = get_post_meta(get_the_ID(), '_review_rating', true);
                        $game_title = get_post_meta(get_the_ID(), '_review_game_title', true);
                        $reviewer_name = get_post_meta(get_the_ID(), '_review_reviewer_name', true);
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card glass-card glass-border rounded-4 p-4 h-100 d-flex flex-column">
                                <div class="review-header d-flex justify-content-between align-items-start mb-3">
                                    <h3 class="review-game-title h5 mb-0"><?php echo esc_html($game_title ?: get_the_title()); ?></h3>
                                    <div class="review-rating">
                                        <?php echo arcade_hub_get_star_rating($rating); ?>
                                    </div>
                                </div>
                                
                                <div class="review-content flex-grow-1 mb-3 text-white">
                                    <?php echo wp_trim_words(get_the_content(), 50); ?>
                                </div>
                                
                                <div class="review-meta d-flex justify-content-between small text-green">
                                    <span>By: <?php echo esc_html($reviewer_name ?: get_the_author()); ?></span>
                                    <span><?php echo get_the_date(); ?></span>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-retro btn-yellow text-dark mt-3 align-self-center">
                                    📖 Read Review
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="col-12"><p class="text-center text-muted">No reviews yet. Be the first to share your retro gaming memories!</p></div>';
                endif;
                ?>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="<?php echo get_post_type_archive_link('reviews'); ?>" class="btn btn-lg btn-retro btn-yellow text-dark">
                        📚 Read All Reviews
                    </a>
                </div>
            </div>
        </div>
    </section>