<!-- Comments Section -->
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                ?>
                <div class="glass glass-border rounded-4 p-3">
                    <div class="text-white p-3">
                        <h3 class="card-title mb-0 d-flex align-items-center">
                            💬 Player Comments 
                            <span class="badge bg-warning text-dark ms-2"><?php echo get_comments_number(); ?></span>
                        </h3>
                        <small class="text-light">Share your thoughts on this gaming news!</small>
                    </div>
                    <div class="p-3 text-light rounded" style="background-color: #111111;">
                        <?php comments_template(); ?>
                    </div>
                </div>
                <?php
            endif;
            ?>
        </div>
    </div>
</div>