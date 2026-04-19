<aside class="sidebar">
    <div class="logo-section">
        <div class="logo">
            <span class="logo-text">MEDILINK</span>
        </div>
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
        <button class="nav-item-bottom sidebar-toggle sidebar-toggle-bottom" type="button" aria-label="Toggle sidebar" aria-expanded="true">
            <span class="sidebar-toggle-icon" aria-hidden="true">&laquo;</span>
            <span class="nav-short" aria-hidden="true">C</span>
            <span class="nav-label">Collapse Sidebar</span>
        </button>
        <a href="#" class="nav-item-bottom" id="logoutBtn">
            <span class="nav-short" aria-hidden="true">L</span>
            <span class="nav-label">Logout</span>
        </a>
    </div>
</aside>

<?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>