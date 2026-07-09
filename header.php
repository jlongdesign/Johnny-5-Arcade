<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/css/all.min.css">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- <div class="crt-overlay"></div> -->
    
    <header class="site-header py-3 bg-black">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col-9 col-md-5">
                    <?php if (has_custom_logo()) : ?>
                        <div class="site-logo d-flex align-items-center flex-md-row text-left">
                            <div class="logo-container me-2 me-md-3 mb-2 mb-md-0">
                                <?php the_custom_logo(); ?>
                            </div>
                            <div>
                                <h1 class="site-title mb-0">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="text-decoration-none">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h1>
                                <?php if (get_bloginfo('description')) : ?>
                                    <p class="site-description mb-0 small"><?php echo get_bloginfo('description'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="text-center text-md-start">
                            <h1 class="site-title mb-0">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="text-decoration-none">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php if (get_bloginfo('description')) : ?>
                                <p class="site-description mb-0 small"><?php echo get_bloginfo('description'); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-auto ms-md-auto text-center text-md-end mt-3 mt-md-0">
                     <?php
                        // Custom Bootstrap nav walker - define before use
                        if (!class_exists('WP_Bootstrap_Navwalker')) {
                            class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
                                function start_lvl(&$output, $depth = 0, $args = null) {
                                    $indent = str_repeat("\t", $depth);
                                    $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
                                }
                                
                                function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                                    $indent = ($depth) ? str_repeat("\t", $depth) : '';
                                    
                                    $classes = empty($item->classes) ? array() : (array) $item->classes;
                                    $classes[] = 'nav-item';
                                    
                                    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                                    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                                    
                                    $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
                                    $id = $id ? ' id="' . esc_attr($id) . '"' : '';
                                    
                                    $indent = ($depth) ? str_repeat("\t", $depth) : '';
                                    
                                    $output .= $indent . '<li' . $id . $class_names .'>';
                                    
                                    $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
                                    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
                                    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
                                    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
                                    
                                    $item_output = $args->before ?? '';
                                    $item_output .= '<a class="nav-link"' . $attributes .'>';
                                    $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
                                    $item_output .= '</a>';
                                    $item_output .= $args->after ?? '';
                                    
                                    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
                                }
                            }
                        }
                        
                        // Fallback menu if no menu is set
                        if (!function_exists('arcade_hub_fallback_menu')) {
                            function arcade_hub_fallback_menu() {
                                echo '<ul class="navbar-nav ms-auto">';
                                echo '<li class="nav-item"><a class="nav-link" href="' . home_url('/') . '">Home</a></li>';
                                echo '<li class="nav-item"><a class="nav-link" href="' . get_post_type_archive_link('games') . '">Games</a></li>';
                                echo '<li class="nav-item"><a class="nav-link" href="' . get_post_type_archive_link('game_mods') . '">Mods</a></li>';
                                echo '<li class="nav-item"><a class="nav-link" href="' . get_post_type_archive_link('retro_videos') . '">Retro TV</a></li>';
                                echo '<li class="nav-item"><a class="nav-link" href="' . get_term_link('resources', 'category') . '">Resources</a></li>';
                                echo '<li class="nav-item"><a class="nav-link" href="' . get_post_type_archive_link('reviews') . '">Reviews</a></li>';
                                echo '</ul>';
                            }
                        }
                        ?>

                        
                        <nav class="main-navigation navbar navbar-expand-lg navbar-dark">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'primary',
                                    'menu_class' => 'navbar-nav me-auto',
                                    'container' => false,
                                    'fallback_cb' => 'arcade_hub_fallback_menu',
                                    'walker' => new WP_Bootstrap_Navwalker(),
                                ));
                                ?>
                            </div><!-- end collapse -->
                        </nav>

                        
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end container -->
    </header>

   

    <main class="site-main">
        <!-- <div class="container-fluid"> -->
