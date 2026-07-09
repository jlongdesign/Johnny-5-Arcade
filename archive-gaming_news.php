<?php get_header(); ?>

<div class="gaming-news-archive container mt-5">
    <section class="arcade-section">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 text-center mb-4">
                <h1 class="text-cyan text-shadow-cyan mb-3">
                    📰 Gaming News Central
                </h1>
                <p class="text-white">
                    Your source for the latest gaming industry news, updates, and breaking stories!
                </p>
                
                <!-- News Categories Filter -->
                <!-- <div style="text-align: center; margin-bottom: 30px;">
                    <a href="<?php echo get_post_type_archive_link('gaming_news'); ?>" class="btn btn-sm btn-retro btn-blue" style="margin: 5px;">
                        📰 All News
                    </a>
                    <?php
                    $news_categories = get_terms(array(
                        'taxonomy' => 'news_category',
                        'hide_empty' => true
                    ));
                    
                    foreach ($news_categories as $category) :
                        ?>
                        <a href="<?php echo get_term_link($category); ?>" class="btn btn-sm btn-retro btn-blue" style="margin: 5px;">
                            <?php echo esc_html($category->name); ?>
                        </a>
                        <?php
                    endforeach;
                    ?>
                </div> -->
                
                </div><!-- end col -->
            </div><!-- end row --> 
            <div class="row">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        $breaking_news = get_post_meta(get_the_ID(), '_breaking_news', true);
                        $featured_article = get_post_meta(get_the_ID(), '_featured_article', true);
                        $article_source = get_post_meta(get_the_ID(), '_article_source', true);
                        $external_link = get_post_meta(get_the_ID(), '_external_link', true);
                        $video_url = get_post_meta(get_the_ID(), '_article_video_url', true);
                        ?>
                        <div class="col-12 col-md-4 mb-4">
                            <article class="news-archive-card glass glass-border rounded-4 h-100 p-relative p-4">
                            
                                <!-- Article thumbnail -->
                                <?php if (has_post_thumbnail()) : ?>
                                    <div style="width: 100%; height: 200px; overflow: hidden; border-radius: 8px; margin-bottom: 20px;">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array(
                                                'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;'
                                            )); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            
                                <!-- Article badges -->
                                <div class="breaking-news-badge-position">
                                    <?php if ($breaking_news === 'yes' && $featured_article !== 'yes') : ?>
                                        <span class="breaking-news-badge">🚨 BREAKING</span>
                                    <?php elseif ($featured_article === 'yes' && $breaking_news !== 'yes') : ?>
                                        <span class="featured-article-badge">⭐ FEATURED</span>
                                    <?php elseif ($breaking_news === 'yes' && $featured_article === 'yes') : ?>
                                        <span class="breaking-news-badge">🚨 BREAKING</span> <span class="featured-article-badge">⭐ FEATURED</span>
                                    <?php endif; ?>
                                </div>
                                
                                <time style="color: #00ff00; font-size: 0.8rem;">
                                    <?php echo get_the_date('M j, Y'); ?>
                                </time>
                            
                            
                                <!-- Article title -->
                                <h2 style="margin-bottom: 15px;">
                                    <a href="<?php the_permalink(); ?>" style="color: #00ffff; text-decoration: none; font-size: 1.3rem; line-height: 1.3;">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                            
                                <!-- Article excerpt -->
                                <p style="color: #ccc; line-height: 1.5; margin-bottom: 20px;">
                                    <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                                </p>
                                
                                <!-- Separator -->
                                <hr>

                                <!-- Article meta -->
                                <div class="mb-3">
                                    <span style="color: #ffff00; font-size: 0.8rem;">✍️ <?php the_author(); ?></span>
                                    <?php if ($article_source) : ?>
                                        <span style="color: #ff8000; font-size: 0.8rem; margin-left: 15px;">📰 <?php echo esc_html($article_source); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex gap-3">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-retro btn-blue flex-grow-1 align-items-center">
                                        📖&nbsp;Read
                                    </a>
                                    <?php if ($external_link) : ?>
                                        <a href="<?php echo esc_url($external_link); ?>" target="_blank" class="btn btn-sm btn-retro btn-green flex-grow-1 align-items-center">
                                            🔗&nbsp;Source
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                        
                    <?php endwhile; ?>
                <?php else : ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #ccc;">
                        No gaming news articles found. Start adding some breaking stories!
                    </p>
                <?php endif; ?>
            </div><!-- end row -->
            <div class="row">
                <div class="col-12 text-center mt-4">
                    <!-- Pagination -->
                    <div style="text-align: center; margin-top: 40px;">
                        <?php echo paginate_links(array(
                            'prev_text' => '« Previous',
                            'next_text' => 'Next »',
                        )); ?>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end arcade section -->
    </section>
</div>

<?php get_footer(); ?>