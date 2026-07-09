<?php get_header(); ?>

<div class="category-resources-page container mt-5">

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="text-warning fw-bold">📚 Resources</h1>
            <p class="text-white lead">User guides, how-to's, and helpful tutorials</p>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="row mb-5">
        <div class="col-12">
            <form method="GET" action="" class="d-flex flex-column flex-md-row gap-3">

                <!-- Search Input -->
                <div class="flex-grow-1">
                    <input 
                        type="text" 
                        name="resources_search" 
                        class="form-control bg-dark text-white border-secondary" 
                        placeholder="🔍 Search resources..."
                        value="<?php echo esc_attr(get_query_var('resources_search', $_GET['resources_search'] ?? '')); ?>"
                    >
                </div>

                <!-- Difficulty Filter -->
                <div>
                    <select name="difficulty" class="form-select bg-dark text-white border-secondary">
                        <option value="">All Levels</option>
                        <option value="beginner"     <?php selected($_GET['difficulty'] ?? '', 'beginner'); ?>>🟢 Beginner</option>
                        <option value="intermediate" <?php selected($_GET['difficulty'] ?? '', 'intermediate'); ?>>🟡 Intermediate</option>
                        <option value="advanced"     <?php selected($_GET['difficulty'] ?? '', 'advanced'); ?>>🔴 Advanced</option>
                    </select>
                </div>

                <!-- Submit & Reset -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-glass btn-retro btn-yellow text-dark text-dark fw-bold">Filter</button>
                    <?php if (!empty($_GET['resources_search']) || !empty($_GET['difficulty'])) : ?>
                        <a href="<?php echo esc_url(get_term_link('resources', 'category')); ?>" class="btn btn-glass btn-secondary">Reset</a>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </div>

    <!-- Resources Grid -->
    <div class="row g-4">
        <?php
        // Build query args
        $query_args = array(
            'category_name'  => 'resources',
            'posts_per_page' => 12,
            'orderby'        => 'date',
            'order'          => 'DESC'
        );

        // Apply search
        if (!empty($_GET['resources_search'])) {
            $query_args['s'] = sanitize_text_field($_GET['resources_search']);
        }

        // Apply difficulty tag filter
        if (!empty($_GET['difficulty'])) {
            $query_args['tag'] = sanitize_text_field($_GET['difficulty']);
        }

        $resources_query = new WP_Query($query_args);

        if ($resources_query->have_posts()) :
            while ($resources_query->have_posts()) : $resources_query->the_post();
                $post_id    = get_the_ID();
                $post_url   = get_permalink($post_id);
                $post_title = get_the_title($post_id);

                // Get difficulty tag for badge
                $tags = get_the_tags();
                $difficulty = '';
                $badge_class = '';
                if ($tags) {
                    foreach ($tags as $tag) {
                        if (in_array($tag->slug, ['beginner', 'intermediate', 'advanced'])) {
                            $difficulty  = ucfirst($tag->slug);
                            $badge_class = match($tag->slug) {
                                'beginner'     => 'bg-success',
                                'intermediate' => 'bg-warning text-dark',
                                'advanced'     => 'bg-danger',
                                default        => 'bg-secondary'
                            };
                            break;
                        }
                    }
                }
        ?>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card bg-dark border-secondary h-100">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php echo esc_url($post_url); ?>">
                                <?php the_post_thumbnail('medium', array('class' => 'card-img-top', 'style' => 'height: 180px; object-fit: cover;')); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">

                            <!-- Difficulty Badge -->
                            <?php if ($difficulty) : ?>
                                <div class="mb-2">
                                    <span class="badge <?php echo $badge_class; ?>"><?php echo $difficulty; ?></span>
                                </div>
                            <?php endif; ?>

                            <h5 class="card-title text-warning">
                                <a href="<?php echo esc_url($post_url); ?>" class="text-decoration-none text-warning">
                                    <?php echo esc_html($post_title); ?>
                                </a>
                            </h5>
                            <p class="card-text text-white-50 small flex-grow-1">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">📅 <?php echo get_the_date('M j, Y'); ?></small>
                                <a href="<?php echo esc_url($post_url); ?>" class="btn btn-sm btn-outline-warning">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <div class="col-12 text-center py-5 glass border border-secondary rounded">
                <span class="display-1">😕</span>
                <p class="text-white">No resources found. Try a different search or filter.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            <?php
            echo paginate_links(array(
                'total'   => $resources_query->max_num_pages,
                'current' => max(1, get_query_var('paged')),
            ));
            ?>
        </div>
    </div>

</div>

<?php get_footer(); ?>