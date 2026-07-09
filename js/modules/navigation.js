/**
 * Navigation Module
 * Handles mobile navigation, menu interactions, and responsive behavior
 */

const Navigation = {
    /**
     * Initialize navigation module
     */
    init() {
        this.initMobileNav();
        this.addEventListeners();
        console.log('🧭 Navigation initialized');
    },

    /**
     * Initialize mobile navigation
     */
    initMobileNav() {
        // Check if nav toggle already exists
        if (jQuery('.nav-toggle').length > 0) {
            return;
        }

        const navToggle = jQuery(`
            <button class="nav-toggle" style="
                display: none;
                background: #00ff00;
                color: #000;
                border: none;
                padding: 10px;
                font-size: 1.2rem;
                cursor: pointer;
                border-radius: 4px;
                position: relative;
                z-index: 1000;
            ">☰</button>
        `);
        
        jQuery('.header-content').append(navToggle);
        
        navToggle.on('click', (e) => {
            e.preventDefault();
            this.toggleMobileMenu();
        });
        
        // Initial check
        this.checkMobileNav();
    },

    /**
     * Add event listeners
     */
    addEventListeners() {
        // Responsive navigation check
        jQuery(window).on('resize', () => {
            this.checkMobileNav();
        });

        // Close mobile menu when clicking outside
        jQuery(document).on('click', (e) => {
            if (!jQuery(e.target).closest('.nav-menu, .nav-toggle').length) {
                this.closeMobileMenu();
            }
        });

        // Close mobile menu on ESC key
        jQuery(document).on('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeMobileMenu();
            }
        });

        // Handle menu item clicks on mobile
        jQuery('.nav-menu a').on('click', () => {
            if (jQuery(window).width() <= 768) {
                this.closeMobileMenu();
            }
        });
    },

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu() {
        const navMenu = jQuery('.nav-menu');
        const navToggle = jQuery('.nav-toggle');
        
        navMenu.toggleClass('mobile-active');
        
        // Update button appearance
        if (navMenu.hasClass('mobile-active')) {
            navToggle.text('✕').css('background', '#ff0000');
            this.preventBodyScroll(true);
        } else {
            navToggle.text('☰').css('background', '#00ff00');
            this.preventBodyScroll(false);
        }
    },

    /**
     * Close mobile menu
     */
    closeMobileMenu() {
        const navMenu = jQuery('.nav-menu');
        const navToggle = jQuery('.nav-toggle');
        
        if (navMenu.hasClass('mobile-active')) {
            navMenu.removeClass('mobile-active');
            navToggle.text('☰').css('background', '#00ff00');
            this.preventBodyScroll(false);
        }
    },

    /**
     * Check if mobile navigation should be shown
     */
    checkMobileNav() {
        const navToggle = jQuery('.nav-toggle');
        
        if (jQuery(window).width() <= 768) {
            navToggle.show();
        } else {
            navToggle.hide();
            this.closeMobileMenu();
        }
    },

    /**
     * Prevent body scroll when mobile menu is open
     */
    preventBodyScroll(prevent) {
        if (prevent) {
            jQuery('body').addClass('mobile-menu-open');
        } else {
            jQuery('body').removeClass('mobile-menu-open');
        }
    },

    /**
     * Add smooth scrolling to anchor links
     */
    addSmoothScrolling() {
        jQuery('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                location.hostname === this.hostname) {
                
                let target = jQuery(this.hash);
                target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
                
                if (target.length) {
                    jQuery('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                    return false;
                }
            }
        });
    },

    /**
     * Highlight active menu item
     */
    highlightActiveMenuItem() {
        const currentPath = window.location.pathname;
        
        jQuery('.nav-menu a').each(function() {
            const link = jQuery(this);
            const href = link.attr('href');
            
            if (href && currentPath.includes(href.replace(/^.*\/\/[^\/]+/, ''))) {
                link.addClass('active');
            }
        });
    }
};

// Make globally available
window.Navigation = Navigation;