<button id="theme-toggle" class="theme-toggle-btn" title="Changer le thème">
    <!-- Moon Icon (for light mode -> switch to dark) -->
    <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: block;">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
    </svg>
    <!-- Sun Icon (for dark mode -> switch to light) -->
    <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
        <circle cx="12" cy="12" r="5"></circle>
        <path d="M12 1v2"></path>
        <path d="M12 21v2"></path>
        <path d="M4.22 4.22l1.42 1.42"></path>
        <path d="M17.36 17.36l1.42 1.42"></path>
        <path d="M1 12h2"></path>
        <path d="M21 12h2"></path>
        <path d="M4.22 19.78l1.42-1.42"></path>
        <path d="M17.36 6.64l1.42-1.42"></path>
    </svg>
</button>

<style>
    .theme-toggle-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background-color: var(--bg-color);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-color);
        transition: all 0.2s;
    }

    .theme-toggle-btn:hover {
        border-color: var(--accent-color);
    }
</style>