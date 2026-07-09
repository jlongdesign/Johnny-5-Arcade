<!-- Look for the game in our database -->
<?php if ($game_title) : ?>
    <?php
    $game_search = new WP_Query(array(
        'post_type' => 'games',
        's' => $game_title,
        'posts_per_page' => 1
    ));
    
    if ($game_search->have_posts()) :
        $game_search->the_post();
        $rom_url = arcade_hub_get_rom_url(get_the_ID());
        $emulator_type = get_post_meta(get_the_ID(), '_game_emulator_type', true);
        ?>
        <section class="arcade-section" style="border-color: #00ff00;">
            <h2 style="color: #00ff00; text-align: center; margin-bottom: 30px;">🎮 Play This Game</h2>
            
            <div style="text-align: center;">
                <?php if (has_post_thumbnail()) : ?>
                    <div style="margin-bottom: 20px;">
                        <?php the_post_thumbnail('medium', array('style' => 'border: 2px solid #00ff00; border-radius: 8px;')); ?>
                    </div>
                <?php endif; ?>
                
                <h3 style="color: #00ffff; margin-bottom: 15px;"><?php the_title(); ?></h3>
                <p style="color: #ccc; margin-bottom: 20px;">
                    <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                </p>
                
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <?php if ($rom_url) : ?>
                        <button class="play-button" onclick="playGame('<?php echo esc_js($rom_url); ?>', '<?php echo esc_js($emulator_type); ?>')">
                            🕹️ Play Game
                        </button>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="retro-button">
                        📖 Game Details
                    </a>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    endif;
    ?>
<?php endif; ?>