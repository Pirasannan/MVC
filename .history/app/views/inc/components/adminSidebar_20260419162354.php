<aside class="sidebar">
    <div class="logo-section">
        <div class="logo">
            <span class="logo-mini" aria-hidden="true">M</span>
            <span class="logo-text">MEDILINK</span>
        </div>
    </div>

    <nav class="nav-menu">
        <?php
            $menu_items = [
                ['label' => 'Dashboard', 'action' => 'adminDashboard', 'icon' => 'dashboard.svg'],
                ['label' => 'Doctors', 'action' => 'adminDoctors', 'icon' => 'doctors.svg'],
                ['label' => 'Patients', 'action' => 'adminPatients', 'icon' => 'patients.svg'],
                ['label' => 'Notifications', 'action' => 'adminNotifications', 'icon' => 'notifications.svg'],
                ['label' => 'Reports', 'action' => 'adminReports', 'icon' => 'reports.svg'],
                ['label' => 'Messages', 'action' => 'adminMessages', 'icon' => 'message.svg'],
                ['label' => 'Login Logs', 'action' => 'adminLoginLogs', 'icon' => 'login_logs.svg'],
                ['label' => 'Profile', 'action' => 'adminProfile', 'icon' => 'profile.svg']
            ];

            foreach ($menu_items as $item) {
                $label = $item['label'];
                $action = $item['action'];
                $icon = $item['icon'];
                $active = isset($current_page) && $current_page === $action ? ' active' : '';

                echo '<a href="' . URLROOT . '/Pages/' . $action . '" class="nav-item' . $active . '">';
                echo '<span class="nav-icon" aria-hidden="true" style="--icon: url(\'' . URLROOT . '/public/img/sidebar_icons/' . rawurlencode($icon) . '\');"></span>';
                echo '<span class="nav-label">' . htmlspecialchars($label) . '</span>';
                echo '</a>';
            }
        ?>
    </nav>

    <div class="sidebar-bottom">
        <button class="nav-item-bottom sidebar-toggle sidebar-toggle-bottom" type="button" aria-label="Toggle sidebar" aria-expanded="true">
            <span class="sidebar-toggle-icon" aria-hidden="true">&laquo;</span>
            <span class="nav-label">Collapse Sidebar</span>
        </button>
        <a href="#" class="nav-item-bottom" id="logoutBtn">
            <span class="nav-icon" aria-hidden="true" style="--icon: url('<?php echo URLROOT; ?>/public/img/sidebar_icons/logout.svg');"></span>
            <span class="nav-label">Logout</span>
        </a>
    </div>
</aside>

<?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>