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
            <a href="<?php echo URLROOT; ?>/Users/logout" class="nav-item-bottom" id="logoutBtn">Logout</a>
        </div>
        <?php require APPROOT.'/views/inc/components/logoutPopup.php'; ?>

    </aside>
?>