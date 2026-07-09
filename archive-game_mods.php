<?php get_header(); ?>

<div class="archive-mods-page container mt-5">
    <section class="p-lg-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h1 class="text-center text-md-start mb-2 mb-md-0 text-orange text-shadow-white">
                🔧 Game Mods
            </h1>
            <div class="text-muted fs-5">
                <?php
                global $wp_query;
                echo $wp_query->found_posts . ' mods found';
                ?>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="glass glass-border rounded-4 p-4 mb-4">
            <div class="card-body">
                <h3 class="card-title mb-3 text-yellow">🎯 Filter Mods</h3>

                <form method="GET">
                    <div class="row g-3">
                        <!-- Base Game Filter -->
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label class="form-label">🎮 Base Game:</label>
                            <select name="base_game" class="form-select">
                                <option value="">All Games</option>
                                <?php
                                $base_games = get_terms(array(
                                    'taxonomy' => 'base_game',
                                    'hide_empty' => true
                                ));
                                foreach ($base_games as $game) :
                                    $selected = isset($_GET['base_game']) && $_GET['base_game'] == $game->slug ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo esc_attr($game->slug); ?>" <?php echo $selected; ?>>
                                        <?php echo esc_html($game->name); ?> (<?php echo $game->count; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label class="form-label text-green">🏷️ Category:</label>
                            <select name="mod_category" class="form-select">
                                <option value="">All Categories</option>
                                <?php
                                $mod_categories = get_terms(array(
                                    'taxonomy' => 'mod_category',
                                    'hide_empty' => true
                                ));
                                foreach ($mod_categories as $category) :
                                    $selected = isset($_GET['mod_category']) && $_GET['mod_category'] == $category->slug ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo esc_attr($category->slug); ?>" <?php echo $selected; ?>>
                                        <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Type Filter -->
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label class="form-label text-green">⚙️ Type:</label>
                            <select name="mod_type" class="form-select">
                                <option value="">All Types</option>
                                <?php
                                $mod_types = get_terms(array(
                                    'taxonomy' => 'mod_type',
                                    'hide_empty' => true
                                ));
                                foreach ($mod_types as $type) :
                                    $selected = isset($_GET['mod_type']) && $_GET['mod_type'] == $type->slug ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo esc_attr($type->slug); ?>" <?php echo $selected; ?>>
                                        <?php echo esc_html($type->name); ?> (<?php echo $type->count; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Sort By -->
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label class="form-label" style="color: #00ff00;">📊 Sort By:</label>
                            <select name="sort_by" class="form-select">
                                <?php
                                $current_sort = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';
                                $sort_options = array(
                                    'date' => 'Newest First',
                                    'date_asc' => 'Oldest First',
                                    'title' => 'A-Z',
                                    'title_desc' => 'Z-A',
                                    'popular' => 'Most Popular'
                                );
                                foreach ($sort_options as $value => $label) :
                                    $selected = $current_sort == $value ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php echo $selected; ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Submit -->
                        <div class="col-lg-3 col-md-3 col-sm-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-lg btn-retro btn-green w-100">
                                🔍 Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <?php if (isset($_GET['base_game']) || isset($_GET['mod_category']) || isset($_GET['mod_type'])) : ?>
            <div class="alert mb-3" style="background: #2a2a1a; border: 1px solid #ffff00; border-radius: 6px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="color: #ffff00;">
                        <strong>Active Filters:</strong>
                        <?php
                        $active_filters = array();
                        if (!empty($_GET['base_game'])) {
                            $game = get_term_by('slug', $_GET['base_game'], 'base_game');
                            if ($game) $active_filters[] = $game->name;
                        }
                        if (!empty($_GET['mod_category'])) {
                            $category = get_term_by('slug', $_GET['mod_category'], 'mod_category');
                            if ($category) $active_filters[] = $category->name;
                        }
                        if (!empty($_GET['mod_type'])) {
                            $type = get_term_by('slug', $_GET['mod_type'], 'mod_type');
                            if ($type) $active_filters[] = $type->name;
                        }
                        echo '<span class="text-muted"> ' . implode(', ', $active_filters) . '</span>';
                        ?>
                    </div>
                    <a href="<?php echo get_post_type_archive_link('game_mods'); ?>" class="text-decoration-none" style="color: #ff6600;">
                        ✖ Clear All
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Mods Grid -->
        <?php if (have_posts()) : ?>
            <div class="row g-4">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    // Get mod meta
                    $mod_version = get_post_meta(get_the_ID(), '_mod_version', true);
                    $mod_author = get_post_meta(get_the_ID(), '_mod_author', true);
                    $base_games = get_the_terms(get_the_ID(), 'base_game');
                    $mod_categories = get_the_terms(get_the_ID(), 'mod_category');
                    $mod_files = get_post_meta(get_the_ID(), '_mod_files', true) ?: array();
                    ?>
                    
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="glass glass-border rounded-3 h-100 d-flex flex-column p-4">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('game-thumbnail', array('class' => 'card-img-top mb-3 rounded-2', 'style' => 'height: 200px; object-fit: cover;')); ?>
                                </a>
                            <?php else : ?>
                                <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(45deg, #2a2a2a, #3a3a3a); border-bottom: 2px solid #ff8000;">
                                    <span style="color: #ff8000; font-size: 3rem;">🔧</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-3" style="color: #ff8000;">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none" style="color: inherit;">
                                        <?php the_title(); ?>
                                    </a>
                                </h5>
                                
                                <!-- Mod Info Tags -->
                                <div class="mb-3">
                                    <?php if ($base_games) : ?>
                                        <span class="badge me-1 mb-1" style="background: #2a2a2a; color: #00ff00;">
                                            🎮 <?php echo esc_html($base_games[0]->name); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($mod_categories) : ?>
                                        <span class="badge me-1 mb-1" style="background: #2a2a2a; color: #ffff00;">
                                            🏷️ <?php echo esc_html($mod_categories[0]->name); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($mod_version) : ?>
                                        <span class="badge me-1 mb-1" style="background: #2a2a2a; color: #ff00ff;">
                                            📦 v<?php echo esc_html($mod_version); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($mod_author) : ?>
                                    <div class="small mb-2">
                                        <strong>👨‍💻 By:</strong> <?php echo esc_html($mod_author); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <p class="card-text text-white small mb-3 flex-grow-1">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </p>
                                
                                <!-- Download Count -->
                                <?php if (!empty($mod_files)) : ?>
                                    <div class="small mb-3" style="color: #00ffff;">
                                        📥 <?php echo count($mod_files); ?> download<?php echo count($mod_files) != 1 ? 's' : ''; ?> available
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-retro btn-orange w-100 mt-auto">
                                    🔧 View Mod Details
                                </a>
                            </div>
                        </div>
                    </div>
                    
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <div class="text-center mt-5">
                <?php
                echo paginate_links(array(
                    'prev_text' => '⬅️ Previous',
                    'next_text' => 'Next ➡️',
                    'mid_size' => 2
                ));
                ?>
            </div>
            
        <?php else : ?>
            <div class="text-center py-5">
                <div class="text-muted" style="font-size: 4rem; margin-bottom: 20px;">🔧</div>
                <h2 class="mb-3" style="color: #ff8000;">No Mods Found</h2>
                <p class="text-muted mb-3">
                    <?php if (isset($_GET['base_game']) || isset($_GET['mod_category']) || isset($_GET['mod_type'])) : ?>
                        Try adjusting your filters or <a href="<?php echo get_post_type_archive_link('game_mods'); ?>" style="color: #ff8000;">browse all mods</a>.
                    <?php else : ?>
                        No mods have been added yet. Check back soon!
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php get_footer(); ?>
