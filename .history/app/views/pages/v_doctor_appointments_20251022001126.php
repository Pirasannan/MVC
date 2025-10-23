<?php require APPROOT.'/views/inc/header.php'; ?>

<div class="dashboard-container doctor">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo">
                <span class="logo-text">MEDILINK</span>
            </div>
        </div>
        <nav class="nav-menu">
            <a href="<?php echo URLROOT; ?>/Pages/doctordashboard" class="nav-item">Dashboard</a>
            <a href="<?php echo URLROOT; ?>/Appointments/doctor" class="nav-item active">Appointments</a>
            <a href="<?php echo URLROOT; ?>/Pages/doctorPrescriptions" class="nav-item">Prescriptions</a>
            <a href="<?php echo URLROOT; ?>/Pages/doctorMessages" class="nav-item">Messages</a>
            <a href="<?php echo URLROOT; ?>/Pages/doctorMedicalrecords" class="nav-item">Medical Records</a>
            <a href="<?php echo URLROOT; ?>/Pages/doctorProfile" class="nav-item">Profile</a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1 class="page-title">Appointments</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar"><span class="avatar-icon">👤</span></div>
                    <div class="user-details">
                        <span class="user-name">Doctor</span>
                        <span class="user-id">ID: —</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-content">

            <?php if(!empty($data['flash'])): ?>
                <div class="notice success"><?= htmlspecialchars($data['flash']) ?></div>
            <?php endif; ?>

            <div class="content-sections">
                <!-- ===== INCOMING (need action) — first box ===== -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            Incoming (need action)
                            <?php $incomingCount = !empty($data['pending']) ? count($data['pending']) : 0; ?>
                            <span class="count-badge"><?= $incomingCount ?></span>
                        </h2>
                    </div>

                    <div class="section-content">
                        <?php if(empty($data['pending'])): ?>
                            <div class="empty-state">
                                <div class="empty-title">No incoming requests</div>
                                <div class="empty-text">New appointment requests that need your approval will appear here.</div>
                            </div>
                        <?php else: ?>
                            <div class="table-wrapper">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Patient</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['pending'] as $a): ?>
                                            <tr>
                                                <td><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
                                                <td><?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?></td>
                                                <td><?= htmlspecialchars($a->patient_name) ?></td>
                                                <td>
                                                    <?php $status = strtolower($a->status);
                                                          $badgeClass = in_array($status, ['approved','confirmed']) ? 'confirmed' :
                                                                        ($status === 'pending' ? 'pending' :
                                                                        ($status === 'rejected' ? 'rejected' :
                                                                        ($status === 'cancelled' ? 'cancelled' : 'scheduled'))); ?>
                                                    <span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($a->status) ?></span>
                                                </td>
                                                <td class="text-right">
                                                    <div class="table-actions">
                                                        <a class="action-link approve" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/approved">Approve</a>
                                                        <span class="action-sep">|</span>
                                                        <a class="action-link reject" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/rejected">Reject</a>
                                                        <span class="action-sep">|</span>
                                                        <a class="action-link cancel" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled">Cancel</a>
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

                <!-- ===== APPROVED (ready to conduct) — second box below ===== -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            Approved (ready to conduct)
                            <?php $approvedCount = !empty($data['approved']) ? count($data['approved']) : 0; ?>
                            <span class="count-badge"><?= $approvedCount ?></span>
                        </h2>
                    </div>

                    <div class="section-content">
                        <?php if(empty($data['approved'])): ?>
                            <div class="empty-state">
                                <div class="empty-title">No approved appointments yet</div>
                                <div class="empty-text">Approved sessions will appear here with quick actions.</div>
                            </div>
                        <?php else: ?>
                            <div class="table-wrapper">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Patient</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['approved'] as $a): ?>
                                            <tr>
                                                <td><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
                                                <td>
                                                    <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?>
                                                    <?php $mins = (int) round((strtotime($a->ends_at) - strtotime($a->starts_at)) / 60);
                                                          if ($mins > 0): ?>
                                                        <small>(<?= $mins ?> min)</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($a->patient_name) ?></td>
                                                <td><span class="status-badge confirmed"><?= htmlspecialchars($a->status) ?></span></td>
                                                <td class="text-right">
                                                    <div class="table-actions">
                                                        <!-- Dummy Start button -->
                                                        <button type="button" class="action-button" disabled title="Coming soon">Start</button>
                                                        <span class="action-sep">|</span>
                                                        <a class="action-link cancel" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled">Cancel</a>
                                                        <span class="action-sep">|</span>
                                                        <a class="action-link complete" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/completed">Complete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="section-footer">
                        <button class="action-button secondary" type="button" onclick="window.scrollTo({top:0, behavior:'smooth'})">Back to Top</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
