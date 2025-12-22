<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title><!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"><!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <!-- Select2 core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 Bootstrap-5 Theme -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.6.2/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" /> -->
    <link href="<?= base_url() ?>assets/css/summary.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/t_patrol.css" rel="stylesheet">

    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
        }

        /* Thumbnail */
        #imagePreview img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: .5rem;
            cursor: zoom-in;
            border: 1px solid rgba(0, 0, 0, .1);
        }

        /* Overlay viewer */
        #imgViewer {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .8);
            display: none;
            z-index: 9999;
        }

        #imgViewer.active {
            display: block;
        }

        .viewer-toolbar {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 8px;
            z-index: 2;
        }

        .viewer-toolbar button {
            background: rgba(255, 255, 255, .9);
            border: 0;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }

        .viewer-stage {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            overflow: hidden;
            touch-action: none;
            /* penting untuk drag di mobile */
        }

        #viewerImg {
            max-width: 90vw;
            max-height: 90vh;
            user-select: none;
            pointer-events: none;
            transform-origin: center center;
            /* diubah via JS */
            will-change: transform;
        }

        /* Thumbnail */
        #imagePreview_edit img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: .5rem;
            cursor: zoom-in;
            border: 1px solid rgba(0, 0, 0, .1);
        }

        /* Overlay viewer */
        #imgViewer_edit {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .8);
            display: none;
            z-index: 9999;
        }

        #imgViewer_edit.active {
            display: block;
        }

        .viewer-toolbar {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 8px;
            z-index: 2;
        }

        .viewer-toolbar button {
            background: rgba(255, 255, 255, .9);
            border: 0;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }

        .viewer-stage {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            overflow: hidden;
            touch-action: none;
            /* penting untuk drag di mobile */
        }

        .previewpdf {
            display: none;
        }

        .previewpdf_fill {
            display: none;
        }

        #viewerImg_edit {
            max-width: 90vw;
            max-height: 90vh;
            user-select: none;
            pointer-events: none;
            transform-origin: center center;
            /* diubah via JS */
            will-change: transform;
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
            <div class="profile-dropdown">
                <img src="https://ui-avatars.com/api/?name=User&amp;background=0d6efd&amp;color=fff&amp;size=128" alt="Profile" class="profile-img" id="profileImg">
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="#" class="dropdown-item" id="logoutBtn">
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
            "><?php if ($role === 1) {
                    echo "Administrator";
                } else if ($role === 2) {
                    echo "Auditor";
                } else {
                    echo "Auditee";
                } ?></p>
        </div>
        <a href="<?= base_url('summary') ?>" class="menu-item " data-page="dashboard">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= base_url('temuan_patrol') ?>" class="menu-item active" data-page="patrol">
            <i class="bi bi-search"></i>
            <span>Data Patrol</span>
        </a>
        <a href="<?= base_url('schedule') ?>" class="menu-item" data-page="schedule">
            <i class="bi bi-calendar-check"></i>
            <span>Schedule</span>
        </a>
        <?php if ($role === 1) : ?>
            <div class="menu-header">
                Master Data
            </div>
            <a href="<?= base_url('admin/mdata_user') ?>" class="menu-item" data-page="user">
                <i class="bi bi-people"></i>
                <span>User</span>
            </a>
            <a href="<?= base_url('admin/mdata_department') ?>" class="menu-item" data-page="department">
                <i class="bi bi-building"></i> <span>Departemen</span>
            </a>
        <?php endif; ?>
    </div><!-- Main Content -->
    <div class="main-content" id="mainContent">
       

        <!-- Data Table -->
         

        <!-- <div class="card" >
            <div class="card-body">
                <h5 class="card-title">Filter</h5>
               
            </div>
        </div> -->


        
        <div class="table-card mt-2">
            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Temuan Patrol</h3>
                    <?php if ($role === 1 || $role === 2) : ?>
                        <button type="button" class="btn btn-primary cekalert" data-bs-toggle="modal" data-bs-target="#modal_tambahdata">
                            <i class="bi bi-plus-circle"></i> Tambah Temuan</button>
                    <?php endif; ?>
                </div>

            </div>
            <div class="table-responsive mt-3">
                <div id="filterContainer" class="row g-2 mb-3"></div>
                <table id="auditTable" class="table table-striped table-hover" style=" font-size: 13px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Patrol</th>
                            <th>Auditor</th>
                            <th>Auditee</th>
                            <th>Area / Proses</th>
                            <th>Temuan</th>
                            <th>Analisa Penyebab</th>
                            <th>Action</th>
                            <th>PIC Action</th>
                            <th>Due Date</th>
                            <th class="text-center">Download Evidence</th>
                            <th class="text-center">Status</th>
                            <th width="100" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        


                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- Footer -->
    <div class="footer" id="footer">
        <p id="footerText">© 2025 Quality Patrol — All rights reserved</p>
    </div>
   
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script><!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script><!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Select2 core JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="<?= base_url() ?>assets/js/temuan_patrol/view-image.js"></script>
    <script src="<?= base_url() ?>assets/js/temuan_patrol/t_patrol.js"></script>

</body>

</html>