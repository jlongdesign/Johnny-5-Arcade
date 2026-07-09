    <!-- Gaming News Section -->
    <section id="home-gaming-news" class="mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">    
                    <h2 class="color-cyan text-shadow-cyan mb-3">📰 Latest Gaming News</h2>
                    <p class="text-white text-shadow-cyan">
                        Stay updated with the hottest gaming news and industry updates!
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $gaming_news = new WP_Query(array(
                    'post_type' => 'gaming_news',
                    'posts_per_page' => 6,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($gaming_news->have_posts()) :
                    while ($gaming_news->have_posts()) : $gaming_news->the_post();
                        $breaking_news = get_post_meta(get_the_ID(), '_breaking_news', true);
                        $featured_article = get_post_meta(get_the_ID(), '_featured_article', true);
                        $article_source = get_post_meta(get_the_ID(), '_article_source', true);
                        $external_link = get_post_meta(get_the_ID(), '_external_link', true);
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="news-card d-flex flex-column h-100 card glass-card glass-border rounded-4 position-relative" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0, 255, 255, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            
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
                            
                            <!-- Article thumbnail -->
                            <div>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="rounded overflow-hidden">
                                        <?php the_post_thumbnail('medium', array(
                                            'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;'
                                        )); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="w-100 text-white d-flex align-items-center justify-content-center rounded mb-4" style="
                                        height: 200px;
                                        background: linear-gradient(135deg, #333, #555);
                                        color: #999;
                                        border: 2px dashed #666;
                                    ">
                                        📰 News Article
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Article title -->
                            <h3 class="p-3 text-cyan h6" style="border-bottom: 1px solid #444; padding-top: 10px;">
                                <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <!-- Article excerpt -->
                            <p class="flex-grow-1 px-3" style="color: #ccc; font-size: 0.9rem; line-height: 1.4; margin-bottom: 15px; height: 4.2em; overflow: hidden;">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>
                            
                            <!-- Article meta -->
                            <div class="px-3 d-flex justify-content-between align-items-center mb-1 small">
                                <span class="text-green">📅 <?php echo get_the_date('M j, Y'); ?></span>
                                <span class="text-yellow">✍️ <?php the_author(); ?></span>
                            </div>
                            
                            <?php if ($article_source) : ?>
                                <div class="px-3 mb-3">
                                    <span class="text-orange small">📰 Source: <?php echo esc_html($article_source); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Action buttons -->
                            <div class="d-flex gap-3 px-3 mb-3" style="border-top: 1px solid #444; padding-top: 10px;">
                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-retro btn-blue flex-grow-1 align-items-center">📖&nbsp;Read More</a>
                                <?php if ($external_link) : ?>
                                    <a href="<?php echo esc_url($external_link); ?>" target="_blank" class="btn btn-sm btn-retro btn-green text-dark flex-grow-1 align-items-center">🔗&nbsp;Source</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p style="color: #ccc; text-align: center; grid-column: 1 / -1;">No gaming news available yet. Start sharing the latest updates!</p>';
                endif;
                ?>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="<?php echo get_post_type_archive_link('gaming_news'); ?>" class="btn btn-retro btn-blue btn-lg">
                📰&nbsp;Read All News
            </a>
        </div>
    </section>