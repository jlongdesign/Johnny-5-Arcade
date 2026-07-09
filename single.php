<?php get_header(); ?>

<div class="container py-5">
    <?php while (have_posts()) : the_post(); ?>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <!-- Back Link -->
                <a href="<?php echo esc_url(get_term_link('resources', 'category')); ?>" class="text-warning text-decoration-none d-inline-block mb-4">
                    ← Back to Resources
                </a>

                <!-- Post Header -->
                <h1 class="text-warning fw-bold mb-3"><?php the_title(); ?></h1>

                <div class="text-white-50 small mb-4">
                    📅 <?php echo get_the_date('F j, Y'); ?> &nbsp;|&nbsp; ✍️ <?php the_author(); ?>
                </div>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-4">
                        <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded border border-secondary')); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Content -->
                <div class="text-white post-content">
                    <?php the_content(); ?>
                </div>

                <!-- Back Link (bottom) -->
                <hr class="border-secondary mt-5">
                <a href="<?php echo esc_url(get_term_link('resources', 'category')); ?>" class="text-warning text-decoration-none">
                    ← Back to Resources
                </a>

            </div>
        </div>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>