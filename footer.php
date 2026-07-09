    </main>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-text social-links d-flex flex-wrap gap-3 justify-content-center">
                <a href="https://www.facebook.com/johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.youtube.com/@johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://www.tikTok.com/@johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://www.twitch.tv/johnny5arcade" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fab fa-twitch"></i>
                </a>
                <a href="https://johnny-5-arcade-shop.creator-spring.com/" target="_blank" rel="noopener" class="social-link btn p-2 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>

            <p class="footer-text">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> - Reliving the Golden Age of Gaming<br/>
                Powered by nostalgia <i class="fa-solid fa-gamepad text-warning"></i> and pixel magic ✨
                <br>
                <a class="text-white text-decoration-none" href="mailto:johnny5arcademail@gmail.com"><i class="fas fa-envelope"></i> Contact Us</a>
            </p>
            
            <p class="footer-text">

            </p>
            
        </div>
    </footer>

    <!-- Game Modal with Retro TV -->
    <div id="game-modal" class="game-modal">
        <div class="game-modal-content">
            <iframe id="game-frame" class="game-frame" src="" frameborder="0"></iframe>
        </div>
    </div>

    <!-- YouTube Player Modal -->
    <div id="youtube-modal" class="game-modal">
        <div class="game-modal-content">
            <button class="close-modal" onclick="closeYouTubeModal()">&times;</button>
            <iframe id="youtube-frame" class="game-frame" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    <?php wp_footer(); ?>

    <canvas id="starfield" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:-1;pointer-events:none;"></canvas>

<script>
const canvas = document.getElementById("starfield");
const ctx = canvas.getContext("2d");

const STAR_COUNT = 200;
const SPEED = 4;

let stars = [];

function resize() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
window.addEventListener("resize", resize);
resize();

function createStar() {
  return {
    x: Math.random() * canvas.width - canvas.width / 2,
    y: Math.random() * canvas.height - canvas.height / 2,
    z: Math.random() * canvas.width,
    pz: 0
  };
}

for (let i = 0; i < STAR_COUNT; i++) {
  const s = createStar();
  s.z = Math.random() * canvas.width; // spread initial z so they don't all rush in at once
  s.pz = s.z;
  stars.push(s);
}

function draw() {
  ctx.fillStyle = "rgba(0, 0, 0, 0.2)";
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  const cx = canvas.width / 2;
  const cy = canvas.height / 2;

  for (let star of stars) {
    star.pz = star.z;
    star.z -= SPEED;

    if (star.z <= 0) {
      Object.assign(star, createStar());
      star.z = canvas.width;
      star.pz = star.z;
      continue;
    }

    const sx = (star.x / star.z) * canvas.width + cx;
    const sy = (star.y / star.z) * canvas.width + cy;
    const px = (star.x / star.pz) * canvas.width + cx;
    const py = (star.y / star.pz) * canvas.width + cy;

    const size = Math.max(0.5, (1 - star.z / canvas.width) * 3);
    const brightness = Math.floor((1 - star.z / canvas.width) * 255);
    const color = `rgb(${brightness},${brightness},${brightness})`;

    ctx.beginPath();
    ctx.moveTo(px, py);
    ctx.lineTo(sx, sy);
    ctx.strokeStyle = color;
    ctx.lineWidth = size;
    ctx.stroke();
  }

  requestAnimationFrame(draw);
}

draw();
</script>

</body>

</html>
