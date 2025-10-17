<?php
/**
 * Layout: Professional Rice Milling System Dashboard
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Rice Milling System';
$identity = $this->Identity->get('username') ?? 'User';
$role = strtolower(trim($this->Identity->get('role') ?? ''));
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $cakeDescription ?> | <?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #6366f1;
            --secondary: #64748b;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --light: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius: 10px;
            --radius-lg: 14px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 400;
            line-height: 1.6;
            color: var(--gray-700);
            background: var(--gray-50);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--light);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            transition: var(--transition);
            z-index: 1000;
            border-right: 1px solid var(--gray-200);
        }

        .sidebar-header {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--light);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.375rem;
            color: var(--gray-900);
            text-decoration: none;
            transition: var(--transition);
        }

        .brand:hover {
            opacity: 0.8;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        .nav-section {
            padding: 1.25rem 0;
        }

        .nav-section:not(:last-child) {
            border-bottom: 1px solid var(--gray-200);
        }

        .nav-title {
            padding: 0 1.5rem 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-400);
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin: 0.25rem 0.875rem;
            animation: slideInLeft 0.3s ease forwards;
            opacity: 0;
        }

        .nav-item:nth-child(1) { animation-delay: 0.05s; }
        .nav-item:nth-child(2) { animation-delay: 0.1s; }
        .nav-item:nth-child(3) { animation-delay: 0.15s; }
        .nav-item:nth-child(4) { animation-delay: 0.2s; }
        .nav-item:nth-child(5) { animation-delay: 0.25s; }
        .nav-item:nth-child(6) { animation-delay: 0.3s; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.125rem 1.5rem;
            color: var(--gray-600);
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            font-weight: 500;
            font-size: 1.375rem;
            position: relative;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
            transition: height 0.3s ease;
        }

        .nav-link:hover {
            background: var(--gray-100);
            color: var(--gray-900);
            transform: translateX(2px);
        }

        .nav-link:hover::before {
            height: 60%;
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            font-weight: 600;
            transform: translateX(0);
        }

        .nav-link.active::before {
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
        }

        .nav-link.active:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
        }

        .nav-link i {
            width: 32px;
            text-align: center;
            font-size: 1.75rem;
            transition: transform 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        .nav-link.text-danger {
            color: var(--danger);
        }

        .nav-link.text-danger:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .nav-link .badge {
            margin-left: auto;
            background: var(--gray-200);
            color: var(--gray-700);
            font-size: 0.6875rem;
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-weight: 600;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: var(--transition);
        }

        /* ===== HEADER ===== */
        .main-header {
            background: var(--light);
            box-shadow: var(--shadow-sm);
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--gray-200);
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            letter-spacing: -0.025em;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.5rem 1rem 0.5rem 0.5rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid var(--gray-200);
        }

        .user-menu:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
            box-shadow: var(--shadow-sm);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9375rem;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.4;
        }

        .user-name {
            font-weight: 600;
            color: var(--gray-900);
            font-size: 0.9375rem;
        }

        .user-role {
            font-size: 0.8125rem;
            color: var(--gray-500);
            text-transform: capitalize;
        }

        /* ===== CONTENT AREA ===== */
        .content-wrapper {
            flex: 1;
            padding: 2rem;
            animation: fadeInUp 0.4s ease-out;
        }

        .content-area {
            background: var(--light);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 2rem;
            min-height: 500px;
            border: 1px solid var(--gray-200);
        }

        /* ===== FOOTER ===== */
        .main-footer {
            background: var(--light);
            border-top: 1px solid var(--gray-200);
            padding: 1.5rem 2rem;
            text-align: center;
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-content div {
            font-weight: 500;
        }

        /* ===== UTILITIES ===== */
        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success { 
            background: #ecfdf5; 
            color: #047857; 
            border: 1px solid #a7f3d0; 
        }

        .badge-warning { 
            background: #fffbeb; 
            color: #b45309; 
            border: 1px solid #fcd34d; 
        }

        .badge-danger { 
            background: #fef2f2; 
            color: #b91c1c; 
            border: 1px solid #fca5a5; 
        }

        .badge-info { 
            background: #eff6ff; 
            color: #1d4ed8; 
            border: 1px solid #93c5fd; 
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            :root {
                --sidebar-width: 260px;
            }
            .content-wrapper {
                padding: 1.5rem;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
                box-shadow: var(--shadow-xl);
            }
            .main-content {
                margin-left: 0;
            }
            .content-wrapper {
                padding: 1.25rem;
            }
            .page-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .main-header {
                padding: 1rem;
            }
            .header-actions {
                gap: 0.75rem;
            }
            .user-info {
                display: none;
            }
            .user-menu {
                padding: 0.5rem;
            }
            .content-wrapper {
                padding: 1rem;
            }
            .content-area {
                padding: 1.25rem;
                border-radius: var(--radius);
            }
            .footer-content {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.25rem;
            }
            .brand {
                font-size: 1.125rem;
            }
            .brand-icon {
                width: 36px;
                height: 36px;
                font-size: 1.125rem;
            }
        }

        /* ===== MOBILE MENU TOGGLE ===== */
        .mobile-menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--gray-700);
            font-size: 1.25rem;
        }

        .mobile-menu-toggle:hover {
            background: var(--gray-200);
        }

        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: flex;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Dashboard specific styles */
        .clickable-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .border-left-primary { border-left: 4px solid #4e73df !important; }
        .border-left-success { border-left: 4px solid #1cc88a !important; }
        .border-left-info { border-left: 4px solid #36b9cc !important; }
        .border-left-warning { border-left: 4px solid #f6c23e !important; }
        .border-left-secondary { border-left: 4px solid #858796 !important; }

        .text-xs {
            font-size: 0.7rem;
        }

        .text-gray-800 {
            color: #5a5c69 !important;
        }

        .text-gray-300 {
            color: #dddfeb !important;
        }

        .dashboard .card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
        }

        .dashboard .shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }
    </style>

    <?= $this->fetch('script') ?>
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= $this->Url->build('/') ?>" class="brand">
                <div class="brand-icon">
                    <i class="bi bi-grid-fill"></i>
                </div>
                RiceMillSys
            </a>
        </div>

        <nav class="sidebar-nav">
            <?php if ($this->Identity->isLoggedIn()): ?>
                <div class="nav-section">
                    <div class="nav-title">Main Navigation</div>
                    <ul class="nav-links">
                        <?php if ($role !== 'customer'): ?>
                            <!-- Staff, Admin, Owner see full dashboard -->
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    '<i class="bi bi-house-door-fill"></i><span>Dashboard</span>',
                                    ['controller' => 'Dashboard', 'action' => 'index'],
                                    [
                                        'class' => 'nav-link' . ($currentController === 'Dashboard' ? ' active' : ''),
                                        'escape' => false
                                    ]
                                ) ?>
                            </li>
                        <?php endif; ?>

                        <!-- All logged-in users see Milling Orders -->
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="bi bi-gear-wide-connected"></i><span>Milling Orders</span>',
                                ['controller' => 'MillingOrders', 'action' => 'index'],
                                [
                                    'class' => 'nav-link' . ($currentController === 'MillingOrders' ? ' active' : ''),
                                    'escape' => false
                                ]
                            ) ?>
                        </li>

                        <?php if ($role !== 'customer'): ?>
                            <!-- Only staff, admin, owner see Customers -->

<li class="nav-item">  
    <?= $this->Html->link(
        '<i class="bi bi-people-fill"></i><span>Customers</span>',
        ['controller' => 'Users', 'action' => 'customers'], // Changed from Customers to Users
        [
            'class' => 'nav-link' . ($currentController === 'Users' && $currentAction === 'customers' ? ' active' : ''),
            'escape' => false
        ]
    ) ?>
</li>

                            <!-- Only staff, admin, owner see Payments -->
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    '<i class="bi bi-credit-card-fill"></i><span>Payments</span>',
                                    ['controller' => 'Payments', 'action' => 'index'],
                                    [
                                        'class' => 'nav-link' . ($currentController === 'Payments' ? ' active' : ''),
                                        'escape' => false
                                    ]
                                ) ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if (in_array($role, ['admin', 'owner'])): ?>
                    <div class="nav-section">
                        <div class="nav-title">Administration</div>
                        <ul class="nav-links">
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    '<i class="bi bi-shield-lock-fill"></i><span>User Management</span>',
                                    ['controller' => 'Users', 'action' => 'index'],
                                    [
                                        'class' => 'nav-link' . ($currentController === 'Users'&& $currentAction === 'index' ? ' active' : ''),
                                        'escape' => false
                                    ]
                                ) ?>
                            </li>
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    '<i class="bi bi-person-plus-fill"></i><span>Add User</span>',
                                    ['controller' => 'Users', 'action' => 'add'],
                                    [
                                        'class' => 'nav-link' . ($currentController === 'Users' && $currentAction === 'add' ? ' active' : ''),
                                        'escape' => false
                                    ]
                                ) ?>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($role === 'staff'): ?>
                    <div class="nav-section">
                        <div class="nav-title">Staff Tools</div>
                        <ul class="nav-links">
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    '<i class="bi bi-person-plus-fill"></i><span>Add Customer</span>',
                                    ['controller' => 'Users', 'action' => 'addcustomers'],
                                    [
                                        'class' => 'nav-link' . ($currentController === 'Users' && $currentAction === 'addcustomers' ? ' active' : ''),
                                        'escape' => false
                                    ]
                                ) ?>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="nav-section">
                    <div class="nav-title">Account</div>
                    <ul class="nav-links">
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="bi bi-person-circle"></i><span>My Profile</span>',
                                ['controller' => 'Users', 'action' => 'profile'],
                                [
                                    'class' => 'nav-link' . ($currentController === 'Users' && $currentAction === 'profile' ? ' active' : ''),
                                    'escape' => false
                                ]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="bi bi-box-arrow-right"></i><span>Logout</span>',
                                ['controller' => 'Users', 'action' => 'logout'],
                                [
                                    'class' => 'nav-link text-danger',
                                    'escape' => false
                                ]
                            ) ?>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Not logged in - Show only login -->
                <div class="nav-section">
                    <div class="nav-title">Get Started</div>
                    <ul class="nav-links">
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="bi bi-box-arrow-in-right"></i><span>Login</span>',
                                ['controller' => 'Users', 'action' => 'login'],
                                ['class' => 'nav-link', 'escape' => false]
                            ) ?>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <header class="main-header">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title"><?= h($this->fetch('title') ?: 'Dashboard') ?></h1>
            </div>
            
            <?php if ($this->Identity->isLoggedIn()): ?>
            <div class="header-actions">
                <div class="user-menu">
                    <div class="user-avatar">
                        <?= strtoupper(substr($identity, 0, 1)) ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= h($identity) ?></div>
                        <div class="user-role"><?= h($role) ?></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </header>

        <div class="content-wrapper">
            <div class="content-area">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>

        <footer class="main-footer">
            <div class="footer-content">
                <div>Rice Milling System</div>
                <div>&copy; <?= date('Y') ?> All Rights Reserved</div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('mobileMenuToggle');

            if (toggle) {
                toggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Close sidebar when clicking nav links on mobile
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>