<!doctype html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"><!-- Flatpickr Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script><!-- Choices.js for Select Dropdown -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script><!-- Glide.js for Touch Sliders -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.core.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.theme.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/glide.min.js"></script><!-- Hammer.js for Touch Gestures -->
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8/hammer.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/mobile/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/inkflow.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/eleganselect.css">

    <style>
        :root {
            --bs-border-radius: 0.5rem;
        }

        .banner {
            width: 100%;
            height: 280px;
            background: #f3f4f6;
            /* opsional */
        }

        .banner img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* tidak terpotong */
        }

        .tab-btn {
            color: #6b7280;
            background-color: transparent;
        }

        .tab-btn.active {
            color: #ffffff;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .dark-mode .tab-btn.active {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .tab-content {
            animation: fadeIn 0.3s ease;
        }

        .form-input:disabled {
            background-color: #f2f2f2;
            /* warna abu soft */
            color: #6c757d;
            /* teks abu */
            border: 1px solid #dcdcdc;
            /* border halus */
            cursor: not-allowed;
            /* cursor tanda tidak bisa diketik */
            opacity: 0.8;
            /* sedikit transparan */
        }

        .dark-mode .form-input:disabled {
            background-color: #2b2b2b !important;
            /* lebih gelap */
            color: #9ca3af !important;
            /* teks abu soft */
            border: 1px solid #555;
            cursor: not-allowed;
            opacity: 0.85;
        }

        .img-fluid {
            max-width: 100%;
            height: auto
        }

        .rounded {
            border-radius: var(--bs-border-radius) !important
        }

        .mt-0 {
            margin-top: 0 !important;
        }

        .mt-1 {
            margin-top: 0.25rem !important;
        }

        .mt-2 {
            margin-top: 0.5rem !important;
        }

        .mt-3 {
            margin-top: 1rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .mt-auto {
            margin-top: auto !important;
        }

        .d-none {
            display: none !important;
        }

        .text-primary {
            color: #0d6efd !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-info {
            color: #0dcaf0 !important;
        }

        .text-light {
            color: #f8f9fa !important;
        }

        .text-dark {
            color: #212529 !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .text-white {
            color: #fff !important;
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

        /* =============================== */
        /* Bootstrap 5 Table Base Styling  */
        /* =============================== */

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        /* Table cells */
        .table th,
        .table td {
            padding: 0.75rem;
            border-top: 1px solid #dee2e6;
            text-align: left;
            vertical-align: middle;
        }

        /* Table header */
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        /* Table body borders */
        .table tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        /* Hover effect (Bootstrap default) */
        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        /* =============================== */
        /* Table Light Header (.table-light) */
        /* =============================== */

        .table-light {
            background-color: #f8f9fa;
            color: #000;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            font-size: 13px;
        }

        .selectx-trigger.disabled {
            pointer-events: none;
            /* semua klik mati */
            opacity: 0.6;
            /* efek visual disabled */
            cursor: not-allowed;
        }
    </style>

</head>

<body class="h-full">
    <div id="app" class="app-wrapper h-full w-full overflow-auto"><!-- Header -->
        <div class="sticky top-0 z-50 card-bg shadow-md">
            <div class="flex items-center justify-between px-4 py-4">
                <div>
                    <h1 class="text-xl font-bold text-dark" id="appTitle">Quality Patrol</h1>
                    <p class="text-sm text-gray" id="companyName">PT. Century Batteries Indonesia</p>
                </div>
                <div class="flex items-center gap-3"><button id="darkModeToggle" class="toggle-switch" aria-label="Toggle Dark Mode">
                        <div class="toggle-thumb"></div>
                    </button> <i class="fas fa-bell text-xl text-gray"></i>
                </div>
            </div>
        </div><!-- Main Content -->
        <div id="mainContent" class="pb-20" style="min-height: calc(100% - 140px);"><!-- Dashboard Page -->
            <!-- Data Patrol Page -->
            <div id="dataPatrolPage" class="page-content">
                <div class="px-4 py-4">
                    <h2 class="text-lg font-semibold text-dark mb-4">Data Patrol</h2>
                    <!-- Tabs -->
                    <div class="flex gap-2 mb-4 card-bg rounded-xl p-1 shadow-md">
                        <button
                            class="tab-btn active flex-1 py-2 px-4 rounded-lg font-semibold text-sm transition-all"
                            onclick="window.location.href='<?= base_url('temuan_patrol/auditor') ?>'">
                            <i class="fas fa-user-check mr-1"></i> Auditor
                        </button>

                        <button
                            class="tab-btn flex-1 py-2 px-4 rounded-lg font-semibold text-sm transition-all"
                            onclick="window.location.href='<?= base_url('temuan_patrol/auditee') ?>'">
                            <i class="fas fa-user-tag mr-1"></i> Auditee
                        </button>

                        <button
                            class="tab-btn flex-1 py-2 px-4 rounded-lg font-semibold text-sm transition-all"
                            onclick="window.location.href='<?= base_url('data-patrol/attendance') ?>'">
                            <i class="fas fa-tasks mr-1"></i> Daftar Hadir
                        </button>
                    </div>
                    <!-- Tab Content: Data Patrol -->
                    <div id="AuditorTab" class="tab-content">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-dark">Temuan Auditor</h3>
                            <button
                                class="text-sm text-blue-600 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1"
                                onclick="toggleFilter('patrolFilter')">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        <!-- Filter Panel -->
                        <div
                            id="patrolFilter"
                            class="hidden mb-4 card-bg rounded-xl p-4 shadow-md filter-panel">
                            <h3 class="text-sm font-bold text-dark mb-3">
                                Filter Data Patrol
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-dark mb-2">Status</label>
                                    <div class="flex gap-3 flex-wrap">
                                        <label class="flex items-center text-xs">
                                            <input type="checkbox" checked class="mr-1" /> Open
                                        </label>
                                        <label class="flex items-center text-xs">
                                            <input type="checkbox" checked class="mr-1" /> In
                                            Progress
                                        </label>
                                        <label class="flex items-center text-xs">
                                            <input type="checkbox" checked class="mr-1" /> Close
                                        </label>
                                    </div>
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button
                                        class="flex-1 border border-gray-300 text-dark rounded-lg py-2 text-sm font-semibold hover:bg-gray-50">
                                        Reset
                                    </button>
                                    <button
                                        class="flex-1 btn-primary text-white rounded-lg py-2 text-sm font-semibold">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Patrol Cards -->
                        <div class="space-y-3" id="patrolCardsContainer">
                            <!-- Card 1 -->
                            <?php foreach ($data_patrol as $patrol) : ?>
                                <div class="card-bg rounded-xl p-4 shadow-md">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-calendar text-blue-600 text-sm"></i>
                                                <span class="text-sm font-semibold text-dark"><?= $patrol['tanggal_patrol'] ?></span>
                                            </div>
                                            <h3 class="text-base font-bold text-dark mb-2"><?= $patrol['deskripsi_temuan'] ?></h3>
                                            <div class="space-y-1">
                                                <p class="text-sm text-gray"><i class="fas fa-map-marker-alt w-4"></i> <?= $patrol['section_name'] ?></p>
                                                <p class="text-sm text-gray"><i class="fas fa-user-tie w-4"></i><?= $patrol['nama_auditor'] ?></p>
                                            </div>
                                        </div>
                                        <?php if ($patrol['status'] == 3) : ?>
                                            <span class="status-badge status-open">Open</span>
                                        <?php elseif ($patrol['status'] == 2) : ?>
                                            <span class="status-badge status-progress">Progress</span>
                                        <?php else: ?>
                                            <span class="status-badge status-close">Close</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="space-y-2">
                                        <?php if (session()->get('role') == 'Administrator') : ?>
                                            <button onclick="showDetail(<?= $patrol['id_temuan_patrol'] ?>)" class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold"> <i class="fas fa-info-circle mr-1"></i> Lihat Detail </button>
                                        <?php endif; ?>
                                        <div class="flex gap-2">
                                            <button
                                                onclick="fill_temuan(<?= $patrol['id_temuan_patrol'] ?>)"
                                                class="flex-1 btn-sm bg-blue-500 text-white flex items-center justify-center gap-1 rounded-lg py-2">
                                                <i class="fas fa-edit"></i> Fill
                                            </button>
                                            <?php if (session()->get('role') == 'Administrator') : ?>
                                                <button
                                                    onclick="confirmDeletePatrol(<?= $patrol['id_temuan_patrol'] ?>)"
                                                    class="flex-1 btn-sm bg-red-500 text-white flex items-center justify-center gap-1 rounded-lg py-2">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>



                        </div>
                    </div>

                    <!-- Tab Content: Daftar Hadir -->

                    <!-- FAB Button for Add Data -->
                    <button
                        id="btnTambahPatrol"
                        class="fab-button btnTambahPatrol"

                        aria-label="Tambah Data Patrol">
                        <i class="fas fa-plus text-white text-2xl"></i>
                    </button>
                </div>
            </div>




        </div><!-- Bottom Navigation -->
        <div class="fixed bottom-0 left-0 right-0 navbar-bg shadow-lg">
            <div class="flex justify-between items-center px-2 py-2">
                <button class="nav-item  flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="dashboard">
                    <i class="fas fa-home text-lg"></i> <span class="text-xs whitespace-nowrap">Dashboard</span>
                </button>
                <button class="nav-item active flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="dataPatrol">
                    <i class="fas fa-clipboard-list text-lg"></i> <span class="text-xs whitespace-nowrap">Data</span>
                </button>
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="schedule">
                    <i class="fas fa-calendar text-lg"></i> <span class="text-xs whitespace-nowrap">Schedule</span>
                </button>

                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="profile">
                    <i class="fas fa-user text-lg"></i> <span class="text-xs whitespace-nowrap">Profile</span>
                </button>
            </div>
        </div>

        <!-- Detail Patrol Modal -->

        <div id="detailModal" class="modal-overlay hidden">
            <div class="modal-content" style="max-width: 500px; max-height: 90%; overflow-y: auto;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-dark">Detail Temuan</h3>
                    <button onclick="closeDetail()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="detailContent" class="space-y-4">
                    <form>
                        <input type="hidden" id="edit_id_temuan_patrol">
                        <div class="card-bg border border-gray-200 rounded-lg p-3">
                            <p class="text-xs text-gray mb-1">Status</p>
                            <span class="detail_status status-badge status-open">Open</span>
                        </div>

                        <div class="card-bg border border-gray-200 rounded-lg p-3 mt-2">
                            <p class="text-xs text-gray mb-1">Tanggal Temuan Patrol</p>
                            <p class="text-sm font-semibold text-dark detail_tanggal_patrol"></p>
                        </div>
                        <div class="card-bg border border-gray-200 rounded-lg p-3 mt-2">
                            <p class="text-xs text-gray mb-1">Auditor</p>
                            <?php if (session()->get('role') != 'Administrator') : ?>
                                <p class="text-sm font-semibold text-dark detail_auditor"></p>
                            <?php else : ?>
                                <select id="detail_auditor" class="form-input card-bg text-dark detail_auditor" disabled>




                                </select>
                            <?php endif; ?>
                        </div>
                        <div class="card-bg border border-gray-200 rounded-lg p-3 mt-2">
                            <p class="text-xs text-gray mb-1">Auditee</p>
                            <?php if (session()->get('role') != 'Administrator') : ?>
                                <p class="text-sm font-semibold text-dark detail_auditee"></p>
                            <?php else : ?>
                                <select id="detail_auditee" class="form-input card-bg text-dark detail_auditee" disabled>




                                </select>
                            <?php endif; ?>
                        </div>
                        <div class="card-bg border border-gray-200 rounded-lg p-3 mt-2">
                            <p class="text-xs text-gray mb-1">Area / Proses</p>
                            <?php if (session()->get('role') != 'Administrator') : ?>
                                <p class="text-sm font-semibold text-dark detail_area_proses"></p>
                            <?php else : ?>
                                <select id="detail_area_proses" class="form-input card-bg text-dark detail_area_proses" disabled>




                                </select>
                            <?php endif; ?>
                        </div>
                        <label for="patrolTime" class="form-label text-dark" style="margin-bottom: 0.1rem !important;font-size:20px;">Evidence Findings</label>
                        <hr style="border: none; border-top: 1px solid #ccc;">
                        <!-- TEMPAT EVIDENCE DITAMPILKAN -->
                        <div class="row mt-2" id="evidence_container">
                            <!-- evidence akan di-inject via JS -->
                        </div>
                        <div class="mt-2">
                            <label for="deskripsi_temuan" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Deskripsi Temuan <small class="text-danger"> *</small></label>
                            <textarea class="form-input card-bg text-dark detail_deskripsi_temuan" placeholder="Masukkan temuan..." rows="2" required></textarea>
                        </div>
                        <div class="card-bg border border-gray-200 rounded-lg p-3 mt-2">
                            <p class="text-xs text-gray mb-1">Analisa Penyebab <small> <i>Di isi oleh Auditee !!!</i></small></p>
                            <p class="text-sm font-semibold text-dark detail_analisa_penyebab"></p>
                        </div>
                        <div class="card-bg border border-gray-200 rounded-lg p-3 mt-2">
                            <p class="text-xs text-gray mb-1">Action <small> <i>Di isi oleh Auditee !!!</i></small></p>
                            <p class="text-sm font-semibold text-dark detail_action"></p>
                        </div>
                        <div class="mt-2 pic_action_temuan_container">
                            <label for="deskripsi_temuan" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">PIC Action</label>
                            <select id="detail_pic_action" class="form-input card-bg text-dark detail_pic_action" disabled>




                            </select>
                        </div>
                        <div class="card-bg border border-gray-200 rounded-lg p-3 mt-2">
                            <p class="text-xs text-gray mb-1">Due Date <small> <i>Di isi oleh Auditee !!!</i></small></p>
                            <p class="text-sm font-semibold text-dark detail_due_date"></p>
                        </div>

                        <div class="form-group mt-3">
                            <label for="fileUpload" class="form-label">
                                <i class="bi bi-upload me-1"></i> Upload File (PDF, Word, Excel, Image) <small> <i>Di isi oleh Auditee !!!</i></small>
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
                            <a type="button" id="previewPDF_fill" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700" target="_blank"> <i class="bi bi-eye me-1"></i> Preview PDF </a>
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
                        <div class="mt-2 option_status_temuan_container">
                            <label for="deskripsi_temuan" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Status</label>
                            <select id="detail_option_status" class="form-input card-bg text-dark">
                                <option value="" disabled selected>-- Pilih Opsi --</option>
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
                        <div class="mt-2">
                            <label for="deskripsi_temuan" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Keterangan Auditor<small class="text-danger"> *</small></label>
                            <textarea class="form-input card-bg text-dark detail_ket_auditor " placeholder="Masukkan keterangan auditor..." rows="2" required></textarea>
                        </div>



                    </form>
                </div>
                <div class="flex gap-3 mt-6">
                    <button onclick="closeDetail()" class="flex-1 border border-gray-300 text-dark rounded-lg py-2 font-semibold"> Tutup </button>
                    <button class="flex-1 btn-primary text-white rounded-lg py-2 font-semibold edit_temuan_btn"> <i class="fas fa-edit mr-1"></i> Edit </button>
                </div>
            </div>
        </div>




    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?= base_url() ?>assets/js/inkflow.js"></script>
    <script src="<?= base_url() ?>assets/js/eleganselect.js"></script>
    <script>
        const defaultConfig = {
            app_title: "Patrol Audit Monitor",
            company_name: "PT. Nama Perusahaan",
            dashboard_subtitle: "Monitoring Real-time",
            primary_color: "#3b82f6",
            secondary_color: "#1e293b",
            text_color: "#1f2937",
            background_color: "#f8fafc",
            card_color: "#ffffff"
        };
        $(document).ready(function() {
            localStorage.removeItem('rekap_temuan');
            renderTable(); // biar tabel ikut kosong
        });

        function renderTable() {
            let list = JSON.parse(localStorage.getItem('rekap_temuan')) || [];
            $('#rekap_tbody').html('');

            list.forEach((item, i) => {
                appendRow(item, i + 1);
            });
        }
        let config = {
            ...defaultConfig
        };
        let charts = {};
        let isDarkMode = false;
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




        async function onConfigChange(newConfig) {
            config = {
                ...config,
                ...newConfig
            };

            document.getElementById('appTitle').textContent = config.app_title || defaultConfig.app_title;
            document.getElementById('companyName').textContent = config.company_name || defaultConfig.company_name;
            document.getElementById('dashboardSubtitle').textContent = config.dashboard_subtitle || defaultConfig.dashboard_subtitle;
        }



        function fill_temuan(id) {
            let selectPic = null;
            document.getElementById('detailModal').classList.remove('hidden');
            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'get_temuan_by_id',
                    id_temuan: id
                },
                dataType: 'json',
                success: function(response) {
                    // Isi form edit dengan data yang diambil

                    if (response.temuan.status_temuan == 1) {
                        $('.detail_status')
                            .removeClass(function(i, cls) {
                                return (cls.match(/(^|\s)status-(open|close|progress|cancel)\b/g) || []).join(' ');
                            })
                            .addClass('status-close')
                            .text('Close');
                        $('.option_status_temuan_container').hide();
                        $('.detail_deskripsi_temuan').prop('disabled', true);
                        $('.detail_ket_auditor').prop('disabled', true);
                        $('.edit_temuan_btn').hide();
                    } else if (response.temuan.status_temuan == 2) {
                        $('.detail_status')
                            .removeClass(function(i, cls) {
                                return (cls.match(/(^|\s)status-(open|close|progress|cancel)\b/g) || []).join(' ');
                            })
                            .addClass('status-progress')
                            .text('In Progress');
                        $('.option_status_temuan_container').show();
                        $('.detail_deskripsi_temuan').prop('disabled', false);
                        $('.detail_ket_auditor').prop('disabled', false);
                        $('.edit_temuan_btn').show();
                    } else if (response.temuan.status_temuan == 3) {
                        $('.detail_status')
                            .removeClass(function(i, cls) {
                                return (cls.match(/(^|\s)status-(open|close|progress|cancel)\b/g) || []).join(' ');
                            })
                            .addClass('status-open')
                            .text('Open');
                        $('.option_status_temuan_container').show();
                        $('.detail_deskripsi_temuan').prop('disabled', false);
                        $('.detail_ket_auditor').prop('disabled', false);
                        $('.edit_temuan_btn').show();
                    }
                    $('.detail_tanggal_patrol').text(response.temuan.tanggal_patrol);
                    <?php if (session()->get('role') != 'Administrator') : ?>
                        $('.detail_auditor').text(response.temuan.nama_auditor);
                    <?php endif; ?>
                    <?php if (session()->get('role') == 'Administrator') : ?>
                        $('.detail_auditor').html(response.temuan.nama_auditor);
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'Administrator') : ?>
                        $('.detail_auditee').text(response.temuan.nama_auditee);
                    <?php endif; ?>
                    <?php if (session()->get('role') == 'Administrator') : ?>
                        $('.detail_auditee').html(response.temuan.nama_auditee);
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'Administrator') : ?>
                        $('.detail_area_proses').text(response.temuan.section_name);
                    <?php endif; ?>
                    <?php if (session()->get('role') == 'Administrator') : ?>
                        $('.detail_area_proses').html(response.temuan.section_name);
                    <?php endif; ?>
                    $('.detail_deskripsi_temuan').val(response.temuan.deskripsi_temuan);
                    $('.detail_analisa_penyebab').text(response.temuan.analisa_penyebab);
                    $('.detail_action').text(response.temuan.action);
                    $('.detail_due_date').text(response.temuan.due_date);
                    $('.detail_ket_auditor').text(response.temuan.keterangan_auditor);
                    $('.detail_pic_action').html(response.temuan.pic_section_name);
                    // kalau sudah pernah dibuat, destroy dulu
                    // if (selectPic) {
                    //     selectPic.destroy();
                    // }
                    // // update option
                    // $('#detail_pic_action')
                    //     .empty()
                    //     .append(response.temuan.pic_section_name);

                    // // buat ulang
                    // selectPic = new SelectX('#detail_pic_action', {
                    //     searchable: true,
                    //     clearable: true
                    // });

                    // // set value
                    // selectPic.setValue(String(response.temuan.pic_action_departement_id));
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
                    // Tambahkan field lain sesuai kebutuhan
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching temuan data for edit:', error);
                }
            });
        }

        // function fill_temuan_auditee(id) {

        //     document.getElementById('fill_temuan').classList.remove('hidden');
        //     $.ajax({
        //         url: '<?= base_url('sendData') ?>',
        //         type: 'POST',
        //         data: {
        //             keterangan: 'get_temuan_auditee_by_id',
        //             id_temuan: id
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             // Isi form edit dengan data yang diambil

        //             if (response.temuan.status_temuan == 1) {
        //                 $('.fill_detail_status')
        //                     .removeClass(function(i, cls) {
        //                         return (cls.match(/(^|\s)status-(open|close|progress|cancel)\b/g) || []).join(' ');
        //                     })
        //                     .addClass('status-close')
        //                     .text('Close');
        //             } else if (response.temuan.status_temuan == 2) {
        //                 $('.fill_detail_status')
        //                     .removeClass(function(i, cls) {
        //                         return (cls.match(/(^|\s)status-(open|close|progress|cancel)\b/g) || []).join(' ');
        //                     })
        //                     .addClass('status-progress')
        //                     .text('In Progress');
        //             } else if (response.temuan.status_temuan == 3) {
        //                 $('.fill_detail_status')
        //                     .removeClass(function(i, cls) {
        //                         return (cls.match(/(^|\s)status-(open|close|progress|cancel)\b/g) || []).join(' ');
        //                     })
        //                     .addClass('status-open')
        //                     .text('Open');
        //             }
        //             $('.detail_tanggal_patrol').text(response.temuan.tanggal_patrol);
        //             $('.detail_auditor').text(response.temuan.nama_auditor);
        //             $('.detail_auditee').text(response.temuan.nama_auditee);
        //             $('.detail_area_proses').text(response.temuan.section_name);
        //             $('.detail_deskripsi_temuan').val(response.temuan.deskripsi_temuan);

        //             // contoh: response.data.finding_evidence
        //             renderEvidenceFinding(response.temuan.finding_evidence);
        //             if (response.temuan.nama_file) {

        //                 const fileName = response.temuan.nama_file;

        //                 // cek apakah gambar
        //                 const isImage = /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(fileName);

        //                 // cek apakah PDF
        //                 const isPDF = /\.pdf$/i.test(fileName);

        //                 if (isImage) {
        //                     // tampilkan gambar
        //                     $('#imagePreview').html(`<img src="<?= base_url('assets/uploads/') ?>${fileName}" alt="Preview Image" id="existingImage"style="cursor: pointer; max-width: 200px;"
        //                         >
        //                     `);

        //                     const existingImage = document.getElementById('existingImage');
        //                     existingImage.addEventListener('click', () => openViewer(existingImage.src));
        //                     $('.previewpdf_fill').css('display', 'none');
        //                 } else if (isPDF) {
        //                     // tampilkan PDF (ikon atau preview mini)
        //                     //     $('#imagePreview').html(`
        //                     //     <div style="cursor: pointer; color: blue; text-decoration: underline;" id="pdfPreview">
        //                     //         Lihat PDF (${fileName})
        //                     //     </div>

        //                     // `);
        //                     // tampilkan PDF (ikon atau preview mini)
        //                     // const encoded = base64url_encode(fileName);
        //                     $('.previewpdf_fill').css('display', 'block');
        //                     $('#previewPDF_fill').attr('href', 'pdf/preview/' + fileName);
        //                     $('#imagePreview').css('display', 'none');
        //                     // const pdfPreview = document.getElementById('pdfPreview');
        //                     // pdfPreview.addEventListener('click', () => openViewer("<?= base_url('assets/uploads/') ?>" + fileName));

        //                 } else {
        //                     // bukan gambar atau pdf
        //                     $('#imagePreview').html(`<p>File: ${fileName}</p>`);
        //                 }
        //             }
        //             // Tambahkan field lain sesuai kebutuhan
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching temuan data for edit:', error);
        //         }
        //     });
        // }
        new SelectX('#detail_option_status', {
            searchable: true,
            clearable: true,
            placeholder: 'Pilih sesuatu...',
            onChange: (value) => console.log(value)
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
        let sxDept = null;
        let sxSeksi = null;



        function closeAddPatrolModal() {
            document.getElementById('addPatrolModal').classList.add('hidden');
        }

        function handleAddPatrol(event) {
            event.preventDefault();

            const formData = {
                tanggal: document.getElementById('patrolDate').value,
                temuan: document.getElementById('patrolFinding').value,
                area: document.getElementById('patrolArea').value,
                auditor: document.getElementById('patrolAuditor').value,
                auditee: document.getElementById('patrolAuditee').value,
                deskripsi: document.getElementById('patrolDescription').value,
                tindakan: document.getElementById('patrolAction').value,
                deadline: document.getElementById('patrolDeadline').value,
                prioritas: document.getElementById('patrolPriority').value,
                status: document.getElementById('patrolStatus').value
            };

            // Add to patrol data (in real app, this would be sent to backend)
            const newId = Object.keys(patrolData).length + 1;
            patrolData[newId] = formData;



            closeAddPatrolModal();
            showToast('Data patrol berhasil ditambahkan!');
        }


        // Close Detail Modal
        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }
        // Close Detail Modal
        function closeDetail2() {
            document.getElementById('fill_temuan').classList.add('hidden');
        }
        // Calendar Functions
        let currentDate = new Date(2024, 0, 1); // Start with January 2024
        const TODAY_REFERENCE = new Date(2024, 0, 15); // Set today as Jan 15, 2024 for demo purposes











        // Toggle Filter Function
        function toggleFilter(filterId) {
            const filterElement = document.getElementById(filterId);
            if (filterElement.classList.contains('hidden')) {
                filterElement.classList.remove('hidden');
                filterElement.classList.add('filter-panel');
            } else {
                filterElement.classList.add('hidden');
            }
        }

        // Navigation
        // document.querySelectorAll('.nav-item').forEach(item => {
        //     item.addEventListener('click', () => {
        //         const page = item.dataset.page;

        //         document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        //         item.classList.add('active');

        //         document.querySelectorAll('.page-content').forEach(content => content.classList.add('hidden'));
        //         document.getElementById(page + 'Page').classList.remove('hidden');
        //     });
        // });
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                const page = item.dataset.page;
                console.log(page);
                if (page == 'dataPatrol') {
                    window.location.href = "<?= base_url('temuan_patrol') ?>";
                } else if (page == 'schedule') {
                    window.location.href = "<?= base_url('schedule') ?>";

                } else if (page == 'profile') {
                    window.location.href = "<?= base_url('profile') ?>";
                } else if (page == 'dashboard') {
                    window.location.href = "<?= base_url('summary') ?>";

                }
            });
        });

        // Dark Mode Toggle
        document.getElementById('darkModeToggle').addEventListener('click', function() {
            isDarkMode = !isDarkMode;
            const app = document.getElementById('app');
            const toggle = document.getElementById('darkModeToggle');

            if (isDarkMode) {
                app.classList.add('dark-mode');
                toggle.classList.add('active');
            } else {
                app.classList.remove('dark-mode');
                toggle.classList.remove('active');
            }


        });





        // PDF Viewer Functions
        let uploadedPDFFile = null;

        function handlePDFUpload(event) {
            const file = event.target.files[0];
            if (file && file.type === 'application/pdf') {
                uploadedPDFFile = file;
                document.getElementById('pdfFileName').textContent = file.name;
                document.getElementById('pdfDisplayArea').classList.remove('hidden');
            }
        }

        function viewPDF() {
            if (uploadedPDFFile) {
                const url = URL.createObjectURL(uploadedPDFFile);
                window.open(url, '_blank');
            }
        }

        function downloadPDF() {
            if (uploadedPDFFile) {
                const url = URL.createObjectURL(uploadedPDFFile);
                const a = document.createElement('a');
                a.href = url;
                a.download = uploadedPDFFile.name;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        }

        function showPDFInfo(docName) {
            const infoModal = document.createElement('div');
            infoModal.className = 'modal-overlay';
            infoModal.innerHTML = `
        <div class="modal-content">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-dark">${docName}</h3>
            <button onclick="this.closest('.modal-overlay').remove()" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          <div class="space-y-3">
            <p class="text-sm text-gray">This is a sample document preview. In a real application, the PDF would be displayed here.</p>
            <div class="flex gap-2">
              <button onclick="this.closest('.modal-overlay').remove()" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
                <i class="fas fa-eye mr-1"></i> View
              </button>
              <button onclick="this.closest('.modal-overlay').remove()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                <i class="fas fa-download mr-1"></i> Download
              </button>
            </div>
          </div>
        </div>
      `;
            document.body.appendChild(infoModal);
        }

        function showFormAlert() {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            alertDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Form submitted successfully!';
            document.body.appendChild(alertDiv);
            setTimeout(() => alertDiv.remove(), 3000);
        }

        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities: (config) => ({
                    recolorables: [],
                    borderables: [],
                    fontEditable: undefined,
                    fontSizeable: undefined
                }),
                mapToEditPanelValues: (config) => new Map([
                    ["app_title", config.app_title || defaultConfig.app_title],
                    ["company_name", config.company_name || defaultConfig.company_name],
                    ["dashboard_subtitle", config.dashboard_subtitle || defaultConfig.dashboard_subtitle]
                ])
            });
        }

        // Filter Functions for Data Patrol
        function applyPatrolFilter() {
            const filterOpen = document.getElementById('filterOpen').checked;
            const filterProgress = document.getElementById('filterProgress').checked;
            const filterClose = document.getElementById('filterClose').checked;
            const filterArea = document.getElementById('filterArea').value;
            const filterAuditor = document.getElementById('filterAuditor').value;
            const filterPriority = document.getElementById('filterPriority').value;
            const filterDateFrom = document.getElementById('filterDateFrom').value;
            const filterDateTo = document.getElementById('filterDateTo').value;
            const filterSearch = document.getElementById('filterSearch').value.toLowerCase();

            const container = document.getElementById('patrolCardsContainer');
            const cards = container.querySelectorAll('.card-bg');
            let visibleCount = 0;

            Object.keys(patrolData).forEach((id, index) => {
                const data = patrolData[id];
                const card = cards[index];

                if (!card) return;

                let shouldShow = true;

                // Status filter
                if (!filterOpen && data.status === 'Open') shouldShow = false;
                if (!filterProgress && data.status === 'In Progress') shouldShow = false;
                if (!filterClose && data.status === 'Close') shouldShow = false;

                // Area filter
                if (filterArea && data.area !== filterArea) shouldShow = false;

                // Auditor filter
                if (filterAuditor && data.auditor !== filterAuditor) shouldShow = false;

                // Priority filter
                if (filterPriority && data.prioritas !== filterPriority) shouldShow = false;

                // Search filter
                if (filterSearch && !data.temuan.toLowerCase().includes(filterSearch) && !data.deskripsi.toLowerCase().includes(filterSearch)) {
                    shouldShow = false;
                }

                // Date filter (simplified - comparing date strings)
                if (filterDateFrom || filterDateTo) {
                    const dataDate = convertDateToISO(data.tanggal);
                    if (filterDateFrom && dataDate < filterDateFrom) shouldShow = false;
                    if (filterDateTo && dataDate > filterDateTo) shouldShow = false;
                }

                if (shouldShow) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show message if no results
            let noResultsMsg = document.getElementById('noResultsMsg');
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noResultsMsg';
                    noResultsMsg.className = 'card-bg rounded-xl p-8 text-center';
                    noResultsMsg.innerHTML = `
            <i class="fas fa-search text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray font-semibold">Tidak ada data yang sesuai dengan filter</p>
            <p class="text-gray text-sm mt-1">Coba ubah kriteria filter Anda</p>
          `;
                    container.appendChild(noResultsMsg);
                }
            } else {
                if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }
        }

        function resetPatrolFilter() {
            document.getElementById('filterOpen').checked = true;
            document.getElementById('filterProgress').checked = true;
            document.getElementById('filterClose').checked = true;
            document.getElementById('filterArea').value = '';
            document.getElementById('filterAuditor').value = '';
            document.getElementById('filterPriority').value = '';
            document.getElementById('filterDateFrom').value = '';
            document.getElementById('filterDateTo').value = '';
            document.getElementById('filterSearch').value = '';

            applyPatrolFilter();
        }

        function convertDateToISO(dateStr) {
            // Convert "15 Januari 2024" to "2024-01-15"
            const months = {
                'Januari': '01',
                'Februari': '02',
                'Maret': '03',
                'April': '04',
                'Mei': '05',
                'Juni': '06',
                'Juli': '07',
                'Agustus': '08',
                'September': '09',
                'Oktober': '10',
                'November': '11',
                'Desember': '12'
            };

            const parts = dateStr.split(' ');
            const day = parts[0].padStart(2, '0');
            const month = months[parts[1]];
            const year = parts[2];

            return `${year}-${month}-${day}`;
        }

        window.addEventListener('load', () => {


            initPlugins();
        });

        // Initialize all plugins
        function initPlugins() {
            // Initialize Flatpickr for single date picker
            flatpickr("#datePickerInput", {
                dateFormat: "d M Y",
                altInput: true,
                altFormat: "j F Y",
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    },
                },
                onChange: function(selectedDates, dateStr, instance) {
                    console.log("Selected date:", dateStr);
                }
            });

            // Initialize Flatpickr for date range picker
            flatpickr("#dateRangeInput", {
                mode: "range",
                dateFormat: "d M Y",
                altInput: true,
                altFormat: "j F Y",
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    },
                },
                onChange: function(selectedDates, dateStr, instance) {
                    console.log("Selected range:", dateStr);
                }
            });



            // Initialize Glide.js
            if (document.querySelector('.glide')) {
                const glide = new Glide('.glide', {
                    type: 'carousel',
                    startAt: 0,
                    perView: 1,
                    focusAt: 'center',
                    gap: 0,
                    autoplay: 3000,
                    hoverpause: true,
                    keyboard: true,
                    animationDuration: 500,
                    animationTimingFunc: 'ease-in-out',
                    dragThreshold: 80,
                    touchRatio: 0.5,
                    rewind: true,
                    swipeThreshold: 80,
                    dragDistance: true
                });

                glide.mount();
            }

            // Initialize Hammer.js for gestures
            const gestureDemo = document.getElementById('gestureDemo');
            const gestureFeedback = document.getElementById('gestureFeedback');
            const gestureLogText = document.getElementById('gestureLogText');

            if (gestureDemo && typeof Hammer !== 'undefined') {
                const hammer = new Hammer(gestureDemo);

                // Enable all directions for swipe
                hammer.get('swipe').set({
                    direction: Hammer.DIRECTION_ALL
                });

                // Enable press (long tap)
                hammer.get('press').set({
                    time: 500
                });

                // Tap event
                hammer.on('tap', function(e) {
                    animateGesture('👆 Tap Detected!', '#3b82f6');
                    gestureLogText.textContent = 'Single tap detected at ' + new Date().toLocaleTimeString();
                });

                // Double tap event
                hammer.on('doubletap', function(e) {
                    animateGesture('👆👆 Double Tap!', '#10b981');
                    gestureLogText.textContent = 'Double tap detected at ' + new Date().toLocaleTimeString();
                });

                // Press (long tap) event
                hammer.on('press', function(e) {
                    animateGesture('✊ Press & Hold!', '#f59e0b');
                    gestureLogText.textContent = 'Press and hold detected at ' + new Date().toLocaleTimeString();
                });

                // Swipe events
                hammer.on('swipeleft', function(e) {
                    animateGesture('👈 Swipe Left!', '#ef4444');
                    gestureLogText.textContent = 'Swipe left detected at ' + new Date().toLocaleTimeString();
                });

                hammer.on('swiperight', function(e) {
                    animateGesture('👉 Swipe Right!', '#8b5cf6');
                    gestureLogText.textContent = 'Swipe right detected at ' + new Date().toLocaleTimeString();
                });

                hammer.on('swipeup', function(e) {
                    animateGesture('👆 Swipe Up!', '#06b6d4');
                    gestureLogText.textContent = 'Swipe up detected at ' + new Date().toLocaleTimeString();
                });

                hammer.on('swipedown', function(e) {
                    animateGesture('👇 Swipe Down!', '#ec4899');
                    gestureLogText.textContent = 'Swipe down detected at ' + new Date().toLocaleTimeString();
                });
            }

            function animateGesture(text, color) {
                gestureFeedback.innerHTML = `
          <i class="fas fa-check-circle text-5xl mb-3"></i>
          <p class="text-2xl font-bold mb-2">${text}</p>
          <p class="text-sm opacity-90">Gesture successfully recognized!</p>
        `;
                gestureDemo.style.background = `linear-gradient(135deg, ${color} 0%, ${adjustColor(color, -30)} 100%)`;

                // Reset after animation
                setTimeout(() => {
                    gestureFeedback.innerHTML = `
            <i class="fas fa-hand-paper text-5xl mb-3"></i>
            <p class="text-xl font-bold mb-2">Try Touch Gestures!</p>
            <p class="text-sm opacity-90">Tap, Double Tap, Swipe, or Press & Hold</p>
          `;
                    gestureDemo.style.background = 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)';
                }, 1500);
            }

            function adjustColor(color, amount) {
                // Simple color adjustment function
                const num = parseInt(color.replace('#', ''), 16);
                const r = Math.max(0, Math.min(255, (num >> 16) + amount));
                const g = Math.max(0, Math.min(255, ((num >> 8) & 0x00FF) + amount));
                const b = Math.max(0, Math.min(255, (num & 0x0000FF) + amount));
                return '#' + ((r << 16) | (g << 8) | b).toString(16).padStart(6, '0');
            }
        }
        // jQuery version (inti penting saja)

        $('.btnTambahPatrol').on('click', function() {
            window.location.href = "<?= base_url('temuan_patrol/start_audit') ?>";
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

        // helper: rebuild SelectX seksi setelah option diubah
        function rebuildSeksiSelectX() {
            if (sxSeksi && typeof sxSeksi.destroy === 'function') sxSeksi.destroy();
            sxSeksi = new SelectX('#pic_action_list_seksi', {
                searchable: true,
                clearable: true,
                placeholder: 'Pilih sesuatu...'
            });
        }

        $('#pic_action_list_dept').on('change', function() {
            const deptId = $(this).val();

            // kalau kosong => disable & reset seksi
            if (!deptId) {
                $('#pic_action_list_seksi').prop('disabled', true);

                $('#pic_action_list_seksi')
                    .empty()
                    .html('<option value="">-- Pilih Opsi --</option>')
                    .val('');

                rebuildSeksiSelectX();
                return;
            }

            // enable seksi
            $('#pic_action_list_seksi').prop('disabled', false);

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'get_seksi_by_dept',
                    id_dept: deptId
                },
                dataType: 'json',
                success: function(response) {
                    const htmlOpt = (response && response.options) ? response.options : '';
                    const selectedSeksiId = (response && response.selected_seksi_id) ? response.selected_seksi_id : '';

                    // replace options seksi
                    $('#pic_action_list_seksi')
                        .empty()
                        .html('<option value="">-- Pilih Opsi --</option>' + htmlOpt)
                        .val(selectedSeksiId);

                    // rebuild SelectX supaya option baru kebaca
                    rebuildSeksiSelectX();

                    // kalau SelectX punya setValue, sync juga (opsional)
                    if (selectedSeksiId && sxSeksi && typeof sxSeksi.setValue === 'function') {
                        sxSeksi.setValue(selectedSeksiId);
                    }

                    // =========================
                    // OPTIONAL: reset dept juga
                    // =========================
                    // Kalau Anda benar-benar mau dept balik kosong setelah sukses:
                    // $('#pic_action_list_dept').val('');
                    // if (sxDept && typeof sxDept.setValue === 'function') sxDept.setValue('');
                    // (kalau perlu rebuild juga dept, sama polanya)
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching seksi data:', error);
                }
            });
        });
        let filesToSend = [];
        let selectedFile = null;
        $('#btn_tambahTemuan').on('click', async function(e) {
            e.preventDefault();

            const $btn = $(this);
            if ($btn.prop('disabled')) return;
            $btn.prop('disabled', true);

            try {
                // ===== ambil data form =====
                const tanggal_patrol = $('#tanggal_patrol').val();
                const id_schedule = $('#list_schedule').val();
                const deptId = $('#list_dept').data('id');
                const seksiId = $('#list_seksi').data('id');
                const nama_auditee = $('#nama_auditee').val();

                const rekap = JSON.parse(localStorage.getItem('rekap_temuan') || '[]');

                // ===== validasi =====
                if (!tanggal_patrol) {
                    alert('Isi Tanggal Patrol!');
                    return;
                }

                if (!id_schedule) {
                    alert('Pilih Schedule terlebih dahulu!');
                    return;
                }

                if (!deptId) {
                    alert('Dept belum dipilih / belum ada data-id!');
                    return;
                }

                if (!seksiId) {
                    alert('Seksi belum dipilih / belum ada data-id!');
                    return;
                }

                if (!nama_auditee) {
                    alert('Isi Nama Auditee!');
                    return;
                }

                if (rekap.length === 0) {
                    alert('Belum ada rekap temuan yang ditambahkan.');
                    return;
                }

                // ===== FormData =====
                const formData = new FormData();
                formData.append('keterangan', 'tambah_temuan_patrol');
                formData.append('id_schedule', id_schedule);
                formData.append('tanggal_patrol', tanggal_patrol);
                formData.append('nama_auditee', nama_auditee);
                formData.append('deptId', deptId);
                formData.append('seksiId', seksiId);
                formData.append('rekap_temuan', JSON.stringify(rekap));

                // ===== evidence files =====
                (filesToSend || []).forEach((f) => {
                    formData.append('evidence_files[]', f);
                });

                // ===== SIGNATURE dari InkFlow =====
                // pastikan objectnya sama dengan yang kamu buat saat InkFlowRender:
                // modalSignaturePad = InkFlowRender(...)
                const signatureBase64 = getInkflowSignatureDataURL();
                if (!signatureBase64) {
                    alert('Tanda tangan masih kosong!');
                    return;
                }

                const sigBlob2 = await (await fetch(signatureBase64)).blob();
                const sigFileName2 = `ttd_${Date.now()}.png`;

                formData.append('signature_file', sigBlob2, sigFileName2);

                if (!signatureBase64) {
                    alert('Tanda tangan masih kosong!');
                    return;
                }

                function getInkflowSignatureDataURL() {
                    const canvas = document.querySelector('#my-signature-container canvas');
                    if (!canvas) return null;

                    // dataURL base64 PNG
                    const dataURL = canvas.toDataURL('image/png');

                    // kalau user belum gambar apa-apa, sebagian library masih ngasih gambar putih.
                    // minimal cek stringnya ada prefix data:image
                    if (!dataURL || !dataURL.startsWith('data:image/png')) return null;

                    return dataURL;
                }
                // base64/dataURL -> Blob
                const sigBlob = await (await fetch(signatureBase64)).blob();

                // bikin nama file sendiri (karena dari canvas tidak ada nama file asli)
                const safeAuditee = nama_auditee.trim().replace(/\s+/g, '_').replace(/[^\w\-]/g, '');
                const safeTanggal = String(tanggal_patrol).replace(/[^\d]/g, ''); // 2026-01-31 -> 20260131
                const sigFileName = `ttd_${safeAuditee || 'auditee'}_${id_schedule}_${safeTanggal || 'notanggal'}_${Date.now()}.png`;

                // kirim sebagai file ke server
                formData.append('signature_file', sigBlob, sigFileName);

                // (opsional) kalau kamu juga mau kirim nama filenya sebagai string
                // formData.append('signature_filename', sigFileName);

                // ===== AJAX =====
                $.ajax({
                    url: "<?= base_url('sendData') ?>",
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

        $('.btn_rekaptemuan').click(function() {

            const deskripsi = String($('#deskripsi_temuan').val() ?? '').trim();
            if (deskripsi === "") {
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

            if (typeof selectedFile !== 'undefined' && selectedFile) {
                fileIndex = filesToSend.length;
                filesToSend.push(selectedFile);

                fileMeta = {
                    file_name: selectedFile.name,
                    file_type: selectedFile.type,
                    file_size: selectedFile.size
                };
            }

            let data = {
                deskripsi_temuan: deskripsi, // ✅ pakai yang sudah trim & aman
                pic_action_section: $('#pic_action_list_seksi option:selected').data('section') ?? null,
                pic_action_section_id: $('#pic_action_list_seksi').val() ?? "",
                pic_action_dept_id: $('#pic_action_list_dept').val() ?? "",
                pic_action_dept: $('#pic_action_list_dept option:selected').data('departement') ?? null,

                file_index: fileIndex,
                file_meta: fileMeta
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
                    <button type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 btn-hapus-temuan" data-index="${index}">
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
    </script>

</body>

</html>