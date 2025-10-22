<?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo">
                <span class="logo-text">MEDILINK</span>
            </div>
        </div>
        
        <nav class="nav-menu">
            <?php 
                $menu_items = [
                    'Dashboard' => 'patientDashboard',
                    'Appointments' => 'patientAppointments',
                    'Prescriptions' => 'patientPrescriptions',
                    'Messages' => 'patientMessages',
                    'Medical Records' => 'patientMedicalrecords',
                    'Profile' => 'patientProfile'
                ];

                foreach($menu_items as $label => $action) {
                    $active = isset($current_page) && $current_page === $action ? ' active' : '';
                    echo '<a href="' . URLROOT . '/Pages/' . $action . '" class="nav-item' . $active . '">';
                    echo $label;
                    echo '</a>';
                }
            ?>
        </nav>

        <div class="sidebar-bottom">
            <a href='#'class="nav-item-bottom" id="logoutBtn">Logout</a>
            <a href="<?php echo URLROOT; ?>/Pages/patientSettings" class="nav-item-bottom" id="settingsBtn">Settings</a>
        </div>
        <?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>

    </aside>
?>