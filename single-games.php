<?php get_header(); ?>

<div class="single-game-page container-fluid mt-5">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $rom_url = arcade_hub_get_rom_url(get_the_ID());
        $emulator_type = get_post_meta(get_the_ID(), '_game_emulator_type', true);
        $trivia = get_post_meta(get_the_ID(), '_game_trivia', true);
        $developer = get_post_meta(get_the_ID(), '_game_developer', true);
        $publisher = get_post_meta(get_the_ID(), '_game_publisher', true);
        
        // Get taxonomies
        $consoles = get_the_terms(get_the_ID(), 'console');
        $genres = get_the_terms(get_the_ID(), 'game_genre');
        $years = get_the_terms(get_the_ID(), 'game_year');
        ?>
        
        <section class="border-radius section-bg border-yellow border-yellow-glow p-lg-4 p-2">
            <?php if ($rom_url) : ?>
                <!-- Retro TV Emulator Section -->
                <div class="retro-tv-container">
                    <div class="text-center mb-4">
                        <h2 style="color: #00ffff; font-size: 2.2rem;">
                            🕹️ Play <?php the_title(); ?> Now!
                        </h2>
                    </div>
                    
                    <div class="retro-tv">
                        <div class="tv-screen">
                            <div class="tv-static"></div>
                            <div class="tv-loading">
                                <div class="loading-text">CLICK TO START GAME</div>
                                <div style="margin-top: 15px; font-size: 1rem; color: #00ff00; cursor: pointer;" onclick="loadGameInTV('<?php echo esc_js($rom_url); ?>', '<?php echo esc_js($emulator_type); ?>')">
                                    ▶️ POWER ON
                                </div>
                            </div>
                            <iframe id="tv-emulator" class="retro-emulator" style="display: none;" frameborder="0"></iframe>
                        </div>
                        <div class="tv-controls">
                            <div class="tv-knob" title="Volume" onclick="toggleGameAudio()"></div>
                            <div class="tv-knob" title="Reset" onclick="resetGame()"></div>
                        </div>
                        <div class="retro-tv-brand">ARCADE-HUB</div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button class="btn play-button me-3 mb-2" onclick="playGame('<?php echo esc_js($rom_url); ?>', '<?php echo esc_js($emulator_type); ?>')">
                            🖥️ Fullscreen Mode
                        </button>
                        <button class="btn retro-button mb-2" onclick="shareGame()">
                            📤 Share Game
                        </button>
                    </div>
                </div>
                
                <!-- Game Controls Info -->
                <div class="card mx-auto mt-4" style="background: #2a2a2a; border: 2px solid #00ff00; max-width: 600px;">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-3" style="color: #00ff00;">🎮 How to Play</h3>
                        <div class="row g-3 text-center">
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="text-muted small">
                                    <strong style="color: #ffff00;">Arrow Keys:</strong><br>Move/D-Pad
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="text-muted small">
                                    <strong style="color: #ffff00;">Z Key:</strong><br>A Button
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="text-muted small">
                                    <strong style="color: #ffff00;">X Key:</strong><br>B Button
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="text-muted small">
                                    <strong style="color: #ffff00;">Enter:</strong><br>Start
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="text-muted small">
                                    <strong style="color: #ffff00;">Shift:</strong><br>Select
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="text-muted small">
                                    <strong style="color: #ffff00;">Spacebar:</strong><br>Pause
                                </div>
                            </div>
                        </div>
                        <p class="text-center text-muted small mt-3 mb-0">
                            💡 Tip: Click on the TV screen first to ensure game controls work properly!
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="row g-4 align-items-start <?php echo $rom_url ? 'mt-4' : ''; ?>">
                <!-- Game Image and Info Section -->
                <div class="col-lg-4 col-md-5 col-sm-12">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="text-center mb-4">
                            <?php the_post_thumbnail('game-screenshot', array('class' => 'img-fluid rounded', 'style' => 'border: 3px solid #00ffff; width: 100%; height: auto;')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Game Specs -->
                    <div class="card" style="background: #2a2a2a; border: 2px solid #ffff00;">
                        <div class="card-header text-center" style="background: rgba(255, 255, 0, 0.1); border-bottom: 1px solid #ffff00;">
                            <h3 class="card-title mb-0" style="color: #ffff00;">📋 Game Info</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($consoles) : ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
                                    <strong style="color: #00ff00;">🕹️ Console:</strong>
                                    <span class="text-muted"><?php echo esc_html($consoles[0]->name); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($genres) : ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
                                    <strong style="color: #00ff00;">🎯 Genre:</strong>
                                    <span class="text-muted"><?php echo esc_html($genres[0]->name); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($years) : ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
                                    <strong style="color: #00ff00;">📅 Year:</strong>
                                    <span class="text-muted"><?php echo esc_html($years[0]->name); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($developer) : ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
                                    <strong style="color: #00ff00;">👨‍💻 Developer:</strong>
                                    <span class="text-muted"><?php echo esc_html($developer); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($publisher) : ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
                                    <strong style="color: #00ff00;">🏢 Publisher:</strong>
                                    <span class="text-muted"><?php echo esc_html($publisher); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($emulator_type) : ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong style="color: #00ff00;">⚙️ Emulator:</strong>
                                    <span class="text-muted text-uppercase"><?php echo esc_html($emulator_type); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Game Details -->
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <h1 class="mb-4" style="color: #00ffff; font-size: 2.5rem; text-shadow: 0 0 15px #00ffff;">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="game-description text-muted mb-4" style="line-height: 1.8;">
                        <?php the_content(); ?>
                    </div>
                    
                    <?php if ($trivia) : ?>
                        <div class="card mt-4" style="background: linear-gradient(135deg, #1a1a1a, #2a1a2a); border: 2px solid #ff8000;">
                            <div class="card-header" style="background: rgba(255, 128, 0, 0.1); border-bottom: 1px solid #ff8000;">
                                <h3 class="card-title mb-0" style="color: #ff8000;">🧠 Fun Trivia</h3>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-muted mb-0" style="line-height: 1.6;">
                                    <?php echo wp_kses_post($trivia); ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <!-- Screenshots Gallery -->
        <?php
        $gallery = get_post_gallery(get_the_ID(), false);
        if (!empty($gallery['ids'])) :
            $image_ids = explode(',', $gallery['ids']);
            ?>
            <section class="my-5">
                <div class="container-fluid">
                    <h2 class="text-center mb-4" style="color: #ff00ff; font-size: 2rem; text-shadow: 0 0 10px #ff00ff;">
                        📸 Screenshots
                    </h2>
                    
                    <div class="row g-3">
                        <?php foreach ($image_ids as $image_id) : ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="screenshot-container" style="position: relative; overflow: hidden; border-radius: 8px; transition: all 0.3s ease;">
                                    <img src="<?php echo wp_get_attachment_image_url($image_id, 'medium'); ?>" 
                                         alt="<?php echo get_post_meta($image_id, '_wp_attachment_image_alt', true); ?>"
                                         class="img-fluid w-100" 
                                         style="border: 2px solid #ff00ff; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                                         onclick="openScreenshot('<?php echo wp_get_attachment_image_url($image_id, 'large'); ?>')">
                                    <div class="screenshot-overlay" style="
                                        position: absolute; 
                                        top: 0; 
                                        left: 0; 
                                        right: 0; 
                                        bottom: 0; 
                                        background: rgba(255, 0, 255, 0.1); 
                                        opacity: 0; 
                                        transition: opacity 0.3s ease;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                    ">
                                        <span style="color: #fff; font-size: 2rem;">🔍</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Related Games -->
        <section class="arcade-section my-5" style="border-color: #00ff00; border-width: 2px; border-style: solid;">
            <div class="container-fluid">
                <h2 class="text-center mb-4" style="color: #00ff00; font-size: 2rem; text-shadow: 0 0 10px #00ff00;">
                    🎮 More Games Like This
                </h2>
                
                <div class="row g-4">
                    <?php
                    $related_args = array(
                        'post_type' => 'games',
                        'posts_per_page' => 4,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'rand'
                    );
                    
                    // Try to get games from the same console first
                    if ($consoles) {
                        $related_args['tax_query'] = array(
                            array(
                                'taxonomy' => 'console',
                                'field' => 'term_id',
                                'terms' => $consoles[0]->term_id
                            )
                        );
                    }
                    
                    $related_games = new WP_Query($related_args);
                    
                    if ($related_games->have_posts()) :
                        while ($related_games->have_posts()) : $related_games->the_post(); ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <?php get_template_part('template-parts/game-card'); ?>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <div class="col-12">
                            <p class="text-center text-muted">No related games found yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
    <?php endwhile; ?>
</div>

<!-- Screenshot Modal -->
<div id="screenshot-modal" class="game-modal">
    <div class="game-modal-content" style="max-width: 95vw; max-height: 95vh;">
        <button class="close-modal" onclick="closeScreenshot()">&times;</button>
        <img id="screenshot-image" src="" style="max-width: 100%; max-height: 80vh; border: 2px solid #ff00ff;">
    </div>
</div>

<script>
function openScreenshot(imageUrl) {
    document.getElementById('screenshot-image').src = imageUrl;
    document.getElementById('screenshot-modal').classList.add('active');
}

function closeScreenshot() {
    document.getElementById('screenshot-modal').classList.remove('active');
    document.getElementById('screenshot-image').src = '';
}

// Close modal when clicking outside
document.getElementById('screenshot-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeScreenshot();
    }
});
</script>

<?php get_footer(); ?>
