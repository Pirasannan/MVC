<?php require APPROOT.'/views/inc/header.php'; ?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/messages.css?v=<?php echo time(); ?>'>

<div class="dashboard-container admin" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-user-type="<?php echo $_SESSION['user_role'] ?? 'admin'; ?>">
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <main class="main-content">
        <header class="top-header">
            <div class="header-left">
                <h1 class="page-title">Messages</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <span class="avatar-icon">👤</span>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                        <span class="user-id">ID: <?php echo $_SESSION['user_id'] ?? ''; ?></span>
                    </div>
                </div>
            </div>
        </header>

        <?php require APPROOT.'/views/inc/components/messageComponent.php'; ?>
    </main>
</div>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>
<script src='<?php echo URLROOT; ?>/js/messages.js?v=<?php echo time(); ?>'></script>

<?php require APPROOT.'/views/inc/footer.php'; ?>