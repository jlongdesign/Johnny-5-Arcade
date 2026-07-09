<?php get_header(); ?>

<div class="games-archive-page container mt-5">
    <section class="arcade-section">
        <div class="text-center mb-5">
            <h1 class="mb-3 text-cyan text-shadow-cyan">
                🎮 Game Library Database
            </h1>
            <p class="text-white fs-5 text-primary">
                Discover and play classic games from the golden age of gaming!
            </p>
        </div>
        
        <!-- Game Filters -->
        <div class="card game-filters mb-4 p-lg-4 p-2 section-bg border-yellow">
            <div class="card-body">
                <h3 class="card-title mb-3 text-yellow">🔍 Filter Games</h3>
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label for="console-filter" class="form-label text-green">🎮 Console:</label>
                        <select id="console-filter" name="console" class="form-select">
                            <option value="">All Consoles</option>
                            <?php
                            $consoles = get_terms(array(
                                'taxonomy' => 'console',
                                'hide_empty' => false
                            ));
                            foreach ($consoles as $console) {
                                echo '<option value="' . esc_attr($console->slug) . '">' . esc_html($console->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label for="genre-filter" class="form-label text-green">🎯 Genre:</label>
                        <select id="genre-filter" name="genre" class="form-select">
                            <option value="">All Genres</option>
                            <?php
                            $genres = get_terms(array(
                                'taxonomy' => 'game_genre',
                                'hide_empty' => false
                            ));
                            foreach ($genres as $genre) {
                                echo '<option value="' . esc_attr($genre->slug) . '">' . esc_html($genre->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label for="year-filter" class="form-label">📅 Year:</label>
                        <select id="year-filter" name="year" class="form-select border-lime">
                            <option value="">All Years</option>
                            <?php
                            $years = get_terms(array(
                                'taxonomy' => 'game_year',
                                'hide_empty' => false
                            ));
                            foreach ($years as $year) {
                                echo '<option value="' . esc_attr($year->slug) . '">' . esc_html($year->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-end">
                        <button id="apply-filters" class="btn retro-button w-100">
                            🎯 Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Games Grid -->
        <div id="games-container" class="row g-4">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    echo '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">';
                    get_template_part('template-parts/game-card');
                    echo '</div>';
                endwhile;
            else :
                echo '<div class="col-12"><p class="text-muted text-center">No games found. Start adding some retro classics!</p></div>';
            endif;
            ?>
        </div>
        
        <!-- Pagination -->
        <div class="text-center mt-5">
            <?php
            echo paginate_links(array(
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
                'type' => 'list'
            ));
            ?>
        </div>
    </section>
</div>

<script>
jQuery(document).ready(function($) {
    $('#apply-filters').on('click', function() {
        var console = $('#console-filter').val();
        var genre = $('#genre-filter').val();
        var year = $('#year-filter').val();
        
        $.ajax({
            url: arcade_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_games',
                console: console,
                genre: genre,
                year: year,
                nonce: arcade_ajax.nonce
            },
            beforeSend: function() {
                $('#games-container').html('<div class="col-12"><div class="d-flex justify-content-center align-items-center py-5"><div class="loading"></div><span class="ms-3 text-muted">Loading games...</span></div></div>');
            },
            success: function(response) {
                $('#games-container').html(response);
            },
            error: function() {
                $('#games-container').html('<div class="col-12"><p class="text-danger text-center py-4">Error loading games. Please try again.</p></div>');
            }
        });
    });
});
</script>

<?php get_footer(); ?>
