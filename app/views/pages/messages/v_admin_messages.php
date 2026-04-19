<?php require APPROOT.'/views/inc/header.php'; 
$current_page = 'adminMessages';
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/messages.css?v=<?php echo time(); ?>'>

<div class="dashboard-container admin">        <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>


        <?php require APPROOT.'/views/inc/components/messageComponent.php'; ?>
    </main>
</div>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>
<script src='<?php echo URLROOT; ?>/js/messages.js?v=<?php echo time(); ?>'></script>

<?php require APPROOT.'/views/inc/footer.php'; ?>