<div class="main-header">
  <div style="display: flex; align-items: center; gap: var(--spacing-md);">
    <img src="../../assets/img/ZPPUS-CICS LOGO.jpg" alt="CICS Logo" style="width: 40px; height: 40px; border-radius: var(--radius-md);">
    <h1 class="main-header-title">Campus Attendance System</h1>
  </div>
  <div class="main-header-actions">
    <button class="btn-icon" title="Settings">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
    </button>
    <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
      <div title="Profile">
        <?php
        // Prefer a project PNG if present on disk and fall back to an inline SVG avatar to avoid broken images.
        $profileImgFile = __DIR__ . '/../../../assets/img/Instructor-Profile.png';
        $profileImgUrl = '/frontend/assets/img/Instructor-Profile.png';
        if (file_exists($profileImgFile)) {
            // Use a root-relative URL; if your setup serves assets from a different base, update this path.
            echo '<img src="' . $profileImgUrl . '" alt="Profile" class="minimalist-avatar-img">';
        } else {
            // Inline minimalist SVG avatar (always renders)
            echo '\n<div class="minimalist-avatar" aria-hidden="true">\n  <svg class="minimalist-avatar-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">\n    <circle cx="12" cy="8" r="3.2" fill="#fff" stroke="#2b3b4a" stroke-width="0.8"/>\n    <path d="M4 20c1.6-4 6.4-6 8-6s6.4 2 8 6" fill="#fff" stroke="#2b3b4a" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>\n  </svg>\n</div>\n';
        }
        ?>
      </div>
    </div>
    <button class="btn-icon" title="Logout" id="instructorHeaderLogoutBtn">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
      </svg>
    </button>
  </div>
</div>

