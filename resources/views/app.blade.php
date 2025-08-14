<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản Lý Sản Phẩm - Warehouse Management System</title>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="manifest" href="{{ asset('images/site.webmanifest') }}">
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
        
    </script>


</body>

</html>