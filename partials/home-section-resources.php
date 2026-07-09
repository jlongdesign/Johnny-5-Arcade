<?php
$resources_query = new WP_Query(array(
    'category_name'  => 'resources',
    'posts_per_page' => 4,
    'orderby'        => 'date',
    'order'          => 'DESC'
));
?>

<section class="py-5">
  <div class="container">

    <div class="row">
      <div class="col-12 text-center mb-4">
        <h2 class="mb-4 text-yellow text-shadow-yellow">📚 Resources</h2>
        <p class="text-white mb-0">User guides, how-to's, and helpful tutorials</p>
      </div>
    </div>

    <!-- Resources Cards -->
    <div class="row g-4">
      <?php if ($resources_query->have_posts()) : ?>
          <?php while ($resources_query->have_posts()) : $resources_query->the_post();
            $post_id  = get_the_ID();
            $post_url = get_permalink($post_id);
            $post_title = get_the_title($post_id);
        ?>

              <div class="col-12 col-md-6 col-lg-4 position-relative">
                <div class="card glass-card glass-border rounded-4 h-100">
                  <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('medium', array(
                          'class' => 'card-img-top',
                          'style' => 'height: 200px; object-fit: cover;'
                      )); ?>
                  <?php endif; ?>
                  <div class="card-body d-flex flex-column">
                      <h6 class="card-title">
                          <a href="<?php echo esc_url($post_url); ?>" class="text-decoration-none text-yellow stretched-link">
                              <?php echo esc_html($post_title); ?>
                          </a>
                      </h6>
                      <p class="card-text text-white small flex-grow-1">
                          <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                      </p>
                      <span class="btn btn-sm btn-retro btn-yellow text-dark mt-3">
                          Read More →
                      </span>
                  </div><!-- end card-body -->
                </div><!-- end glass-card -->
              </div><!-- end col -->
        
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

      <?php else : ?>
          <div class="col-12 text-center py-4">
              <p class="text-white-50">No resources posted yet. Check back soon!</p>
          </div>
      <?php endif; ?>
    </div><!-- end row -->

    <!-- View All Button -->
    <div class="row">
      <div class="col-12 text-center mt-4">
        <a href="<?php echo esc_url(get_term_link('resources', 'category')); ?>" class="btn btn-lg btn-retro btn-yellow text-dark ">
          View All Resources →
        </a>
      </div><!-- end col -->
    </div><!-- end row -->

  </div><!-- end container -->
</section>