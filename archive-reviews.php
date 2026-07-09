<?php get_header(); ?>

<div class="reviews-archive-page container mt-5">
    <section class="reviews-section">
        <div class="text-center mb-5">
            <h1 class="mb-3 text-yellow text-shadow-yellow">
                ⭐ Retro Game Reviews
            </h1>
            <p class="text-white fs-5">
                Nostalgic reviews and memories from retro gaming enthusiasts!
            </p>
        </div>
        
        <?php if (have_posts()) : ?>
            <div class="row g-4">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $rating = get_post_meta(get_the_ID(), '_review_rating', true);
                    $game_title = get_post_meta(get_the_ID(), '_review_game_title', true);
                    $reviewer_name = get_post_meta(get_the_ID(), '_review_reviewer_name', true);
                    ?>
                    <div class="col-lg-6 col-md-12">
                        <div class="card glass-card glass-border rounded-4 h-100 review-card">
                            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center" style="background: rgba(255, 255, 0, 0.1); border-bottom: 1px solid #ffff00;">
                                <h5 class="card-title mb-2 mb-sm-0" style="color: #ffff00;">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none" style="color: inherit;">
                                        <?php echo esc_html($game_title ?: get_the_title()); ?>
                                    </a>
                                </h5>
                                <div class="review-rating">
                                    <?php echo arcade_hub_get_star_rating($rating); ?>
                                </div>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="text-center mb-3">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'img-fluid rounded', 'style' => 'border: 2px solid #ff8000; max-height: 200px; object-fit: cover;')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="review-content flex-grow-1 text-white py-3">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <div class="review-meta d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mt-auto pt-3" style="border-top: 1px solid #555;">
                                    <div class="mb-2 mb-sm-0">
                                        <small class="text-green d-block">📝 By: <?php echo esc_html($reviewer_name ?: get_the_author()); ?></small>
                                        <small class="text-yellow">📅 <?php echo get_the_date(); ?></small>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-retro btn-yellow text-dark btn-sm">
                                        💬 Read Full Review
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <div class="text-center mt-5">
                <?php
                echo paginate_links(array(
                    'prev_text' => '← Previous Reviews',
                    'next_text' => 'Next Reviews →',
                    'type' => 'list'
                ));
                ?>
            </div>
        <?php else : ?>
            <div class="row">
                <div class="col-12">
                    <div class="card review-card" style="background: #2a2a2a; border: 2px solid #ffff00;">
                        <div class="card-body text-center py-5">
                            <h2 class="card-title mb-4" style="color: #ffff00;">No reviews yet!</h2>
                            <p class="card-text text-white mb-4">
                                Be the first to share your retro gaming memories and reviews.
                            </p>
                            <a href="<?php echo admin_url('post-new.php?post_type=reviews'); ?>" class="btn retro-button">
                                ✍️ Write a Review
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Review Guidelines -->
        <div class="arcade-section mt-5" style="border-color: #ffff00;">
            <h3 class="text-center mb-4" style="color: #ffff00;">
                📜 Review Guidelines
            </h3>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 text-center" style="background: #2a2a2a; border: 2px solid #00ff00;">
                        <div class="card-body">
                            <h5 class="card-title mb-3" style="color: #00ff00;">🎮 Nostalgic</h5>
                            <p class="card-text text-muted small">Sharing memories and experiences with the game.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 text-center" style="background: #2a2a2a; border: 2px solid #ff8000;">
                        <div class="card-body">
                            <h5 class="card-title mb-3" style="color: #ff8000;">📝 Detailed</h5>
                            <p class="card-text text-muted small">Gameplay, graphics, sound, and what made it special.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 text-center" style="background: #2a2a2a; border: 2px solid #ff00ff;">
                        <div class="card-body">
                            <h5 class="card-title mb-3" style="color: #ff00ff;">⭐ Fairness</h5>
                            <p class="card-text text-muted small">Rated honestly and considered the game's historical context.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
