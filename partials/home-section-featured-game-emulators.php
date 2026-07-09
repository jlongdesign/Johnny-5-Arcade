    <!-- Featured Games Section -->
    <section class="p-lg-4 p-2">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="text-center mb-4">🎮 Featured Games</h2>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $featured_games = new WP_Query(array(
                    'post_type' => 'games',
                    'posts_per_page' => 8,
                    'meta_query' => array(
                        array(
                            'key' => '_featured_game',
                            'value' => 'yes',
                            'compare' => '='
                        )
                    )
                ));

                if ($featured_games->have_posts()) :
                    while ($featured_games->have_posts()) : $featured_games->the_post();
                        $rom_url = arcade_hub_get_rom_url(get_the_ID());
                        $emulator_type = get_post_meta(get_the_ID(), '_game_emulator_type', true);
                        ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card-bg card-animate border-radius border-cyan p-4 h-100 d-flex flex-column">
                                <div class="game-image-container mb-3">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('game-thumbnail', array('class' => 'img-fluid rounded')); ?>
                                    <?php else : ?>
                                        <div class="placeholder-image bg-secondary rounded d-flex align-items-center justify-content-center text-muted" style="height: 150px;">
                                            No Image
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="h5 mb-2 text-magenta"><?php the_title(); ?></h3>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </p>
                                
                                <div class="mt-auto">
                                    <?php if ($rom_url) : ?>
                                        <button class="play-button btn btn-primary w-100" onclick="playGame('<?php echo esc_js($rom_url); ?>', '<?php echo esc_js($emulator_type); ?>')">
                                            🕹️ Play Now
                                        </button>
                                    <?php else : ?>
                                        <a href="<?php the_permalink(); ?>" class="play-button btn btn-outline-primary w-100">
                                            📖 Learn More
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Show random games if no featured games
                    $random_games = new WP_Query(array(
                        'post_type' => 'games',
                        'posts_per_page' => 6,
                        'orderby' => 'rand'
                    ));
                    
                    if ($random_games->have_posts()) :
                        while ($random_games->have_posts()) : $random_games->the_post();
                            $rom_url = arcade_hub_get_rom_url(get_the_ID());
                            $emulator_type = get_post_meta(get_the_ID(), '_game_emulator_type', true);
                            ?>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="game-card h-100 d-flex flex-column">
                                    <div class="game-image-container mb-3">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('game-thumbnail', array('class' => 'img-fluid rounded')); ?>
                                        <?php else : ?>
                                            <div class="placeholder-image bg-secondary rounded d-flex align-items-center justify-content-center text-muted" style="height: 150px;">
                                                No Image
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h3 class="h5 mb-2"><?php the_title(); ?></h3>
                                    <p class="text-muted small mb-3 flex-grow-1">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <?php if ($rom_url) : ?>
                                            <button class="play-button btn btn-primary w-100" onclick="playGame('<?php echo esc_js($rom_url); ?>', '<?php echo esc_js($emulator_type); ?>')">
                                                🕹️ Play Now
                                            </button>
                                        <?php else : ?>
                                            <a href="<?php the_permalink(); ?>" class="play-button btn btn-outline-primary w-100">
                                                📖 Learn More
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<div class="col-12"><p class="text-center text-muted">No games available yet. Start adding some retro classics!</p></div>';
                    endif;
                endif;
                ?>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="<?php echo get_post_type_archive_link('games'); ?>" class="retro-button btn btn-lg">
                        🎯 Browse All Games
                    </a>
                </div>
            </div>
        </div>
    </section>