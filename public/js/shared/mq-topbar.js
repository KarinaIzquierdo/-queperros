(function () {
    const topbars = Array.from(document.querySelectorAll('.mqx-topbar'));
    if (!topbars.length) return;

    const initPopovers = (root) => {
        const popovers = {
            user: root.querySelector('[data-mqx-popover="user"]'),
            notifications: root.querySelector('[data-mqx-popover="notifications"]'),
        };

        const isOpen = (el) => !!el && el.classList.contains('mqx-popover--open');

        const openPopover = (el) => {
            if (!el) return;
            el.classList.add('mqx-popover--open');
            el.setAttribute('aria-hidden', 'false');
        };

        const closePopover = (el) => {
            if (!el) return;
            el.classList.remove('mqx-popover--open');
            el.setAttribute('aria-hidden', 'true');
        };

        const hideAll = (except) => {
            Object.keys(popovers).forEach((key) => {
                if (key === except) return;
                closePopover(popovers[key]);
            });
        };

        root.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-mqx-toggle]');
            if (!btn) return;

            const target = btn.getAttribute('data-mqx-toggle');
            const el = popovers[target];
            if (!el) return;

            const willShow = !isOpen(el);
            hideAll(willShow ? target : null);
            if (willShow) openPopover(el);
            else closePopover(el);

            e.stopPropagation();
        });

        document.addEventListener('click', (e) => {
            if (root.contains(e.target)) return;
            hideAll(null);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            hideAll(null);
        });
    };

    const initSidebarToggleSync = () => {
        const adminLayout = document.querySelector('.admin-layout');
        const mqDashboard = document.querySelector('.mq-dashboard');

        const scope = adminLayout ? 'admin' : (mqDashboard ? 'mq' : null);
        if (!scope) return;

        const storageKey = scope === 'admin' ? 'mq.sidebarCollapsed.admin' : 'mq.sidebarCollapsed.mq';

        // Intentar obtener el checkbox original o los nuevos de admin/dueño/entrenador
        const topbarToggleCheckbox = document.getElementById('mqxSidebarCheckbox') || 
                                   document.getElementById('checkbox') || 
                                   document.getElementById('checkbox2') ||
                                   document.getElementById('checkbox3');
        if (!topbarToggleCheckbox) return;

        const isMobile = () => window.matchMedia('(max-width: 1024px)').matches;

        const setState = (next) => {
            if (scope === 'admin') {
                adminLayout?.classList.toggle('is-sidebar-collapsed', next);
            } else {
                if (isMobile()) {
                    mqDashboard?.classList.toggle('is-sidebar-active', next);
                    mqDashboard?.classList.remove('is-sidebar-collapsed');
                } else {
                    mqDashboard?.classList.toggle('is-sidebar-collapsed', next);
                    mqDashboard?.classList.remove('is-sidebar-active');
                }
            }

            const adminCheckbox = document.getElementById('adminSidebarCheckbox');
            const mqCheckbox = document.getElementById('mqSidebarCheckbox');
            const newAdminCheckbox = document.getElementById('checkbox');
            const newOwnerCheckbox = document.getElementById('checkbox2');
            const newTrainerCheckbox = document.getElementById('checkbox3');

            if (adminCheckbox) adminCheckbox.checked = next;
            if (mqCheckbox) mqCheckbox.checked = next;
            if (newAdminCheckbox) newAdminCheckbox.checked = next;
            if (newOwnerCheckbox) newOwnerCheckbox.checked = next;
            if (newTrainerCheckbox) newTrainerCheckbox.checked = next;
            if (topbarToggleCheckbox) topbarToggleCheckbox.checked = next;

            if (!isMobile()) {
                localStorage.setItem(storageKey, next ? '1' : '0');
            }
        };

        const initCollapsed = !isMobile() && localStorage.getItem(storageKey) === '1';
        setState(initCollapsed);

        const adminCheckbox = document.getElementById('adminSidebarCheckbox');
        const mqCheckbox = document.getElementById('mqSidebarCheckbox');
        const newAdminCheckbox = document.getElementById('checkbox');
        const newOwnerCheckbox = document.getElementById('checkbox2');
        const newTrainerCheckbox = document.getElementById('checkbox3');

        adminCheckbox?.addEventListener('change', () => setState(!!adminCheckbox.checked));
        mqCheckbox?.addEventListener('change', () => setState(!!mqCheckbox.checked));
        newAdminCheckbox?.addEventListener('change', () => setState(!!newAdminCheckbox.checked));
        newOwnerCheckbox?.addEventListener('change', () => setState(!!newOwnerCheckbox.checked));
        newTrainerCheckbox?.addEventListener('change', () => setState(!!newTrainerCheckbox.checked));
        
        if (topbarToggleCheckbox && 
            topbarToggleCheckbox !== newAdminCheckbox && 
            topbarToggleCheckbox !== newOwnerCheckbox &&
            topbarToggleCheckbox !== newTrainerCheckbox) {
            topbarToggleCheckbox.addEventListener('change', () => setState(!!topbarToggleCheckbox.checked));
        }

        mqDashboard?.addEventListener('click', (e) => {
            if (!isMobile() || !mqDashboard.classList.contains('is-sidebar-active')) return;
            if (e.target.closest('.mq-dashboard-sidebar') || e.target.closest('.mqx-sidebar-toggle-wrapper')) return;
            setState(false);
        });

        window.addEventListener('resize', () => {
            const checked = !!topbarToggleCheckbox.checked;
            if (isMobile()) {
                mqDashboard?.classList.remove('is-sidebar-collapsed');
                if (!checked) mqDashboard?.classList.remove('is-sidebar-active');
            } else {
                mqDashboard?.classList.remove('is-sidebar-active');
                mqDashboard?.classList.toggle('is-sidebar-collapsed', checked);
            }
        });
    };

    topbars.forEach(initPopovers);
    initSidebarToggleSync();
})();
