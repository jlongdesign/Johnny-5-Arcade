<?php
// Enqueue game mods specific CSS
wp_enqueue_style('arcade-hub-game-mods', get_template_directory_uri() . '/css/game-mods.min.css', array('arcade-hub-style'), '1.0');
?>

<?php get_header(); ?>

<div class="single-mod-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        // Get mod meta data
        $mod_version = get_post_meta(get_the_ID(), '_mod_version', true);
        $mod_author = get_post_meta(get_the_ID(), '_mod_author', true);
        $mod_website = get_post_meta(get_the_ID(), '_mod_website', true);
        $mod_requirements = get_post_meta(get_the_ID(), '_mod_requirements', true);
        $install_instructions = get_post_meta(get_the_ID(), '_install_instructions', true);
        $mod_files = get_post_meta(get_the_ID(), '_mod_files', true) ?: array();
        
        // Get load order meta
        $load_order = get_post_meta(get_the_ID(), '_mod_load_order', true);
        $compatibility = get_post_meta(get_the_ID(), '_mod_compatibility', true);
        $conflicts = get_post_meta(get_the_ID(), '_mod_conflicts', true);
        $recommended_mods = get_post_meta(get_the_ID(), '_recommended_mods', true);
        
        // Get taxonomies
        $base_games = get_the_terms(get_the_ID(), 'base_game');
        $mod_categories = get_the_terms(get_the_ID(), 'mod_category');
        $mod_types = get_the_terms(get_the_ID(), 'mod_type');
        ?>
        
        <section id="mod-details" class="mt-5 mb-4">
            <div class="container">
                            <!-- Back Link -->
            <a href="<?php echo get_post_type_archive_link('game_mods'); ?>" class="text-warning text-decoration-none d-inline-block mb-4">
                ← Back to Game Mods
            </a>
                <div class="row g-4">
                <!-- Mod Sidebar -->
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="mod-sidebar">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="text-center mb-4">
                                <?php the_post_thumbnail('game-screenshot', array(
                                    'class' => 'img-fluid rounded',
                                    'style' => 'border: 3px solid #ff8000; width: 100%; height: auto;'
                                )); ?>
                            </div>
                            <?php endif; ?>
                        
                            <!-- Mod Specs -->
                            <div class="card mb-4" style="background: #2a2a2a; border: 2px solid #ff8000;">
                            <div class="card-header text-center" style="background: transparent; border-color: #ff8000;">
                                <h3 class="h5 mb-0" style="color: #ff8000;">🔧 Mod Info</h3>
                            </div>
                            <div class="card-body">
                                <?php if ($base_games) : ?>
                                    <div class="mb-3">
                                        <strong style="color: #00ff00;">🎮 Base Game:</strong>
                                        <span class="text-white"><?php echo esc_html($base_games[0]->name); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($mod_version) : ?>
                                    <div class="mb-3">
                                        <strong style="color: #00ff00;">📦 Version:</strong>
                                        <span class="text-white"><?php echo esc_html($mod_version); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($mod_author) : ?>
                                    <div class="mb-3">
                                        <strong style="color: #00ff00;">👨‍💻 Author:</strong>
                                        <span class="text-white"><?php echo esc_html($mod_author); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($mod_categories) : ?>
                                    <div class="mb-3">
                                        <strong style="color: #00ff00;">🏷️ Category:</strong>
                                        <span class="badge ms-2" style="background: rgba(255, 128, 0, 0.2); color: #ff8000;">
                                            <?php echo esc_html($mod_categories[0]->name); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($mod_types) : ?>
                                    <div class="mb-3">
                                        <strong style="color: #00ff00;">⚙️ Type:</strong>
                                        <span class="badge ms-2" style="background: rgba(0, 255, 0, 0.2); color: #00ff00;">
                                            <?php echo esc_html($mod_types[0]->name); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($mod_website) : ?>
                                    <div class="mb-0">
                                        <strong style="color: #00ff00;">🌐 Official:</strong>
                                        <a href="<?php echo esc_url($mod_website); ?>" target="_blank" class="btn btn-link p-0 text-info">
                                            Visit Mod Page
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            </div>
                        
                            <!-- Load Order & Compatibility -->
                            <?php if ($load_order || $compatibility || $conflicts || $recommended_mods) : ?>
                            <div class="card" style="background: #2a1a2a; border: 2px solid #ffff00;">
                                <div class="card-header text-center" style="background: transparent; border-color: #ffff00;">
                                    <h3 class="h5 mb-0" style="color: #ffff00;">📋 Load Order & Compatibility</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($load_order) : ?>
                                        <div class="mb-3">
                                            <strong style="color: #00ff00;">🔢 Suggested Load Order:</strong>
                                            <pre class="bg-dark text-muted p-2 rounded mt-2 small" style="white-space: pre-wrap;"><?php echo esc_html($load_order); ?></pre>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($compatibility) : ?>
                                        <div class="mb-3">
                                            <strong style="color: #00ff00;">✅ Compatibility:</strong>
                                            <p class="text-white mt-2 mb-0"><?php echo wp_kses_post($compatibility); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($conflicts) : ?>
                                        <div class="mb-3">
                                            <strong style="color: #ff6600;">⚠️ Known Conflicts:</strong>
                                            <p class="mt-2 mb-0" style="color: #ffcc99;"><?php echo wp_kses_post($conflicts); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($recommended_mods) : ?>
                                        <div class="mb-0">
                                            <strong style="color: #00ff00;">💡 Recommended Combinations:</strong>
                                            <p class="text-white mt-2 mb-0"><?php echo wp_kses_post($recommended_mods); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Mod Content -->
                <div class="col-12 col-md-8 col-lg-8">
                    <div class="mod-content">
                        <h1 class="display-4 mb-4" style="color: #ff8000; text-shadow: 0 0 15px #ff8000;">
                            <?php the_title(); ?>
                        </h1>
                        
                        <div class="mod-description text-white mb-4" style="line-height: 1.8;">
                            <?php the_content(); ?>
                        </div>
                
                        <!-- Requirements Accordion -->
                        <?php if ($mod_requirements) : ?>
                            <div class="accordion mb-4" id="requirementsAccordion">
                                <div class="accordion-item" style="background: linear-gradient(135deg, #1a1a1a, #2a2a1a); border: 2px solid #ffff00;">
                                    <h2 class="accordion-header mb-0" id="requirementsHeading">
                                        <button class="accordion-button collapsed" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#requirementsCollapse" 
                                                aria-expanded="false" 
                                                aria-controls="requirementsCollapse"
                                                style="background: linear-gradient(135deg, #2a2a1a, #3a3a2a); color: #ffff00; border: none; border-radius: 0;">
                                            <span class="">
                                                ⚡ Requirements
                                                <span class="badge" style="background: #ffff00; color: #000;">
                                                    <?php echo substr_count($mod_requirements, "\n") + 1; ?> items
                                                </span>
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="requirementsCollapse" 
                                        class="accordion-collapse collapse" 
                                        aria-labelledby="requirementsHeading" 
                                        data-bs-parent="#requirementsAccordion">
                                        <div class="accordion-body text-white" style="background: linear-gradient(135deg, #1a1a1a, #2a2a1a);">
                                            <?php 
                                            // Split requirements into list items for better formatting
                                            $requirements_list = explode("\n", $mod_requirements);
                                            if (count($requirements_list) > 1) :
                                                echo '<ul class="list-unstyled mb-0">';
                                                foreach ($requirements_list as $requirement) :
                                                    $requirement = trim($requirement);
                                                    if (!empty($requirement)) :
                                                        // Add emoji based on requirement type
                                                        $emoji = '🔹';
                                                        if (stripos($requirement, 'dlc') !== false || stripos($requirement, 'expansion') !== false) {
                                                            $emoji = '📦';
                                                        } elseif (stripos($requirement, 'mod') !== false) {
                                                            $emoji = '🔧';
                                                        } elseif (stripos($requirement, 'version') !== false || stripos($requirement, 'patch') !== false) {
                                                            $emoji = '🆙';
                                                        } elseif (stripos($requirement, 'space') !== false || stripos($requirement, 'gb') !== false || stripos($requirement, 'mb') !== false) {
                                                            $emoji = '💾';
                                                        }
                                                        echo '<li class="mb-2"><span style="color: #ffff00;">' . $emoji . '</span> ' . esc_html($requirement) . '</li>';
                                                    endif;
                                                endforeach;
                                                echo '</ul>';
                                            else :
                                                echo wp_kses_post(nl2br($mod_requirements));
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Download Files -->
                        <?php if (!empty($mod_files)) : ?>
                            <div class="card mb-4" style="background: linear-gradient(135deg, #0a1a0a, #1a2a1a); border: 2px solid #00ff00;">
                                <div class="card-header" style="background: transparent; border-color: #00ff00;">
                                    <h3 class="h5 mb-0" style="color: #00ff00;">📥 Download Files</h3>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($mod_files as $file) : ?>
                                        <div class="card mb-3" style="background: #2a2a2a; border: 1px solid #444;">
                                            <div class="card-body">
                                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2">
                                                    <div class="mb-2 mb-md-0">
                                                        <strong class="text-info h6"><?php echo esc_html($file['name']); ?></strong>
                                                        <span class="badge bg-secondary ms-2 small">
                                                            <?php echo esc_html($file['type']); ?>
                                                        </span>
                                                    </div>
                                                    <?php if (!empty($file['url'])) : ?>
                                                        <a href="<?php echo esc_url($file['url']); ?>" target="_blank" class="retro-button btn btn-sm">
                                                            📁 Download
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($file['description'])) : ?>
                                                    <p class="text-white mb-0 small"><?php echo esc_html($file['description']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Installation Instructions Accordion -->
                        <?php if ($install_instructions) : ?>
                            <div class="accordion mb-4" id="installationAccordion">
                                <div class="accordion-item" style="background: linear-gradient(135deg, #1a0a1a, #2a1a2a); border: 2px solid #ff00ff;">
                                    <h2 class="accordion-header mb-0" id="installationHeading">
                                        <button class="accordion-button collapsed" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#installationCollapse" 
                                                aria-expanded="false" 
                                                aria-controls="installationCollapse"
                                                style="background: linear-gradient(135deg, #2a1a2a, #3a2a3a); color: #ff00ff; border: none; border-radius: 0;">
                                            <span>
                                                🛠️ Installation Instructions
                                                <span class="badge" style="background: #ff00ff; color: #fff;">
                                                    <?php echo substr_count($install_instructions, "\n") + 1; ?> steps
                                                </span>
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="installationCollapse" 
                                        class="accordion-collapse collapse" 
                                        aria-labelledby="installationHeading" 
                                        data-bs-parent="#installationAccordion">
                                        <div class="accordion-body text-white" style="background: linear-gradient(135deg, #1a0a1a, #2a1a2a);">
                                            <?php echo wp_kses_post(nl2br($install_instructions)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- Screenshots Gallery -->
                        <?php $gallery = get_post_gallery(get_the_ID(), false);
                        if (!empty($gallery['ids'])) :
                            $image_ids = explode(',', $gallery['ids']);
                            ?>
                            <section class="arcade-section mt-5">
                                    <div class="row">
                                        <div class="col-12">
                                            <h2 class="text-center mb-4" style="color: #ff00ff;">📸 Screenshots</h2>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <?php foreach ($image_ids as $image_id) : ?>
                                            <div class="col-12 col-md-4 col-lg-3">
                                                <div class="screenshot-container">
                                                    <img src="<?php echo wp_get_attachment_image_url($image_id, 'medium'); ?>" 
                                                        alt="<?php echo get_post_meta($image_id, '_wp_attachment_image_alt', true); ?>"
                                                        class="img-fluid rounded"
                                                        style="width: 100%; height: auto; border: 2px solid #ff00ff; cursor: pointer; aspect-ratio: 16/9; object-fit: cover;"
                                                        onclick="openScreenshot('<?php echo wp_get_attachment_image_url($image_id, 'large'); ?>')">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                            </section>
                        <?php endif; ?>
                    </div><!-- .mod-content -->
                </div><!-- .row -->
                
            </div><!-- .container -->
                
            </div>
        </section>
        
        

        <?php get_template_part('template-parts/comments'); ?>
        
        <!-- Related Mods -->
        <section class="arcade-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center mb-4" style="color: #ff8000;">
                            🔧 More Mods for <?php echo $base_games ? esc_html($base_games[0]->name) : 'This Game'; ?>
                        </h2>
                    </div>
                </div>
                
                <div class="row g-4">
                    <?php
                    $related_args = array(
                        'post_type' => 'game_mods',
                        'posts_per_page' => 4,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'rand'
                    );
                    
                    // Try to get mods from the same base game first
                    if ($base_games) {
                        $related_args['tax_query'] = array(
                            array(
                                'taxonomy' => 'base_game',
                                'field' => 'term_id',
                                'terms' => $base_games[0]->term_id
                            )
                        );
                    }
                    
                    $related_mods = new WP_Query($related_args);
                    
                    if ($related_mods->have_posts()) :
                        while ($related_mods->have_posts()) : $related_mods->the_post();
                            ?>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="game-card h-100 d-flex flex-column" style="border: 2px solid #ff8000; background: #2a2a2a; border-radius: 8px; padding: 15px;">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="mb-3">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('game-thumbnail', array(
                                                    'class' => 'img-fluid rounded',
                                                    'style' => 'width: 100%; height: auto; border: 2px solid #ff8000;'
                                                )); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="h6 mb-2" style="color: #ff8000;">
                                        <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <p class="text-white small mb-3 flex-grow-1"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                    <div class="mt-auto">
                                        <a href="<?php the_permalink(); ?>" class="retro-button btn btn-sm w-100">
                                            🔧 View Mod
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<div class="col-12"><p class="text-center text-white">No related mods found yet.</p></div>';
                    endif;
                    ?>
                </div>
            </div>
        </section>
        
    <?php endwhile; ?>
</div>

<!-- Screenshot Modal -->
<div id="screenshot-modal" class="modal fade" tabindex="-1" aria-labelledby="screenshotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark" style="border: 2px solid #ff00ff;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-light" id="screenshotModalLabel">Screenshot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="screenshot-image" src="" class="img-fluid rounded" style="max-width: 100%; max-height: 80vh; border: 2px solid #ff00ff;">
            </div>
        </div>
    </div>
</div>

<script>
    // Open screenshot modal using Bootstrap
    function openScreenshot(imageUrl) {
        document.getElementById('screenshot-image').src = imageUrl;
        const modal = new bootstrap.Modal(document.getElementById('screenshot-modal'));
        modal.show();
    }

    function closeScreenshot() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('screenshot-modal'));
        if (modal) {
            modal.hide();
        }
        document.getElementById('screenshot-image').src = '';
    }
</script>

<?php get_footer(); ?>
