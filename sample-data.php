<?php
/**
 * Sample Data Installer for Arcade Hub Theme
 * Run this file once to populate the theme with demo content
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Install sample data for the Arcade Hub theme
 */
function arcade_hub_install_sample_data() {
    // Create sample consoles
    $consoles = array(
        'NES' => 'Nintendo Entertainment System',
        'SNES' => 'Super Nintendo Entertainment System',
        'Game Boy' => 'Nintendo Game Boy',
        'Game Boy Color' => 'Nintendo Game Boy Color',
        'Game Boy Advance' => 'Nintendo Game Boy Advance',
        'Genesis' => 'Sega Genesis',
        'Arcade' => 'Arcade Games'
    );
    
    foreach ($consoles as $slug => $name) {
        if (!term_exists($name, 'console')) {
            wp_insert_term($name, 'console', array('slug' => strtolower(str_replace(' ', '-', $slug))));
        }
    }
    
    // Create sample genres
    $genres = array(
        'Action', 'Adventure', 'RPG', 'Puzzle', 'Platformer', 
        'Racing', 'Sports', 'Fighting', 'Shooter', 'Strategy'
    );
    
    foreach ($genres as $genre) {
        if (!term_exists($genre, 'game_genre')) {
            wp_insert_term($genre, 'game_genre');
        }
    }
    
    // Create sample years
    $years = array('1985', '1986', '1987', '1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995');
    
    foreach ($years as $year) {
        if (!term_exists($year, 'game_year')) {
            wp_insert_term($year, 'game_year');
        }
    }
    
    // Create sample games
    $sample_games = array(
        array(
            'title' => 'Super Mario Bros.',
            'content' => 'The legendary platformer that saved the video game industry! Join Mario on his quest to rescue Princess Peach from Bowser\'s castle. Experience the game that defined a generation and established Nintendo as the king of gaming.',
            'console' => 'NES',
            'genre' => 'Platformer',
            'year' => '1985',
            'developer' => 'Nintendo',
            'publisher' => 'Nintendo',
            'emulator' => 'nes',
            'trivia' => 'Did you know? Super Mario Bros. was originally going to be called "Super Mario Brothers" but was shortened for the title screen. The game sold over 40 million copies worldwide!'
        ),
        array(
            'title' => 'The Legend of Zelda: A Link to the Past',
            'content' => 'An epic adventure through Hyrule! This masterpiece combines puzzle-solving, exploration, and action in a perfectly crafted world. Follow Link as he travels between the Light and Dark Worlds to save Princess Zelda.',
            'console' => 'SNES',
            'genre' => 'Adventure',
            'year' => '1991',
            'developer' => 'Nintendo',
            'publisher' => 'Nintendo',
            'emulator' => 'snes',
            'trivia' => 'A Link to the Past was the first Zelda game to feature the now-iconic "parallel worlds" concept, which became a staple of the series.'
        ),
        array(
            'title' => 'Tetris',
            'content' => 'The most addictive puzzle game ever created! Arrange falling blocks to create complete lines in this timeless classic. Simple to learn, impossible to master - Tetris has captured hearts for decades.',
            'console' => 'Game Boy',
            'genre' => 'Puzzle',
            'year' => '1989',
            'developer' => 'Nintendo',
            'publisher' => 'Nintendo',
            'emulator' => 'gb',
            'trivia' => 'Tetris was created by Russian programmer Alexey Pajitnov in 1984. The Game Boy version helped make both Tetris and the Game Boy incredibly popular worldwide.'
        ),
        array(
            'title' => 'Sonic the Hedgehog',
            'content' => 'Gotta go fast! Experience the blue blur\'s debut adventure as he races through Green Hill Zone and beyond to stop Dr. Robotnik. With incredible speed and attitude, Sonic became Sega\'s mascot and Nintendo\'s biggest rival.',
            'console' => 'Genesis',
            'genre' => 'Platformer',
            'year' => '1991',
            'developer' => 'Sonic Team',
            'publisher' => 'Sega',
            'emulator' => 'genesis',
            'trivia' => 'Sonic was originally going to be called "Mr. Needlemouse" during development. The character was designed to be Sega\'s answer to Mario.'
        ),
        array(
            'title' => 'Street Fighter II',
            'content' => 'The fighting game that started it all! Choose from 8 world warriors and battle your way to victory. With precise controls, balanced gameplay, and iconic characters, Street Fighter II revolutionized the fighting game genre.',
            'console' => 'Arcade',
            'genre' => 'Fighting',
            'year' => '1991',
            'developer' => 'Capcom',
            'publisher' => 'Capcom',
            'emulator' => 'arcade',
            'trivia' => 'Street Fighter II introduced the six-button control scheme that became standard for fighting games. The game generated over $10 billion in revenue!'
        ),
        array(
            'title' => 'Pokémon Red Version',
            'content' => 'Catch \'em all in this revolutionary RPG! Begin your journey as a Pokémon trainer, explore the Kanto region, and become the very best. With trading, battling, and collecting mechanics, Pokémon created a global phenomenon.',
            'console' => 'Game Boy',
            'genre' => 'RPG',
            'year' => '1995',
            'developer' => 'Game Freak',
            'publisher' => 'Nintendo',
            'emulator' => 'gb',
            'trivia' => 'Pokémon was originally conceived by Satoshi Tajiri based on his childhood hobby of collecting insects. The link cable feature was inspired by his vision of creatures traveling between Game Boys.'
        )
    );
    
    foreach ($sample_games as $game_data) {
        // Check if game already exists
        $existing_game = get_page_by_title($game_data['title'], OBJECT, 'games');
        if ($existing_game) continue;
        
        // Create the game post
        $game_id = wp_insert_post(array(
            'post_title' => $game_data['title'],
            'post_content' => $game_data['content'],
            'post_status' => 'publish',
            'post_type' => 'games',
            'post_excerpt' => wp_trim_words($game_data['content'], 20)
        ));
        
        if ($game_id) {
            // Add meta data
            update_post_meta($game_id, '_game_developer', $game_data['developer']);
            update_post_meta($game_id, '_game_publisher', $game_data['publisher']);
            update_post_meta($game_id, '_game_emulator_type', $game_data['emulator']);
            update_post_meta($game_id, '_game_trivia', $game_data['trivia']);
            update_post_meta($game_id, '_featured_game', 'yes');
            
            // Assign taxonomies
            wp_set_object_terms($game_id, $game_data['console'], 'console');
            wp_set_object_terms($game_id, $game_data['genre'], 'game_genre');
            wp_set_object_terms($game_id, $game_data['year'], 'game_year');
        }
    }
    
    // Create sample reviews
    $sample_reviews = array(
        array(
            'title' => 'Super Mario Bros. - A Timeless Classic',
            'content' => 'Playing Super Mario Bros. again after all these years brings back incredible memories. The tight controls, memorable music, and perfect level design still hold up today. This game taught me what video games could be - pure joy and imagination. Every jump feels perfect, every enemy placement is deliberate. It\'s amazing how something so simple could be so revolutionary. Nintendo truly created magic with this one.',
            'game_title' => 'Super Mario Bros.',
            'rating' => '5',
            'reviewer' => 'RetroGamer85'
        ),
        array(
            'title' => 'Zelda: A Link to the Past - Adventure Perfected',
            'content' => 'This is the game that made me fall in love with adventure games. The dual-world mechanic was mind-blowing back then, and it still impresses today. Every dungeon is a masterclass in design, every item feels meaningful. The story of Link\'s uncle, the Master Sword, the Seven Sages - it all comes together beautifully. Plus, that soundtrack! I still get chills hearing the main theme.',
            'game_title' => 'The Legend of Zelda: A Link to the Past',
            'rating' => '5',
            'reviewer' => 'HyruleExplorer'
        ),
        array(
            'title' => 'Tetris - Pure Addictive Gaming',
            'content' => 'There\'s something magical about Tetris that transcends time and age. My mom used to play this on our Game Boy for hours, and now I understand why. It\'s meditation through gaming - simple, elegant, and endlessly satisfying. The music gets stuck in your head for days, but you don\'t mind. Every cleared line feels like a small victory.',
            'game_title' => 'Tetris',
            'rating' => '5',
            'reviewer' => 'PuzzleMaster'
        )
    );
    
    foreach ($sample_reviews as $review_data) {
        // Check if review already exists
        $existing_review = get_page_by_title($review_data['title'], OBJECT, 'reviews');
        if ($existing_review) continue;
        
        $review_id = wp_insert_post(array(
            'post_title' => $review_data['title'],
            'post_content' => $review_data['content'],
            'post_status' => 'publish',
            'post_type' => 'reviews',
            'post_excerpt' => wp_trim_words($review_data['content'], 20)
        ));
        
        if ($review_id) {
            update_post_meta($review_id, '_review_rating', $review_data['rating']);
            update_post_meta($review_id, '_review_game_title', $review_data['game_title']);
            update_post_meta($review_id, '_review_reviewer_name', $review_data['reviewer']);
        }
    }
    
    // Create sample retro videos
    $sample_videos = array(
        array(
            'title' => 'Super Mario Bros. Commercial - 1985',
            'content' => 'Classic 80s commercial for Super Mario Bros. featuring the iconic "It\'s the Super Mario Brothers Super Show!" jingle. This commercial helped introduce Mario to American audiences.',
            'youtube_id' => 'dQw4w9WgXcQ', // Placeholder ID
            'type' => 'commercial'
        ),
        array(
            'title' => 'Street Fighter II Tournament Gameplay',
            'content' => 'Amazing tournament footage from the early 90s arcade scene. Watch as players pull off incredible combos and special moves in this classic fighting game.',
            'youtube_id' => 'dQw4w9WgXcQ', // Placeholder ID
            'type' => 'gameplay'
        ),
        array(
            'title' => 'The Making of Zelda: A Link to the Past',
            'content' => 'Rare behind-the-scenes footage and developer interviews about the creation of one of the greatest adventure games ever made.',
            'youtube_id' => 'dQw4w9WgXcQ', // Placeholder ID
            'type' => 'documentary'
        )
    );
    
    foreach ($sample_videos as $video_data) {
        // Check if video already exists
        $existing_video = get_page_by_title($video_data['title'], OBJECT, 'retro_videos');
        if ($existing_video) continue;
        
        $video_id = wp_insert_post(array(
            'post_title' => $video_data['title'],
            'post_content' => $video_data['content'],
            'post_status' => 'publish',
            'post_type' => 'retro_videos',
            'post_excerpt' => wp_trim_words($video_data['content'], 15)
        ));
        
        if ($video_id) {
            update_post_meta($video_id, '_video_youtube_id', $video_data['youtube_id']);
            update_post_meta($video_id, '_video_type', $video_data['type']);
        }
    }
    
    return true;
}

