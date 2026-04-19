<aside class="sidebar">
    <div class="logo-section">
        <div class="logo">
            <span class="logo-text">MEDILINK</span>
        </div>
        <button class="sidebar-toggle" type="button" aria-label="Toggle sidebar" aria-expanded="true">
            <span class="sidebar-toggle-icon">&laquo;</span>
        </button>
    </div>

    <nav class="nav-menu">
        <?php
            $menu_items = [
                'Dashboard' => 'adminDashboard',
                'Doctors' => 'adminDoctors',
                'Patients' => 'adminPatients',
                'Notifications' => 'adminNotifications',
                'Reports' => 'adminReports',
                'Messages' => 'adminMessages',
                'Login Logs' => 'adminLoginLogs',
                'Profile' => 'adminProfile'
            ];

            foreach ($menu_items as $label => $action) {
                $active = isset($current_page) && $current_page === $action ? ' active' : '';
                $words = preg_split('/\s+/', trim($label));
                $short = '';
                foreach ($words as $word) {
                    if ($word === '') {
                        continue;
                    }
                    $short .= strtoupper(substr($word, 0, 1));
                    if (strlen($short) >= 2) {
                        break;
                    }
                }
                if ($short === '') {
                    $short = strtoupper(substr($label, 0, 1));
                }

                echo '<a href="' . URLROOT . '/Pages/' . $action . '" class="nav-item' . $active . '">';
                echo '<span class="nav-short" aria-hidden="true">' . htmlspecialchars($short) . '</span>';
                echo '<span class="nav-label">' . htmlspecialchars($label) . '</span>';
                echo '</a>';
            }
        ?>
    </nav>

    <div class="sidebar-bottom">
        <a href="<?php echo URLROOT; ?>/Pages/adminSettings" class="nav-item-bottom" id="settingsBtn">
            <span class="nav-short" aria-hidden="true">S</span>
            <span class="nav-label">Settings</span>
        </a>
        <a href="#" class="nav-item-bottom" id="logoutBtn">
            <span class="nav-short" aria-hidden="true">L</span>
            <span class="nav-label">Logout</span>
        </a>
    </div>
</aside>

<?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>

<script>
(function () {
    if (window.medilinkSidebarInit) {
        return;
    }

    window.medilinkSidebarInit = true;
    var storageKey = 'medilinkSidebarCollapsed';
    var body = document.body;
    var buttons = document.querySelectorAll('.sidebar-toggle');

    if (!body || !buttons.length) {
        return;
    }

    var applyState = function (collapsed) {
        body.classList.toggle('sidebar-collapsed', collapsed);
        buttons.forEach(function (btn) {
            btn.setAttribute('aria-expanded', (!collapsed).toString());
            btn.setAttribute('title', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
            var icon = btn.querySelector('.sidebar-toggle-icon');
            if (icon) {
                icon.textContent = collapsed ? '\u00BB' : '\u00AB';
            }
        });
    };

    var collapsed = false;
    try {
        collapsed = localStorage.getItem(storageKey) === '1';
    } catch (error) {
        collapsed = false;
    }

    applyState(collapsed);

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var nextState = !body.classList.contains('sidebar-collapsed');
            applyState(nextState);
            try {
                localStorage.setItem(storageKey, nextState ? '1' : '0');
            } catch (error) {
                // Ignore storage write issues.
            }
        });
    });
})();
</script>