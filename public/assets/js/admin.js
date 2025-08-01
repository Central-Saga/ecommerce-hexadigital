document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const main = document.querySelector('.admin-main');
    const body = document.body;

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            main.classList.toggle('active');
            body.classList.toggle('sidebar-open');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 992) { // $breakpoint-lg
            if (sidebar && sidebar.classList.contains('active')) {
                // Check if click is outside sidebar and not on toggle button
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                    main.classList.remove('active');
                    body.classList.remove('sidebar-open');
                }
            }
        }
    });

    // Close sidebar on window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth > 992) { // $breakpoint-lg
            sidebar.classList.remove('active');
            main.classList.remove('active');
            body.classList.remove('sidebar-open');
        }
    });

    // Active menu item highlighting
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');

    navLinks.forEach((link) => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // Dropdown menu functionality
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (
                !dropdownToggle.contains(e.target) &&
                !dropdownMenu.contains(e.target)
            ) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});
