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
    <!-- Air Datepicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <!-- Select2 core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.bootstrap5.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
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

        .patrol-tabs {
            display: inline-flex;
            background-color: white;
            padding: 6px;
            border-radius: 14px;
            gap: 6px;
        }

        .dark-theme .patrol-tabs {
            background-color: #1e1e1e;
        }

        .patrol-tabs .nav-link {
            padding: 8px 20px;
            border-radius: 10px;
            color: #4ea1ff;
            font-weight: 500;
            white-space: nowrap;
        }

        .patrol-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            box-shadow: 0 4px 12px rgba(13, 110, 253, .35);
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

        .submenu-item.active {
            background-color: #0d6efd;
            color: #fff;
            box-shadow: 0 4px 12px rgba(13, 110, 253, .35);
        }

        .signature-wrap {
            height: 220px;
            position: relative;
            overflow: hidden;
        }

        /* Penting: canvas ikut tinggi/lebar wrapper */
        #signature_pad {
            width: 100%;
            height: 100%;
            display: block;
            touch-action: none;
            /* biar tidak scroll pas teken */
        }

        .light-theme .submenu-item {
            color: #2c3e50;
        }

        .light-theme .submenu-item.active {
            color: #e2e2e2ff;
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
            "><?= $role; ?></p>
        </div>
        <a href="<?= base_url('summary') ?>" class="menu-item " data-page="dashboard">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <div class="menu-item has-submenu open">
            <div class="menu-item submenu-toggle">
                <div class="menu-left">
                    <i class="bi bi-search"></i>
                    <span>Data Patrol</span>
                </div>
                <i class="bi bi-chevron-down chevron"></i>
            </div>

            <div class="submenu">
                <a href="<?= base_url('temuan_patrol/auditor') ?>" class="submenu-item active">
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
        <a href="<?= base_url('schedule') ?>" class="menu-item" data-page="schedule">
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


        <!-- Data Table -->


        <!-- <div class="card" >
            <div class="card-body">
                <h5 class="card-title">Filter</h5>
               
            </div>
        </div> -->


        <div class="table-card text-light">

            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Temuan Patrol</h3>

                    <button type="button" class="btn btn-primary cekalert" data-bs-toggle="modal" data-bs-target="#modal_tambahdata">
                        <i class="bi bi-plus-circle"></i> Tambah Temuan</button>

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
                            <th width="150">Temuan</th>
                            <th width="150">Analisa Penyebab</th>
                            <th>Action</th>
                            <th>PIC Action</th>
                            <th>Due Date</th>
                            <th class="text-center">Download Evidence</th>
                            <th class="text-center">Status</th>
                            <th width="100" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_patrol as $index => $patrol) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $patrol['tanggal_patrol'] ?></td>
                                <td><?= $patrol['nama_auditor'] ?></td>
                                <td><?= $patrol['nama_auditee'] ?></td>
                                <td><?= $patrol['section_name'] ?></td>
                                <td><?= $patrol['deskripsi_temuan'] ?></td>
                                <td><?= $patrol['analisa_penyebab'] ?></td>
                                <td><?= $patrol['action'] ?></td>
                                <td><?= $patrol['pic_section_name'] ?></td>
                                <td><?= $patrol['due_date'] ?></td>
                                <td class="text-center">
                                    <?php if ($patrol['nama_file']) : ?>
                                        <button type="button" class="btn btn-secondary btnDownload" data-namafile="<?= $patrol['nama_file'] ?>"><i class="bi bi-download"></i>
                                        <?php endif; ?>
                                        </button>
                                </td>
                                <td class="text-center">
                                    <?php if ($patrol['status'] == 1) : ?>
                                        <span class="badge bg-success">Close</span>
                                    <?php elseif ($patrol['status'] == 2) : ?>
                                        <span class="badge bg-warning">In Progress</span>
                                    <?php elseif ($patrol['status'] == 4) : ?>
                                        <span class="badge bg-danger">Cancel</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Open</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($role === 'user') : ?>

                                        <button type="button" class="btn btn-info fill_data btn-sm" data-bs-toggle="modal" data-bs-target="#modal_fillData" data-id="<?= $patrol['id_temuan_patrol'] ?>" data-npkauditor="<?= $patrol['id_auditor'] ?>"><i class="bi bi-journal-arrow-down"></i> Fill</button>

                                    <?php endif; ?>
                                    <?php if ($role === 'Administrator') : ?>
                                        <button type="button" class="btn btn-info mt-2 edit_data btn-sm" data-bs-toggle="modal" data-bs-target="#modal_editdata" data-id="<?= $patrol['id_temuan_patrol'] ?>"><i class="bi bi-pencil-fill"></i> Edit</button>
                                        <button type="button" class="btn btn-danger mt-2 hapus_data btn-sm" data-id="<?= $patrol['id_temuan_patrol'] ?>"><i class="bi bi-trash3-fill"></i> Hapus</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    </tbody>
                </table>
            </div>






        </div>



    </div><!-- Footer -->
    <div class="footer" id="footer">
        <p id="footerText">© 2025 Quality Patrol — All rights reserved</p>
    </div>
    <!-- Button trigger modal -->

    <div class="modal fade" id="modal_fillData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Jawab Temuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="fill_id_temuan_patrol">
                        <input type="hidden" id="fill_id_auditor">
                        <input type="hidden" id="fill_id_departement_temuan">
                        <input type="hidden" id="fill_id_section_temuan">


                        <div class="form-group">

                            <label for="auditorName" class="form-label">
                                <i class="bi bi-check-circle-fill text-success"></i> Status </label>
                            <input type="text" class="form-control" id="fill_status_temuan" value="aaabc" disabled>
                        </div>
                        <div class="form-group">

                            <label for="auditorName" class="form-label">
                                <i class="bi bi-person-fill me-1"></i> Tanggal Patrol </label>
                            <input type="text" class="form-control" id="fill_tanggal_patrol" value="aaabc" disabled>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 mt-2">
                                <label for="auditorName" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i> Auditor </label>
                                <input type="text" class="form-control" id="fill_auditorName" value="aaabc" disabled>
                            </div>
                            <div class="form-group col-md-6 mt-2">
                                <label for="auditorName" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i> Auditee </label>
                                <input type="text" class="form-control" id="fill_auditeeName" value="aaabc" disabled>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label for="auditorName" class="form-label">
                                <i class="bi bi-building me-1"></i> Area / Proses </label>
                            <input type="text" class="form-control" id="fill_area_proses" value="Incoming" disabled>
                        </div>
                        <div class="form-group">
                            <label for="Departemen" class="form-label mt-3" style="font-size: 20px;">

                                Evidence Findings
                            </label>
                            <hr style="height: 2px; border: none;">
                        </div>
                        <!-- TEMPAT EVIDENCE DITAMPILKAN -->
                        <div class="row mt-2" id="evidence_container">
                            <!-- evidence akan di-inject via JS -->
                        </div>
                        <div class="form-group mt-2">
                            <label for="auditorName" class="form-label">
                                <i class="bi bi-journal-text me-1"></i> Deskripsi Temuan </label> <small> <i>Di isi oleh Auditor !!!</i></small>

                            <textarea class="form-control" placeholder="Leave a comment here" id="fill_deskripsi_temuan" disabled></textarea>

                        </div>
                        <div class="form-group mt-2">
                            <label for="auditorName" class="form-label">
                                <i class="bi bi-journal-text me-1"></i> Analisa Penyebab </label><small> <i>Di isi oleh Audite !!!</i></small>

                            <textarea class="form-control" placeholder="Leave a comment here" id="fill_analisa_penyebab" disabled></textarea>

                        </div>
                        <div class="form-group mt-2">
                            <label for="auditorName" class="form-label">
                                <i class="bi bi-journal-text me-1"></i> Action </label><small> <i>Di isi oleh Audite !!!</i></small>


                            <textarea class="form-control" placeholder="Leave a comment here" id="fill_action" disabled></textarea>

                        </div>
                        <div class="form-group mt-2">
                            <label for="fill_pic_action" class="form-label">
                                <i class="bi bi-building me-1"></i> PIC Action </label><small> <i>Di isi oleh Audite !!!</i></small>

                            <select class="form-select select2" id="fill_pic_action" style="width:100%; " disabled>

                                <option value="">Blank</option>

                            </select>

                        </div>
                        <div class="form-group mt-2">
                            <label for="auditDate" class="form-label">
                                <i class="bi bi-calendar-fill me-1"></i> Due Date </label><small class="text-danger"> <i>Cek Tanggal Kembali !</i></small>

                            <input type="text" class="form-control auditDate" id="fill_due_date" placeholder="Pilih tanggal audit" required disabled>

                        </div>
                        <div class="form-group mt-3">
                            <label for="fileUpload" class="form-label">
                                <i class="bi bi-upload me-1"></i> Upload File (PDF, Word, Excel, Image)
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="fileUpload"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,image/*" disabled>

                        </div>
                        <div class="form-group col-md-12 mt-3 previewpdf_fill">
                            <label for="fileUpload_edit" class="form-label">
                                <!-- Preview PDF -->
                            </label>
                            <a type="button" id="previewPDF_fill" class="btn btn-primary" target="_blank"> <i class="bi bi-eye me-1"></i> Preview PDF </a>
                        </div>
                        <!-- Preview gambar -->
                        <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                        <!-- Viewer overlay (BUKAN modal) -->
                        <div id="imgViewer" aria-hidden="true">
                            <div class="viewer-toolbar">
                                <button type="button" id="zoomInBtn" title="Zoom In">+</button>
                                <button type="button" id="zoomOutBtn" title="Zoom Out">−</button>
                                <button type="button" id="resetBtn" title="Reset">Reset</button>
                                <button type="button" id="closeBtn" title="Tutup">×</button>
                            </div>
                            <div class="viewer-stage" id="viewerStage">
                                <img id="viewerImg" alt="Preview detail">
                            </div>
                        </div>


                        <div class="form-group mt-2" id="list_option_status_container">
                            <label for="option_status" class="form-label">

                                <i class="bi bi-collection"></i> Option Status </label>
                            <select class="form-select" id="list_option_status" style="width:100%;">
                                <option value="">-- Pilih Opsi --</option>
                                <?php if ($role === 'Administrator') : ?>
                                    <option value="4">Cancel</option>
                                <?php endif; ?>
                                <option value="3">Open</option>
                                <?php if ($role === 'Administrator') : ?>
                                    <option value="2">In Progress</option>
                                <?php endif; ?>

                                <option value="1">Close</option>
                                <?php if ($role === 'Administrator') : ?>
                                    <option value="4">Cancel</option>
                                <?php endif; ?>
                            </select>

                        </div>
                        <div class="form-group mt-2" id="row_keterangan_auditor2">
                            <label for="keterangan_auditor2" class="form-label">
                                <!-- todo : carikan saya icon yang cocok untuk kata status -->
                                <i class="bi bi-collection"></i> Keterangan Auditor </label>
                            <textarea class="form-control" placeholder="Leave a comment here" id="keterangan_auditor2" required></textarea>

                        </div>
                        <div class="form-group mt-2" id="row_keterangan_cancel2" style="display: none;">
                            <label for="keterangan_cancel" class="form-label">
                                <!-- todo : carikan saya icon yang cocok untuk kata status -->
                                <i class="bi bi-collection"></i> Keterangan Cancel </label>
                            <textarea class="form-control" placeholder="Leave a comment here" id="keterangan_cancel2" required></textarea>

                        </div>




                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btnSubmit_filldata" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_tambahdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Temuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="list_schedule" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i> Schedule Audit </label>
                                <select class="" id="list_schedule" style="width:100%;">
                                    <option value="">-- Pilih Schedule --</option>
                                    <?php foreach ($schedule_audit as $sa) : ?>
                                        <?php setlocale(LC_TIME, 'id_ID.UTF-8', 'Indonesian_indonesia.1252');
                                        $date = DateTime::createFromFormat('d/m/Y', $sa['tanggal_patrol']);
                                        ?>
                                        <option value="<?= $sa['id_schedule'] ?>">[Tanggal Schedule Patrol : <?php echo strftime('%d %B %Y', $date->getTimestamp()); ?>] - Departement : <?= $sa['departement_name'] ?>; Section : <?= $sa['section_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="form-group col-md-6">
                                <label for="auditorName" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i> Nama Auditor </label>

                                <input type="text" class="form-control" id="auditorName" value="<?= $nama; ?>" disabled>


                            </div>
                            <div class="form-group col-md-6">
                                <label for="tanggal_patrol" class="form-label">
                                    <i class="bi bi-calendar-fill me-1"></i> Tanggal Patrol Actual</label>
                                <input type="text" class="form-control auditDate" id="tanggal_patrol" placeholder="Auditee" required readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="Departemen" class="form-label mt-3" style="font-size: 20px;">

                                    Area Patrol
                                </label>
                                <hr style="height: 2px; border: none;">
                            </div>
                            <div class="form-group col-md-4 mt-2">
                                <label for="Deskripsi Temuan" class="form-label">
                                    <i class="bi bi-journal-text me-1"></i>Departement</label>
                                <input class="form-control" placeholder="Leave a comment here" id="list_dept" data-id="" disabled>
                            </div>
                            <div class="form-group col-md-4 mt-2">
                                <label for="Deskripsi Temuan" class="form-label">
                                    <i class="bi bi-journal-text me-1"></i>Seksi</label>
                                <input class="form-control" placeholder="Leave a comment here" id="list_seksi" data-id="" disabled>
                            </div>
                            <div class="form-group col-md-4 mt-2">
                                <label for="Deskripsi Temuan" class="form-label">
                                    <i class="bi bi-journal-text me-1"></i>Auditee</label>
                                <input type="text" class="form-control Auditee" id="nama_auditee" placeholder="Nama Auditee" disabled>
                            </div>


                        </div>
                        <div class="form-group mt-2">
                            <label for="Deskripsi Temuan" class="form-label">
                                <i class="bi bi-journal-text me-1"></i>Deskripsi Temuan</label>

                            <textarea class="form-control" placeholder="Leave a comment here" id="deskripsi_temuan"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label for="upload_file" class="form-label">
                                <i class="bi bi-upload me-1"></i>Upload File
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="fileUpload_tambah"
                                name="fileUpload_tambah"
                                onchange="handleFileUpload()">

                            <!-- Preview Image -->
                            <div class="mt-2">
                                <img
                                    id="preview_image"
                                    class="img-fluid rounded d-none"
                                    style="max-height: 200px;"
                                    alt="Preview Image">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Departemen" class="form-label mt-3" style="font-size: 20px;">

                                PIC Action
                            </label>
                            <hr style="height: 2px; border: none;">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 mt-2">
                                <label for="pic_action_list_dept" class="form-label">
                                    <i class="bi bi-building me-1"></i>Departemen </label>
                                <select class="select_tom" id="pic_action_list_dept" style="width:100%;">
                                    <option value="">- Pilih Departement -</option>
                                    <?php foreach ($data_dept as $dept) : ?>
                                        <option value="<?= $dept['id_departement'] ?>" data-departement="<?= $dept['departement'] ?>"><?= $dept['departement'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="form-group col-md-6 mt-2">
                                <label for="pic_action_list_seksi" class="form-label">
                                    <i class="bi bi-diagram-3 me-1"></i>Seksi </label>
                                <select class="select_tom" id="pic_action_list_seksi" style="width:100%;" disabled>
                                    <option value="">-- Pilih Opsi --</option>

                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="Departemen" class="form-label mt-3" style="font-size: 20px;">

                                    Rekap temuan
                                </label>
                                <button type="button" class="btn btn-success float-right mt-2 btn_rekaptemuan"><i class="bi bi-plus-circle"></i> Tambah temuan </button>
                                <hr style="height: 2px; border: none;">
                            </div>

                        </div>
                        <div class="form-group mt-2">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Deskripsi temuan</th>
                                        <th scope="col">File</th>
                                        <th scope="col">PIC Action Area</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="rekap_tbody">

                                </tbody>
                            </table>
                        </div>
                        <div class="row g-3" id="">
                            <!-- PILIH JENIS SIGN -->
                            <div class="form-group col-md-12">
                                <label for="sign_type" class="form-label">
                                    <i class="bi bi-pen me-1"></i> Jenis Tanda Tangan
                                </label>
                                <select class="form-select" id="sign_type" name="sign_type">
                                    <option value="digital" selected>Tanda tangan digital</option>
                                    <option value="upload">Upload file gambar</option>
                                </select>
                            </div>

                            <!-- DIGITAL SIGN -->
                            <div class="col-12" id="digital_section">
                                <div class="border rounded-3 p-3">

                                    <div class="d-flex flex-wrap gap-3 align-items-end mb-3">
                                        <div>
                                            <label class="form-label mb-1">Warna</label>
                                            <input type="color" id="pen_color" class="form-control form-control-color" value="#ff006a"
                                                title="Pilih warna">
                                        </div>

                                        <div style="min-width: 240px;">
                                            <label class="form-label mb-1">Tebal garis: <span id="pen_width_label">3</span> px</label>
                                            <input type="range" class="form-range" id="pen_width" min="1" max="12" step="1" value="3">
                                        </div>

                                        <div class="ms-auto d-flex gap-2">
                                            <button type="button" class="btn btn-outline-secondary" id="btnClearSign">
                                                <i class="bi bi-eraser me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>

                                    <label class="form-label">
                                        <i class="bi bi-check2-square me-1"></i> Area Approval
                                    </label>

                                    <div class="rounded-3 border bg-light signature-wrap">
                                        <canvas id="signature_pad"></canvas>
                                    </div>

                                    <input type="hidden" name="signature_data" id="signature_data">
                                    <small class="text-muted d-block mt-2">
                                        Tulis tanda tangan pada area di atas.
                                    </small>
                                </div>
                            </div>

                            <!-- UPLOAD SIGN -->
                            <div class="col-12 d-none" id="upload_section">
                                <div class="border rounded-3 p-3">
                                    <label for="sign_file" class="form-label">
                                        <i class="bi bi-upload me-1"></i> Upload tanda tangan (PNG/JPG)
                                    </label>
                                    <input class="form-control" type="file" id="sign_file" name="sign_file"
                                        accept="image/png,image/jpeg">

                                    <div class="mt-3 d-none" id="upload_preview_wrap">
                                        <label class="form-label mb-1">Preview</label>
                                        <div class="border rounded-3 p-2 bg-light">
                                            <img id="upload_preview" alt="Preview" style="max-width: 100%; max-height: 220px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btn_tambahTemuan"><i class="bi bi-plus-circle"></i> Submit </button>
                </div>
            </div>
        </div>

    </div>

    <?php if ($role === 'Administrator') : ?>
        <div class="modal fade" id="modal_editdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit data Temuan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <input type="hidden" id="edit_id_temuan_patrol">

                            <div class="form-group">

                                <label for="auditorName" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i> Tanggal Patrol </label><small class="text-danger"> <i>Cek Tanggal Kembali !</i></small>
                                <input type="text" class="form-control" id="edit_tanggal_patrol">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 mt-2">
                                    <label for="auditorName" class="form-label">
                                        <i class="bi bi-person-fill me-1"></i> Auditor </label>
                                    <select class="form-select select2" id="edit_nama_auditor" style="width:100%;">

                                        <option value="">Blank</option>

                                    </select>

                                </div>
                                <div class="form-group col-md-6 mt-2">
                                    <label for="auditorName" class="form-label">
                                        <i class="bi bi-person-fill me-1"></i> Auditee </label>
                                    <select class="form-select select2" id="edit_nama_auditee" style="width:100%;">

                                        <option value="">Blank</option>

                                    </select>
                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-md-6 mt-2">
                                    <label for="auditorName" class="form-label">
                                        <i class="bi bi-building me-1"></i> Area / Proses </label>
                                    <select class="form-select select2" id="edit_area_proses" style="width:100%;">

                                        <option value="">Blank</option>

                                    </select>

                                </div>
                                <div class="form-group col-md-6 mt-2">
                                    <label for="auditorName" class="form-label">
                                        <i class="bi bi-building me-1"></i> PIC Action </label>
                                    <select class="form-select select2" id="edit_pic_action" style="width:100%;">

                                        <option value="">Blank</option>

                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="Departemen" class="form-label mt-3" style="font-size: 20px;">

                                        Evidence Findings
                                    </label>
                                    <hr style="height: 2px; border: none;">
                                </div>
                                <!-- TEMPAT EVIDENCE DITAMPILKAN -->
                                <div class="row mt-2" id="evidence_container2">
                                    <!-- evidence akan di-inject via JS -->
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="auditorName" class="form-label">
                                    <i class="bi bi-journal-text me-1"></i> Deskripsi Temuan </label>

                                <textarea class="form-control" placeholder="Leave a comment here" id="edit_deskripsi_temuan"></textarea>

                            </div>
                            <div class="form-group mt-2">
                                <label for="auditorName" class="form-label">
                                    <i class="bi bi-journal-text me-1"></i> Analisa Penyebab </label>
                                <textarea class="form-control" placeholder="Leave a comment here" id="edit_analisa_penyebab"></textarea>
                            </div>
                            <div class="form-group mt-2">
                                <label for="auditorName" class="form-label">
                                    <i class="bi bi-journal-text me-1"></i> Action </label>
                                <textarea class="form-control" placeholder="Leave a comment here" id="edit_action" required></textarea>
                            </div>

                            <div class="form-group mt-2">
                                <label for="edit_due_date" class="form-label">
                                    <i class="bi bi-calendar-fill me-1"></i> Due Date </label> <small class="text-danger"> <i>Cek Tanggal Kembali !</i></small>
                                <input type="text" class="form-control" id="edit_due_date" placeholder="Pilih tanggal audit" required>
                            </div>
                            <div class="row">

                                <div class="form-group col-md-12 mt-3">
                                    <label for="fileUpload_edit" class="form-label">
                                        <i class="bi bi-upload me-1"></i> Upload File (PDF, Word, Excel, Image)
                                    </label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        id="fileUpload_edit"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,image/*">
                                </div>
                                <div class="form-group col-md-12 mt-3 previewpdf">
                                    <!-- <label for="fileUpload_edit" class="form-label">
                                        Preview PDF
                                    </label> -->
                                    <a type="button" id="previewPDF" class="btn btn-primary" target="_blank"> <i class="bi bi-eye me-1"></i> Preview PDF </a>
                                </div>
                                <!-- Preview gambar -->
                                <div id="imagePreview_edit" class="mt-3 d-flex flex-wrap gap-2"></div>
                                <!-- Viewer overlay (BUKAN modal) -->
                                <div id="imgViewer_edit" aria-hidden="true">
                                    <div class="viewer-toolbar">
                                        <button type="button" id="zoomInBtn_edit" title="Zoom In">+</button>
                                        <button type="button" id="zoomOutBtn_edit" title="Zoom Out">−</button>
                                        <button type="button" id="resetBtn_edit" title="Reset">Reset</button>
                                        <button type="button" id="closeBtn_edit" title="Tutup">×</button>
                                    </div>
                                    <div class="viewer-stage" id="viewerStage_edit">
                                        <img id="viewerImg_edit" alt="Preview detail">
                                    </div>
                                </div>
                            </div>



                            <div class="form-group mt-2">
                                <label for="option_status" class="form-label">
                                    <!-- todo : carikan saya icon yang cocok untuk kata status -->
                                    <i class="bi bi-collection"></i> Option Status </label>
                                <select class="form-select" id="list_option_status_edit" style="width:100%;" required>
                                    <option value="">-- Pilih Opsi --</option>
                                    <option value="3">Open</option>
                                    <option value="2">In Progress</option>
                                    <option value="1">Close</option>
                                    <option value="4">Cancel</option>

                                </select>

                            </div>
                            <div class="form-group mt-2" id="row_keterangan_auditor">
                                <label for="keterangan_auditor" class="form-label">
                                    <!-- todo : carikan saya icon yang cocok untuk kata status -->
                                    <i class="bi bi-collection"></i> Keterangan Auditor </label>
                                <textarea class="form-control" placeholder="Leave a comment here" id="keterangan_auditor" required></textarea>

                            </div>
                            <div class="form-group mt-2" id="row_keterangan_cancel" style="display: none;">
                                <label for="keterangan_cancel" class="form-label">
                                    <!-- todo : carikan saya icon yang cocok untuk kata status -->
                                    <i class="bi bi-collection"></i> Keterangan Cancel </label>
                                <textarea class="form-control" placeholder="Leave a comment here" id="keterangan_cancel" required></textarea>

                            </div>

                            <!-- Preview gambar -->
                            <!-- <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div> -->
                            <!-- Viewer overlay (BUKAN modal) -->
                            <!-- <div id="imgViewer" aria-hidden="true">
                                <div class="viewer-toolbar">
                                    <button type="button" id="zoomInBtn" title="Zoom In">+</button>
                                    <button type="button" id="zoomOutBtn" title="Zoom Out">−</button>
                                    <button type="button" id="resetBtn" title="Reset">Reset</button>
                                    <button type="button" id="closeBtn" title="Tutup">×</button>
                                </div>
                                <div class="viewer-stage" id="viewerStage">
                                    <img id="viewerImg" alt="Preview detail">
                                </div>
                            </div> -->


                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="btnSubmit_editdata" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script><!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script><!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Air Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.min.js"></script>
    <!-- Select2 core JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <!-- SIGNATURE PAD -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="<?= base_url() ?>assets/js/temuan_patrol/view-image.js"></script>
    <script src="<?= base_url() ?>assets/js/temuan_patrol/t_patrol.js"></script>
    <script>
        document.querySelectorAll('.submenu-toggle').forEach(item => {
            item.addEventListener('click', () => {
                item.parentElement.classList.toggle('open');
            });
        });
        var baseurl = '<?= base_url() ?>';
    </script>
    <script>
        $(document).ready(function() {
            localStorage.removeItem('rekap_temuan');
            renderTable(); // biar tabel ikut kosong
        });
        // Inisialisasi flatpickr
        const fpTanggalPatrol = flatpickr("#edit_tanggal_patrol", {
            locale: "id",
            dateFormat: "d M Y", // sesuaikan dengan format tanggal kamu
        });

        function renderEvidenceFinding(finding_evidence) {

            const container = $('#evidence_container');
            container.html(''); // reset

            if (!finding_evidence) {
                container.html('<p class="text-muted">Tidak ada evidence.</p>');
                return;
            }

            // path ke folder uploads
            const fileUrl = '<?= base_url('uploads/findings_evidence/') ?>' + finding_evidence;

            // cek ekstensi gambar
            const imageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            const ext = finding_evidence.split('.').pop().toLowerCase();

            if (imageExt.includes(ext)) {
                // ===== IMAGE PREVIEW =====
                container.html(`
      <div class="col-md-4">
        <img 
          src="${fileUrl}" 
          class="img-fluid img-thumbnail mb-2"
          style="max-height:250px; cursor:pointer"
          alt="Evidence Finding"
          onclick="openViewer('${fileUrl}')"
        >
      </div>
    `);
            } else {
                // ===== NON IMAGE =====
                container.html(`
      <div class="col-md-12">
        <a href="${fileUrl}" target="_blank" class="btn btn-outline-primary">
          <i class="bi bi-paperclip"></i> ${finding_evidence}
        </a>
      </div>
    `);
            }
        }

        function renderEvidenceFinding2(finding_evidence) {

            const container = $('#evidence_container2');
            container.html(''); // reset

            if (!finding_evidence) {
                container.html('<p class="text-muted">Tidak ada evidence.</p>');
                return;
            }

            // path ke folder uploads
            const fileUrl = '<?= base_url('uploads/findings_evidence/') ?>' + finding_evidence;

            // cek ekstensi gambar
            const imageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            const ext = finding_evidence.split('.').pop().toLowerCase();

            if (imageExt.includes(ext)) {
                // ===== IMAGE PREVIEW =====
                container.html(`
      <div class="col-md-4">
        <img 
          src="${fileUrl}" 
          class="img-fluid img-thumbnail mb-2"
          style="max-height:250px; cursor:pointer"
          alt="Evidence Finding"
          onclick="openViewer('${fileUrl}')"
        >
      </div>
    `);
            } else {
                // ===== NON IMAGE =====
                container.html(`
      <div class="col-md-12">
        <a href="${fileUrl}" target="_blank" class="btn btn-outline-primary">
          <i class="bi bi-paperclip"></i> ${finding_evidence}
        </a>
      </div>
    `);
            }
        }
        let selectedFileData = null; // {file_name, file_type, file_size, file_dataurl}

        function handleFileUpload() {
            var fileInput = $('#fileUpload_tambah')[0];
            var preview = document.getElementById('preview_image');

            // reset
            selectedFile = null;
            preview.src = '';
            preview.classList.add('d-none');

            if (!fileInput || !fileInput.files || fileInput.files.length === 0) return;

            var file = fileInput.files[0];

            const allowedTypes = [
                "image/jpeg", "image/png", "image/jpg", "image/gif", "image/webp",
                "application/pdf",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/vnd.ms-excel",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ];

            if (!allowedTypes.includes(file.type)) {
                alert("Tipe file tidak diperbolehkan!");
                fileInput.value = "";
                return;
            }

            // optional: batasi ukuran (misal 10MB)
            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                alert("Ukuran file terlalu besar. Maksimal 10MB.");
                fileInput.value = "";
                return;
            }

            selectedFile = file;

            // preview hanya untuk gambar (pakai objectURL)
            if (file.type.startsWith("image/")) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            }
        }



        const tsList = new TomSelect("#list_schedule", {
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        const tsDept = new TomSelect("#pic_action_list_dept", {
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        const tsSeksi = new TomSelect("#pic_action_list_seksi", {
            sortField: {
                field: "text",
                direction: "asc"
            }
        });


        // awal: disabled
        tsSeksi.disable();

        $('.sign_hadir').click(function() {
            var id = $(this).data('id');
            var url = "<?= base_url('temuan_patrol/daftar_hadir') ?>/" + id;

            window.open(url, '_blank');
        });
        $('#list_schedule').change(function() {
            var scheduleid = $(this).val();


            // TODO : Ambil nama seksi nya berdasarkan scheduleid menggunakan jquery ajax
            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'get_deptSection_bySchedule',
                    scheduleid: scheduleid
                },
                dataType: 'json',
                success: function(response) {

                    $('#list_dept').attr('data-id', response.id_departement);

                    $('#list_dept').val(response.departement);
                    $('#list_seksi').attr('data-id', response.id_section);

                    $('#list_seksi').val(response.section);
                    $('#nama_auditee').val(response.nama);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching seksi data:', error);
                }
            });
        });
        // $('#list_dept').change(function() {
        //     var deptId = $(this).val();
        //     $('#list_seksi').prop('disabled', !deptId);

        //     // TODO : Ambil nama seksi nya berdasarkan deptId menggunakan jquery ajax
        //     $.ajax({
        //         url: '<?= base_url('sendData') ?>',
        //         type: 'POST',
        //         data: {
        //             keterangan: 'get_seksi_by_dept',
        //             id_dept: deptId
        //         },
        //         dataType: 'json',
        //         success: function(response) {

        //             var seksiOptions = '';
        //             // todo : perbaiki error ini Cannot use 'in' operator to search for 'length' in <option value="15">Casting</option><option value="16">Pasting</option><option value="17">Formation</option>
        //             seksiOptions += response.options;

        //             $('#list_seksi').html(seksiOptions);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching seksi data:', error);
        //         }
        //     });
        // });
        $('#pic_action_list_dept').on('change', function() {
            const deptId = $(this).val();

            if (!deptId) {
                $('#pic_action_list_seksi').prop('disabled', true);
                tsSeksi.disable();
                tsSeksi.clear(true);
                tsSeksi.clearOptions();
                tsSeksi.addOption({
                    value: "",
                    text: "-- Pilih Opsi --"
                });
                tsSeksi.refreshOptions(false);
                return;
            }

            $('#pic_action_list_seksi').prop('disabled', false);
            tsSeksi.enable();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'get_seksi_by_dept',
                    id_dept: deptId
                },
                dataType: 'json',
                success: function(response) {
                    const $sel = $('#pic_action_list_seksi');

                    const htmlOpt = (response && response.options) ? response.options : '';

                    // 1) bersihkan data option di TomSelect (ini penting supaya tidak nyangkut/duplicate)
                    tsSeksi.clear(true); // kosongkan selected
                    tsSeksi.clearOptions(); // hapus semua option di TomSelect

                    // 2) replace isi <select> (bukan append)
                    $sel.empty().html('<option value="">-- Pilih Opsi --</option>' + htmlOpt);

                    // 3) sync ulang TomSelect dari DOM
                    tsSeksi.sync();

                    // 4) refresh dropdown
                    tsSeksi.refreshOptions(false);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching seksi data:', error);
                }
            });
        });
        // $('#list_seksi').change(function() {
        //     var seksiId = $(this).val();
        //     $.ajax({
        //         url: '<?= base_url('sendData') ?>',
        //         type: 'POST',
        //         data: {
        //             keterangan: 'find_auditee_by_seksi',
        //             id_seksi: seksiId
        //         },
        //         dataType: 'json',
        //         success: function(response) {

        //             $('#nama_auditee').val(response.auditee);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching seksi data:', error);
        //         }
        //     });

        // });


        $('.btn_rekaptemuan').click(function() {

            if ($('#deskripsi_temuan').val().trim() === "") {
                alert('Isi deskripsi temuan');
                return;
            }

            if (!$('#pic_action_list_dept').val()) {
                alert('Pilih PIC Action');
                return;
            }

            // cegah double submit
            var $btn = $(this);
            if ($btn.prop('disabled')) return;
            $btn.prop('disabled', true);

            let list = JSON.parse(localStorage.getItem('rekap_temuan')) || [];

            // kalau ada file, simpan ke filesToSend dan catat index-nya
            let fileIndex = null;
            let fileMeta = null;

            if (selectedFile) {
                fileIndex = filesToSend.length;
                filesToSend.push(selectedFile);

                fileMeta = {
                    file_name: selectedFile.name,
                    file_type: selectedFile.type,
                    file_size: selectedFile.size
                };
            }

            let data = {
                deskripsi_temuan: $('#deskripsi_temuan').val(),
                pic_action_section: $('#pic_action_list_seksi option:selected').data('section'),
                pic_action_section_id: $('#pic_action_list_seksi').val(),
                pic_action_dept_id: $('#pic_action_list_dept').val(),
                pic_action_dept: $('#pic_action_list_dept option:selected').data('departement'),

                // ✅ kaitkan file
                file_index: fileIndex, // bisa null
                file_meta: fileMeta // bisa null
            };

            list.push(data);
            localStorage.setItem('rekap_temuan', JSON.stringify(list));

            appendRow(data, list.length);

            // reset input form
            $('#deskripsi_temuan').val('');
            $('#pic_action_list_dept').val('').trigger('change');
            $('#pic_action_list_seksi').val('').trigger('change');

            // reset file input + preview + selectedFile
            $('#fileUpload_tambah').val('');
            $('#preview_image').attr('src', '').addClass('d-none');
            selectedFile = null;

            $btn.prop('disabled', false);
        });



        // FUNGSI UNTUK MENAMBAH BARIS
        function appendRow(item, number) {

            let fileHtml = '-';
            if (item.file_meta && item.file_meta.file_name) {
                if (item.file_meta.file_type && item.file_meta.file_type.startsWith('image/')) {
                    // preview gambar: ambil File dari filesToSend berdasarkan file_index
                    const f = (item.file_index !== null && filesToSend[item.file_index]) ? filesToSend[item.file_index] : null;
                    if (f) {
                        fileHtml = `<img src="${URL.createObjectURL(f)}" class="img-thumbnail" style="max-height:80px; max-width:120px;" alt="preview">`;
                    } else {
                        fileHtml = `<span><i class="bi bi-paperclip"></i> ${item.file_meta.file_name}</span>`;
                    }
                } else {
                    fileHtml = `<span><i class="bi bi-paperclip"></i> ${item.file_meta.file_name}</span>`;
                }
            }

            let picText = item.pic_action_section ?? '-';
            let index = number - 1;

            $('#rekap_tbody').append(`
    <tr>
      <td>${number}</td>
      <td>${item.deskripsi_temuan}</td>
      <td>${fileHtml}</td>
      <td>${picText}</td>
      <td>
        <button type="button" class="btn btn-danger btn-sm btn-hapus-temuan" data-index="${index}">
          <i class="bi bi-trash"></i> Hapus
        </button>
      </td>
    </tr>
  `);
        }

        $(document).on('click', '.btn-hapus-temuan', function() {
            const index = parseInt($(this).data('index'), 10);

            let list = JSON.parse(localStorage.getItem('rekap_temuan')) || [];
            if (Number.isNaN(index) || index < 0 || index >= list.length) return;

            const removed = list[index];
            const removedFileIndex = (removed && removed.file_index !== null) ? removed.file_index : null;

            // hapus item dari list
            list.splice(index, 1);

            // kalau item punya file, hapus file-nya dari filesToSend dan rapikan file_index lainnya
            if (removedFileIndex !== null) {
                filesToSend.splice(removedFileIndex, 1);

                // item lain yang file_index > removedFileIndex harus dikurangi 1
                list = list.map(it => {
                    if (it.file_index !== null && it.file_index > removedFileIndex) {
                        return {
                            ...it,
                            file_index: it.file_index - 1
                        };
                    }
                    return it;
                });
            }

            localStorage.setItem('rekap_temuan', JSON.stringify(list));

            // render ulang
            $('#rekap_tbody').html('');
            list.forEach((it, i) => appendRow(it, i + 1));
        });


        function renderTable() {
            let list = JSON.parse(localStorage.getItem('rekap_temuan')) || [];
            $('#rekap_tbody').html('');

            list.forEach((item, i) => {
                appendRow(item, i + 1);
            });
        }
        let filesToSend = [];
        let selectedFile = null;

        $('#btn_tambahTemuan').on('click', async function(e) {
            e.preventDefault();

            var $btn = $(this);
            if ($btn.prop('disabled')) return;
            $btn.prop('disabled', true);

            try {
                var tanggal_patrol = $('#tanggal_patrol').val();
                var id_schedule = $('#list_schedule').val();
                var deptId = $('#list_dept').data('id');
                var seksiId = $('#list_seksi').data('id');
                var nama_auditee = $('#nama_auditee').val();

                let rekap = JSON.parse(localStorage.getItem('rekap_temuan') || '[]');

                if (!tanggal_patrol) {
                    alert('Isi Tanggal Patrol!');
                    return;
                }

                if (rekap.length === 0) {
                    alert('Belum ada rekap temuan yang ditambahkan.');
                    return;
                }

                const signType = $('#sign_type').val();

                // ✅ FormData
                const formData = new FormData();
                formData.append('keterangan', 'tambah_temuan_patrol');
                formData.append('id_schedule', id_schedule);
                formData.append('tanggal_patrol', tanggal_patrol);
                formData.append('nama_auditee', nama_auditee);
                formData.append('deptId', deptId);
                formData.append('seksiId', seksiId);

                formData.append('rekap_temuan', JSON.stringify(rekap));

                // evidence files
                filesToSend.forEach((f) => {
                    formData.append('evidence_files[]', f);
                });

                // ✅ kirim jenis ttd
                formData.append('sign_type', signType);

                // ✅ kirim ttd sebagai FILE (signature_file)
                if (signType === 'digital') {
                    if (!signaturePad || signaturePad.isEmpty()) {
                        alert("Tanda tangan digital masih kosong.");
                        return;
                    }

                    const dataURL = signaturePad.toDataURL('image/png');

                    // convert dataURL -> Blob
                    const blob = await (await fetch(dataURL)).blob();

                    // append sebagai file
                    formData.append('signature_file', blob, 'signature.png');

                    // optional: kalau tetap mau simpan base64 di DB, kirim juga
                    // formData.append('signature_data', dataURL);

                } else {
                    const file = document.getElementById('sign_file').files[0];
                    if (!file) {
                        alert("Silakan pilih file gambar tanda tangan.");
                        return;
                    }
                    formData.append('signature_file', file);
                }

                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        alert('Temuan berhasil ditambahkan!');

                        localStorage.removeItem('rekap_temuan');
                        filesToSend = [];
                        selectedFile = null;

                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding temuan patrol:', error);
                        alert('Gagal menyimpan temuan.');
                    }
                });

            } finally {
                $btn.prop('disabled', false);
            }
        });



        function initTomSelect(selector) {
            document.querySelectorAll(selector).forEach(el => {
                if (el.tomselect) el.tomselect.destroy();

                // skip kalau disabled
                if (el.disabled) return;

                new TomSelect(el, {
                    allowEmptyOption: true,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });
        }
        const localeID = {
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
            months: [
                'Januari', 'Februari', 'Maret', 'April',
                'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'
            ],
            monthsShort: [
                'Jan', 'Feb', 'Mar', 'Apr',
                'Mei', 'Jun', 'Jul', 'Agu',
                'Sep', 'Okt', 'Nov', 'Des'
            ],
            today: 'Hari Ini',
            clear: 'Hapus',
            dateFormat: 'dd/MM/yyyy',
            timeFormat: 'HH:mm',
            firstDay: 1
        };

        let dpDueDate = null;

        function attachAirDatepickerResponsive(inputId) {
            const $input = $('#' + inputId);

            // destroy kalau sudah ada instance
            if (dpDueDate) {
                dpDueDate.destroy();
                dpDueDate = null;
            }

            dpDueDate = new AirDatepicker('#' + inputId, {
                locale: localeID,
                dateFormat: 'dd/MM/yyyy',
                autoClose: true,
                buttons: ['today', 'clear'],

                // biar gak ke-clip modal & bebas dari stacking context
                container: 'body',
                zIndex: 20000,

                position({
                    $datepicker,
                    $target
                }) {
                    // hitung posisi input relatif viewport (responsive)
                    const r = $target.getBoundingClientRect();
                    const dpH = $datepicker.offsetHeight || 280; // fallback
                    const gap = 6;

                    const spaceBelow = window.innerHeight - r.bottom;
                    const showAbove = spaceBelow < dpH + gap;

                    $datepicker.style.position = 'fixed';
                    $datepicker.style.left = `${Math.max(8, r.left)}px`;
                    $datepicker.style.top = showAbove ?
                        `${Math.max(8, r.top - dpH - gap)}px` :
                        `${Math.min(window.innerHeight - dpH - 8, r.bottom + gap)}px`;

                    $datepicker.style.zIndex = 20000;
                }
            });

            // helper untuk paksa hitung ulang posisi saat kondisi berubah
            const reposition = () => {
                if (!dpDueDate) return;
                // show() akan trigger position() lagi
                if (dpDueDate.visible) dpDueDate.show();
            };

            // saat user scroll halaman / modal scroll / resize
            window.addEventListener('resize', reposition);
            window.addEventListener('scroll', reposition, true); // true = capture, ngikut scroll di modal juga

            // saat input fokus / klik
            $input.on('focus click', function() {
                dpDueDate.show();
            });
        }
        $('#modal_editdata').on('shown.bs.modal', function() {

            attachAirDatepickerResponsive('edit_due_date');
        });

        function parseDdMmYyyy(tglStr) {
            if (!tglStr) return null;
            const [dd, mm, yyyy] = tglStr.split('/').map(Number);
            return new Date(yyyy, mm - 1, dd);
        }
        $('.fill_data').click(function() {
            var temuanId = $(this).data('id');

            // TODO : Ambil data temuan berdasarkan temuanId menggunakan jquery ajax
            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'get_temuan_by_id',
                    id_temuan: temuanId
                },
                dataType: 'json',
                success: function(response) {
                    $('#fill_id_temuan_patrol').val(response.temuan.id_temuan_patrol);
                    $('#fill_id_auditor').val(response.temuan.id_auditor);
                    $('#fill_id_departement_temuan').val(response.temuan.id_departement);
                    $('#fill_id_section_temuan').val(response.temuan.id_section);
                    if (response.temuan.status_temuan == 1) {
                        $('#row_keterangan_cancel2').hide();
                        $('#btnSubmit_filldata').hide();
                        $('#fill_id_temuan_patrol').hide();
                        $('#fill_id_auditor').hide();
                        $('#fill_id_departement_temuan').hide();
                        $('#fill_id_section_temuan').hide();
                        $('#fill_status_temuan').val('Close');
                        $('#list_option_status_container').hide();
                    } else if (response.temuan.status_temuan == 2) {
                        $('#row_keterangan_cancel2').hide();
                        $('#btnSubmit_filldata').show();
                        $('#fill_id_temuan_patrol').show();
                        $('#fill_id_auditor').show();
                        $('#fill_deskripsi_temuan').attr('disabled', false);
                        $('#fill_id_departement_temuan').show();
                        $('#fill_id_section_temuan').show();
                        $('#fill_status_temuan').val('In Progress');
                        $('#list_option_status_container').show();
                    } else if (response.temuan.status_temuan == 3) {
                        $('#row_keterangan_cancel2').hide();
                        $('#btnSubmit_filldata').show();
                        $('#fill_deskripsi_temuan').attr('disabled', false);
                        $('#fill_id_temuan_patrol').show();
                        $('#fill_id_auditor').show();
                        $('#fill_id_departement_temuan').show();
                        $('#fill_id_section_temuan').show();
                        $('#fill_status_temuan').val('Open');
                        $('#list_option_status_container').show();
                    } else if (response.temuan.status_temuan == 4) {
                        $('#row_keterangan_cancel2').show();
                        $('#fill_deskripsi_temuan').attr('disabled', true);
                        $('#keterangan_cancel2').attr('disabled', true);
                        $('#keterangan_cancel2').val(response.temuan.keterangan_cancel);
                        $('#btnSubmit_filldata').hide();
                        $('#fill_id_temuan_patrol').hide();
                        $('#fill_id_auditor').hide();
                        $('#fill_id_departement_temuan').hide();
                        $('#fill_id_section_temuan').hide();
                        $('#fill_status_temuan').val('Cancel');
                        $('#list_option_status_container').hide();

                    } else {
                        $('#fill_status_temuan').val('');
                    }
                    $('#fill_tanggal_patrol').val(response.temuan.tanggal_patrol);
                    $('#fill_auditorName').val(response.temuan.nama_auditor);
                    $('#fill_auditeeName').val(response.temuan.nama_auditee);
                    $('#fill_area_proses').val(response.temuan.section_name);
                    $('#fill_deskripsi_temuan').val(response.temuan.deskripsi_temuan);
                    $('#fill_analisa_penyebab').val(response.temuan.analisa_penyebab);
                    $('#fill_action').val(response.temuan.action);
                    $('#fill_pic_action').html(response.temuan.pic_section_name);
                    $('#keterangan_cancel').val(response.temuan.keterangan_cancel);
                    $('#keterangan_auditor2').val(response.temuan.keterangan_auditor);

                    // todo : saya ingin melakukan set value pada tanggal due date dengan plugin airdatepicker
                    const dateObj = parseDdMmYyyy(response.temuan.due_date); // "23/12/2025"
                    $('#fill_due_date').val(response.temuan.due_date);

                    var id_section_user = '<?= $id_section_user ?>';
                    var id_dept_user = '<?= $id_dept_user ?>';
                    // cek apakah user yang akses adalah Auditee dari temuan tersebut
                    if (id_section_user == response.temuan.id_section || id_dept_user == response.temuan.id_departement) {
                        $('#fill_analisa_penyebab').attr('disabled', false);
                        $('#fill_action').attr('disabled', false);
                        $('#fill_pic_action').attr('disabled', false);
                        $('#fill_due_date').attr('disabled', false);
                        $('#fileUpload').attr('disabled', false);
                        initTomSelect('#fill_pic_action');


                    } else {
                        // $('#fill_deskripsi_temuan').attr('disabled', false);

                    }

                    // contoh: response.data.finding_evidence
                    renderEvidenceFinding(response.temuan.finding_evidence);
                    if (response.temuan.nama_file) {

                        const fileName = response.temuan.nama_file;

                        // cek apakah gambar
                        const isImage = /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(fileName);

                        // cek apakah PDF
                        const isPDF = /\.pdf$/i.test(fileName);

                        if (isImage) {
                            // tampilkan gambar
                            $('#imagePreview').html(`<img src="<?= base_url('assets/uploads/') ?>${fileName}" alt="Preview Image" id="existingImage"style="cursor: pointer; max-width: 200px;"
                                >
                            `);

                            const existingImage = document.getElementById('existingImage');
                            existingImage.addEventListener('click', () => openViewer(existingImage.src));
                            $('.previewpdf_fill').css('display', 'none');
                        } else if (isPDF) {
                            // tampilkan PDF (ikon atau preview mini)
                            //     $('#imagePreview').html(`
                            //     <div style="cursor: pointer; color: blue; text-decoration: underline;" id="pdfPreview">
                            //         Lihat PDF (${fileName})
                            //     </div>

                            // `);
                            // tampilkan PDF (ikon atau preview mini)
                            // const encoded = base64url_encode(fileName);
                            $('.previewpdf_fill').css('display', 'block');
                            $('#previewPDF_fill').attr('href', 'pdf/preview/' + fileName);
                            $('#imagePreview').css('display', 'none');
                            // const pdfPreview = document.getElementById('pdfPreview');
                            // pdfPreview.addEventListener('click', () => openViewer("<?= base_url('assets/uploads/') ?>" + fileName));

                        } else {
                            // bukan gambar atau pdf
                            $('#imagePreview').html(`<p>File: ${fileName}</p>`);
                        }
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching temuan data:', error);
                }
            });
        });
        $('#btnSubmit_filldata').click(function(e) {
            e.preventDefault();

            var fd = new FormData();
            var npk_user_log = '<?= $npk ?>';
            var npk_auditor = $('#fill_id_auditor').val();
            fd.append('keterangan', 'update_temuan_patrol');
            fd.append('id_temuan', $('#fill_id_temuan_patrol').val());
            fd.append('npk_auditor', $('#fill_id_auditor').val());
            fd.append('id_section_temuan', $('#fill_id_section_temuan').val());
            fd.append('id_departement_temuan', $('#fill_id_departement_temuan').val());
            fd.append('deskripsi_temuan', $('#fill_deskripsi_temuan').val());
            fd.append('analisa_penyebab', $('#fill_analisa_penyebab').val());
            fd.append('action', $('#fill_action').val());
            fd.append('keterangan_auditor', $('#keterangan_auditor2').val());
            fd.append('due_date', $('#fill_due_date').val());
            if (npk_user_log == npk_auditor) {
                fd.append('status', $('#list_option_status').val());
            } else {
                fd.append('pic_action', $('#fill_pic_action').val());

            }
            // penting: kirim objek file, bukan file.name
            var file = $('#fileUpload')[0].files[0];
            // cek dulu isi inputnya
            if (npk_user_log == npk_auditor) {
                if (!$('#fill_deskripsi_temuan').val().trim() === "0" // cek jika hasilnya 0
                ) {
                    alert('isi data yang masih kosong ! [Deskripsi temuan]');
                    return; // stop di sini, jangan kirim ajax
                }
            } else {
                if (
                    !$('#fill_deskripsi_temuan').val().trim() ||
                    !$('#fill_analisa_penyebab').val().trim() ||
                    !$('#fill_due_date').val().trim() ||
                    $('#fill_due_date').val() === "0" // cek jika hasilnya 0
                ) {
                    alert('isi data yang masih kosong ! [Deskripsi temuan, Analisa Penyebab, due date]');
                    return; // stop di sini, jangan kirim ajax
                }
            }


            if (file) fd.append('file', file); // 'file' harus sama dengan getFile('file') di server

            $.ajax({
                url: '<?= base_url("sendData") ?>',
                type: 'POST',
                data: fd,
                processData: false, // jangan ubah FormData jadi query string
                contentType: false, // biar otomatis multipart/form-data + boundary
                dataType: 'json',
                success: function() {
                    alert('Temuan berhasil diperbarui!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating temuan patrol:', error);
                }
            });
        });
        <?php if ($role == 'Administrator') : ?>
            $('#list_option_status_edit').change(function() {
                var status = $(this).val();
                console.log('Selected status:', status);
                if (status == '4') {
                    $('#row_keterangan_cancel').show();
                } else {
                    $('#row_keterangan_cancel').hide();

                }
            });
            $('#btnSubmit_editdata').click(function(e) {
                e.preventDefault();

                var fd = new FormData();
                fd.append('keterangan', 'update_temuan_patrol');
                fd.append('id_temuan', $('#edit_id_temuan_patrol').val());
                fd.append('deskripsi_temuan', $('#edit_deskripsi_temuan').val());
                fd.append('analisa_penyebab', $('#edit_analisa_penyebab').val());
                fd.append('action', $('#edit_action').val());
                fd.append('due_date', $('#edit_due_date').val());
                fd.append('tanggal_patrol', $('#edit_tanggal_patrol').val());
                fd.append('status', $('#list_option_status_edit').val());
                fd.append('keterangan_cancel', $('#keterangan_cancel').val());
                fd.append('keterangan_auditor', $('#keterangan_auditor').val());


                // --- VALIDASI FILE UPLOAD ---
                var fileInput = $('#fileUpload_edit')[0]; // pastikan ID-nya sesuai HTML

                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    var file = fileInput.files[0];

                    // daftar file yang diperbolehkan
                    const allowedTypes = [
                        "image/jpeg", "image/png", "image/jpg", "image/gif", "image/webp",
                        "application/pdf",
                        "application/msword",
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                        "application/vnd.ms-excel",
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    ];

                    // cek file valid / tidak
                    if (!allowedTypes.includes(file.type)) {
                        alert("File tidak diperbolehkan! Hanya boleh gambar, PDF, Word, atau Excel.");
                        return; // stop submit
                    }

                    fd.append('file', file); // kirim file ke server
                }

                $.ajax({
                    url: '<?= base_url("sendData") ?>',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function() {
                        alert('Temuan berhasil diperbarui!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating temuan patrol:', error);
                    }
                });


            });
        <?php endif; ?>

        function base64url_encode(str) {
            return btoa(str)
                .replace(/\+/g, '-') // ganti + jadi -
                .replace(/\//g, '_') // ganti / jadi _
                .replace(/=+$/, ''); // hapus semua =
        }
        <?php if ($role == 'user') : ?>
            const fileInput = document.getElementById('fileUpload');
            const previewContainer = document.getElementById('imagePreview');

            const viewer = document.getElementById('imgViewer');
            const stage = document.getElementById('viewerStage');
            const vImg = document.getElementById('viewerImg');
            const zoomInBtn = document.getElementById('zoomInBtn');
            const zoomOutBtn = document.getElementById('zoomOutBtn');
            const resetBtn = document.getElementById('resetBtn');
            const closeBtn = document.getElementById('closeBtn');

            // State zoom & pan
            let scale = 1,
                minScale = 0.5,
                maxScale = 6;
            let originX = 0,
                originY = 0; // posisi pan (px)
            let isPanning = false,
                startX = 0,
                startY = 0;

            function renderTransform() {
                vImg.style.transform = `translate(${originX}px, ${originY}px) scale(${scale})`;
            }

            function openViewer(src) {
                vImg.src = src;
                // reset transform
                scale = 1;
                originX = 0;
                originY = 0;
                renderTransform();
                viewer.classList.add('active');
                viewer.setAttribute('aria-hidden', 'false');

                // opsional: masuk fullscreen
                if (viewer.requestFullscreen) {
                    viewer.requestFullscreen().catch(() => {});
                }
            }

            function closeViewer() {
                viewer.classList.remove('active');
                viewer.setAttribute('aria-hidden', 'true');
                if (document.fullscreenElement && document.exitFullscreen) document.exitFullscreen();
            }


            // Buat thumbnail dari gambar yang dipilih / yang sudah ada di dalam PreviewContainer

            fileInput.addEventListener('change', () => {
                previewContainer.innerHTML = '';
                Array.from(fileInput.files).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;
                        img.style.cursor = 'pointer'; // biar kelihatan bisa diklik
                        img.addEventListener('click', () => openViewer(img.src));
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });


            // Zoom via tombol
            zoomInBtn.addEventListener('click', () => {
                scale = Math.min(maxScale, scale * 1.2);
                renderTransform();
            });
            zoomOutBtn.addEventListener('click', () => {
                scale = Math.max(minScale, scale / 1.2);
                renderTransform();
            });
            resetBtn.addEventListener('click', () => {
                scale = 1;
                originX = 0;
                originY = 0;
                renderTransform();
            });
            closeBtn.addEventListener('click', closeViewer);

            // Zoom via scroll
            stage.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = Math.sign(e.deltaY);
                const prevScale = scale;
                scale = delta > 0 ? Math.max(minScale, scale / 1.1) : Math.min(maxScale, scale * 1.1);

                // Zoom ke arah posisi kursor (sedikit math biar nyaman)
                const rect = vImg.getBoundingClientRect();
                const cx = e.clientX - rect.left - rect.width / 2;
                const cy = e.clientY - rect.top - rect.height / 2;
                originX -= cx * (scale - prevScale);
                originY -= cy * (scale - prevScale);

                renderTransform();
            }, {
                passive: false
            });

            // Drag untuk pan (desktop & mobile)
            const startPan = (x, y) => {
                isPanning = true;
                startX = x - originX;
                startY = y - originY;
            };
            const movePan = (x, y) => {
                if (!isPanning) return;
                originX = x - startX;
                originY = y - startY;
                renderTransform();
            };
            const endPan = () => {
                isPanning = false;
            };

            stage.addEventListener('pointerdown', e => {
                e.preventDefault();
                stage.setPointerCapture(e.pointerId);
                startPan(e.clientX, e.clientY);
            });
            stage.addEventListener('pointermove', e => movePan(e.clientX, e.clientY));
            stage.addEventListener('pointerup', endPan);
            stage.addEventListener('pointercancel', endPan);
            stage.addEventListener('dblclick', () => { // toggle zoom 1x <-> 2x
                scale = scale > 1 ? 1 : 2;
                originX = 0;
                originY = 0;
                renderTransform();
            });

            // Tutup bila klik area kosong (bukan gambar)
            viewer.addEventListener('click', (e) => {
                const clickedStage = e.target === viewer || e.target === stage;
                if (clickedStage) closeViewer();
            });
            // Esc untuk tutup
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && viewer.classList.contains('active')) closeViewer();
            });
        <?php endif; ?>
        <?php if ($role == 'Administrator') : ?>
            $('.edit_data').click(function() {
                var temuanId = $(this).data('id');
                // TODO : Ambil data temuan berdasarkan temuanId menggunakan jquery ajax
                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    data: {
                        keterangan: 'get_temuan_by_id',
                        id_temuan: temuanId
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Isi form edit dengan data yang diambil
                        $('#edit_id_temuan_patrol').val(response.temuan.id_temuan_patrol);
                        const dateObj = parseDdMmYyyy(response.temuan.tanggal_patrol);
                        fpTanggalPatrol.setDate(response.temuan.tanggal_patrol, true);
                        $('#edit_nama_auditor').html(response.temuan.nama_auditor);
                        $('#edit_nama_auditee').html(response.temuan.nama_auditee);
                        $('#edit_area_proses').html(response.temuan.section_name);
                        $('#edit_deskripsi_temuan').val(response.temuan.deskripsi_temuan);

                        $('#edit_analisa_penyebab').val(response.temuan.analisa_penyebab);
                        $('#edit_action').val(response.temuan.action);
                        $('#edit_pic_action').html(response.temuan.pic_section_name);
                        $('#edit_due_date').val(response.temuan.due_date);
                        $('#keterangan_cancel').val(response.temuan.keterangan_cancel);
                        $('#keterangan_auditor').val(response.temuan.keterangan_auditor);

                        renderEvidenceFinding2(response.temuan.finding_evidence);
                        if (response.temuan.status_temuan == 1) {
                            $('#row_keterangan_cancel').hide();

                        } else if (response.temuan.status_temuan == 2) {
                            $('#row_keterangan_cancel').hide();

                        } else if (response.temuan.status_temuan == 3) {
                            $('#row_keterangan_cancel').hide();

                        } else if (response.temuan.status_temuan == 4) {
                            $('#row_keterangan_cancel').show();


                        } else {
                            $('#list_option_status_edit').val('');
                        }
                        if (response.temuan.nama_file) {

                            const fileName = response.temuan.nama_file;

                            // cek apakah gambar
                            const isImage = /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(fileName);

                            // cek apakah PDF
                            const isPDF = /\.pdf$/i.test(fileName);

                            if (isImage) {
                                // tampilkan gambar
                                $('#imagePreview_edit').html(`<img src="<?= base_url('assets/uploads/') ?>${fileName}" alt="Preview Image" id="existingImage_edit"style="cursor: pointer; max-width: 200px;"
                                >
                            `);

                                const existingImage = document.getElementById('existingImage_edit');
                                existingImage.addEventListener('click', () => openViewer(existingImage.src));
                                $('.previewpdf').css('display', 'none');
                                $('#imagePreview_edit')
                                    .removeClass('d-none')
                                    .addClass('d-flex');
                            } else if (isPDF) {
                                // tampilkan PDF (ikon atau preview mini)
                                // const encoded = base64url_encode(fileName);
                                $('.previewpdf').css('display', 'block');
                                $('#previewPDF').attr('href', 'pdf/preview/' + fileName);
                                $('#imagePreview_edit').removeClass('d-flex').addClass('d-none');
                            } else {
                                // bukan gambar atau pdf
                                $('#imagePreview_edit').html(`<p>File: ${fileName}</p>`);
                            }
                        }
                        // Tambahkan field lain sesuai kebutuhan
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching temuan data for edit:', error);
                    }
                });
            });
        <?php endif; ?>
        $(document).on("click", ".btnDownload", function() {
            let namaFile = $(this).data("namafile");
            let url = "/download/file/" + namaFile;

            // Buat tag <a> virtual
            let a = document.createElement("a");
            a.href = url;
            a.download = namaFile; // memberi tahu browser untuk download
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
        <?php if ($role == 'Administrator') : ?>

            const fileInput2 = document.getElementById("fileUpload_edit");
            const previewContainer2 = document.getElementById("imagePreview_edit");

            const viewer2 = document.getElementById("imgViewer_edit");
            const stage2 = document.getElementById("viewerStage_edit");
            const vImg2 = document.getElementById("viewerImg_edit");
            const zoomInBtn2 = document.getElementById("zoomInBtn_edit");
            const zoomOutBtn2 = document.getElementById("zoomOutBtn_edit");
            const resetBtn2 = document.getElementById("resetBtn_edit");
            const closeBtn2 = document.getElementById("closeBtn_edit");

            // State zoom & pan
            let scale2 = 1,
                minScale2 = 0.5,
                maxScale2 = 6;
            let originX2 = 0,
                originY2 = 0; // posisi pan (px)
            let isPanning2 = false,
                startX2 = 0,
                startY2 = 0;

            function renderTransform() {
                vImg2.style.transform = `translate(${originX2}px, ${originY2}px) scale(${scale2})`;
            }

            function openViewer(src) {
                vImg2.src = src;
                // reset transform
                scale2 = 1;
                originX2 = 0;
                originY2 = 0;
                renderTransform();
                viewer2.classList.add("active");
                viewer2.setAttribute("aria-hidden", "false");

                // opsional: masuk fullscreen
                if (viewer2.requestFullscreen) {
                    viewer2.requestFullscreen().catch(() => {});
                }
            }

            function closeViewer() {
                viewer2.classList.remove("active");
                viewer2.setAttribute("aria-hidden", "true");
                if (document.fullscreenElement && document.exitFullscreen)
                    document.exitFullscreen();
            }

            // Buat thumbnail dari gambar yang dipilih / yang sudah ada di dalam PreviewContainer

            fileInput2.addEventListener("change", () => {
                previewContainer2.innerHTML = "";

                // Jika tidak ada file, hentikan
                if (!fileInput2.files || fileInput2.files.length === 0) {
                    console.warn("Tidak ada file yang dipilih.");
                    return;
                }

                Array.from(fileInput2.files).forEach((file) => {

                    // VALIDASI: cek apakah file adalah gambar
                    if (!file.type || !file.type.startsWith("image/")) {
                        console.warn(`File "${file.name}" bukan gambar, dilewati.`);
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.alt = file.name;
                        img.style.cursor = "pointer";

                        img.addEventListener("click", () => openViewer(img.src));

                        previewContainer2.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                });
            });


            // Zoom via tombol
            zoomInBtn2.addEventListener("click", () => {
                scale2 = Math.min(maxScale2, scale2 * 1.2);
                renderTransform();
            });
            zoomOutBtn2.addEventListener("click", () => {
                scale2 = Math.max(minScale2, scale2 / 1.2);
                renderTransform();
            });
            resetBtn2.addEventListener("click", () => {
                scale2 = 1;
                originX2 = 0;
                originY2 = 0;
                renderTransform();
            });
            closeBtn2.addEventListener("click", closeViewer);

            // Zoom via scroll
            stage2.addEventListener(
                "wheel",
                (e) => {
                    e.preventDefault();
                    const delta = Math.sign(e.deltaY);
                    const prevScale = scale2;
                    scale2 =
                        delta > 0 ?
                        Math.max(minScale2, scale2 / 1.1) :
                        Math.min(maxScale2, scale2 * 1.1);

                    // Zoom ke arah posisi kursor (sedikit math biar nyaman)
                    const rect = vImg2.getBoundingClientRect();
                    const cx = e.clientX - rect.left - rect.width / 2;
                    const cy = e.clientY - rect.top - rect.height / 2;
                    originX2 -= cx * (scale2 - prevScale);
                    originY2 -= cy * (scale2 - prevScale);

                    renderTransform();
                }, {
                    passive: false,
                }
            );

            // Drag untuk pan (desktop & mobile)
            const startPan2 = (x, y) => {
                isPanning2 = true;
                startX = x - originX2;
                startY = y - originY2;
            };
            const movePan2 = (x, y) => {
                if (!isPanning2) return;
                originX2 = x - startX;
                originY2 = y - startY;
                renderTransform();
            };
            const endPan2 = () => {
                isPanning2 = false;
            };

            stage2.addEventListener("pointerdown", (e) => {
                e.preventDefault();
                stage2.setPointerCapture(e.pointerId);
                startPan2(e.clientX, e.clientY);
            });
            stage2.addEventListener("pointermove", (e) => movePan2(e.clientX, e.clientY));
            stage2.addEventListener("pointerup", endPan2);
            stage2.addEventListener("pointercancel", endPan2);
            stage2.addEventListener("dblclick", () => {
                // toggle zoom 1x <-> 2x
                scale2 = scale2 > 1 ? 1 : 2;
                originX2 = 0;
                originY2 = 0;
                renderTransform();
            });

            // Tutup bila klik area kosong (bukan gambar)
            viewer2.addEventListener("click", (e) => {
                const clickedStage = e.target === viewer2 || e.target === stage2;
                if (clickedStage) closeViewer();
            });
            // Esc untuk tutup
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape" && viewer2.classList.contains("active")) closeViewer();
            });
        <?php endif; ?>
        $('.hapus_data').click(function() {
            var id_temuan_patrol = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '<?= base_url('sendData') ?>', // ganti dengan URL endpoint kamu
                    type: 'POST',
                    data: {
                        id_temuan_patrol: id_temuan_patrol,
                        keterangan: 'hapus_temuan_patrol'
                    },
                    success: function(response) {
                        alert('Temuan berhasil dihapus.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            }
        });
        let signaturePad = null;

        function resizeCanvasToDisplaySize(canvas) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();

            canvas.width = Math.floor(rect.width * ratio);
            canvas.height = Math.floor(rect.height * ratio);

            const ctx = canvas.getContext("2d");
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0); // scale untuk retina
        }

        function fillWhiteBackground(canvas) {
            // isi putih beneran (bukan cuma property)
            const ctx = canvas.getContext("2d");
            ctx.save();
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.fillStyle = "#fff";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.restore();
        }

        function setPenOptions() {
            if (!signaturePad) return;

            const color = document.getElementById("pen_color").value;
            const width = Number(document.getElementById("pen_width").value);

            signaturePad.penColor = color;
            signaturePad.minWidth = width;
            signaturePad.maxWidth = width;

            document.getElementById("pen_width_label").textContent = width;
        }

        function initSignaturePad() {
            const canvas = document.getElementById("signature_pad");

            // resize sesuai ukuran tampilannya
            resizeCanvasToDisplaySize(canvas);

            // isi background putih (agar tidak ada efek “hitam nyelip”)
            fillWhiteBackground(canvas);

            // jika ada instance lama, matikan event-nya
            if (signaturePad) {
                signaturePad.off();
                signaturePad = null;
            }

            signaturePad = new SignaturePad(canvas, {
                backgroundColor: "rgb(255,255,255)",
                penColor: document.getElementById("pen_color").value,
                minWidth: Number(document.getElementById("pen_width").value),
                maxWidth: Number(document.getElementById("pen_width").value),
            });

            // clear akan apply backgroundColor
            signaturePad.clear();
            setPenOptions();
        }

        function bindUpdateBeforeDraw() {
            const canvas = document.getElementById("signature_pad");
            const update = () => setPenOptions();

            // paksa update pen sebelum mulai coret
            canvas.addEventListener("pointerdown", update);
            canvas.addEventListener("mousedown", update);
            canvas.addEventListener("touchstart", update, {
                passive: true
            });
        }

        function toggleSignType() {
            const val = document.getElementById("sign_type").value;
            const digital = document.getElementById("digital_section");
            const upload = document.getElementById("upload_section");

            if (val === "digital") {
                digital.classList.remove("d-none");
                upload.classList.add("d-none");

                // re-init canvas setelah ditampilkan (biar ukuran pas)
                setTimeout(() => {
                    initSignaturePad();
                }, 50);
            } else {
                digital.classList.add("d-none");
                upload.classList.remove("d-none");
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // init signature
            initSignaturePad();
            bindUpdateBeforeDraw();

            // toggle jenis sign
            document.getElementById("sign_type").addEventListener("change", toggleSignType);

            // warna & tebal
            document.getElementById("pen_color").addEventListener("input", setPenOptions);
            document.getElementById("pen_width").addEventListener("input", setPenOptions);

            // clear
            document.getElementById("btnClearSign").addEventListener("click", function() {
                if (!signaturePad) return;
                signaturePad.clear();
            });

            // preview upload
            document.getElementById("sign_file").addEventListener("change", function(e) {
                const file = e.target.files && e.target.files[0];
                const wrap = document.getElementById("upload_preview_wrap");
                const img = document.getElementById("upload_preview");

                if (!file) {
                    wrap.classList.add("d-none");
                    img.src = "";
                    return;
                }

                img.src = URL.createObjectURL(file);
                wrap.classList.remove("d-none");
            });

            // submit
            document.getElementById("btn_tambahTemuan").addEventListener("click", function() {
                const signType = document.getElementById("sign_type").value;

                if (signType === "digital") {
                    if (!signaturePad || signaturePad.isEmpty()) {
                        alert("Tanda tangan digital masih kosong.");
                        return;
                    }
                    // base64 png
                    const dataURL = signaturePad.toDataURL("image/png");
                    document.getElementById("signature_data").value = dataURL;
                } else {
                    const file = document.getElementById("sign_file").files[0];
                    if (!file) {
                        alert("Silakan pilih file gambar tanda tangan.");
                        return;
                    }
                }

                // TODO: sesuaikan submit
                // document.getElementById("formSign").submit();
                console.log("Submit OK. sign_type =", signType);
            });

            // re-init saat modal muncul (penting supaya ukuran canvas pas)
            const modalEl = document.getElementById("modal_tambahdata");
            modalEl.addEventListener("shown.bs.modal", function() {
                if (document.getElementById("sign_type").value === "digital") {
                    setTimeout(() => initSignaturePad(), 50);
                }
            });
        });
    </script>
</body>

</html>