(function () {
  if (window.medilinkSidebarInit) {
    return;
  }

  window.medilinkSidebarInit = true;

  var initializeSidebarToggle = function () {
    var body = document.body;
    var hasSidebar = !!document.querySelector('.sidebar');
    var storageKey = 'medilinkSidebarCollapsed';
    var mobileMedia = window.matchMedia('(max-width: 1024px)');
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

    var ensureMobileControls = function () {
      var logoText = 'MEDILINK';
      var logoNode = document.querySelector('.sidebar .logo-text');
      if (logoNode && logoNode.textContent && logoNode.textContent.trim() !== '') {
        logoText = logoNode.textContent.trim();
      }

      var topbar = document.querySelector('.mobile-topbar');
      if (!topbar) {
        topbar = document.createElement('div');
        topbar.className = 'mobile-topbar';
        topbar.innerHTML = '' +
          '<div class="mobile-topbar-brand"></div>' +
          '<button type="button" class="mobile-sidebar-toggle" aria-label="Open menu">&#9776;</button>';
        body.appendChild(topbar);
      }

      var topbarBrand = topbar.querySelector('.mobile-topbar-brand');
      if (topbarBrand) {
        topbarBrand.textContent = logoText;
      }

      var toggleButton = topbar.querySelector('.mobile-sidebar-toggle');
      if (!toggleButton) {
        toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'mobile-sidebar-toggle';
        toggleButton.setAttribute('aria-label', 'Open menu');
        toggleButton.innerHTML = '&#9776;';
        topbar.appendChild(toggleButton);
      }

      var backdrop = document.querySelector('.mobile-sidebar-backdrop');
      if (!backdrop) {
        backdrop = document.createElement('div');
        backdrop.className = 'mobile-sidebar-backdrop';
        body.appendChild(backdrop);
      }
    };

    var closeMobileSidebar = function () {
      body.classList.remove('mobile-sidebar-open');
    };

    var toggleMobileSidebar = function () {
      body.classList.toggle('mobile-sidebar-open');
    };

    ensureMobileControls();

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

        if (button.classList.contains('sidebar-toggle-bottom')) {
          var label = button.querySelector('.nav-label');
          if (label) {
            label.textContent = collapsed ? 'Expand Sidebar' : 'Collapse Sidebar';
          }

          var shortLabel = button.querySelector('.nav-short');
          if (shortLabel) {
            shortLabel.textContent = collapsed ? 'E' : 'C';
          }
        }
      });

      var mobileToggle = document.querySelector('.mobile-sidebar-toggle');
      if (mobileToggle) {
        var isOpen = body.classList.contains('mobile-sidebar-open');
        mobileToggle.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
        mobileToggle.innerHTML = isOpen ? '&times;' : '&#9776;';
      }
    };

    var applyState = function (collapsed, persist) {
      if (mobileMedia.matches) {
        body.classList.remove('sidebar-collapsed');
        updateButtons(false);

        if (persist) {
          try {
            localStorage.setItem(storageKey, collapsed ? '1' : '0');
          } catch (error) {
            // Ignore storage write failures.
          }
        }

        return;
      }

      closeMobileSidebar();
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
      var mobileToggle = event.target.closest('.mobile-sidebar-toggle');
      if (mobileToggle) {
        toggleMobileSidebar();
        updateButtons(body.classList.contains('sidebar-collapsed'));
        return;
      }

      var backdrop = event.target.closest('.mobile-sidebar-backdrop');
      if (backdrop) {
        closeMobileSidebar();
        updateButtons(body.classList.contains('sidebar-collapsed'));
        return;
      }

      var toggle = event.target.closest('.sidebar-toggle');
      if (!toggle) {
        if (mobileMedia.matches && body.classList.contains('mobile-sidebar-open')) {
          var clickedInsideSidebar = !!event.target.closest('.sidebar');
          if (!clickedInsideSidebar) {
            closeMobileSidebar();
            updateButtons(body.classList.contains('sidebar-collapsed'));
          }
        }
        return;
      }

      if (mobileMedia.matches) {
        closeMobileSidebar();
        updateButtons(false);
        return;
      }

      var next = !body.classList.contains('sidebar-collapsed');
      applyState(next, true);
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && body.classList.contains('mobile-sidebar-open')) {
        closeMobileSidebar();
        updateButtons(body.classList.contains('sidebar-collapsed'));
      }
    });

    window.addEventListener('resize', function () {
      if (!mobileMedia.matches) {
        closeMobileSidebar();
      }

      applyState(getStoredState(), false);
    });

    window.medilinkApplySidebarState = applyState;
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeSidebarToggle);
  } else {
    initializeSidebarToggle();
  }
})();
