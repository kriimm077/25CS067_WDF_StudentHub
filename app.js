// StudentHub – dark mode + basic interactivity
(function () {
    const toggle = document.getElementById('darkModeToggle');
    const body = document.body;
    const KEY = 'studenthub-theme';

    function applyTheme(theme) {
        if (theme === 'dark') {
            body.classList.add('dark-mode');
            if (toggle) toggle.textContent = '☀️';
        } else {
            body.classList.remove('dark-mode');
            if (toggle) toggle.textContent = '🌙';
        }
    }

    // Restore
    const saved = localStorage.getItem(KEY) || 'light';
    applyTheme(saved);

    if (toggle) {
        toggle.addEventListener('click', () => {
            const next = body.classList.contains('dark-mode') ? 'light' : 'dark';
            localStorage.setItem(KEY, next);
            applyTheme(next);
        });
    }
})();
