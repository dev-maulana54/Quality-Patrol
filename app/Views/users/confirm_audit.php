<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title><!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"><!-- Google Fonts - Poppins -->
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <!-- Select2 core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 Bootstrap-5 Theme -->

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/summary.css" rel="stylesheet">

    <style>
        /* @view-transition {
            navigation: auto;
        } */
        .chart-container {
            position: relative;
            width: 100%;
            height: 380px;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 260px;
            }
        }

        .dark-theme .select2-container--default .select2-selection--single {
            background-color: #1e1e1e !important;
            border: 1px solid #444 !important;
            color: #b0b0b0 !important;
            height: 38px;
            display: flex;
            align-items: center;
            border-radius: 6px;
        }

        .dark-theme .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #b0b0b0 !important;
            padding-left: 10px;
        }

        .dark-theme.select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #bbb transparent transparent transparent !important;
        }

        .dark-theme .select2-dropdown {
            background-color: #2a2a2a !important;
            border: 1px solid #444 !important;
        }

        .dark-theme .select2-results__option {
            color: #b0b0b0 !important;
            padding: 8px 12px;
        }

        .dark-theme .select2-results__option--highlighted {
            background-color: #3b3b3b !important;
            color: #fff !important;
        }

        .dark-theme .select2-search--dropdown .select2-search__field {
            background-color: #1e1e1e !important;
            color: #b0b0b0 !important;
            border: 1px solid #555 !important;
            border-radius: 4px;
        }

        .dark-theme .select2-container--default .select2-results__option--selected {
            background-color: #0d6efd !important;
        }

        /* ===== LAYOUT DASAR ===== */
        .sidebar {
            width: 260px;
            /* sesuaikan dengan lebar sidebar kamu */
            transition: width 0.3s ease;
        }

        .main-content {
            margin-left: 260px;
            /* HARUS sama dengan lebar sidebar */
            transition: margin-left 0.3s ease;
        }

        /* ===== SAAT DISEMBUNYIKAN ===== */
        .sidebar.collapsed {
            width: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }

        .main-content.expanded {
            margin-left: 0 !important;
        }

        /* Biar chart ikut menyesuaikan lebar */
        .chart-container {
            width: 10 0%;
        }

        /* ==== Submenu container ==== */
        .menu-item.has-submenu {
            flex-direction: column;
            align-items: stretch;
        }

        /* ==== Toggle row (biar sama kayak menu-item lain) ==== */
        .menu-item.submenu-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        /* kiri: icon + text sejajar */
        .menu-item.submenu-toggle .menu-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* icon ukuran konsisten (opsional) */
        .menu-item.submenu-toggle i,
        .submenu-item i {
            font-size: 18px;
            width: 22px;
            /* bikin icon kolomnya rata */
            text-align: center;
        }

        /* chevron di kanan */
        .chevron {
            font-size: 12px;
            transition: transform 0.2s ease;
        }

        /* open state */
        .menu-item.has-submenu.open .chevron {
            transform: rotate(180deg);
        }

        /* ==== Submenu items ==== */
        .submenu {
            display: none;
            flex-direction: column;
            padding-left: 42px;
            /* indent rapi */
            margin-top: 6px;
            gap: 6px;
        }

        .menu-item.has-submenu.open .submenu {
            display: flex;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            text-decoration: none;
            color: #ddd;
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .light-theme .submenu-item {
            color: #2c3e50;
        }
    </style>

    <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
</head>

<body class="light-theme"><!-- Header -->
    <div class="header">
        <div class="header-left"><button class="mobile-toggle" id="mobileToggle"> <i class="bi bi-list"></i> </button>
            <h1 class="logo-text" id="appName">Quality Patrol</h1>
        </div>
        <div class="header-right">

            <button class="theme-toggle" id="themeToggle">
                <i class="bi bi-sun-fill"></i>
            </button>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-layout-sidebar-inset"></i>
            </button>
            <div class="profile-dropdown">
                <img src="https://ui-avatars.com/api/?name=User&amp;background=0d6efd&amp;color=fff&amp;size=128" alt="Profile" class="profile-img" id="profileImg">
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="javascript:void(0)" class="dropdown-item" id="logoutBtn">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout </a>
                </div>
            </div>
        </div>
    </div><!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div style="
            padding: 20px;
            margin: 0 15px 20px 15px;
            background: linear-gradient(135deg, #0d6efd, #0056b3);
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        ">
            <h4 style="
                color: white;
                font-size: 14px;
                font-weight: 600;
                margin: 0 0 4px 0;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            " id="sidebarUserName"><?= $nama; ?> </h4>
            <p style="
                color: rgba(255, 255, 255, 0.8);
                font-size: 11px;
                margin: 0;
                font-weight: 400;
            "><?= $role; ?></p>
        </div>
        <a href="<?= base_url('summary') ?>" class="menu-item active" data-page="dashboard">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <div class="menu-item has-submenu">
            <div class="menu-item submenu-toggle">
                <div class="menu-left">
                    <i class="bi bi-search"></i>
                    <span>Data Patrol</span>
                </div>
                <i class="bi bi-chevron-down chevron"></i>
            </div>

            <div class="submenu">
                <a href="<?= base_url('temuan_patrol/auditor') ?>" class="submenu-item">
                    <i class="bi bi-person-badge"></i>
                    <span>Data Auditor</span>
                </a>
                <a href="<?= base_url('temuan_patrol/auditee') ?>" class="submenu-item">
                    <i class="bi bi-person-check"></i>
                    <span>Data Auditee</span>
                </a>
                <a href="<?= base_url('temuan_patrol/list_daftar_hadir') ?>" class="submenu-item">
                    <i class="bi bi-person-check"></i>
                    <span>Daftar Hadir</span>
                </a>
            </div>
        </div>



        <a href="<?= base_url('schedule') ?>" class="menu-item" data-page="patrol">
            <i class="bi bi-calendar-check"></i>
            <span>Schedule</span>
        </a>
        <?php if ($role === 'Administrator') : ?>
            <div class="menu-header">
                Master Data
            </div>
            <a href="<?= base_url('admin/mdata_user') ?>" class="menu-item" data-page="user">
                <i class="bi bi-people"></i>
                <span>User</span>
            </a>

        <?php endif; ?>
    </div><!-- Main Content -->
    <div class="main-content" id="mainContent">
        <h2 class="page-title" id="dashboardTitle">Dashboard Overview</h2><!-- Charts Row 1 -->
        <div class="row">




        </div>
        <!-- Button di bawah row -->
        <div class="text-center mt-4">
            <!-- Button di bawah row -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success btn-lg px-5">
                    <i class="bi bi-play-circle-fill me-2"></i>
                    Mulai Audit
                </button>
            </div>
        </div>


    </div><!-- Footer -->
    <div class="footer" id="footer">
        <p id="footerText">© 2025 Quality Patrol — All rights reserved</p>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script><!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <!-- HighChart -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/non-cartesian-zoom.js"></script>
    <script src="https://code.highcharts.com/modules/mouse-wheel-zoom.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/themes/adaptive.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Select2 core JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="<?= base_url() ?>assets/js/summary/summary.js"></script>
    <script>
        document.querySelectorAll('.submenu-toggle').forEach(item => {
            item.addEventListener('click', () => {
                item.parentElement.classList.toggle('open');
            });
        });
    </script>


    <script>










    </script>
</body>

</html>