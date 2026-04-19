(function () {
  if (window.medilinkSidebarInit) {
    return;
  }

  window.medilinkSidebarInit = true;

  var initializeSidebarToggle = function () {
    var body = document.body;
    var hasSidebar = !!document.querySelector('.sidebar');
    var storageKey = 'medilinkSidebarCollapsed';
    var initializedAttr = 'data-sidebar-init';

    if (!body || !hasSidebar) {
      if (body) {
        body.classList.remove('sidebar-collapsed');
      }
      return;
    }

    if (body.getAttribute(initializedAttr) === '1') {
      return;
    }

    body.setAttribute(initializedAttr, '1');

    var getStoredState = function () {
      try {
        return localStorage.getItem(storageKey) === '1';
      } catch (error) {
        return false;
      }
    };

    var updateButtons = function (collapsed) {
      var buttons = document.querySelectorAll('.sidebar-toggle');

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
      body.classList.toggle('sidebar-collapsed', collapsed);
      updateButtons(collapsed);

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

    document.addEventListener('click', function (event) {
      var toggle = event.target.closest('.sidebar-toggle');
      if (!toggle) {
        return;
      }

      var next = !body.classList.contains('sidebar-collapsed');
      applyState(next, true);
    });

    window.addEventListener('resize', function () {
      updateButtons(body.classList.contains('sidebar-collapsed'));
    });

    window.medilinkApplySidebarState = applyState;
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeSidebarToggle);
  } else {
    initializeSidebarToggle();
  }
})();
