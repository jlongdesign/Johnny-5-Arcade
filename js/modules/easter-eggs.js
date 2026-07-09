/**
 * Konami Code easter egg
 */
let konamiCode = [];
const konamiSequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // ↑↑↓↓←→←→BA

jQuery(document).on('keydown', function(e) {
    konamiCode.push(e.keyCode);
    
    if (konamiCode.length > konamiSequence.length) {
        konamiCode.shift();
    }
    
    if (konamiCode.length === konamiSequence.length && 
        konamiCode.every((code, index) => code === konamiSequence[index])) {
        
        activateKonamiCode();
        konamiCode = [];
    }
});

/**
 * Activate Konami Code easter egg
 */
function activateKonamiCode() {
    console.log('🎉 Konami Code activated!');
    
    // Add special effects
    jQuery('body').addClass('konami-active');
    
    // Show easter egg message
    const message = jQuery(`
        <div id="konami-message" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(45deg, #ff0080, #8000ff);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            z-index: 10001;
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            border: 3px solid #fff;
            animation: pulse 1s infinite;
        ">
            <h3>🎉 KONAMI CODE ACTIVATED! 🎉</h3>
            <p>You've unlocked the secret retro mode!</p>
            <p style="font-size: 0.9rem; margin-top: 15px;">
                ↑↑↓↓←→←→BA - Classic!
            </p>
            <button onclick="jQuery('#konami-message').fadeOut()" 
                    style="background: white; color: #8000ff; border: none; padding: 10px 20px; border-radius: 5px; margin-top: 15px; cursor: pointer; font-weight: bold;">
                AWESOME!
            </button>
        </div>
    `);
    
    jQuery('body').append(message);
    
    // Remove after 10 seconds
    setTimeout(() => {
        message.fadeOut();
        jQuery('body').removeClass('konami-active');
    }, 10000);
}