document.addEventListener('DOMContentLoaded', () => {
    // --- THEME TOGGLE ---
    const toggleBtn = document.getElementById('theme-toggle');
    const sunIcon = toggleBtn.querySelector('.icon-sun');
    const moonIcon = toggleBtn.querySelector('.icon-moon');
    const html = document.documentElement;

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateIcons(savedTheme);

    toggleBtn.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcons(newTheme);
    });

    function updateIcons(theme) {
        if (theme === 'light') {
            moonIcon.style.display = 'block';
            sunIcon.style.display = 'none';
        } else {
            moonIcon.style.display = 'none';
            sunIcon.style.display = 'block';
        }
    }

    // --- SMOOTH SCROLL OPTIMIZED ---
    const links = document.querySelectorAll('.sidebar a');

    // Improved Easing: easeInOutCubic (smoother acceleration/deceleration)
    const easeInOutCubic = (t) => {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    };

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.href;
            const targetUrl = new URL(href);

            if (targetUrl.origin === window.location.origin &&
                targetUrl.pathname === window.location.pathname &&
                targetUrl.hash) {

                const targetId = targetUrl.hash;
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    e.preventDefault();

                    const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - 80;
                    const startPosition = window.scrollY;
                    const distance = targetPosition - startPosition;
                    // Slow duration: 1500ms
                    const duration = 1500;
                    let start = null;

                    function animation(currentTime) {
                        if (start === null) start = currentTime;
                        const timeElapsed = currentTime - start;

                        let progress = timeElapsed / duration;
                        if (progress > 1) progress = 1;

                        const easeVal = easeInOutCubic(progress);
                        const run = startPosition + (distance * easeVal);

                        // Force predictable scroll behavior
                        window.scrollTo({ top: run, behavior: 'auto' });

                        if (timeElapsed < duration) {
                            requestAnimationFrame(animation);
                        }
                    }
                    requestAnimationFrame(animation);
                }
            }
        });
    });

    // --- SCROLL SPY OPTIMIZED ---
    const navLinks = Array.from(document.querySelectorAll('.sidebar a'));
    const sectionsFromLinks = navLinks.map(link => {
        if (link.hash && link.origin === window.location.origin && link.pathname === window.location.pathname) {
            const id = link.hash.substring(1);
            const el = document.getElementById(id);
            return { link, el, id };
        }
        return null;
    }).filter(item => item !== null && item.el !== null);

    let isTicking = false;

    const handleScrollSpy = () => {
        const scrollPosition = window.scrollY + 150; // Offset
        let currentId = '';

        // Check sections
        for (const item of sectionsFromLinks) {
            if (item.el.offsetTop <= scrollPosition) {
                currentId = item.id;
            }
        }

        // Update classes
        navLinks.forEach(a => {
            a.classList.remove('active');
            if (currentId && a.hash === '#' + currentId) {
                a.classList.add('active');
            }
        });

        isTicking = false;
    };

    const onScroll = () => {
        if (!isTicking) {
            window.requestAnimationFrame(handleScrollSpy);
            isTicking = true;
        }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    handleScrollSpy(); // Trigger once on load

    // --- HIGHLIGHT.JS ---
    if (typeof hljs !== 'undefined') {
        hljs.highlightAll();
    }
});
