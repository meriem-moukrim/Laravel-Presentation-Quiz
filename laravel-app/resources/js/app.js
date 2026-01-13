import './bootstrap';
import hljs from 'highlight.js';

// Init Highlight.js
hljs.highlightAll();

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

    // --- SMOOTH SCROLL ---
    const links = document.querySelectorAll('.sidebar a');

    // Easing function (easeInOutQuad)
    const ease = (t, b, c, d) => {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    };

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - 80; // Offset for padding
                const startPosition = window.scrollY;
                const distance = targetPosition - startPosition;
                const duration = 1500;
                let start = null;

                function animation(currentTime) {
                    if (start === null) start = currentTime;
                    const timeElapsed = currentTime - start;
                    const run = ease(timeElapsed, startPosition, distance, duration);
                    window.scrollTo(0, run);
                    if (timeElapsed < duration) requestAnimationFrame(animation);
                }
                requestAnimationFrame(animation);
            }
        });
    });

    // --- SCROLL SPY ---
    const sections = Array.from(document.querySelectorAll('section, h2[id="intro"]'));
    // Note: intro might be a section or just h2 depending on data. My Blade wraps in <section id="intro">
    // But data intro content doesn't have a title for the first block usually?
    // Let's look at intro.json: id: "intro", title: "Introduction".
    // Blade: <section id="intro">...

    const handleScrollSpy = () => {
        let current = '';
        const scrollPosition = window.scrollY + 150; // Offset

        // We specifically check all section ids that match sidebar links
        const navLinks = document.querySelectorAll('.sidebar a');

        navLinks.forEach(link => {
            const id = link.getAttribute('href').substring(1);
            const section = document.getElementById(id);
            if (section) {
                // checking if scroll is past this section
                if (section.offsetTop <= scrollPosition) {
                    current = id;
                }
            }
        });

        navLinks.forEach(a => {
            a.classList.remove('active');
            if (a.getAttribute('href') === '#' + current) {
                a.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', handleScrollSpy);
    handleScrollSpy(); // Trigger once on load
});
