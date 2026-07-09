<!-- Livestream Section -->
<?php if (get_theme_mod('livestream_enabled', false)) : 
    $platform = get_theme_mod('livestream_platform', 'youtube');
    $title = get_theme_mod('livestream_title', '🔴 Live Gaming Stream');
    $description = get_theme_mod('livestream_description', 'Join us for live retro gaming action!');
    
    // Get proper domain for Twitch parent parameter
    $domain = $_SERVER['HTTP_HOST'];
    $twitch_parent = str_replace('www.', '', $domain);

    // Handle local development domains
    if (strpos($domain, '.local') !== false) {
        $twitch_parent = 'localhost'; // Use localhost for local development
    }
    ?>
    
    <div class="livestream-section p-3">
        <div class="justify-content-center">
            <div class="">
                <div>
                    <h2 class="h4 text-center mb-1" style="color: #ff4444;">
                        <?php echo esc_html($title); ?>
                    </h2>
                    
                    <p class="text-center text-white mb-2">
                        <?php echo esc_html($description); ?>
                    </p>
                    
                    <div class="bg-dark border-lime border-orange p-2 rounded-3">
                        <?php if ($platform === 'youtube') : 
                            $youtube_url = get_theme_mod('livestream_youtube_url', '');
                            $youtube_id = arcade_hub_get_youtube_id($youtube_url);
                            if ($youtube_id) :
                                ?>
                                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                                    <iframe 
                                        src="https://www.youtube.com/embed/<?php echo esc_attr($youtube_id); ?>?autoplay=0&mute=1"
                                        title="<?php echo esc_attr($title); ?>"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        class="livestream-iframe">
                                    </iframe>
                                </div>
                                <?php
                            endif;
                        elseif ($platform === 'twitch') :
                            $twitch_input = get_theme_mod('livestream_twitch_channel', '');
                            if ($twitch_input) :
                                $current_domain = $_SERVER['HTTP_HOST'];
                                // Build multiple parent parameters for better compatibility
                                $parent_params = '';
                                $parents = [
                                    $current_domain,
                                    str_replace('www.', '', $current_domain),
                                    'localhost',
                                    '127.0.0.1'
                                ];
                                
                                // Remove duplicates and build parent string
                                $parents = array_unique($parents);
                                foreach ($parents as $parent) {
                                    $parent_params .= '&parent=' . urlencode($parent);
                                }
                                ?>
                                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                                    <?php if (is_numeric($twitch_input)) : ?>
                                        <!-- VOD Embed -->
                                        <iframe 
                                            src="https://embed.twitch.tv/embed/v1.html?video=<?php echo esc_attr($twitch_input); ?>&parent=<?php echo esc_attr($current_domain); ?>&autoplay=false&muted=true"
                                            title="<?php echo esc_attr($title); ?>"
                                            frameborder="0"
                                            scrolling="no"
                                            allowfullscreen
                                            class="livestream-iframe">
                                        </iframe>
                                    <?php else : ?>
                                        <!-- Live Stream Embed -->
                                        <iframe 
                                            src="https://embed.twitch.tv/embed/v1.html?channel=<?php echo esc_attr($twitch_input); ?>&parent=<?php echo esc_attr($current_domain); ?>&autoplay=false&muted=true"
                                            title="<?php echo esc_attr($title); ?>"
                                            frameborder="0"
                                            scrolling="no"
                                            allowfullscreen
                                            class="livestream-iframe">
                                        </iframe>
                                    <?php endif; ?>
                                </div>
                            <?php endif;
                        endif; ?>
                    </div><!-- end border div -->
                    
                    <!-- Dynamic indicator -->
                    <div class="text-center mt-3">
                        <?php 
                        $is_vod = ($platform === 'twitch' && is_numeric(get_theme_mod('livestream_twitch_channel', '')));
                        if ($is_vod) : ?>
                            <span class="badge vod-badge px-3 py-2" style="
                                background: linear-gradient(45deg, #9146ff, #b366ff);
                                color: white;
                                font-size: 0.8rem;
                            ">
                                📹 PAST STREAM
                            </span>
                        <?php else : ?>
                            <!-- <span class="badge live-badge px-3 py-2" style="
                                background: linear-gradient(45deg, #ff0000, #ff4444);
                                color: white;
                                font-size: 0.8rem;
                                animation: pulse-red 2s infinite;
                            ">
                                🔴 LIVE NOW
                            </span> -->
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>