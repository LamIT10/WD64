<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản Lý Sản Phẩm - Warehouse Management System</title>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet" />
    <style>
        /* Smooth sidebar transition */
        .sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 50;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .sidebar-overlay-active {
            display: block;
        }

        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0) !important;
            }

            .sidebar-overlay {
                display: none !important;
            }

            .main-content {
                margin-left: 14rem;
                /* Adjusted to match w-56 */
            }
        }

        /* Dropdown animation */
        .dropdown-menu {
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            overflow: hidden;
        }

        .dropdown-menu-hidden {
            max-height: 0;
            opacity: 0;
        }

        .dropdown-icon {
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-icon-open {
            transform: rotate(180deg);
        }

        /* Responsive table */
        @media (max-width: 767px) {
            .responsive-table {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .product-card {
                display: none;
            }
        }
    </style>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @routes
    @inertiaHead
</head>

<body class="bg-slate-100 font-sans">
    @inertia
    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-hidden');
            mainContent.classList.toggle('main-content-full');
        });

        // Dropdown toggle functionality
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(`${menuId.split('-')[0]}-icon`);
            const isHidden = menu.classList.contains('dropdown-menu-hidden');

            // Toggle menu visibility
            menu.classList.toggle('dropdown-menu-hidden');
            menu.style.maxHeight = isHidden ? `${menu.scrollHeight}px` : '0';
            menu.style.opacity = isHidden ? '1' : '0';

            // Toggle icon rotation
            icon.classList.toggle('dropdown-icon-open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (event) => {
            if (
                window.innerWidth < 1024 &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)
            ) {
                sidebar.classList.add('sidebar-hidden');
                mainContent.classList.add('main-content-full');
            }
        });
    </script>
</body>

</html>

