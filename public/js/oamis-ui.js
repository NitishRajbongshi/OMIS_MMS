(function () {
    var storageKey = 'oamis-theme';
    var fontStorageKey = 'oamis-font-size';
    var root = document.documentElement;
    var fontSizes = {
        small: .9,
        normal: 1,
        large: 1.1,
        xlarge: 1.2
    };
    var fontOrder = ['small', 'normal', 'large', 'xlarge'];
    var fontSelector = [
        'body', 'p', 'span', 'a', 'button', 'label', 'input', 'select', 'textarea',
        'th', 'td', 'li', 'dt', 'dd', 'legend', 'small', 'strong', 'em',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        '.btn', '.nav-link', '.dropdown-item', '.form-control', '.form-select',
        '.custom-select', '.card-title', '.badge', '.alert', '.page-link',
        '.dataTables_wrapper', '.dataTables_wrapper *', '.table', '.table *'
    ].join(',');
    var mutationTimer = null;

    function preferredTheme() {
        try {
            return localStorage.getItem(storageKey) || 'light';
        } catch (error) {
            return 'light';
        }
    }

    function preferredFontSize() {
        var stored = 'normal';
        try {
            stored = localStorage.getItem(fontStorageKey) || 'normal';
        } catch (error) {
            stored = 'normal';
        }
        return fontSizes[stored] ? stored : 'normal';
    }

    function applyTheme(theme) {
        root.setAttribute('data-theme', theme);
        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            var isDark = theme === 'dark';
            button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            button.innerHTML = '<i class="fas ' + (isDark ? 'fa-sun' : 'fa-moon') + '"></i><span>' + (isDark ? 'Light' : 'Dark') + '</span>';
        });
    }

    function shouldScaleFont(element) {
        if (!element || !element.tagName) {
            return false;
        }
        if (element.closest('.oamis-accessibility-panel')) {
            return false;
        }
        if (element.closest('script, style, svg, canvas')) {
            return false;
        }
        if (element.matches('i, svg, canvas, .fa, .fas, .far, .fab, .fa-solid, .fa-regular, .fa-brands, .glyphicon, [class*=" ion-"], [class^="ion-"]')) {
            return false;
        }
        return true;
    }

    function rememberBaseFontSize(element) {
        if (!element.dataset.oamisBaseFontSize) {
            var size = parseFloat(window.getComputedStyle(element).fontSize);
            if (Number.isFinite(size) && size > 0) {
                element.dataset.oamisBaseFontSize = String(size);
            }
        }
    }

    function applyScaledFontToElement(element, scale) {
        if (!shouldScaleFont(element)) {
            return;
        }
        rememberBaseFontSize(element);
        var baseSize = parseFloat(element.dataset.oamisBaseFontSize);
        if (Number.isFinite(baseSize) && baseSize > 0) {
            element.style.fontSize = (baseSize * scale).toFixed(2) + 'px';
        }
    }

    function updateFontButtons(size) {
        document.querySelectorAll('[data-font-size-control]').forEach(function (button) {
            var control = button.getAttribute('data-font-size-control');
            var isCurrent = control === size;
            button.setAttribute('aria-pressed', isCurrent ? 'true' : 'false');
            button.classList.toggle('is-active', isCurrent);
        });
    }

    function applyFontSize(size) {
        var safeSize = fontSizes[size] ? size : 'normal';
        var scale = fontSizes[safeSize];
        root.setAttribute('data-font-size', safeSize);
        root.style.setProperty('--oamis-font-scale', scale);
        document.querySelectorAll(fontSelector).forEach(function (element) {
            applyScaledFontToElement(element, scale);
        });
        updateFontButtons(safeSize);
    }

    function setFontSize(size) {
        var safeSize = fontSizes[size] ? size : 'normal';
        try {
            localStorage.setItem(fontStorageKey, safeSize);
        } catch (error) {}
        applyFontSize(safeSize);
    }

    function stepFontSize(direction) {
        var current = preferredFontSize();
        var currentIndex = fontOrder.indexOf(current);
        var nextIndex = Math.max(0, Math.min(fontOrder.length - 1, currentIndex + direction));
        setFontSize(fontOrder[nextIndex]);
    }

    function createAccessibilityPanel() {
        if (document.querySelector('.oamis-accessibility-panel')) {
            return;
        }
        var panel = document.createElement('div');
        panel.className = 'oamis-accessibility-panel';
        panel.setAttribute('role', 'group');
        panel.setAttribute('aria-label', 'Text size controls');
        panel.innerHTML = '' +
            '<button type="button" data-font-size-step="-1" aria-label="Decrease font size">A-</button>' +
            '<button type="button" data-font-size-control="normal" aria-label="Reset font size">A</button>' +
            '<button type="button" data-font-size-step="1" aria-label="Increase font size">A+</button>';

        var publicActions = document.querySelector('.public-nav-actions, .oamis-login-actions');
        if (publicActions) {
            publicActions.insertBefore(panel, publicActions.firstChild);
            return;
        }

        var topbarActions = document.querySelector('.oamis-topbar .navbar-nav.ml-auto');
        if (topbarActions) {
            var item = document.createElement('li');
            item.className = 'nav-item ml-1';
            item.appendChild(panel);
            topbarActions.insertBefore(item, topbarActions.firstChild);
            return;
        }

        panel.classList.add('is-floating');
        document.body.appendChild(panel);
    }

    function observeDynamicContent() {
        if (!window.MutationObserver) {
            return;
        }
        var observer = new MutationObserver(function () {
            window.clearTimeout(mutationTimer);
            mutationTimer = window.setTimeout(function () {
                applyFontSize(preferredFontSize());
            }, 120);
        });
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    applyTheme(preferredTheme());
    root.setAttribute('data-font-size', preferredFontSize());
    root.style.setProperty('--oamis-font-scale', fontSizes[preferredFontSize()]);

    document.addEventListener('click', function (event) {
        var button = event.target.closest('[data-theme-toggle]');
        if (button) {
            var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            try {
                localStorage.setItem(storageKey, nextTheme);
            } catch (error) {}
            applyTheme(nextTheme);
        }

        var fontButton = event.target.closest('[data-font-size-control]');
        if (fontButton) {
            setFontSize(fontButton.getAttribute('data-font-size-control'));
        }

        var fontStepButton = event.target.closest('[data-font-size-step]');
        if (fontStepButton) {
            stepFontSize(Number(fontStepButton.getAttribute('data-font-size-step')) || 0);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        createAccessibilityPanel();
        applyTheme(preferredTheme());
        applyFontSize(preferredFontSize());
        observeDynamicContent();
        document.body.classList.add('oamis-ready');
    });
})();
