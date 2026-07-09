# 🕹️ Johnny 5 Arcade - Retro Gaming WordPress Theme

Welcome to **Johnny 5 Arcade**, a retro 90's arcade gaming WordPress theme that transforms your website into a nostalgic gaming paradise! This theme features browser-based game emulation, YouTube video galleries, a game database with filtering, and a retro reviews system.

## ✨ Features

### 🎮 Game Library Database
- **Custom Games Post Type** with detailed meta fields
- **Browser Emulation Support** for NES, SNES, Game Boy, Genesis, and Arcade games
- **Advanced Filtering** by console, genre, and release year
- **Game Screenshots Gallery** with lightbox modal
- **Trivia and Game Information** sections

### 📺 Retro TV Gallery
- **YouTube Video Integration** with custom video post type
- **Video Categories**: Commercials, Gameplay, Reviews, Documentaries
- **Modal Video Player** for seamless viewing experience
- **Video Statistics** and organized browsing

### ⭐ Reviews System
- **Nostalgic Game Reviews** with star ratings
- **User Submissions** with reviewer credits
- **Related Game Integration** linking reviews to your game database
- **Review Guidelines** and best practices

### 🎨 90's Retro Design
- **CRT Monitor Effects** with scanlines and glow
- **Neon Color Palette** (cyan, magenta, green, orange)
- **Pixel-Perfect Typography** using Courier New monospace
- **Retro Button Animations** with hover effects
- **Mobile Responsive** design for all devices

### 🎯 Special Features
- **Konami Code Easter Egg** (↑↑↓↓←→←→BA)
- **Sample Data Installer** for quick setup
- **AJAX Game Filtering** for smooth user experience
- **Accessibility Features** with keyboard navigation
- **SEO Optimized** with proper markup

## 🚀 Installation

1. **Upload the theme** to `/wp-content/themes/arcade-hub/`
2. **Activate the theme** in WordPress Admin → Appearance → Themes
3. **Install sample data** by going to Appearance → Arcade Sample Data
4. **Set up your navigation menu** with the recommended pages

## 📋 Setup Instructions

### 1. Install Sample Content
After activating the theme, you'll see a notice to install sample data. This includes:
- 6 Classic retro games (Mario, Zelda, Tetris, Sonic, etc.)
- 3 Nostalgic reviews
- 3 Retro video examples
- Pre-configured taxonomies (consoles, genres, years)

### 2. Create Navigation Menu
Go to **Appearance → Menus** and create a menu with these pages:
- **Home** (your main page)
- **Games** (`/games/` - Game library archive)
- **Retro TV** (`/retro-videos/` - Video gallery)
- **Reviews** (`/reviews/` - Reviews archive)

### 3. Add Your Content

#### Adding Games:
1. Go to **Games → Add New**
2. Fill in the game title and description
3. Add game meta information:
   - **ROM Options**: Choose between external URL or file upload
     - **External ROM URL**: Link to browser-playable ROM hosted elsewhere
     - **Upload ROM File**: Upload ROM directly to WordPress media library
   - **Emulator Type**: Choose the appropriate console
   - **Developer/Publisher**: Game creators
   - **Trivia**: Fun facts about the game
4. Set **Console**, **Genre**, and **Year** taxonomies
5. Upload a **featured image** for the game thumbnail

**ROM File Upload Notes:**
- Supported formats: .nes, .smc, .gb, .gbc, .gba, .bin, .md, .gen, .z64, .n64, .cue, .iso
- Files are stored in `/wp-content/uploads/` with automatic organization by date
- Uploaded ROM files take priority over external URLs
- Maximum file size depends on your WordPress/hosting settings
- **If upload fails**: Try renaming file to `.txt` extension (e.g., `game.nes.txt`) - this bypasses hosting restrictions

#### Adding Videos:
1. Go to **Retro Videos → Add New**
2. Enter video title and description
3. Add **YouTube Video ID** (the part after `watch?v=` in YouTube URLs)
4. Select **Video Type** (Commercial, Gameplay, Review, Documentary)

#### Adding Reviews:
1. Go to **Reviews → Add New**
2. Write your nostalgic review
3. Set the **Game Title** you're reviewing
4. Choose a **Star Rating** (1-5 stars)
5. Add **Reviewer Name** (defaults to post author)

## 🎮 Browser Game Emulation

The theme supports browser-based retro game emulation for these systems:
- **NES** (Nintendo Entertainment System)
- **SNES** (Super Nintendo)
- **Game Boy** / Game Boy Color / Game Boy Advance
- **Sega Genesis**
- **Arcade Games**

