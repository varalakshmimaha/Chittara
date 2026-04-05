/**
 * Chittara Star Awards — Shared layout renderer
 * Include this on every page to get consistent header, navbar, and footer.
 *
 * Usage:
 *   <div id="shared-header"></div>   ← header + social + navbar + vote CTA
 *   <div id="shared-footer"></div>   ← footer + sticky vote button
 *   <script src="/js/shared.js"></script>
 *   <script> loadSharedLayout(); </script>
 *
 *   Or call loadSharedLayout(callback) to get siteData after load.
 */

// Page route map — hash anchors → dedicated pages
var PAGE_MAP = {
  '#home':     '/',
  '#about':    '/',
  '#nominees': '/nominees.html',
  '#videos':   '/videos.html',
  '#gallery':  '/gallery.html',
  '#jury':     '/jury.html',
  '#partners': '/partners.html',
  '#vote':     '/vote.html'
};

// Detect current page for active highlighting
function getCurrentPage() {
  var path = window.location.pathname;
  if (path === '/' || path === '/index.html') return 'home';
  // e.g. /nominees.html → nominees
  var match = path.match(/\/(\w+)\.html/);
  return match ? match[1] : '';
}

// Map a nav link URL (from DB) to the label-based page key
function getPageKeyForUrl(url) {
  if (!url) return '';
  var mapped = PAGE_MAP[url];
  if (mapped === '/') return 'home';
  if (mapped) {
    var m = mapped.match(/\/(\w+)\.html/);
    return m ? m[1] : '';
  }
  return '';
}

function renderSharedHeader(siteData) {
  var s = siteData.settings || {};
  var container = document.getElementById('shared-header');
  if (!container) return;

  var html = '';

  // ===== TOP HEADER =====
  html += '<header class="top-header" id="home">';
  html += '<div class="top-header-inner">';
  html += '<div class="logo-left" id="logoLeft">';
  if (s.logo_top_left) html += '<a href="/"><img src="' + s.logo_top_left + '" alt="Logo"></a>';
  html += '</div>';
  html += '<div class="header-center" id="headerCenter">';
  if (s.logo1) html += '<a href="/"><img src="' + s.logo1 + '" alt="Chittara"></a>';
  html += '</div>';
  html += '<div class="logos-right" id="logosRight">';
  if (s.logo2) html += '<img src="' + s.logo2 + '" alt="Logo">';
  if (s.logo3) html += '<img src="' + s.logo3 + '" alt="Logo">';
  html += '</div>';
  html += '</div></header>';

  // ===== SOCIAL ROW =====
  html += '<div class="social-row"><div class="social-row-inner" id="socialRow">';
  if (s.social_twitter) {
    html += '<a href="' + s.social_twitter + '" target="_blank" class="social-icon" title="Twitter/X">' +
      '<svg viewBox="0 0 24 24"><path fill="#8b1a1a" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>';
  }
  if (s.social_instagram) {
    html += '<a href="' + s.social_instagram + '" target="_blank" class="social-icon" title="Instagram">' +
      '<svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="#8b1a1a"/><circle cx="12" cy="12" r="5" fill="none" stroke="#fff" stroke-width="2.5"/><circle cx="17.5" cy="6.5" r="1.3" fill="#fff"/></svg></a>';
  }
  if (s.social_youtube) {
    html += '<a href="' + s.social_youtube + '" target="_blank" class="social-icon" title="YouTube">' +
      '<svg viewBox="0 0 24 24"><rect x="1" y="5" width="22" height="14" rx="4" ry="4" fill="#8b1a1a"/><polygon points="10,8.5 16,12 10,15.5" fill="#fff"/></svg></a>';
  }
  if (s.social_facebook) {
    html += '<a href="' + s.social_facebook + '" target="_blank" class="social-icon" title="Facebook">' +
      '<svg viewBox="0 0 24 24"><path fill="#8b1a1a" d="M14 6h3V2h-3c-2.76 0-5 2.24-5 5v2H6v4h3v10h4V13h3l1-4h-4V7c0-.55.45-1 1-1z"/></svg></a>';
  }
  html += '</div></div>';

  // ===== NAVBAR =====
  html += '<nav class="navbar">';
  html += '<button class="mobile-menu-btn" onclick="document.getElementById(\'navLinks\').classList.toggle(\'open\')">&#9776;</button>';
  html += '<div class="navbar-inner" id="navLinks">';

  var currentPage = getCurrentPage();
  var navLinks = siteData.navLinks || [];

  navLinks.forEach(function(link) {
    var url = link.url || '#';
    var href = PAGE_MAP[url] || url;
    var pageKey = getPageKeyForUrl(url);
    var isActive = (pageKey === currentPage);

    // For hash links on the homepage, keep them as hash links when we're on the homepage
    if (url.startsWith('#') && !PAGE_MAP[url] && currentPage === 'home') {
      href = url;
    }

    html += '<a href="' + href + '"' + (isActive ? ' class="active"' : '') + '>' + link.label + '</a>';
  });

  html += '</div></nav>';

  // ===== VOTE CTA BAR =====
  html += '<div class="vote-cta-bar">';
  html += '<a href="/vote.html">' + (s.vote_button_text || 'Click here To Vote Now') + '</a>';
  html += '</div>';

  container.innerHTML = html;
}

function renderSharedFooter(siteData) {
  var s = siteData.settings || {};
  var container = document.getElementById('shared-footer');
  if (!container) return;

  var html = '';

  // Sticky vote button
  html += '<a href="/vote.html" class="sticky-vote-btn">Vote Now</a>';

  // Footer
  html += '<footer class="footer">';
  html += '<p>' + (s.footer_text || 'Copyright &copy; 2026 Chittara Awards. All rights reserved.') + '</p>';
  html += '<p>Powered by Chittara Media Networks</p>';
  html += '</footer>';

  container.innerHTML = html;
}

// Apply gold-wave background to all section-gold-bg elements
function applyGoldBg(siteData) {
  var s = siteData.settings || {};
  if (s.about_bg) {
    document.querySelectorAll('.section-gold-bg').forEach(function(el) {
      el.style.backgroundImage = "url('" + s.about_bg + "')";
    });
  }
}

/**
 * Main entry: fetch API data, render header + footer, then call your page callback.
 * @param {Function} callback  — called with siteData after layout is rendered
 */
async function loadSharedLayout(callback) {
  try {
    var res = await fetch('/api/public/data');
    var siteData = await res.json();
    var s = siteData.settings || {};

    // Set page title suffix
    if (s.site_title) {
      var currentTitle = document.title;
      if (!currentTitle.includes(s.site_title)) {
        document.title = currentTitle + ' | ' + s.site_title;
      }
    }

    renderSharedHeader(siteData);
    renderSharedFooter(siteData);
    applyGoldBg(siteData);

    if (typeof callback === 'function') {
      callback(siteData);
    }
  } catch (err) {
    console.error('Failed to load site data:', err);
  }
}

// Helper: YouTube/Vimeo embed URL
function getVideoEmbed(url) {
  if (!url) return '';
  var ytMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
  if (ytMatch) return 'https://www.youtube.com/embed/' + ytMatch[1];
  var vimeoMatch = url.match(/(?:vimeo\.com\/)(\d+)/);
  if (vimeoMatch) return 'https://player.vimeo.com/video/' + vimeoMatch[1];
  return url;
}
