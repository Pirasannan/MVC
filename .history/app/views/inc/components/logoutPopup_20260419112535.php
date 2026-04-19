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
(function () {
  if (window.medilinkLogoutInit) {
    return;
  }

  window.medilinkLogoutInit = true;

  var initializeLogoutPopup = function () {
    var popup = document.getElementById('logoutPopup');
    var cancelBtn = document.getElementById('cancelLogout');
    var confirmBtn = document.getElementById('confirmLogout');

    if (!popup || !cancelBtn || !confirmBtn) {
      return;
    }

    document.addEventListener('click', function (event) {
      var trigger = event.target.closest('#logoutBtn');
      if (!trigger) {
        return;
      }

      event.preventDefault();
      popup.style.display = 'flex';
    });

    cancelBtn.addEventListener('click', function () {
      popup.style.display = 'none';
    });

    confirmBtn.addEventListener('click', function () {
      window.location.href = '<?php echo URLROOT; ?>/Users/logout';
    });

    popup.addEventListener('click', function (event) {
      if (event.target === popup) {
        popup.style.display = 'none';
      }
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeLogoutPopup);
  } else {
    initializeLogoutPopup();
  }
})();
</script>