/**
 * Admin function to install sample data
 */
function arcade_hub_install_sample_data_admin() {
    if (current_user_can('manage_options')) {
        arcade_hub_install_sample_data();
        wp_redirect(admin_url('themes.php?arcade-sample-installed=1'));
        exit;
    }
}

// Add admin notice for sample data installation
function arcade_hub_sample_data_notice() {
    if (isset($_GET['arcade-sample-installed'])) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>Arcade Hub:</strong> Sample data installed successfully! Visit your site to see the retro gaming content.</p></div>';
    } elseif (get_template() === 'arcade-hub') {
        // Check if we have any games
        $games_count = wp_count_posts('games');
        if ($games_count->publish == 0) {
            echo '<div class="notice notice-info"><p><strong>Arcade Hub Theme:</strong> Want to see the theme in action? <a href="' . admin_url('admin.php?page=arcade-install-sample') . '">Install sample gaming content</a> to get started!</p></div>';
        }
    }
}
add_action('admin_notices', 'arcade_hub_sample_data_notice');

// Add admin menu for sample data installer
function arcade_hub_admin_menu() {
    add_theme_page(
        'Install Sample Data',
        'Arcade Sample Data',
        'manage_options',
        'arcade-install-sample',
        'arcade_hub_sample_data_page'
    );
}
add_action('admin_menu', 'arcade_hub_admin_menu');

