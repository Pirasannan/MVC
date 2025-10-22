     <!-- Logout confirmation popup -->
<div id="logoutPopup" class="popup-overlay">
  <div class="popup-box">
    <h3>Confirm Logout</h3>
    <p>Are you sure you want to log out?</p>
    <div class="popup-actions">
      <button id="confirmLogout" class="confirm-btn">Yes, Logout</button>
      <button id="cancelLogout" class="cancel-btn">Cancel</button>
    </div>
  </div>
</div>


<script>
document.getElementById("logoutBtn").addEventListener("click", function(e) {
  e.preventDefault();
  document.getElementById("logoutPopup").style.display = "flex";
});

document.getElementById("cancelLogout").addEventListener("click", function() {
  document.getElementById("logoutPopup").style.display = "none";
});

document.getElementById("confirmLogout").addEventListener("click", function() {
  window.location.href = "<?php echo URLROOT; ?>/Users/logout";
});
</script>