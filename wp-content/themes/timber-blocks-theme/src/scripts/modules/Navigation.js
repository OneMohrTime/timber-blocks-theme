// =============================================================================
// Modules: Navigation
// =============================================================================
// Controls show/hide of primary navigation menus

// Import dependencies
// =============================================================================
import { module as es6Module } from 'modujs';

// Set default function and extend it ontop of our imported 'module'
// =============================================================================
export default class extends es6Module {
  // Set initial values
  // =========================================================================
  constructor(m) {
    super(m);

    // Vars
    this.classes = [];
    this.header = null;
    this.navigation = null;
    this.menu = null;
    this.toggleMenu = null;
    this.overlay = null;
    this.desktopBreakpoint = 900;
  }

  // Init module
  // =========================================================================
  init() {
    // Vars
    this.header     = this.el;
    this.navigation = this.header.querySelector('.c-navigation');
    this.menu       = this.header.querySelector('.c-navigation__menu');
    this.toggleMenu = this.header.querySelector('.c-navigation__toggle');
    this.dropdownLg = this.header.querySelectorAll('[data-trigger-megamenu]');
    this.dropdownSm = this.header.querySelectorAll('[data-trigger-submenu]');
    this.overlay    = document.querySelector('.o-site__overlay');
    this.updatePrimaryMenuAccessibility();
    this.updateOverlay();

    // Functions
    this.handleScroll();

    // Function to handle navbar transparency
    this.handleNavbarTransparency();

    if (this.toggleMenu) {
      this.toggleMenu.addEventListener('click', () => this.togglePrimaryNav());
    }

    if (this.menu) {
      this.menu.addEventListener('mouseenter', () => {
        if (window.innerWidth >= this.desktopBreakpoint) this.updateOverlay();
      });
      this.menu.addEventListener('mouseleave', () => {
        if (window.innerWidth >= this.desktopBreakpoint) this.updateOverlay();
      });
    }

    // Submenu support
    this.handleMobileDropdowns();

    // Mega menu support
    this.handleMegaMenu();

    window.matchMedia(`(min-width: ${this.desktopBreakpoint}px)`)
      .addEventListener('change', () => {
        this.closeAllDropdowns();
        this.updatePrimaryMenuAccessibility();
      });

    const allVisibleMenus = this.header.querySelectorAll('.c-submenu, .c-mega-menu');
    allVisibleMenus.forEach((menu) => {
      this.setDropdownTabOrder(menu, false);
    });

    // Overlay click support
    this.handleOverlayClick();
  }

  // Handle Scroll
  // =========================================================================
  handleScroll() {
    // Store navbar classes
    this.classes = this.header.classList;

    // Initial scroll position
    let scrollState = 0;

    // Returns current scroll position
    const scrollTop = () => window.scrollY;

    // Primary scroll event function
    const scrollDetect = (home, down, up) => {
      // Current scroll position
      const currentScroll = scrollTop();
      if (currentScroll === 0) {
        home();
      } else if (currentScroll > scrollState) {
        down();
      } else {
        up();
      }
      // Set previous scroll position
      scrollState = scrollTop();
    };

    const homeAction = () => {
      this.classes.remove('is-slide-up');
      this.classes.remove('is-slide-down');
      this.classes.add('is-slide-top');
    };

    const downAction = () => {
      this.classes.remove('is-slide-top');
      this.classes.remove('is-slide-down');
      this.classes.add('is-slide-up');
    };

    const upAction = () => {
      this.classes.remove('is-slide-top');
      this.classes.remove('is-slide-up');
      this.classes.add('is-slide-down');
    };

    // Attach the scroll event handler
    window.addEventListener('scroll', () => {
      scrollDetect(homeAction, downAction, upAction);
    });
  }

  // Navbar Transparency
  // =========================================================================
  handleNavbarTransparency() {
    // Add or remove scrolling navbar classes
    window.addEventListener('scroll', () => {
      const nav = this.header.querySelector('nav');

      if (50 < window.scrollY) {
        nav.classList.add('is-transparent');
      } else {
        nav.classList.remove('is-transparent');
      }
    });
  }

