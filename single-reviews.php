<?php get_header(); ?>

<div class="single-review-page container mt-5">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $rating = get_post_meta(get_the_ID(), '_review_rating', true);
        $game_title = get_post_meta(get_the_ID(), '_review_game_title', true);
        $reviewer_name = get_post_meta(get_the_ID(), '_review_reviewer_name', true);
        ?>
        
        <section class="reviews-section mb-5">
            <div class="container">
                <div class="glass glass-border rounded-3 p-4 mb-4" style="max-width: 800px; margin: 0 auto;">
                    <div class="review-header">
                        <h1 class="review-game-title" style="font-size: 2.5rem; margin-bottom: 20px;">
                            <?php echo esc_html($game_title ?: get_the_title()); ?>
                        </h1>
                        <div class="review-rating" style="font-size: 1.5rem;">
                            <?php echo arcade_hub_get_star_rating($rating); ?>
                            <span style="color: #ccc; margin-left: 15px;">
                                <?php echo esc_html($rating); ?>/5 Stars
                            </span>
                        </div>
                    </div>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div style="text-align: center; margin: 30px 0;">
                            <?php the_post_thumbnail('large', array('style' => 'border: 3px solid #ff8000; border-radius: 10px; max-width: 100%; height: auto;')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="review-content text-white" style="font-size: 1.1rem; line-height: 1.8;">
                        <?php the_content(); ?>
                    </div>
                    
                    <hr>

                    <div class="review-meta">
                        <div class="row">
                            <div class="col-3">
                                <strong style="color: #ff8000;">📝 Reviewer:</strong><br>
                                <span style="color: #ccc;"><?php echo esc_html($reviewer_name ?: get_the_author()); ?></span>
                            </div>
                            <div class="col-3">
                                <strong style="color: #ff8000;">📅 Review Date:</strong><br>
                                <span style="color: #ccc;"><?php echo get_the_date(); ?></span>
                            </div>
                            <div class="col-3">
                                <strong style="color: #ff8000;">⭐ Rating:</strong><br>
                                <span style="color: #ccc;"><?php echo esc_html($rating); ?> out of 5 stars</span>
                            </div>
                            <?php if ($game_title) : ?>
                            <div class="col-3">
                                <strong style="color: #ff8000;">🎮 Game:</strong><br>
                                <span style="color: #ccc;"><?php echo esc_html($game_title); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div><!-- end container -->
        </section>
    
        
        <!-- Related Reviews -->
        <section class="reviews-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <h2 class="text-yellow text-shadow-yellow text-center">📚 More Reviews</h2>
                    </div>
                </div><!-- end row -->
                <div class="row g-4">
                    <?php
                    $related_reviews = new WP_Query(array(
                        'post_type' => 'reviews',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'rand'
                    ));
                    
                    if ($related_reviews->have_posts()) :
                        while ($related_reviews->have_posts()) : $related_reviews->the_post();
                            $related_rating = get_post_meta(get_the_ID(), '_review_rating', true);
                            $related_game_title = get_post_meta(get_the_ID(), '_review_game_title', true);
                            $related_reviewer_name = get_post_meta(get_the_ID(), '_review_reviewer_name', true);
                            ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="review-card glass-card glass-border rounded-3 h-100 mb-4 p-3">
                                    <div class="review-header">
                                        <h3 class="review-game-title me-2">
                                            <a href="<?php the_permalink(); ?>" style="color: #ffff00; text-decoration: none;">
                                                <?php echo esc_html($related_game_title ?: get_the_title()); ?>
                                            </a>
                                        </h3>
                                        <div class="review-rating">
                                            <?php echo arcade_hub_get_star_rating($related_rating); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="review-content mb-3">
                                        <?php echo wp_trim_words(get_the_content(), 30); ?>
                                    </div>
                                    
                                    <div class="review-meta d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-green">📝 By: <?php echo esc_html($related_reviewer_name ?: get_the_author()); ?></span>
                                        <span class="text-yellow">📅 <?php echo get_the_date(); ?></span>
                                    </div>

                                    <span class="d-block text-center">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-retro btn-yellow text-dark btn-sm">Read Full Review →</a>
                                    </span>
                                </div>
                            </div>
                            
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p style="color: #ccc; text-align: center;">No other reviews available yet.</p>';
                    endif;
                    ?>
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="<?php echo get_post_type_archive_link('reviews'); ?>" class="btn btn-retro btn-yellow text-dark btn-lg">
                            📚 Browse All Reviews
                        </a>
                    </div>
                </div><!-- end row -->
            </div><!-- end container -->
            
        </section>
        
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
