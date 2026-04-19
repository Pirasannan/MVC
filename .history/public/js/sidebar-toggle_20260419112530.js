(function () {
  if (window.medilinkSidebarInit) {
    return;
  }

  window.medilinkSidebarInit = true;

  document.addEventListener('DOMContentLoaded', function () {
    var body = document.body;
    var sidebar = document.querySelector('.sidebar');
    var buttons = document.querySelectorAll('.sidebar-toggle');
    var storageKey = 'medilinkSidebarCollapsed';
    var desktopMedia = window.matchMedia('(min-width: 1025px)');

    if (!body || !sidebar || !buttons.length) {
      if (body) {
        body.classList.remove('sidebar-collapsed');
      }
      return;
    }

    var getStoredState = function () {
      try {
        return localStorage.getItem(storageKey) === '1';
      } catch (error) {
        return false;
      }
    };

    var updateButtons = function (collapsed) {
      buttons.forEach(function (button) {
        button.setAttribute('aria-expanded', (!collapsed).toString());
        button.setAttribute('title', collapsed ? 'Expand sidebar' : 'Collapse sidebar');

        var icon = button.querySelector('.sidebar-toggle-icon');
        if (icon) {
          icon.textContent = collapsed ? '\u00BB' : '\u00AB';
        }
      });
    };

    var applyState = function (collapsed, persist) {
      var nextCollapsed = desktopMedia.matches ? collapsed : false;
      body.classList.toggle('sidebar-collapsed', nextCollapsed);
      updateButtons(nextCollapsed);

      if (!persist) {
        return;
      }

      try {
        localStorage.setItem(storageKey, collapsed ? '1' : '0');
      } catch (error) {
        // Ignore storage write failures.
      }
    };

    applyState(getStoredState(), false);

    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        var next = !body.classList.contains('sidebar-collapsed');
        applyState(next, true);
      });
    });

    window.addEventListener('resize', function () {
      applyState(getStoredState(), false);
    });
  });
})();
