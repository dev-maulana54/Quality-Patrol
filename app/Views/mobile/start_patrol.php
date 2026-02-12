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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script><!-- Choices.js for Select Dropdown -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script><!-- Glide.js for Touch Sliders -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.core.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.theme.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/glide.min.js"></script><!-- Hammer.js for Touch Gestures -->
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8/hammer.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/mobile/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/eleganselect.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/notify_claim.css">
    <style>
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


        .btn:disabled {
            opacity: 0.4;
            /* transparan */
            cursor: not-allowed !important;
            pointer-events: auto !important;
            /* tidak bisa diklik */
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
            <div id="dashboardPage" class="page-content">
                <div class="px-4 py-4">
                    <div class="space-y-4"><!-- Combined Chart -->
                        <div class="card-bg card-animated rounded-xl p-5 shadow-md hover:shadow-xl">
                            <div class="flex flex-col gap-3 mb-4">

                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center"><i class="fas fa-clipboard-list text-lg"></i>

                                    </div>
                                    <h3 class="text-base font-bold text-dark">Tambah Temuan</h3>
                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="auditorname" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Auditor</label>
                                    <input type="text" id="auditorName" value="<?= $nama; ?>" disabled class="form-input card-bg text-dark">
                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="tanggal_patrol" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Tanggal Patrol <small class="text-danger"> *</small></label>
                                    <input type="date" id="tanggal_patrol" class="form-input card-bg text-dark" required>
                                </div>
                                <div style="margin-top: 0.7rem;">
                                    <label for="patrolTime" class="form-label text-dark" style="margin-bottom: 0.1rem !important;font-size:20px;"> Area Patrol</label>
                                    <hr style="border: none; border-top: 1px solid #ccc;">

                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="list_dept" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Departement</label>

                                    <select id="list_dept" class="form-input card-bg text-dark" required>
                                        <option value="" disabled selected>-- Pilih Departement --</option>
                                        <?php foreach ($all_dept as $sa) : ?>
                                            <option value="<?= $sa['id_departement'] ?>"><?= $sa['departement'] ?></option>

                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="list_seksi" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Seksi</label>
                                    <select id="list_seksi" class="form-input card-bg text-dark" required disabled>
                                        <option value="" disabled selected>-- Pilih Seksi --</option>


                                    </select>
                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="list_auditee" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Nama Auditee</label>
                                    <input type="text" id="nama_auditee" disabled class="form-input card-bg text-dark">
                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="deskripsi_temuan" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Deskripsi Temuan <small class="text-danger"> *</small></label>
                                    <textarea id="deskripsi_temuan"
                                        class="form-input card-bg text-dark"
                                        placeholder="Masukkan temuan..."
                                        rows="4"
                                        required></textarea>
                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="list_auditee" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Upload Evidence <small class="text-danger" style="font-style: italic;">Maksimal 1 gambar!</small></label>

                                    <input
                                        type="file"
                                        class="form-input card-bg text-dark"
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
                                <div style="margin-top: 0.7rem;">
                                    <label for="patrolTime" class="form-label text-dark" style="margin-bottom: 0.1rem !important;font-size:20px;"> PIC Action</label>
                                    <hr style="border: none; border-top: 1px solid #ccc;">

                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="pic_action_list_dept" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Departement <small class="text-danger"> *</small></label>
                                    <select id="pic_action_list_dept" class="form-input card-bg text-dark" required>
                                        <option value="" disabled selected>- Pilih Departement -</option>
                                        <?php foreach ($all_dept as $dept) : ?>
                                            <option value="<?= $dept['id_departement'] ?>" data-departement="<?= $dept['departement'] ?>"><?= $dept['departement'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div style="margin-top: 0.3rem;">
                                    <label for="pic_action_list_seksi" class="form-label text-dark" style="margin-bottom: 0.1rem !important;">Seksi</label>
                                    <select id="pic_action_list_seksi" class="form-input card-bg text-dark" disabled>
                                        <option value="" disabled selected>- Pilih Seksi -</option>


                                    </select>
                                </div>
                                <div style="margin-top: 0.7rem; display: flex; align-items: center; justify-content: space-between;">

                                    <label for="Departemen" class="form-label" style="font-size: 20px; margin: 0;">
                                        Rekap temuan
                                    </label>

                                    <button type="button"
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 btn_rekaptemuan">
                                        <i class="fas fa-plus-circle"></i> Simpan & Tambah
                                    </button>

                                </div>
                                <hr style="border: none; border-top: 1px solid #ccc; margin-top: 1px;">
                                <div style="margin-top:0.3em;">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Deskripsi temuan</th>
                                                    <th>File</th>
                                                    <th>PIC Action Area</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rekap_tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-2 mt-5">
                                <button type="button" class="flex-1 border border-gray-300 text-dark rounded-lg py-3 text-sm font-semibold hover:bg-gray-50 backhistory"> Batal </button>
                                <button type="button" class="flex-1 btn btn-primary text-white rounded-lg py-3 text-sm font-semibold" id="btn_tambahTemuan"> <i class="fas fa-save mr-1"></i> Submit </button>
                            </div>
                        </div><!-- Stacked Bar by Area -->
                    </div>
                </div>
            </div><!-- Data Patrol Page -->




        </div><!-- Bottom Navigation -->
        <div class="fixed bottom-0 left-0 right-0 navbar-bg shadow-lg">
            <div class="flex justify-between items-center px-2 py-2">
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="dashboard">
                    <i class="fas fa-home text-lg"></i> <span class="text-xs whitespace-nowrap">Dashboard</span>
                </button>
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="dataPatrol">
                    <i class="fas fa-clipboard-list text-lg"></i> <span class="text-xs whitespace-nowrap">Data</span>
                </button>
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="schedule">
                    <i class="fas fa-calendar text-lg"></i> <span class="text-xs whitespace-nowrap">Schedule</span>
                </button>

                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="profile">
                    <i class="fas fa-user text-lg"></i> <span class="text-xs whitespace-nowrap">Profile</span>
                </button>
            </div>
        </div><!-- Logout Modal -->


    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?= base_url() ?>assets/js/eleganselect.js"></script>
    <script src="<?= base_url() ?>assets/js/notify_claim.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script> -->

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

        let config = {
            ...defaultConfig
        };
        let charts = {};
        let isDarkMode = false;

        // Data dummy untuk detail patrol

        $(document).ready(function() {
            localStorage.removeItem('rekap_temuan');
            renderTable(); // biar tabel ikut kosong
        });
        $('.backhistory').on('click', function() {
            window.history.back();
        });

        function renderTable() {
            let list = JSON.parse(localStorage.getItem('rekap_temuan')) || [];
            $('#rekap_tbody').html('');

            list.forEach((item, i) => {
                appendRow(item, i + 1);
            });
        }
        $('.startaudit').on('click', function() {
            window.location.href = "<?= base_url('temuan_patrol/auditor') ?>";
        });


        // Show Detail Modal


        // Close Detail Modal
        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Calendar Functions
        let currentDate = new Date(2024, 0, 1); // Start with January 2024
        const TODAY_REFERENCE = new Date(2024, 0, 15); // Set today as Jan 15, 2024 for demo purposes





        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);

        }





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


        new SelectX('#list_dept', {
            searchable: true,
            clearable: true,
            placeholder: 'Pilih sesuatu...',
            onChange: (value) => console.log(value)
        });
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                const page = item.dataset.page;
                console.log(page);
                if (page == 'dataPatrol') {
                    window.location.href = "<?= base_url('temuan_patrol') ?>";
                } else if (page == 'schedule') {
                    window.location.href = "<?= base_url('schedule') ?>";

                } else if (page == 'uiElements') {
                    window.location.href = "<?= base_url('uiElements') ?>";
                } else if (page == 'profile') {
                    window.location.href = "<?= base_url('profile') ?>";
                } else if (page == 'dashboard') {
                    window.location.href = "<?= base_url('summary') ?>";
                }
            });
        });
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
        $('#filterBtn_year').click(function() {
            var val = $('#list_year').val();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'getData_filter_year',
                    tahun: val
                },
                dataType: 'json',
                success: function(response) {
                    // Validasi sederhana
                    if (!response) return;

                    // Helper: pastikan array angka (bukan string)
                    const toNumberArray = (arr) => (Array.isArray(arr) ? arr.map(v => Number(v) || 0) : []);

                    const categories = Array.isArray(response.categories) ?
                        response.categories : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                    const open = toNumberArray(response.open);
                    const progress = toNumberArray(response.progress);
                    const close = toNumberArray(response.close);
                    const cancel = toNumberArray(response.cancel);
                    const total = toNumberArray(response.total);

                    // Ambil chart instance Chart.js
                    // (kalau kamu simpan di charts.combined seperti contohmu)
                    let chart = charts && charts.combined ? charts.combined : null;

                    // Kalau belum ada chart, bikin baru dari response (fallback aman)
                    if (!chart) {
                        const combinedCtx = document.getElementById('combinedChart').getContext('2d');
                        charts = charts || {};

                        charts.combined = new Chart(combinedCtx, {
                            type: 'bar',
                            data: {
                                labels: categories,
                                datasets: [{
                                        type: 'bar',
                                        label: 'Open',
                                        data: open,
                                        backgroundColor: '#686B6F',
                                        borderRadius: 6
                                    },
                                    {
                                        type: 'bar',
                                        label: 'In Progress',
                                        data: progress,
                                        backgroundColor: '#FFC005',
                                        borderRadius: 6
                                    },
                                    {
                                        type: 'bar',
                                        label: 'Close',
                                        data: close,
                                        backgroundColor: '#57e26e',
                                        borderRadius: 6
                                    },
                                    {
                                        type: 'bar',
                                        label: 'Cancel',
                                        data: cancel,
                                        backgroundColor: '#DF3545',
                                        borderRadius: 6
                                    },
                                    {
                                        type: 'line',
                                        label: 'Total Temuan',
                                        data: total,
                                        borderColor: '#ef4444',
                                        backgroundColor: 'transparent',
                                        tension: 0.4,
                                        borderWidth: 2
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: textColor
                                        }
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    }
                                },
                                scales: {
                                    y: {
                                        ticks: {
                                            color: textColor
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: textColor
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    }
                                }
                            }
                        });

                        return;
                    }

                    // Update existing chart (Chart.js)
                    chart.data.labels = categories;

                    // Pastikan urutan dataset sama seperti chart awal:
                    // 0 Open, 1 Progress, 2 Close, 3 Cancel, 4 Total (line)
                    if (chart.data.datasets[0]) chart.data.datasets[0].data = open;
                    if (chart.data.datasets[1]) chart.data.datasets[1].data = progress;
                    if (chart.data.datasets[2]) chart.data.datasets[2].data = close;
                    if (chart.data.datasets[3]) chart.data.datasets[3].data = cancel;
                    if (chart.data.datasets[4]) chart.data.datasets[4].data = total;

                    // Redraw sekali saja
                    chart.update();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
        $('#filterBtn_rangeDate').click(function() {
            const $btn = $(this);

            $btn.attr('disabled', true);
            $btn.html('Loading ...');

            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'getData_filter_rangeDate',
                    startDate: startDate,
                    endDate: endDate
                },
                dataType: 'json',
                success: function(response) {
                    $btn.attr('disabled', false);
                    $btn.html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');

                    // Helper: pastikan array angka
                    const toNumberArray = (arr) => (Array.isArray(arr) ? arr.map(v => Number(v) || 0) : []);

                    const categories = Array.isArray(response.categories) ? response.categories : [];
                    const open = toNumberArray(response.open_count);
                    const progress = toNumberArray(response.progress_count);
                    const close = toNumberArray(response.close_count);
                    const cancel = toNumberArray(response.cancel_count);

                    // Ambil chart instance Chart.js (sesuai cara kamu simpan)
                    let chart = charts && charts.area ? charts.area : null;

                    // Kalau chart belum ada, bikin baru (fallback)
                    if (!chart) {
                        const areaCtx = document.getElementById('areaChart').getContext('2d');
                        charts = charts || {};

                        charts.area = new Chart(areaCtx, {
                            type: 'bar',
                            data: {
                                labels: categories,
                                datasets: [{
                                        label: 'Open',
                                        data: open,
                                        backgroundColor: '#686B6F'
                                    },
                                    {
                                        label: 'In Progress',
                                        data: progress,
                                        backgroundColor: '#FFC005'
                                    },
                                    {
                                        label: 'Close',
                                        data: close,
                                        backgroundColor: '#57e26e'
                                    },
                                    {
                                        label: 'Cancel',
                                        data: cancel,
                                        backgroundColor: '#DF3545'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: textColor
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            afterLabel: function(context) {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = total ? ((context.parsed.y / total) * 100).toFixed(1) : '0.0';
                                                return percentage + '%';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                        ticks: {
                                            color: textColor
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    },
                                    y: {
                                        stacked: true,
                                        ticks: {
                                            color: textColor
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    }
                                }
                            }
                        });

                        return;
                    }

                    // Update chart yang sudah ada
                    chart.data.labels = categories;

                    // Pastikan urutan dataset sama seperti inisialisasi chart:
                    // 0 Open, 1 In Progress, 2 Close, 3 Cancel
                    if (chart.data.datasets[0]) chart.data.datasets[0].data = open;
                    if (chart.data.datasets[1]) chart.data.datasets[1].data = progress;
                    if (chart.data.datasets[2]) chart.data.datasets[2].data = close;
                    if (chart.data.datasets[3]) chart.data.datasets[3].data = cancel;

                    chart.update();
                },
                error: function(xhr, status, error) {
                    $btn.attr('disabled', false);
                    $btn.html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');
                    console.error('Error fetching data range date:', error);
                }
            });
        });
        new SelectX('#list_seksi', {
            searchable: true,
            clearable: true,
            placeholder: 'Pilih sesuatu...',
            onChange: (value) => console.log(value)
        });
        new SelectX('#pic_action_list_dept', {
            searchable: true,
            clearable: true,
            placeholder: 'Pilih sesuatu...',
            onChange: (value) => console.log(value)
        });
        new SelectX('#pic_action_list_seksi', {
            searchable: true,
            clearable: true,
            placeholder: 'Pilih sesuatu...',
            onChange: (value) => console.log(value)
        });
        $('#pic_action_list_dept').on('change', function() {
            const deptId = $(this).val();

            // kalau kosong => disable & reset seksi
            if (!deptId) {
                $('#pic_action_list_seksi').prop('disabled', true);

                $('#pic_action_list_seksi')
                    .empty()
                    .html('<option value="">-- Pilih Opsi --</option>')
                    .val('');


                return;
            }

            // enable seksi
            $('#pic_action_list_seksi').prop('disabled', false);
            $('#pic_action_list_seksi')
                .next('.selectx-container')
                .find('.selectx-trigger')
                .removeClass('disabled');
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
        $('#list_dept').change(function() {
            var id = $(this).val();
            var $seksi = $('#list_seksi');
            // enable select asli
            $seksi.prop('disabled', false);
            $('#list_seksi')
                .next('.selectx-container')
                .find('.selectx-trigger')
                .removeClass('disabled');


            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'get_seksi_by_dept',
                    id_dept: id
                },
                dataType: 'json',
                success: function(response) {


                    // Masukkan option dari server ke select
                    $seksi.html(response.options);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching dept2 data:', error);
                }
            });
        });
        $('#list_seksi').change(function() {
            var id = $(this).val();
            var id_dept = $('#list_dept').val();
            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'find_auditee_by_id_seksi_dept',
                    id_seksi: id,
                    id_dept: id_dept
                },
                dataType: 'json',
                success: function(response) {
                    $('#nama_auditee').val(response.auditee);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching auditee data:', error);
                }
            });
        });
        $('#filterBtn_dept').click(function() {
            const $btn = $(this);
            const dept = $('#list_dept').val();

            $btn.attr('disabled', true);
            $btn.html('Loading ...');

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'getData_filter_dept',
                    dept: dept
                },
                dataType: 'json',
                success: function(response) {
                    $btn.attr('disabled', false);
                    $btn.html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');

                    if (!response) return;

                    // Helper: pastikan numeric
                    const n = (v) => Number(v) || 0;
                    const toNumberArray = (arr) => (Array.isArray(arr) ? arr.map(v => n(v)) : []);

                    // ===== BAR CHART BULANAN (Chart.js) =====
                    // Pakai categories dari server, kalau kosong fallback 12 bulan
                    const categories = Array.isArray(response.categories) && response.categories.length ?
                        response.categories : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                    const openArr = toNumberArray(response.open);
                    const progressArr = toNumberArray(response.progress);
                    const closeArr = toNumberArray(response.close);
                    const cancelArr = toNumberArray(response.cancel);

                    // Ambil chart instance bar bulanan.
                    // Kalau bar bulanan kamu adalah combinedChart: pakai charts.combined
                    // Kalau ternyata bar bulanan dept pakai chart lain, ganti di sini.
                    const barChart = charts && charts.combined ? charts.combined : null;

                    if (!barChart) {
                        console.warn('Chart bar bulanan (charts.combined) tidak ditemukan.');
                    } else {
                        barChart.data.labels = categories;

                        // Urutan dataset harus sama seperti inisialisasi combinedChart kamu:
                        // 0 Open, 1 In Progress, 2 Close, 3 Cancel, 4 (line Total) opsional
                        if (barChart.data.datasets[0]) barChart.data.datasets[0].data = openArr;
                        if (barChart.data.datasets[1]) barChart.data.datasets[1].data = progressArr;
                        if (barChart.data.datasets[2]) barChart.data.datasets[2].data = closeArr;
                        if (barChart.data.datasets[3]) barChart.data.datasets[3].data = cancelArr;

                        // Kalau chart bar kamu punya dataset line "Total Temuan", update juga (opsional):
                        // total per bulan = open+progress+close+cancel
                        if (barChart.data.datasets[4] && barChart.data.datasets[4].type === 'line') {
                            const totalArr = categories.map((_, i) =>
                                (openArr[i] || 0) + (progressArr[i] || 0) + (closeArr[i] || 0) + (cancelArr[i] || 0)
                            );
                            barChart.data.datasets[4].data = totalArr;
                        }

                        barChart.update();
                    }

                    // ===== PIE CHART TOTAL (Chart.js) =====
                    const pieChart = charts && charts.pie ? charts.pie : null;

                    // Total dari server (bukan array)
                    const openTotal = n(response.open_count);
                    const progressTotal = n(response.progress_count);
                    const closeTotal = n(response.close_count);
                    const cancelTotal = n(response.cancel_count);

                    if (!pieChart) {
                        console.warn('Chart pie (charts.pie) tidak ditemukan.');
                    } else {
                        // labels boleh tetap, tapi aman kalau kamu mau set ulang:
                        pieChart.data.labels = ['Open', 'In Progress', 'Close', 'Cancel'];

                        // dataset pie hanya 1
                        pieChart.data.datasets[0].data = [openTotal, progressTotal, closeTotal, cancelTotal];

                        // Kalau kamu pakai dark mode dan borderColor berubah saat mode berubah,
                        // ini opsional untuk sync:
                        pieChart.data.datasets[0].borderColor = isDarkMode ? '#1e293b' : '#ffffff';

                        pieChart.update();
                    }
                },
                error: function(xhr, status, error) {
                    $btn.attr('disabled', false);
                    $btn.html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');
                    console.error('Error fetching data dept:', error);
                }
            });
        });
        // helper: update required (mandatory) untuk #pic_action_list_seksi
        function updateSeksiRequirement() {
            var $seksi = $('#pic_action_list_seksi');

            // hitung option selain placeholder (value kosong)
            var optionValidCount = $seksi.find('option[value!=""]').length;

            if (optionValidCount > 0) {
                // ada pilihan lain -> wajib
                $seksi.prop('required', true);
            } else {
                // cuma placeholder -> tidak wajib
                $seksi.prop('required', false);
            }

            return optionValidCount; // kalau butuh dipakai
        }
        $('.btn_rekaptemuan').click(function() {

            const deskripsi = String($('#deskripsi_temuan').val() ?? '').trim();
            const tanggal_patrol_actual = $('#tanggal_patrol').val();

            if (!tanggal_patrol_actual) {
                Notify.fire({
                    type: "error",
                    title: "Oops!",
                    text: "Isi Tanggal Patrol terlebih dahulu.",
                });
                return;
            }

            if (!$('#list_dept').val()) {
                Notify.fire({
                    type: "error",
                    title: "Oops!",
                    text: "Pilih Departemen Area Patrol",
                });
                return;
            }

            if (deskripsi === "") {
                Notify.fire({
                    type: "error",
                    title: "Oops!",
                    text: "Isi Deskripsi Temuan terlebih dahulu.",
                });
                return;
            }

            if (!$('#pic_action_list_dept').val()) {
                Notify.fire({
                    type: "error",
                    title: "Oops!",
                    text: "Pilih PIC Action",
                });
                return;
            }

            // ✅ IMPLEMENTASI: seksi hanya mandatory kalau memang ada opsi selain placeholder
            var optionValidCount = updateSeksiRequirement();
            if (optionValidCount > 0 && !$('#pic_action_list_seksi').val()) {
                Notify.fire({
                    type: "error",
                    title: "Oops!",
                    text: "Pilih Seksi PIC Action",
                });
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
                deskripsi_temuan: deskripsi,
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

            $('#pic_action_list_seksi')
                .next('.selectx-container')
                .find('.selectx-clear')
                .trigger('click');

            $('#pic_action_list_dept')
                .next('.selectx-container')
                .find('.selectx-clear')
                .trigger('click');

            // disable seksi lagi
            $('#pic_action_list_seksi')
                .next('.selectx-container')
                .find('.selectx-trigger')
                .addClass('disabled');

            $('#pic_action_list_seksi').prop('disabled', true);

            // ✅ IMPLEMENTASI: setelah reset/disable, pastikan required sesuai kondisi option
            updateSeksiRequirement();

            // reset file input + preview + selectedFile
            $('#fileUpload_tambah').val('');
            $('#preview_image').attr('src', '').addClass('d-none');
            selectedFile = null;

            $btn.prop('disabled', false);
        });

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
        let filesToSend = [];
        let selectedFile = null;
        $('#btn_tambahTemuan').on('click', async function(e) {
            e.preventDefault();

            const $btn = $(this);


            $('#btn_tambahTemuan').prop('disabled', true);
            $btn.html('Tunggu Sebentar...');
            try {
                // ===== ambil data form =====
                const tanggal_patrol = $('#tanggal_patrol').val();

                const deptId = $('#list_dept').val();
                const seksiId = $('#list_seksi').val();
                const nama_auditee = $('#nama_auditee').val();

                const rekap = JSON.parse(localStorage.getItem('rekap_temuan') || '[]');

                // ===== validasi =====
                if (!tanggal_patrol) {

                    Notify.fire({
                        type: "error",
                        title: "Oops!",
                        text: "Isi Tanggal Patrol terlebih dahulu.",
                    });

                    return;
                }



                if (!$('#list_dept').val()) {
                    Notify.fire({
                        type: "error",
                        title: "Oops!",
                        text: "Pilih Departemen Area Patrol",
                    });

                    return;
                }



                if (!nama_auditee) {
                    Notify.fire({
                        type: "error",
                        title: "Oops!",
                        text: "Isi Nama Auditee terlebih dahulu.",
                    });
                    return;
                }

                if (rekap.length === 0) {
                    Notify.fire({
                        type: "error",
                        title: "Oops!",
                        text: "Belum ada rekap temuan yang ditambahkan.",
                    });
                    return;
                }

                // ===== FormData =====
                const formData = new FormData();
                formData.append('keterangan', 'tambah_temuan_patrol');
                // formData.append('id_schedule', id_schedule);
                formData.append('tanggal_patrol', tanggal_patrol);
                formData.append('nama_auditee', nama_auditee);
                formData.append('deptId', deptId);
                formData.append('seksiId', seksiId);
                formData.append('rekap_temuan', JSON.stringify(rekap));

                // ===== evidence files =====
                (filesToSend || []).forEach((f) => {
                    formData.append('evidence_files[]', f);
                });

                // ===== AJAX =====
                $.ajax({
                    url: "<?= base_url('sendData') ?>",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        Notify.fire({
                            type: "success",
                            title: "Berhasil!",
                            text: "Temuan berhasil ditambahkan.",
                        });
                        Notify.fire({
                            type: "success",
                            title: "Berhasil!",
                            text: "Temuan berhasil ditambahkan.",
                            showCancelButton: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                localStorage.removeItem('rekap_temuan');
                                filesToSend = [];
                                selectedFile = null;

                                location.reload();
                            }
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding temuan patrol:', error);
                        alert('Gagal menyimpan temuan.');
                    }
                });

            } finally {
                $('#btn_tambahTemuan').prop('disabled', false);
                $('#btn_tambahTemuan').html('<i class="fas fa-save mr-1"></i> Submit');
            }
        });

        $('#filterBtn_dept2').click(function() {
            const $btn = $(this);
            const dept = $('#list_dept2').val();

            $btn.attr('disabled', true);
            $btn.html('Loading ...');

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'getData_filter_dept',
                    dept: dept
                },
                dataType: 'json',
                success: function(response) {
                    $btn.attr('disabled', false);
                    $btn.html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');

                    if (!response) return;

                    // Helper: pastikan numeric
                    const n = (v) => Number(v) || 0;
                    const toNumberArray = (arr) => (Array.isArray(arr) ? arr.map(v => n(v)) : []);

                    // Ambil categories (bulan) dari server, fallback 12 bulan
                    const categories = (Array.isArray(response.categories) && response.categories.length) ?
                        response.categories : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                    // Data per bulan dari server
                    const openArr = toNumberArray(response.open);
                    const progressArr = toNumberArray(response.progress);
                    const closeArr = toNumberArray(response.close);
                    const cancelArr = toNumberArray(response.cancel);

                    // Ambil Chart.js instance yang sesuai: charts.month
                    const monthChart = (charts && charts.month) ? charts.month : null;

                    if (!monthChart) {
                        console.warn('Chart month (charts.month) tidak ditemukan.');
                        return;
                    }

                    // Update labels (bulan)
                    monthChart.data.labels = categories;

                    // Update dataset sesuai urutan di inisialisasi chart month:
                    // 0 Open, 1 In Progress, 2 Close, 3 Cancel
                    if (monthChart.data.datasets[0]) monthChart.data.datasets[0].data = openArr;
                    if (monthChart.data.datasets[1]) monthChart.data.datasets[1].data = progressArr;
                    if (monthChart.data.datasets[2]) monthChart.data.datasets[2].data = closeArr;
                    if (monthChart.data.datasets[3]) monthChart.data.datasets[3].data = cancelArr;

                    monthChart.update();
                },
                error: function(xhr, status, error) {
                    $btn.attr('disabled', false);
                    $btn.html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');
                    console.error('Error fetching data dept2:', error);
                }
            });
        });
    </script>

</body>

</html>