### Adding Playable Games:
1. Find browser-compatible ROM files or use services like:
   - RetroGames.cc
   - EmulatorJS
   - Archive.org
2. Add the emulator URL to the "ROM URL" field in your game post
3. Select the correct emulator type
4. The "Play Game" button will open the emulator in a modal

### 🎯 Best ROM URL Types:

#### **Option 1: Embedded Game URLs (Recommended)**
- `https://archive.org/embed/msdos_Super_Mario_Bros_1985`
- `https://archive.org/embed/SuperMarioWorld_201805`
- These work reliably without CORS issues
- Archive.org provides stable, fast loading games

#### **Option 2: Alternative Embedded Services**
- `https://demo.emulatorjs.org/beta/?system=nes&url=ROM_URL`
- Note: Some services like retrogames.cc may have CORS restrictions
- Always test URLs before using in production

#### **Option 2: Direct ROM Files**
- `https://archive.org/download/NINTENDO-NES-ROMS/Super%20Mario%20Bros.%20%281985%29%20%28World%29.nes` (Archive.org - recommended)
- `https://archive.org/download/GameBoyROMSet/Tetris%20%28World%29%20%28Rev%201%29.gb` (Game Boy example)
- `https://yoursite.com/wp-content/uploads/2025/07/mario.nes`
- Archive.org files have proper CORS headers and work in local development
- **Find ROMs**: Browse archive.org/details/NINTENDO-NES-ROMS or similar collections
- Self-hosted files require proper CORS headers for cross-origin access
- Best when hosted on the same domain as your website

#### **Option 3: External ROM URLs**
- Must be publicly accessible and allow cross-origin requests
- May have CORS restrictions causing green screen issues
- Test the URL in a browser first to ensure it downloads properly

### 📁 File Hosting Setup for Bluehost:

#### **Recommended Directory Structure:**
```
/wp-content/uploads/arcade-hub/
├── roms/
│   ├── nes/           # Nintendo ROMs
│   ├── snes/          # Super Nintendo ROMs  
│   ├── gameboy/       # Game Boy ROMs
│   ├── genesis/       # Sega Genesis ROMs
│   └── arcade/        # Arcade ROMs
├── emulators/
│   ├── retroarch/     # RetroArch Web Player files
│   └── cores/         # Emulator cores
├── saves/             # Game save states
├── screenshots/       # Game screenshots
└── covers/           # Game cover art
```

#### **File Upload Instructions:**

**Option A: Direct ROM Upload (Recommended)**
1. **Via WordPress Admin:**
   - Go to **Games → Add New** or edit existing game
   - Use the "Upload ROM File" button in the ROM File Options section
   - Select your ROM file from your computer
   - WordPress will automatically organize the file in `/wp-content/uploads/YYYY/MM/`

**Option B: Manual File Upload**
1. **Via cPanel File Manager:**
   - Login to your Bluehost cPanel
   - Navigate to `/public_html/wp-content/uploads/`
   - Create `arcade-hub` folder and subfolders above
   - Upload ROM files to appropriate console folders

2. **Via FTP/SFTP:**
   - Use FileZilla or similar FTP client
   - Connect to your Bluehost server
   - Navigate to `/public_html/wp-content/uploads/arcade-hub/`
   - Upload your ROM files

3. **File URLs in WordPress:**
   - **Uploaded files**: Automatically managed by WordPress (e.g., `https://yoursite.com/wp-content/uploads/2025/07/mario.nes`)
   - **Manual uploads**: `https://yoursite.com/wp-content/uploads/arcade-hub/roms/nes/game.nes`
   - **Example**: `https://yoursite.com/wp-content/uploads/arcade-hub/roms/nes/mario.nes`

#### **Important File Considerations:**
- **File Size Limits:** Check Bluehost upload limits (usually 64MB-128MB)
- **File Types:** Ensure ROM file extensions (.nes, .smc, .gb, .bin) are allowed
- **Legal Compliance:** Only upload ROMs you legally own or public domain games
- **Performance:** Consider using a CDN for faster ROM loading
- **Backup:** Include ROM files in your regular site backups

## 🎨 Customization

### Colors and Branding:
- Edit `style.css` to modify the retro color scheme
- The theme uses CSS custom properties for easy color changes
- Upload a custom logo in **Appearance → Customize**

### Adding Custom Consoles/Genres:
- Go to **Games → Consoles** or **Games → Genres**
- Add new taxonomy terms as needed
- The filter dropdowns will automatically update

### Custom CSS:
Add custom styles in **Appearance → Customize → Additional CSS**

## 📱 Mobile Experience

