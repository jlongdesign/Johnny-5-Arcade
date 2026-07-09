<!-- Stats Section -->
    <section class="arcade-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="text-yellow text-shadow-yellow">🏆 Arcade Stats</h2>
                </div>
            </div>
            
            <div class="row g-4 text-center">
                <div class="col-12 col-md-3">
                    <div class="bg-dark p-4 rounded" style="border: 2px solid #00ff00;">
                        <h3 class="display-4 mb-2" style="color: #00ff00;">
                            <?php echo wp_count_posts('gaming_news')->publish; ?>
                        </h3>
                        <p class="text-white mb-0">Gaming News</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="bg-dark p-4 rounded" style="border: 2px solid #ff8000;">
                        <h3 class="display-4 mb-2" style="color: #ff8000;">
                            <?php echo wp_count_posts('game_mods')->publish; ?>
                        </h3>
                        <p class="text-white mb-0">Game Mods</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="bg-dark p-4 rounded" style="border: 2px solid #ff00ff;">
                        <h3 class="display-4 mb-2" style="color: #ff00ff;">
                            <?php echo wp_count_posts('reviews')->publish; ?>
                        </h3>
                        <p class="text-white mb-0">Reviews Written</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="bg-dark p-4 rounded" style="border: 2px solid #00ffff;">
                        <h3 class="display-4 mb-2" style="color: #00ffff;">
                            <?php echo wp_count_posts('retro_videos')->publish; ?>
                        </h3>
                        <p class="text-white mb-0">Retro Videos</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>