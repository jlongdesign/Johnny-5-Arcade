<?php
/**
 * Home Section: Amazon Affiliate Picks
 *
 * To update products, edit the $amazon_picks array below.
 * - image:       Direct URL to the product image (use your own hosted image or
 *                an Amazon image URL from the affiliate dashboard).
 * - name:        Product display name.
 * - description: Short one-liner shown on the card.
 * - price:       Display price string (update manually when price changes).
 * - badge:       Optional badge text, e.g. "Top Pick", "Best Value", "Staff Fave". Leave '' to hide.
 * - link:        Your full Amazon affiliate product URL.
 */

$amazon_picks = array(
    array(
        'image'       => 'https://m.media-amazon.com/images/I/61sBd4+C3lL._SX522_.jpg',
        'name'        => 'My Top Retro Pick',
        'description' => 'Retro Handheld Game Linux System RG3566 3.5 inch IPS Screen,RG353VS with 64G TF Card Pre-Installed 4452 Games Supports 5G WiFi 4.2 Bluetooth Online Fighting,Streaming and HDMI',
        'price'       => '$99.00',
        'badge'       => 'Top Pick',
        'link'        => 'https://amzn.to/4wyp4Rw',
    ),
    array(
        'image'       => 'https://m.media-amazon.com/images/I/81QAWSjYuHL._AC_SX679_.jpg',
        'name'        => 'My Second Fave',
        'description' => 'CanaKit Raspberry Pi 4 4GB Starter PRO Kit - 4GB RAM',
        'price'       => '$149.98',
        'badge'       => 'Staff Fave',
        'link'        => 'https://amzn.to/4wyp4Rw',
    ),
    array(
        'image'       => 'https://m.media-amazon.com/images/I/51hUzkHpoEL._SX522_.jpg',
        'name'        => 'Best Value Find',
        'description' => '$25 PlayStation Store Gift Card [Digital Code]',
        'price'       => '$23.75',
        'badge'       => 'Best Value',
        'link'        => 'https://amzn.to/44fLT0d',
    ),
    array(
        'image'       => 'https://m.media-amazon.com/images/I/71BMsuCG2QL._SX522_.jpg',
        'name'        => 'Hidden Gem',
        'description' => 'Retrotech Super 900 In 1 Cartridge For SNES Super Nintendo 16Bit Game Console - Red',
        'price'       => '$70.99',
        'badge'       => '',
        'link'        => 'https://amzn.to/4vVJDHJ',
    ),
);

$storefront_url = 'https://a.co/d/0frvA0qY';
?>

<!-- Amazon Affiliate Picks Section -->
<section id="home-amazon-picks" class="amazon-picks-section mb-5">
    <div class="container">

        <!-- Section Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="amazon-picks-title color-orange text-shadow-orange mb-2">
                    🛒 Johnny's Top Amazon Picks
                </h2>
                <p class="amazon-picks-subtitle text-white">
                    Gear I actually use and recommend — curated from my game room to yours.
                </p>
                <p class="amazon-picks-disclosure text-white">
                    <small><em>As an Amazon Associate I earn from qualifying purchases.</em></small>
                </p>
            </div>
        </div>

        <!-- Product Cards Grid -->
        <div class="row g-4 mb-5">
            <?php foreach ( $amazon_picks as $product ) : ?>
            <div class="col-6 col-lg-3 d-flex">
                <a href="<?php echo esc_url( $product['link'] ); ?>"
                   target="_blank"
                   rel="noopener sponsored"
                   class="text-decoration-none amazon-product-link w-100 h-100">
                    <div class="amazon-product-card glass glass-card rounded-3 h-100 d-flex flex-column">

                        <!-- Badge -->
                        <?php if ( ! empty( $product['badge'] ) ) : ?>
                        <div class="amazon-product-badge">
                            <?php echo esc_html( $product['badge'] ); ?>
                        </div>
                        <?php endif; ?>

                        <!-- Image -->
                        <div class="amazon-product-img-wrap bg-white">
                            <img
                                src="<?php echo esc_url( $product['image'] ); ?>"
                                alt="<?php echo esc_attr( $product['name'] ); ?>"
                                class="amazon-product-img"
                                loading="lazy"
                                onerror="this.parentElement.innerHTML='<div class=\'amazon-product-img-placeholder\'><i class=\'fas fa-shopping-cart\'></i></div>'">
                        </div>

                        <!-- Info -->
                        <div class="amazon-product-body d-flex flex-column flex-grow-1 p-3">
                            <h3 class="amazon-product-name color-cyan">
                                <?php echo esc_html( $product['name'] ); ?>
                            </h3>
                            <p class="amazon-product-desc text-white">
                                <?php echo esc_html( $product['description'] ); ?>
                            </p>
                            <div class="mt-auto pt-2">
                                <div class="amazon-product-price color-yellow">
                                    <?php echo esc_html( $product['price'] ); ?>
                                </div>
                                <div class="amazon-product-cta btn btn-retro btn-orange w-100 mt-2">
                                    <i class="fab fa-amazon me-1"></i> View on Amazon
                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- View All Button -->
        <div class="row">
            <div class="col-12 text-center">
                <a href="<?php echo esc_url( $storefront_url ); ?>"
                   target="_blank"
                   rel="noopener sponsored"
                   class="btn btn-lg btn-retro btn-orange amazon-storefront-btn">
                    <i class="fab fa-amazon me-2"></i> View My Full Amazon Storefront
                </a>
            </div>
        </div>

    </div>
</section>
