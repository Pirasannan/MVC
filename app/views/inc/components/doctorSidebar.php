<?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo">
                <span class="logo-text">MEDILINK</span>
            </div>
        </div>
        
       
        <div class="sidebar-top"></div>
        <nav class="nav-menu">
            <?php 
                $menu_items = [
                    'Dashboard' => 'doctorDashboard',
                    'Appointments' => 'doctorAppointments',
                    'Prescriptions' => 'doctorPrescriptions',
                    'Messages' => 'doctorMessages',
                    'Medical Records' => 'doctorMedicalrecords',
                    'Profile' => 'doctorProfile'
                ];

                foreach($menu_items as $label => $action) {
                    $active = isset($current_page) && $current_page === $action ? ' active' : '';
                    echo '<a href="' . URLROOT . '/Pages/' . $action . '" class="nav-item' . $active . '">';
                    echo $label;
                    echo '</a>';
                }
            ?>
        </nav>

        <div class="sidebar-bottom nav-menu">
            <a href='#'class="nav-item-bottom" id="logoutBtn">Logout</a>
            <a href="<?php echo URLROOT; ?>/Pages/doctorSettings" class="nav-item-bottom" id="settingsBtn">Settings</a>
        </div>
        <?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>

    </aside>

   