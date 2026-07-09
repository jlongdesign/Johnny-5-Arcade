<?php
/**
 * Template Name: Work With Me
 *
 * @package arcade-hub
 */

get_header();

// Contact form submission handler feedback
$wwm_message = '';
$wwm_error   = '';
if ( isset( $_GET['wwm_sent'] ) ) {
    $wwm_message = 'Your message has been sent! We\'ll be in touch soon. 🎮';
} elseif ( isset( $_GET['wwm_error'] ) ) {
    $wwm_error = 'Something went wrong. Please try again or email us directly.';
}
?>

<div class="work-with-me-page">

    <!-- =============================================
         HERO SECTION
    ============================================= -->
    <section class="wwm-hero text-center py-5">
        <div class="container">
            <div class="wwm-hero-badge mb-3">
                <span class="badge-text">🤝 PARTNERSHIP INQUIRIES</span>
            </div>
            <h1 class="wwm-hero-title mb-3">Work With <span class="color-cyan">Johnny5Arcade</span></h1>
            <p class="wwm-hero-subtitle text-white">
                Let's build something legendary together. From sponsored content to long-term brand partnerships,<br class="d-none d-md-block">
                there's a spot on the Player 2 side of the screen just for you.
            </p>
        </div>
    </section>

    <!-- =============================================
         ABOUT SECTION
    ============================================= -->
    <section class="wwm-section py-5" id="about">
        <div class="container">
            <div class="wwm-section-header text-center mb-5">
                <h2 class="wwm-section-title">🎮 About Johnny5Arcade</h2>
                <div class="wwm-title-line"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="glass glass-border rounded-3 p-4 p-md-5">
                        <p class="wwm-about-text text-white mb-4">
                            Johnny5Arcade is a retro gaming brand dedicated to keeping the golden age of gaming alive and glowing. 
                            From dusty cartridge blowing to high-score hunting, the channel delivers gaming nostalgia with humor, 
                            heart, and a healthy dose of pixel-fueled chaos. Whether it's a tour of an epic game room setup, a 
                            deep-dive into classic arcade hardware, or a comedic take on gaming culture, Johnny5Arcade connects 
                            with audiences who grew up pressing Start and never stopped.
                        </p>
                        <div class="row g-3 text-center">
                            <div class="col-6 col-md-3">
                                <div class="wwm-niche-chip">
                                    <span class="wwm-niche-icon">👾</span>
                                    <span class="wwm-niche-label">Retro Gaming</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="wwm-niche-chip">
                                    <span class="wwm-niche-icon">📼</span>
                                    <span class="wwm-niche-label">Nostalgia</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="wwm-niche-chip">
                                    <span class="wwm-niche-icon">😂</span>
                                    <span class="wwm-niche-label">Gaming Humor</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="wwm-niche-chip">
                                    <span class="wwm-niche-icon">🕹️</span>
                                    <span class="wwm-niche-label">Game Room Setups</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =============================================
         AUDIENCE SECTION
    ============================================= -->
    <section class="wwm-section wwm-section-alt py-5" id="audience">
        <div class="container">
            <div class="wwm-section-header text-center mb-5">
                <h2 class="wwm-section-title">📊 The Audience</h2>
                <div class="wwm-title-line wwm-title-line--yellow"></div>
            </div>

            <!-- Platform Stats -->
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://www.instagram.com/johnny5arcade" target="_blank" rel="noopener" class="text-decoration-none">
                        <div class="glass glass-card glass-neon-blue rounded-3 p-3 text-center wwm-platform-card">
                            <i class="fab fa-instagram wwm-platform-icon" style="color: #e1306c;"></i>
                            <div class="wwm-platform-name">Instagram</div>
                            <div class="wwm-platform-stat color-cyan">Growing</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://www.tiktok.com/@johnny5arcade" target="_blank" rel="noopener" class="text-decoration-none">
                        <div class="glass glass-card glass-neon-blue rounded-3 p-3 text-center wwm-platform-card">
                            <i class="fab fa-tiktok wwm-platform-icon" style="color: #69c9d0;"></i>
                            <div class="wwm-platform-name">TikTok</div>
                            <div class="wwm-platform-stat color-cyan">Growing</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://www.youtube.com/@johnny5arcade" target="_blank" rel="noopener" class="text-decoration-none">
                        <div class="glass glass-card glass-neon-blue rounded-3 p-3 text-center wwm-platform-card">
                            <i class="fab fa-youtube wwm-platform-icon" style="color: #ff0000;"></i>
                            <div class="wwm-platform-name">YouTube</div>
                            <div class="wwm-platform-stat color-cyan">Growing</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://www.facebook.com/johnny5arcade" target="_blank" rel="noopener" class="text-decoration-none">
                        <div class="glass glass-card glass-neon-blue rounded-3 p-3 text-center wwm-platform-card">
                            <i class="fab fa-facebook-f wwm-platform-icon" style="color: #1877f2;"></i>
                            <div class="wwm-platform-name">Facebook</div>
                            <div class="wwm-platform-stat color-cyan">Growing</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://www.twitch.tv/johnny5arcade" target="_blank" rel="noopener" class="text-decoration-none">
                        <div class="glass glass-card glass-neon-blue rounded-3 p-3 text-center wwm-platform-card">
                            <i class="fab fa-twitch wwm-platform-icon" style="color: #9146ff;"></i>
                            <div class="wwm-platform-name">Twitch</div>
                            <div class="wwm-platform-stat color-cyan">Growing</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-6 col-md-4">
                    <div class="glass glass-border rounded-3 p-4 text-center wwm-stat-box">
                        <div class="wwm-stat-number color-yellow">Multi-Platform</div>
                        <div class="wwm-stat-label text-white">Total Presence</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="glass glass-border rounded-3 p-4 text-center wwm-stat-box">
                        <div class="wwm-stat-number color-cyan">Growing</div>
                        <div class="wwm-stat-label text-white">Monthly Views</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="glass glass-border rounded-3 p-4 text-center wwm-stat-box">
                        <div class="wwm-stat-number color-green">Retro Fans</div>
                        <div class="wwm-stat-label text-white">Core Audience</div>
                    </div>
                </div>
            </div>

            <!-- Demographics note -->
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <p class="wwm-demo-note text-white">
                        <i class="fas fa-chart-bar color-yellow me-2"></i>
                        Full audience demographics — including age, location, and engagement breakdowns — are available in our <strong class="color-cyan">Media Kit</strong>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- =============================================
         PARTNERSHIP OPPORTUNITIES
    ============================================= -->
    <section class="wwm-section py-5" id="partnerships">
        <div class="container">
            <div class="wwm-section-header text-center mb-5">
                <h2 class="wwm-section-title">🤝 Partnership Opportunities</h2>
                <div class="wwm-title-line wwm-title-line--magenta"></div>
                <p class="text-white mt-3">Here's how we can level up together.</p>
            </div>
            <div class="row g-4">

                <div class="col-md-6 col-lg-4">
                    <div class="glass glass-card glass-neon-amber rounded-3 p-4 h-100 wwm-opportunity-card">
                        <div class="wwm-opp-icon">🎬</div>
                        <h3 class="wwm-opp-title color-orange">Sponsored Reels</h3>
                        <p class="wwm-opp-desc text-white">Short-form vertical video content crafted for Instagram Reels featuring your brand naturally integrated into retro gaming storytelling.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="glass glass-card glass-neon-red rounded-3 p-4 h-100 wwm-opportunity-card">
                        <div class="wwm-opp-icon">▶️</div>
                        <h3 class="wwm-opp-title color-yellow">YouTube Shorts</h3>
                        <p class="wwm-opp-desc text-white">High-impact 60-second YouTube Shorts that blend nostalgia-driven content with authentic brand messaging for maximum reach.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="glass glass-card glass-neon-blue rounded-3 p-4 h-100 wwm-opportunity-card">
                        <div class="wwm-opp-icon">🕹️</div>
                        <h3 class="wwm-opp-title color-cyan">Product Reviews</h3>
                        <p class="wwm-opp-desc text-white">Honest, in-depth reviews of gaming gear, retro hardware, accessories, or lifestyle products aligned with the arcade aesthetic.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="glass glass-card glass-neon-green rounded-3 p-4 h-100 wwm-opportunity-card">
                        <div class="wwm-opp-icon">🌐</div>
                        <h3 class="wwm-opp-title color-green">Website Features</h3>
                        <p class="wwm-opp-desc text-white">Dedicated blog posts, sponsored articles, or homepage placements on Johnny5Arcade.com — reaching our most engaged visitors.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="glass glass-card glass-neon-amber rounded-3 p-4 h-100 wwm-opportunity-card">
                        <div class="wwm-opp-icon">💰</div>
                        <h3 class="wwm-opp-title color-orange">Affiliate Campaigns</h3>
                        <p class="wwm-opp-desc text-white">Performance-based affiliate partnerships with custom tracking links, featured prominently across social channels and the website.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="glass glass-card glass-neon-blue rounded-3 p-4 h-100 wwm-opportunity-card">
                        <div class="wwm-opp-icon">🏆</div>
                        <h3 class="wwm-opp-title color-magenta">Brand Ambassadorships</h3>
                        <p class="wwm-opp-desc text-white">Long-term collaborative partnerships where your brand becomes a recurring part of the Johnny5Arcade universe across all platforms.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =============================================
         PREVIOUS PARTNERS
    ============================================= -->
    <section class="wwm-section wwm-section-alt py-5" id="partners">
        <div class="container">
            <div class="wwm-section-header text-center mb-5">
                <h2 class="wwm-section-title">🏆 Previous Partners</h2>
                <div class="wwm-title-line wwm-title-line--green"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="glass glass-border rounded-3 p-5 wwm-partners-placeholder">
                        <div class="wwm-partners-insert mb-4">
                            <i class="fas fa-handshake wwm-partners-icon color-yellow"></i>
                        </div>
                        <h3 class="color-cyan mb-3">Now Accepting Partnership Inquiries</h3>
                        <p class="text-white mb-0">
                            We're growing fast and actively looking for our first wave of brand partners. 
                            Be one of the first to collaborate with Johnny5Arcade and lock in ground-floor rates 
                            before the audience scales.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =============================================
         MEDIA KIT
    ============================================= -->
    <section class="wwm-section py-5" id="media-kit">
        <div class="container text-center">
            <div class="wwm-section-header mb-4">
                <h2 class="wwm-section-title">📄 Media Kit</h2>
                <div class="wwm-title-line mx-auto wwm-title-line--cyan"></div>
            </div>
            <p class="text-white mb-4 wwm-kit-desc">
                Everything you need to know about partnering with Johnny5Arcade — 
                stats, audience insights, rates, and past work — all in one download.
            </p>
            <a href="<?php echo esc_url( get_template_directory_uri() . '/media-kit/johnny5arcade-media-kit.pdf' ); ?>"
               class="btn btn-retro btn-orange wwm-kit-btn"
               target="_blank"
               rel="noopener">
                <i class="fas fa-download me-2"></i> Download Media Kit (PDF)
            </a>
            <p class="wwm-kit-note text-white mt-3">
                <small>Don't have time? Just <a href="#contact" class="color-cyan">send us a message</a> and we'll send it straight to your inbox.</small>
            </p>
        </div>
    </section>

    <!-- =============================================
         CONTACT SECTION
    ============================================= -->
    <section class="wwm-section wwm-section-alt py-5" id="contact">
        <div class="container">
            <div class="wwm-section-header text-center mb-5">
                <h2 class="wwm-section-title">📬 Get In Touch</h2>
                <div class="wwm-title-line mx-auto wwm-title-line--yellow"></div>
                <p class="text-white mt-3">Ready to insert coin? Let's talk.</p>
            </div>

            <div class="row g-5 justify-content-center">

                <!-- Contact Info -->
                <div class="col-md-4 col-lg-3">
                    <div class="glass glass-border rounded-3 p-4 h-100">
                        <h4 class="color-cyan mb-4">Direct Contact</h4>
                        <div class="wwm-contact-item mb-4">
                            <div class="wwm-contact-label color-orange">
                                <i class="fas fa-envelope me-2"></i>Email
                            </div>
                            <a href="mailto:johnny5arcademail@gmail.com" class="wwm-contact-value text-white text-decoration-none">
                                johnny5arcademail@gmail.com
                            </a>
                        </div>
                        <div class="wwm-contact-item mb-4">
                            <div class="wwm-contact-label color-orange">
                                <i class="fab fa-instagram me-2"></i>Instagram
                            </div>
                            <a href="https://www.instagram.com/johnny5arcade" target="_blank" rel="noopener" class="wwm-contact-value text-white text-decoration-none">
                                @johnny5arcade
                            </a>
                        </div>
                        <div class="wwm-contact-item">
                            <div class="wwm-contact-label color-orange">
                                <i class="fab fa-tiktok me-2"></i>TikTok
                            </div>
                            <a href="https://www.tiktok.com/@johnny5arcade" target="_blank" rel="noopener" class="wwm-contact-value text-white text-decoration-none">
                                @johnny5arcade
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-md-8 col-lg-7">
                    <div class="glass glass-border rounded-3 p-4 p-md-5">

                        <?php if ( $wwm_message ) : ?>
                            <div class="alert alert-success wwm-alert wwm-alert--success mb-4" role="alert">
                                <?php echo esc_html( $wwm_message ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $wwm_error ) : ?>
                            <div class="alert alert-danger wwm-alert wwm-alert--error mb-4" role="alert">
                                <?php echo esc_html( $wwm_error ); ?>
                            </div>
                        <?php endif; ?>

                        <h4 class="color-cyan mb-4">Send a Partnership Inquiry</h4>

                        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" class="wwm-contact-form" novalidate>
                            <input type="hidden" name="action" value="wwm_contact">
                            <?php wp_nonce_field( 'wwm_contact_nonce', 'wwm_nonce' ); ?>
                            <input type="hidden" name="wwm_redirect" value="<?php echo esc_url( get_permalink() ); ?>">

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="wwm_name" class="form-label">Your Name <span class="color-orange">*</span></label>
                                    <input type="text" id="wwm_name" name="wwm_name" class="form-control" placeholder="Insert Name Here" required maxlength="100">
                                </div>
                                <div class="col-sm-6">
                                    <label for="wwm_email" class="form-label">Email Address <span class="color-orange">*</span></label>
                                    <input type="email" id="wwm_email" name="wwm_email" class="form-control" placeholder="you@brand.com" required maxlength="200">
                                </div>
                                <div class="col-sm-6">
                                    <label for="wwm_company" class="form-label">Company / Brand</label>
                                    <input type="text" id="wwm_company" name="wwm_company" class="form-control" placeholder="Acme Games Co." maxlength="100">
                                </div>
                                <div class="col-sm-6">
                                    <label for="wwm_type" class="form-label">Partnership Type <span class="color-orange">*</span></label>
                                    <select id="wwm_type" name="wwm_type" class="form-control" required>
                                        <option value="">— Select One —</option>
                                        <option value="Sponsored Reels">Sponsored Reels</option>
                                        <option value="YouTube Shorts">YouTube Shorts</option>
                                        <option value="Product Review">Product Review</option>
                                        <option value="Website Feature">Website Feature</option>
                                        <option value="Affiliate Campaign">Affiliate Campaign</option>
                                        <option value="Brand Ambassadorship">Brand Ambassadorship</option>
                                        <option value="Other">Other / Not Sure Yet</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="wwm_message" class="form-label">Tell Us About Your Brand <span class="color-orange">*</span></label>
                                    <textarea id="wwm_message" name="wwm_message" class="form-control" rows="5"
                                              placeholder="What's your product? What kind of collaboration did you have in mind? Any budget or timeline info is helpful." required maxlength="2000"></textarea>
                                </div>
                                <div class="col-12 text-center mt-2">
                                    <button type="submit" class="btn btn-retro btn-green wwm-submit-btn">
                                        <i class="fas fa-paper-plane me-2"></i> Insert Coin & Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div><!-- .work-with-me-page -->

<?php get_footer(); ?>