The theme is fully responsive and includes:
- **Mobile-optimized navigation** with hamburger menu
- **Touch-friendly buttons** meeting accessibility standards
- **Responsive game grid** that adapts to screen size
- **Modal optimization** for mobile viewing

## 🎯 SEO and Performance

- **Semantic HTML5** markup for better search engine understanding
- **Optimized images** with proper alt tags and responsive sizing
- **Fast loading** with minimal external dependencies
- **Schema markup** for games and reviews

## 🛠️ Technical Requirements

- **WordPress 5.0+**
- **PHP 7.4+**
- **Modern browser** with CSS Grid support
- **JavaScript enabled** for interactive features

## 🎪 Easter Eggs

Try entering the **Konami Code** while browsing: ↑↑↓↓←→←→BA
(Use arrow keys, then B and A keys)

## 🐛 Troubleshooting

### ROM File Upload Issues:
If WordPress media library won't accept ROM files, try these solutions:

**Solution 1: Rename File Extension**
1. Rename your ROM file from `game.nes` to `game.nes.txt`
2. Upload the file through WordPress media library
3. The theme will automatically handle .txt files as ROM files
4. No need to rename back - it will work as-is!

**Solution 2: Extract ZIP Files First**
1. If you uploaded a .zip file, extract it first
2. Upload the actual ROM file (.nes, .smc, .gb, etc.)
3. ZIP files cannot be emulated directly

**Solution 3: Use External URL**
1. Upload your ROM file via cPanel File Manager or FTP
2. Place it in `/wp-content/uploads/arcade-hub/roms/[console]/`
3. Use the external ROM URL field instead: `https://yoursite.com/wp-content/uploads/arcade-hub/roms/nes/game.nes`

**Solution 4: Contact Hosting Provider**
- Some hosting providers block binary file uploads
- Contact Bluehost support to whitelist ROM file extensions
- Ask them to allow: .nes, .smc, .gb, .gba, .bin, .z64, .iso

### Local Development Issues:
If testing on localhost (johnny-5-arcade.local):

**Common Problems:**
- Mixed HTTP/HTTPS content warnings
- CORS restrictions preventing ROM loading
- Download prompts instead of emulation
- "Insecure connection" errors

**Solutions for Local Development:**
1. **Use Demo Games**: Leave ROM URL empty to test with built-in demo games
2. **Use Embedded URLs**: Try `https://www.retrogames.cc/embed/40034-super-mario-bros-world.html`
3. **Deploy to Live Site**: Full ROM functionality works best on your live Bluehost site
4. **HTTPS Setup**: Configure local HTTPS if needed for testing

### Game Emulator Not Loading:
- **Green Screen Issue**: This usually means the ROM file URL is not accessible
  - Check that the ROM URL opens in a new browser tab
  - Ensure the file has proper CORS headers for cross-origin access
  - Try using an embedded game URL instead of direct ROM file
- **CORS Errors**: Direct ROM files need proper server headers
  - **Archive.org recommended**: Use `https://archive.org/embed/game-name` for reliable embedded games
  - **Avoid retrogames.cc**: Currently has CORS issues with EmulatorJS loader
  - Host ROM files on the same domain as your website
  - Add CORS headers to your server if hosting ROM files directly
- **Third-party Service Issues**: Some emulator services may become unavailable
  - Try alternative embedded URLs like Archive.org
  - Consider hosting ROM files directly on your server
  - Use the .txt file workaround if needed for uploads
- Check that the ROM URL is valid and publicly accessible
- Ensure the emulator type matches the game system
- Some ROMs may be blocked by browser security policies
- **Recommended**: Use Archive.org embedded games like `https://archive.org/embed/msdos_Super_Mario_Bros_1985`

### Videos Not Playing:
- Verify the YouTube Video ID is correct
- Check that the video is public and embeddable
- Some videos may have embedding restrictions

### Filters Not Working:
- Ensure JavaScript is enabled in the browser
- Check browser console for any script errors
- Verify AJAX is working (test with other WordPress AJAX features)

## 📝 Changelog

### Version 1.0
- Initial release
- Complete retro gaming theme with all core features
- Sample data installer
- Mobile responsive design
- Browser game emulation support

## 🤝 Contributing

Found a bug or have a feature request? This theme was created as a demonstration of WordPress theme development capabilities. Feel free to use it as a starting point for your own retro gaming projects!

## 📄 License

This theme is released under the GPL v2 or later license, same as WordPress itself.

---

**🎮 Happy Retro Gaming! 🕹️**

*Created with nostalgia and pixel-perfect love for the golden age of gaming.*
