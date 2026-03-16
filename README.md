<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel">
  
  # 🌟 JOJO CMS: THE ULTIMATE FAN ARCHIVE 🌟
  
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php)](https://php.net)
  [![Tailwind](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
  [![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

  **A high-octane archive for JoJo's Bizarre Adventure fans, built with Laravel 12.**  
  *Social features, Episode Tracking, and Premium JoJo Protagonist Avatars.*
</div>

---

## ✨ KEY FEATURES

### 🏗️ Complete Arc Inventory (Parts 1-7)
Meticulously curated data for every JoJo arc, from **Phantom Blood** to a fully custom implementation of **Steel Ball Run**.
- **Official Media**: Restored high-fidelity thumbnails and posters.
- **Dynamic Updates**: Automatically tracks and displays the latest released episode on the home page.

### 👤 Premium Social Sanctuary
Your profile isn't just data—it's your JoJo identity.
- **Identity Selection**: Choose from 8 custom-generated protagonist avatars (Jonathan to Gappy).
- **Social Stats**: Track your Followers, Following, and Watch History.
- **Fan Discovery**: Find other JoJo fans via the global **Community Search** and follow their journey.

### 📺 Immersive Viewing Experience
- **Episode Tracking**: Mark episodes as "Watched" and keep track of your progress.
- **Favorites**: Build your personal collection of favorite parts and episodes.
- **Rich Media**: Integrated official trailers and verified episode metadata.

---

## 🎨 DESIGN AESTHETICS
The JoJo CMS uses a custom **Bizarre Brand Interface**:
- **Palette**: Deep Purples, Vibrant Fuchsias, and Golden Yellows.
- **Typography**: Uses 'Bangers' for high-impact JoJo-style headers.
- **Interactions**: Skewed transforms, glassmorphism, and bold "DoDoDo" micro-animations.

---

## 🚀 QUICK START

### Prerequisites
- PHP 8.4+
- MySQL
- Composer & NPM

### Installation
1. **Clone the repository**
   ```bash
   git clone https://github.com/Yaman69420/jojo-cms.git
   cd jojo-cms
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database & Content Seeding**
   ```bash
   php artisan migrate --seed
   ```

5. **Storage Activation**
   ```bash
   php artisan storage:link
   ```

---

## 🛡️ SECURITY & LICENSE
This project is open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.  
All JoJo's Bizarre Adventure assets are property of their respective creators (Hirohiko Araki / Lucky Land Communications / David Production).

---

<div align="center">
  <sub>Built with ❤️ by Yaman & Antigravity</sub>
</div>