// Sample data installer page
function arcade_hub_sample_data_page() {
    ?>
    <div class="wrap">
        <h1>🕹️ Arcade Hub - Install Sample Data</h1>
        <div class="notice notice-info">
            <p><strong>This will install sample retro gaming content including:</strong></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li>6 Classic games (Mario, Zelda, Tetris, Sonic, Street Fighter II, Pokémon)</li>
                <li>3 Nostalgic reviews</li>
                <li>3 Retro videos/commercials</li>
                <li>Gaming consoles, genres, and years taxonomy</li>
            </ul>
            <p><em>Note: This is safe to run multiple times - it won't create duplicates.</em></p>
        </div>
        
        <form method="post" action="<?php echo admin_url('admin.php?page=arcade-install-sample&install=1'); ?>">
            <?php wp_nonce_field('arcade_install_sample'); ?>
            <p>
                <button type="submit" class="button button-primary button-large">
                    🎮 Install Sample Gaming Content
                </button>
            </p>
        </form>
        
        <?php if (isset($_GET['install']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'arcade_install_sample')) : ?>
            <?php 
            arcade_hub_install_sample_data();
            echo '<div class="notice notice-success"><p><strong>Success!</strong> Sample data has been installed. <a href="' . home_url() . '">Visit your site</a> to see it in action!</p></div>';
            ?>
        <?php endif; ?>
        
        <hr>
        
        <h2>🎯 Next Steps</h2>
        <div style="background: #fff; border: 1px solid #ccd0d4; padding: 20px; border-radius: 4px;">
            <h3>1. Set up your navigation menu:</h3>
            <p>Go to <strong>Appearance → Menus</strong> and create a menu with links to:</p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li>Games Archive: <code><?php echo get_post_type_archive_link('games'); ?></code></li>
                <li>Retro TV: <code><?php echo get_post_type_archive_link('retro_videos'); ?></code></li>
                <li>Reviews: <code><?php echo get_post_type_archive_link('reviews'); ?></code></li>
            </ul>
            
            <h3>2. Add more content:</h3>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><strong>Games:</strong> Add ROM URLs for browser emulation</li>
                <li><strong>Videos:</strong> Add YouTube video IDs for retro commercials</li>
                <li><strong>Reviews:</strong> Write nostalgic reviews of your favorite games</li>
            </ul>
            
            <h3>3. Customize the theme:</h3>
            <ul style="list-style: disc; margin-left: 20px;">
                <li>Upload a custom logo in <strong>Appearance → Customize</strong></li>
                <li>Add game screenshots to create galleries</li>
                <li>Organize games by console, genre, and year</li>
            </ul>
        </div>
    </div>
    <?php
}
?>
