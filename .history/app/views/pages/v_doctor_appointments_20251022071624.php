<?php require APPROOT.'/views/inc/header.php'; ?>




<div class="dashboard-container doctor">        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <span class="logo-text">MEDILINK</span>
                </div> 
            </div>
            
            <nav class="nav-menu">
                <a href="<?php echo URLROOT; ?>/Pages/doctordashboard" class="nav-item ">
                    Dashboard
                </a>
                <a href="<?php echo URLROOT; ?>/Appointments/doctor" class="nav-item active">
                    Appointments
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorPrescriptions" class="nav-item">
                    Prescriptions
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorMessages" class="nav-item">
                    Messages
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorMedicalrecords" class="nav-item">
                    Medical Records
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorProfile" class="nav-item">
                    Profile
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="appointments-container">
                <div class="page-header">
                    <h2 class="page-title">Appointments Management</h2>
                    <?php if(!empty($data['flash'])): ?>
                        <div class="flash-message">
                            <?= htmlspecialchars($data['flash']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pending Appointments Section -->
                <div class="appointments-section">
                    <div class="section-header">
                        <h3 class="section-title">Incoming Appointments</h3>
                        <span class="section-subtitle">Requests that need your action</span>
                    </div>
                    <div class="section-content">
                        <?php if(empty($data['pending'])): ?>
                            <div class="empty-state">
                                <p>No incoming requests at the moment.</p>
                            </div>
                        <?php else: ?>
                            <div class="appointments-table-container">
                                <table class="appointments-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Patient</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['pending'] as $a): ?>
                                            <tr>
                                                <td class="date-cell"><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
                                                <td class="time-cell"><?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?></td>
                                                <td class="patient-cell"><?= htmlspecialchars($a->patient_name) ?></td>
                                                <td class="status-cell">
                                                    <span class="status-badge pending"><?= htmlspecialchars($a->status) ?></span>
                                                </td>
                                                <td class="actions-cell">
                                                    <div class="action-buttons">
                                                        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/approved" class="btn btn-approve">Approve</a>
                                                        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/rejected" class="btn btn-reject">Reject</a>
                                                        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled" class="btn btn-cancel">Cancel</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Approved Appointments Section -->
                <div class="appointments-section">
                    <div class="section-header">
                        <h3 class="section-title">Approved Appointments</h3>
                        <span class="section-subtitle">Ready to conduct appointments</span>
                    </div>
                    <div class="section-content">
                        <?php if(empty($data['approved'])): ?>
                            <div class="empty-state">
                                <p>No approved appointments yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="appointments-table-container">
                                <table class="appointments-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Patient</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['approved'] as $a): ?>
                                            <tr>
                                                <td class="date-cell"><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
                                                <td class="time-cell">
                                                    <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?>
                                                    <small class="duration">(15 min)</small>
                                                </td>
                                                <td class="patient-cell"><?= htmlspecialchars($a->patient_name) ?></td>
                                                <td class="status-cell">
                                                    <span class="status-badge approved"><?= htmlspecialchars($a->status) ?></span>
                                                </td>
                                                <td class="actions-cell">
                                                    <div class="action-buttons">
                                                        <button type="button" class="btn btn-start" disabled>Start</button>
                                                        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled" class="btn btn-cancel">Cancel</a>
                                                        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/completed" class="btn btn-complete">Complete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>



        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>