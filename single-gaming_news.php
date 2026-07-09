<?php get_header(); ?>

<div class="single-news-article container mt-5">
    <?php while (have_posts()) : the_post(); ?>
        <article class="arcade-section">
            <?php
            $breaking_news = get_post_meta(get_the_ID(), '_breaking_news', true);
            $featured_article = get_post_meta(get_the_ID(), '_featured_article', true);
            $article_source = get_post_meta(get_the_ID(), '_article_source', true);
            $external_link = get_post_meta(get_the_ID(), '_external_link', true);
            $video_url = get_post_meta(get_the_ID(), '_video_url', true);
            $gallery_images = get_post_meta(get_the_ID(), '_gallery_images', true);
            ?>
            
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="rounded-4 p-4 mb-5">
                        <div class="">
                            <!-- Article header -->
                            <header class="text-center">
                                <!-- Badges -->
                                <div>
                                    <?php if ($breaking_news) : ?>
                                        <span class="badge breaking-news-badge text-white px-2 py-3 me-2 fs-6 fw-bold text-uppercase">
                                            🚨 BREAKING NEWS
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($featured_article && !$breaking_news) : ?>
                                        <span class="badge featured-article-badge text-white px-2 py-3 fs-6 fw-bold text-uppercase">
                                            ⭐ FEATURED ARTICLE
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Title -->
                                <h1 class="text-white mb-4 lh-base fw-bold">
                                    <?php the_title(); ?>
                                </h1>
                            
                                <!-- Meta information -->
                                <div class="text-muted mb-4">
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                                        <span class="text-orange fw-semibold">
                                            📅 <?php echo get_the_date('F j, Y'); ?>
                                        </span>
                                        <span class="d-none d-md-inline text-secondary">|</span>
                                        <span class="text-warning fw-semibold">
                                            ✍️ <?php the_author(); ?>
                                        </span>
                                        <?php if ($article_source) : ?>
                                            <span class="d-none d-lg-inline text-secondary">|</span>
                                            <span class="text-orange fw-semibold">
                                                📰 Source: <?php echo esc_html($article_source); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            
                                <!-- Featured image -->
                                <?php if (has_post_thumbnail()) : ?>
                                    <div>
                                        <?php the_post_thumbnail('large', array(
                                            'class' => 'img-fluid w-100 rounded-3 shadow-lg'
                                        )); ?>
                                    </div>
                                <?php endif; ?>
                            </header>
                        
                            <hr class="my-5">

                            <!-- Article content -->
                            <div class="mb-5">
                                <?php the_content(); ?>
                            </div>
                        
                            <!-- Video section -->
                            <?php if ($video_url) : ?>
                                <div class="mb-5">
                                    <h3 class="text-magenta mb-3 text-center fw-bold">📹 Related Video</h3>
                                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow video-container">
                                        <?php if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) : ?>
                                            <?php
                                            $video_id = '';
                                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $video_url, $matches)) {
                                                $video_id = $matches[1];
                                            }
                                            ?>
                                            <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" 
                                                    class="border-0"
                                                    allowfullscreen></iframe>
                                        <?php else : ?>
                                            <video controls class="w-100 h-100">
                                                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        
                            <!-- Gallery section -->
                            <?php if ($gallery_images) : ?>
                                <div class="mb-5">
                                    <h3 class="text-warning mb-4 text-center fw-bold">🖼️ Image Gallery</h3>
                                    <div class="row g-3">
                                        <?php
                                        $images = array_map('trim', explode(',', $gallery_images));
                                        foreach ($images as $index => $image_url) :
                                            if (!empty($image_url)) :
                                                ?>
                                                <div class="col-6 col-md-4 col-lg-3">
                                                    <div class="gallery-item position-relative rounded-2 overflow-hidden" 
                                                        role="button" 
                                                        tabindex="0"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#imageModal"
                                                        onclick="openImageModal('<?php echo esc_js($image_url); ?>')">
                                                        <img src="<?php echo esc_url($image_url); ?>" 
                                                            alt="Gallery Image <?php echo $index + 1; ?>" 
                                                            class="img-fluid w-100"
                                                            style="aspect-ratio: 1; object-fit: cover;">
                                                        <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0">
                                                            <span class="text-white fs-2">🔍</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        
                            <!-- Article footer -->
                            <footer class="py-4 border-top border-secondary text-center">
                                <!-- Action buttons -->
                                <?php if ($external_link) : ?>
                                    <div class="row g-3 mb-4">
                                        <div class="col-12 col-md-6">
                                            <a href="<?php echo esc_url($external_link); ?>" 
                                            target="_blank" 
                                            class="btn btn-success w-100 fw-bold py-2">
                                                🔗 Read Original Article
                                            </a>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <a href="<?php echo get_post_type_archive_link('gaming_news'); ?>" 
                                            class="btn btn-primary w-100 fw-bold py-2">
                                                📰 Back to All News
                                            </a>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="mb-4">
                                        <a href="<?php echo get_post_type_archive_link('gaming_news'); ?>" 
                                        class="btn btn-primary fw-bold py-2 px-4">
                                            📰 Back to All News
                                        </a>
                                    </div>
                                <?php endif; ?>
                            
                                <!-- Categories and tags -->
                                <?php
                                $categories = get_the_terms(get_the_ID(), 'news_category');
                                $tags = get_the_terms(get_the_ID(), 'news_tag');
                                ?>
                            
                                <?php if ($categories) : ?>
                                    <div class="mb-3">
                                        <span class="text-white fw-bold fs-6">📁 Categories: </span>
                                        <div class="d-flex flex-wrap gap-2 justify-content-center mt-2">
                                            <?php foreach ($categories as $category) : ?>
                                                <a href="<?php echo get_term_link($category); ?>" 
                                                class="badge category-link text-decoration-none py-2 px-3">
                                                    <?php echo esc_html($category->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            
                                <?php if ($tags) : ?>
                                    <div class="mb-4">
                                        <span class="text-orange fw-bold fs-6">🏷️ Tags: </span>
                                        <div class="d-flex flex-wrap gap-2 justify-content-center mt-2">
                                            <?php foreach ($tags as $tag) : ?>
                                                <span class="badge tag-badge py-2 px-3">
                                                    <?php echo esc_html($tag->name); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            
                                <!-- Social share -->
                                <div class="social-share">
                                    <h4 class="text-cyan mb-3 fw-bold">📢 Share This Article</h4>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                        target="_blank" 
                                        class="btn btn-sm btn-retro btn-blue fw-semibold">
                                            <i class="fab fa-facebook-f fa-lg me-2"></i> Facebook
                                        </a>
                                        <a href="https://x.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                        target="_blank" 
                                        class="btn btn-retro btn-black btn-sm fw-semibold">
                                            <i class="fab fa-x-twitter fa-lg me-2"></i> X.com
                                        </a>
                                        <a href="https://www.reddit.com/submit?url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" 
                                        target="_blank" 
                                        class="btn btn-sm btn-retro btn-red fw-semibold">
                                            <i class="fab fa-reddit-alien fa-lg me-2"></i> Reddit
                                        </a>
                                    </div>
                                </div>
                            </footer>
                        </div><!-- end of bg-dark wrapper -->
                    </div>

                    
                </div><!-- end of column -->
            </div><!-- end of row -->
        </article>
                   

        <?php get_template_part('template-parts/comments'); ?>
        
        <!-- Related articles -->
        <section class="related-articles arcade-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto">
                        <hr class="mb-5 border-secondary">
                        <h3 class="text-center text-magenta mb-4 fw-bold">📰 Related Articles</h3>
                        
                        <div class="row g-4">
                            <?php
                            $related_articles = new WP_Query(array(
                                'post_type' => 'gaming_news',
                                'posts_per_page' => 3,
                                'orderby' => 'rand',
                                'post__not_in' => array(get_the_ID())
                            ));
                            
                            if ($related_articles->have_posts()) :
                                while ($related_articles->have_posts()) : $related_articles->the_post();
                                    ?>
                                    <div class="col-12 col-md-4">
                                        <div class="card related-article-card h-100 border-0 rounded-3">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="overflow-hidden rounded-top" style="height: 150px;">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('medium', array(
                                                            'class' => 'card-img-top w-100 h-100',
                                                            'style' => 'object-fit: cover; transition: transform 0.3s ease;'
                                                        )); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="card-body d-flex flex-column p-3">
                                                <h4 class="card-title h6 mb-2 fw-bold">
                                                    <a href="<?php the_permalink(); ?>" class="text-magenta text-decoration-none">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h4>
                                                
                                                <p class="card-text text-muted small mb-auto lh-base">
                                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                                </p>
                                                
                                                <div class="text-muted small mt-2 fw-semibold">
                                                    📅 <?php echo get_the_date('M j, Y'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<div class="col-12"><p class="text-center text-muted">No related articles found.</p></div>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    <?php endwhile; ?>
</div>

<!-- Bootstrap Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="modal-image" src="" class="img-fluid rounded" alt="Gallery Image">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageUrl) {
    document.getElementById('modal-image').src = imageUrl;
}
</script>

<?php get_footer(); ?>