<?php
$rom_url = arcade_hub_get_rom_url(get_the_ID());
$emulator_type = get_post_meta(get_the_ID(), '_game_emulator_type', true);
$developer = get_post_meta(get_the_ID(), '_game_developer', true);
$publisher = get_post_meta(get_the_ID(), '_game_publisher', true);

// Get taxonomies
$consoles = get_the_terms(get_the_ID(), 'console');
$genres = get_the_terms(get_the_ID(), 'game_genre');
$years = get_the_terms(get_the_ID(), 'game_year');
?>

<div class="game-card">
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('game-thumbnail'); ?>
        </a>
    <?php else : ?>
        <div style="width: 100%; height: 150px; background: linear-gradient(45deg, #333, #555); display: flex; align-items: center; justify-content: center; color: #999; margin-bottom: 15px; border-radius: 8px;">
            🎮 No Image
        </div>
    <?php endif; ?>
    
    <h3>
        <a href="<?php the_permalink(); ?>" style="color: #00ffff; text-decoration: none;">
            <?php the_title(); ?>
        </a>
    </h3>
    
    <!-- Game Meta Info -->
    <div style="margin: 10px 0; font-size: 0.8rem;">
        <?php if ($consoles) : ?>
            <div style="color: #ffff00; margin-bottom: 5px;">
                🕹️ <?php echo esc_html($consoles[0]->name); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($genres) : ?>
            <div style="color: #ff8000; margin-bottom: 5px;">
                🎯 <?php echo esc_html($genres[0]->name); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($years) : ?>
            <div style="color: #ff00ff; margin-bottom: 5px;">
                📅 <?php echo esc_html($years[0]->name); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($developer) : ?>
            <div style="color: #00ff00; margin-bottom: 5px;">
                👨‍💻 <?php echo esc_html($developer); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <p style="color: #ccc; font-size: 0.9rem; margin-bottom: 15px;">
        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
    </p>
    
    <div style="display: flex; gap: 10px; justify-content: center;">
        <?php if ($rom_url) : ?>
            <button class="play-button" onclick="playGame('<?php echo esc_js($rom_url); ?>', '<?php echo esc_js($emulator_type); ?>')" style="font-size: 0.9rem;">
                🕹️ Play
            </button>
        <?php endif; ?>
        
        <a href="<?php the_permalink(); ?>" class="retro-button" style="font-size: 0.9rem; padding: 8px 16px;">
            📖 Details
        </a>
    </div>
</div>
