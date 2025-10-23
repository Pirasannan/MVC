
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
                    'Profile' => 'adminProfile'
                ];

                foreach($menu_items as $label => $action) {
                    $active = isset($current_page) && $current_page === $action ? ' active' : '';
                    echo '<a href="' . URLROOT . '/Pages/' . $action . '" class="nav-item' . $active . '">';
                    echo $label;
                    echo '</a>';
                }
            ?>
        </nav>
    </aside>
?>