  // Mega Menu Accessibility Toggle
  // =========================================================================
  handleMegaMenu() {
    const triggers = this.header.querySelectorAll('[data-trigger-megamenu]');
    const isDesktop = () => window.matchMedia(`(min-width: ${this.desktopBreakpoint}px)`).matches;

    triggers.forEach((trigger) => {
      const menu = document.getElementById(trigger.getAttribute('data-toggle'));
      const parent = trigger.closest('.c-navigation__menu-item');
      if (!menu || !menu.classList.contains('c-mega-menu') || !parent) return;
      const backButton = menu.querySelector('[data-megamenu-back]');
      let suppressFocusOpen = false;
      let closeTimer;

      const showMenu = () => {
        window.clearTimeout(closeTimer);
        this.openDropdown(menu, trigger);
      };

      const hideMenu = () => {
        this.closeDropdown(menu, trigger);
      };

      if (backButton) {
        backButton.addEventListener('click', () => {
          hideMenu();
          trigger.focus();
        });
      }

      const hideMenuIfOutside = () => {
        window.clearTimeout(closeTimer);
        closeTimer = window.setTimeout(() => {
          const isHoveringMenu = parent.matches(':hover') || menu.matches(':hover');
          const hasFocusInMenu = parent.contains(document.activeElement)
            || menu.contains(document.activeElement);

          if (isDesktop() && !isHoveringMenu && !hasFocusInMenu) hideMenu();
        }, 100);
      };

      parent.addEventListener('mouseenter', () => {
        if (isDesktop()) showMenu();
      });
      parent.addEventListener('mouseleave', () => {
        if (isDesktop()) hideMenuIfOutside();
      });
      parent.addEventListener('focusin', () => {
        if (suppressFocusOpen) {
          suppressFocusOpen = false;
        } else if (isDesktop()) {
          showMenu();
        }
      });
      parent.addEventListener('focusout', (e) => {
        if (isDesktop() && !menu.contains(e.relatedTarget)) hideMenuIfOutside();
      });

      menu.addEventListener('mouseenter', () => {
        if (isDesktop()) window.clearTimeout(closeTimer);
      });
      menu.addEventListener('mouseleave', () => {
        if (isDesktop()) hideMenuIfOutside();
      });
      menu.addEventListener('focusin', () => {
        if (isDesktop()) window.clearTimeout(closeTimer);
      });
      menu.addEventListener('focusout', (e) => {
        if (isDesktop() && !parent.contains(e.relatedTarget)) hideMenuIfOutside();
      });

      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        if (menu.classList.contains('is-visible')) {
          hideMenu();
        } else {
          showMenu();
        }
      });

      const handleKeydown = (e) => {
        if (e.key === 'Escape') {
          hideMenu();
          suppressFocusOpen = true;
          trigger.focus();
        } else if (e.target === trigger && (e.key === ' ' || e.key === 'Spacebar')) {
          e.preventDefault();
          trigger.click();
        }
      };
      parent.addEventListener('keydown', handleKeydown);
      menu.addEventListener('keydown', handleKeydown);
    });
  }

  setDropdownTabOrder(menu, isOpen) {
    const links = menu.querySelectorAll('a');
    links.forEach((link) => {
      if (isOpen) {
        link.removeAttribute('tabindex');
        link.removeAttribute('inert');
      } else {
        link.setAttribute('tabindex', '-1');
        link.setAttribute('inert', 'true');
      }
    });
  }

  // Close All Dropdowns
  // =========================================================================
  openDropdown(menu, trigger) {
    if (menu.classList.contains('is-visible')) return;

    this.closeAllDropdowns();
    menu.classList.add('is-visible');
    menu.classList.remove('is-hidden');
    menu.setAttribute('aria-hidden', 'false');
    trigger.setAttribute('aria-expanded', 'true');
    trigger.classList.add('is-active');
    this.setDropdownTabOrder(menu, true);
    if (this.menu && menu.classList.contains('c-mega-menu') && window.innerWidth < this.desktopBreakpoint) {
      this.menu.classList.add('has-active-mega-menu');
      this.updatePrimaryMenuAccessibility();
      const backButton = menu.querySelector('[data-megamenu-back]');
      if (backButton) backButton.focus();
    }
    this.updateOverlay();
  }

  closeDropdown(menu, trigger) {
    menu.classList.remove('is-visible');
    menu.classList.add('is-hidden');
    menu.setAttribute('aria-hidden', 'true');
    trigger.setAttribute('aria-expanded', 'false');
    trigger.classList.remove('is-active');
    this.setDropdownTabOrder(menu, false);
    if (this.menu && menu.classList.contains('c-mega-menu')) {
      this.menu.classList.remove('has-active-mega-menu');
      this.updatePrimaryMenuAccessibility();
    }
    this.updateOverlay();
  }

  closeAllDropdowns() {
    // Close all megamenus
    const triggers = this.header.querySelectorAll('[data-trigger-megamenu]');
    triggers.forEach((otherTrigger) => {
      const otherMenu = document.getElementById(otherTrigger.getAttribute('data-toggle'));
      if (otherMenu && otherMenu.classList.contains('c-mega-menu')) {
        otherMenu.classList.remove('is-visible');
        otherMenu.classList.add('is-hidden');
        otherMenu.setAttribute('aria-hidden', 'true');
        this.setDropdownTabOrder(otherMenu, false);
        otherTrigger.setAttribute('aria-expanded', 'false');
        otherTrigger.classList.remove('is-active');
      }
    });
    // Close all submenus
    const allSubmenus = this.header.querySelectorAll('.c-submenu');
    allSubmenus.forEach((submenu) => {
      submenu.classList.remove('is-visible');
      submenu.classList.add('is-hidden');
      submenu.setAttribute('aria-hidden', 'true');
      this.setDropdownTabOrder(submenu, false);
    });
    const allToggles = this.header.querySelectorAll('[data-toggle^="primary__sub-"]');
    allToggles.forEach((toggle) => {
      toggle.classList.remove('is-active');
      toggle.setAttribute('aria-expanded', 'false');
    });
    if (this.menu) this.menu.classList.remove('has-active-mega-menu');
    this.updatePrimaryMenuAccessibility();
    this.updateOverlay();
  }

  // Update Primary Menu Accessibility
  // =========================================================================
  updatePrimaryMenuAccessibility() {
    if (!this.menu) return;

    const isPrimaryMenuHidden = window.innerWidth < this.desktopBreakpoint
      && (this.menu.classList.contains('is-hidden')
        || this.menu.classList.contains('has-active-mega-menu'));
    this.menu.setAttribute('aria-hidden', String(isPrimaryMenuHidden));
  }

  // Update Overlay Based on Navigation Area State
  // =========================================================================
  updateOverlay() {
    if (!this.overlay) return;

    if (window.innerWidth < this.desktopBreakpoint) {
      const isPrimaryNavOpen = this.header.classList.contains('is-active');
      this.overlay.classList.toggle('is-visible', isPrimaryNavOpen);
      this.overlay.classList.toggle('is-hidden', !isPrimaryNavOpen);
      return;
    }

    const visibleMegaMenu = this.header.querySelector('.c-mega-menu.is-visible');
    const isNavigationAreaActive = (this.menu
      && (this.menu.matches(':hover') || this.menu.contains(document.activeElement)))
      || (visibleMegaMenu
        && (visibleMegaMenu.matches(':hover') || visibleMegaMenu.contains(document.activeElement)));

    if (isNavigationAreaActive) {
      this.overlay.classList.remove('is-hidden');
      this.overlay.classList.add('is-visible');
    } else {
      this.overlay.classList.remove('is-visible');
      this.overlay.classList.add('is-hidden');
    }
  }

  // Handle Overlay Click
  // =========================================================================
  handleOverlayClick() {
    if (!this.overlay) return;

    this.overlay.addEventListener('click', () => {
      // Close mobile menu if it's open
      if (this.header.classList.contains('is-active')) {
        this.togglePrimaryNav();
      }

      // Close all dropdowns
      this.closeAllDropdowns();
    });
  }

  // Toggle Primary Nav
  // =========================================================================
  togglePrimaryNav() {
    // Find sit elements
    const siteContainer = document.querySelector('.o-site');

    // Set classes to "open"
    this.header.classList.toggle('is-active');
    this.navigation.classList.toggle('is-visible');
    this.menu.classList.toggle('is-hidden');
    this.toggleMenu.classList.toggle('open');
    this.toggleMenu.querySelector('[data-toggle-menu]').setAttribute(
      'aria-expanded',
      String(this.header.classList.contains('is-active'))
    );
    siteContainer.classList.toggle('-activeNavigationAreaUpTopButNotWhenScrolling');
    this.updatePrimaryMenuAccessibility();

    // Close dropdowns when the primary navigation closes.
    if (!this.header.classList.contains('is-active')) {
      this.closeAllDropdowns();
    }

    this.updateOverlay();
  }

  // Mobile Dropdown Submenus Toggle
  // =========================================================================
  handleMobileDropdowns() {
    const isDesktop = () => window.matchMedia(`(min-width: ${this.desktopBreakpoint}px)`).matches;
    const toggles = this.header.querySelectorAll('[data-toggle^="primary__sub-"]');

    toggles.forEach((toggle) => {
      const targetId = toggle.getAttribute('data-toggle');
      const targetMenu = this.header.querySelector(`#${targetId}`);
      const parent = toggle.closest('.c-navigation__menu-item');

      if (!targetMenu || !parent) return;
      let suppressFocusOpen = false;

      toggle.addEventListener('click', (e) => {
        e.preventDefault();

        if (targetMenu.classList.contains('is-visible')) {
          this.closeDropdown(targetMenu, toggle);
        } else {
          this.openDropdown(targetMenu, toggle);
        }
      });

      parent.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          this.closeDropdown(targetMenu, toggle);
          suppressFocusOpen = true;
          toggle.focus();
        } else if (e.target === toggle && (e.key === ' ' || e.key === 'Spacebar')) {
          e.preventDefault();
          toggle.click();
        }
      });

      parent.addEventListener('mouseenter', () => {
        if (isDesktop()) this.openDropdown(targetMenu, toggle);
      });
      parent.addEventListener('mouseleave', () => {
        if (isDesktop() && !parent.contains(document.activeElement)) {
          this.closeDropdown(targetMenu, toggle);
        }
      });
      parent.addEventListener('focusin', () => {
        if (suppressFocusOpen) {
          suppressFocusOpen = false;
        } else if (isDesktop()) {
          this.openDropdown(targetMenu, toggle);
        }
      });
      parent.addEventListener('focusout', (e) => {
        if (isDesktop() && !parent.matches(':hover') && !parent.contains(e.relatedTarget)) {
          this.closeDropdown(targetMenu, toggle);
        }
      });
    });
  }

  // Destroy
  // =========================================================================
  destroy() {}
}
