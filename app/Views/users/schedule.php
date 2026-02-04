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
    <!-- Air Datepicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.min.css" rel="stylesheet">
    <!-- Select2 core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 Bootstrap-5 Theme -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.6.2/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" /> -->
    <link href="<?= base_url() ?>assets/css/summary.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/schedule.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }



        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            flex-wrap: wrap;
            gap: 10px;
        }

        .nav-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s;
        }

        .nav-btn:hover {
            background: #764ba2;
        }

        .current-month {
            font-size: 1.3em;
            font-weight: bold;
            color: #333;
        }

        .filter-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-input {
            padding: 8px 12px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 0.9em;
        }

        .add-row-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .add-row-btn:hover {
            background: #218838;
        }

        .footer {
            margin-left: 0px;
        }

        .table-container {
            padding: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
            font-size: 0.85em;
        }

        th {
            background: #667eea;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .area-col {
            background: #f8f9fa;
            font-weight: bold;
            text-align: left;
            min-width: 120px;
        }

        .row-type {
            background: #f8f9fa;
            font-weight: 500;
            text-align: left;
            /* padding-left: 20px; */
        }

        .date-cell {
            min-width: 35px;
            height: 50px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .date-cell:hover {
            background: #f8f9fa;
        }

        .date-cell.filled {
            background: repeating-linear-gradient(45deg,
                    #e3f2fd,
                    #90afc5 2px,
                    #7a9bb5 2px,
                    #7a9bb5 4px)
        }

        .date-cell.filled-actual {
            background: repeating-linear-gradient(45deg,
                    #e8f5e9,
                    #81c784 2px,
                    #66bb6a 2px,
                    #66bb6a 4px)
        }




        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
            font-size: 0.9em;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.9em;
            resize: vertical;
            min-height: 60px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        /* Hover effect */
        .date-cell.filled-plan:hover {
            opacity: 0.8;
        }

        .date-cell.filled-actual:hover {
            opacity: 0.8;
        }


        .delete-area {
            cursor: pointer;
            color: #dc3545;
            margin-left: 10px;
            font-size: 0.9em;
        }

        .delete-area:hover {
            color: #c82333;
        }

        .dark-theme #currentMonth {
            color: #b0b0b0;
        }

        .dark-theme #planActualTable tbody tr td.area-col {
            color: #b0b0b0;
            background: #1e1e1e;
        }

        .dark-theme #planActualTable tbody tr td.row-type {
            color: #b0b0b0;
            background: #1e1e1e;
        }

        .dark-theme #planActualTable thead tr th.area-col {
            color: #b0b0b0;
            background: #1e1e1e;
        }

        #planActualTable thead tr th.area-col {
            color: #413f3fff;

        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 5px solid #ccc;
            border-top: 5px solid #4CAF50;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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

        .btn-group-xs>.btn,
        .btn-xs {
            --bs-btn-padding-y: 0.15rem;
            --bs-btn-padding-x: 0.35rem;
            --bs-btn-font-size: 0.75rem;
            --bs-btn-border-radius: 0.2rem;
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
            "><?= $role ?></p>
        </div>
        <a href="<?= base_url('summary') ?>" class="menu-item " data-page="dashboard">
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
            </div>
        </div>
        <a href="<?= base_url('schedule') ?>" class="menu-item active" data-page="schedule">
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

        <div class="table-card mt-2">
            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div style="display: flex; gap: 10px;">
                        <button class="nav-btn" onclick="app.prevMonth()">◄ Bulan Sebelumnya</button>
                        <button class="nav-btn" onclick="app.nextMonth()">Bulan Berikutnya ►</button>
                    </div>
                    <div class="current-month" id="currentMonth"></div>
                    <?php if ($role == 'Administrator') : ?>
                        <div class="filter-section">

                            <button class="add-row-btn" data-bs-toggle="modal" data-bs-target="#modal_tambahschedule">+ Tambah Schedule</button>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
            <div class="table-container">
                <div id="loadingIndicator" style="display: none; text-align: center; padding: 20px;">
                    <div class="spinner"></div>
                    <div style="margin-top: 10px;">Memuat data...</div>
                </div>


                <table id="planActualTable">
                    <thead>
                        <tr>
                            <th class="area-col" colspan="2">Area</th>
                            <!-- <th></th> -->
                            <th colspan="31" id="datesHeader"></th>
                        </tr>

                    </thead>
                    <tbody id="tableBody">

                    </tbody>
                </table>
            </div>

        </div>
        <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header" id="modalHeader">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Data Schedule</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="planActualForm">
                            <input type="hidden" id="selectedArea">
                            <input type="hidden" id="data_id_schedule">
                            <input type="hidden" id="selectedDate">
                            <input type="hidden" id="selectedType">

                            <div class="form-group mt-2">
                                <label for="fill_pic_action" class="form-label">
                                    <i class="bi bi-building me-1"></i> Nama Auditor [Plan]</label>
                                <input type="text" class="form-control" id="plan_nama_auditor" placeholder="Nama Auditor" disabled>
                                <select class="form-select select2" id="edit_nama_auditor" multiple="multiple" style="width:100%;">

                                    <option value="">Blank</option>

                                </select>
                            </div>
                            <div class="form-group mt-2 actual_container" style="display: none;">
                                <label for="fill_pic_action" class="form-label">
                                    <i class="bi bi-building me-1"></i> Nama Auditor [Actual] </label>
                                <input type="text" class="form-control" id="actual_nama_auditor" placeholder="Nama Auditor" disabled>

                            </div>

                            <div class="form-group">
                                <label for="edit_tanggal_patrol" class="form-label">
                                    <i class="bi bi-calendar-fill me-1"></i> Tanggal Patrol
                                </label>
                                <input type="text" class="form-control" id="edit_tanggal_patrol" placeholder="Pilih Tanggal" required autocomplete="off">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="btn_ubahdata"><i class="bi bi-plus-circle"></i> Ubah data </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal fade" id="modal_tambahschedule" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header" id="modalHeader">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Schedule</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="planActualForm">
                            <div class="row">
                                <div class="form-group col-md-6 ">
                                    <label for="Departemen" class="form-label">
                                        <i class="bi bi-building me-1"></i>Nama Auditor
                                    </label>
                                    <select class="form-select select2" id="list_auditor" multiple="multiple" style="width:100%;">

                                        <?php foreach ($getdata_auditor as $gda) : ?>
                                            <option value="<?= $gda['npk'] ?>"><?= $gda['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label for="Departemen" class="form-label">
                                        <i class="bi bi-building me-1"></i>Departemen
                                    </label>
                                    <select class="form-select select2" id="list_dept" style="width:100%;">
                                        <option value="">-- Pilih Departemen --</option>
                                        <?php foreach ($data_dept as $dept) : ?>
                                            <option value="<?= $dept['id_departement'] ?>" data-departement="<?= $dept['departement'] ?>"><?= $dept['departement'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label for="Seksi" class="form-label">
                                        <i class="bi bi-diagram-3 me-1"></i>Seksi
                                    </label>
                                    <select class="form-select select2" id="list_seksi" style="width:100%;" disabled>
                                        <option value="">-- Pilih Opsi --</option>
                                    </select>

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tanggal_patrol" class="form-label">
                                        <i class="bi bi-calendar-fill me-1"></i> Tanggal Patrol
                                    </label>
                                    <input type="text" class="form-control" id="tanggal_patrol" placeholder="Pilih Tanggal" required autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="btn_tambahSchedule"><i class="bi bi-plus-circle"></i> Submit </button>
                    </div>
                </div>
            </div>

        </div>
        <!-- Footer -->
        <div class="footer" id="footer">
            <p id="footerText">© 2025 Quality Patrol — All rights reserved</p>
        </div>


        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script><!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script><!-- DataTables -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <!-- Air Datepicker JS -->
        <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.min.js"></script>
        <!-- Optional: Localization (Bahasa Indonesia) -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/locale/id.js"></script> -->
        <!-- Select2 core JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
        <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
        <script src="<?= base_url() ?>assets/js/temuan_patrol/view-image.js"></script>
        <script src="<?= base_url() ?>assets/js/temuan_patrol/schedule.js"></script>
        <script>
            document.querySelectorAll('.submenu-toggle').forEach(item => {
                item.addEventListener('click', () => {
                    item.parentElement.classList.toggle('open');
                });
            });
            var baseurl = '<?= base_url() ?>';
        </script>
        <script>
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

            $("#modal_tambahschedule .select2").select2({
                dropdownParent: $("#modal_tambahschedule"),
            });
            $("#modal_tambahschedule #list_auditor").select2({
                dropdownParent: $("#modal_tambahschedule"),
                placeholder: "-- Pilih Auditor --",
                allowClear: true
            });
            $("#modal .select2").select2({
                dropdownParent: $("#modal"),
            });
            $(document).on('click', '.hapus_schedule', function() {
                var id_schedule = $(this).data('id');
                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    data: {
                        keterangan: 'hapus_schedule',
                        id_schedule: id_schedule
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.status == 'success') {
                            alert('Data schedule berhasil dihapus.');
                            location.reload();
                        } else {
                            alert('Gagal menghapus data schedule.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching seksi data:', error);
                    }
                });

            });
            $('#list_dept').change(function() {
                var deptId = $(this).val();
                $('#list_seksi').prop('disabled', !deptId);

                // TODO : Ambil nama seksi nya berdasarkan deptId menggunakan jquery ajax
                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    data: {
                        keterangan: 'get_seksi_by_dept',
                        id_dept: deptId
                    },
                    dataType: 'json',
                    success: function(response) {

                        var seksiOptions = '';
                        // todo : perbaiki error ini Cannot use 'in' operator to search for 'length' in <option value="15">Casting</option><option value="16">Pasting</option><option value="17">Formation</option>
                        seksiOptions += response.options;

                        $('#list_seksi').html(seksiOptions);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching seksi data:', error);
                    }
                });
            });
            let dpEdit = null;

            $('#modal').on('shown.bs.modal', function() {
                if (!dpEdit) {
                    dpEdit = new AirDatepicker('#edit_tanggal_patrol', {
                        locale: localeID, // ⬅️ INI KUNCINYA
                        dateFormat: 'dd/MM/yyyy',
                        autoClose: true,
                        position: 'bottom left',
                        container: '#modal',
                        buttons: ['today', 'clear']
                    });
                }
            });
            $('#modal').on('hidden.bs.modal', function() {
                if (dpEdit) {
                    dpEdit.destroy();
                    dpEdit = null;
                }
            });

            function setDateFromServer(datepicker, tglStr) {
                if (!tglStr) return;

                const [dd, mm, yyyy] = tglStr.split('/').map(Number);
                const dateObj = new Date(yyyy, mm - 1, dd);

                datepicker.selectDate(dateObj, {
                    silent: true
                });
            }
            const app = {
                currentDate: new Date(),
                data: {},
                areas: <?= json_encode($get_schedule_area) ?>,
                scheduleData: <?= json_encode($schedule_data ?? []) ?>,

                init() {
                    this.loadScheduleData();
                    this.render();
                    document.getElementById('planActualForm').addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.saveData();
                    });
                },

                loadScheduleData() {
                    this.data = {}; // ✅ tambahan ini
                    // Load data dari database ke dalam data object
                    if (this.scheduleData && this.scheduleData.length > 0) {
                        this.scheduleData.forEach(item => {
                            // Gunakan 'tanggal' bukan 'tanggal_patrol'
                            const date = new Date(item.tanggal);
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const day = String(date.getDate()).padStart(2, '0');
                            const dateKey = `${year}-${month}-${day}`;
                            const dataKey = `${item.area}-${item.type}-${dateKey}`;

                            this.data[dataKey] = {
                                area: item.area,
                                date: item.tanggal,
                                type: item.type,
                                id_schedule: item.id_schedule
                            };
                        });
                    }

                    console.log('Loaded schedule data:', this.data);
                    console.log('Loaded schedule data:', this.item);
                },

                render() {
                    this.renderHeader();
                    this.renderTable();
                },

                renderHeader() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const monthName = new Date(year, month).toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });

                    document.getElementById('currentMonth').textContent = monthName;

                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    let dateNumbersHTML = '';
                    for (let day = 1; day <= daysInMonth; day++) {
                        dateNumbersHTML += `<th class="date-col">${day}</th>`;
                    }

                    const headerRow = document.querySelector('thead tr:first-child');
                    headerRow.innerHTML = `
            <th class="area-col" colspan="2">Area</th>
            ${dateNumbersHTML}
        `;
                },

                renderTable() {
                    const tbody = document.getElementById('tableBody');
                    tbody.innerHTML = '';

                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const today = new Date();

                    this.areas.forEach((area, index) => {
                        const planRow = document.createElement('tr');
                        const actualRow = document.createElement('tr');

                        let planCells = '';
                        let actualCells = '';

                        // ✅ cari id_schedule milik area ini (ambil yang pertama ketemu di bulan itu)
                        let areaIdSchedule = null;

                        for (let day = 1; day <= daysInMonth; day++) {
                            const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                            const planData = this.data[`${area}-plan-${dateKey}`];
                            const actualData = this.data[`${area}-actual-${dateKey}`];

                            // ✅ simpan id_schedule pertama yang ketemu (plan atau actual)
                            if (!areaIdSchedule && (planData?.id_schedule || actualData?.id_schedule)) {
                                areaIdSchedule = planData?.id_schedule ?? actualData?.id_schedule;
                            }

                            const isToday = (day === today.getDate() && month === today.getMonth() && year === today.getFullYear());
                            const todayClass = isToday ? 'today' : '';

                            planCells += `<td class="date-cell ${planData ? 'filled filled-plan' : ''} ${todayClass}"
            onclick="app.openModal('${area}', ${day}, 'plan', ${planData ? planData.id_schedule : null})"></td>`;

                            actualCells += `<td class="date-cell ${actualData ? 'filled filled-actual' : ''} ${todayClass}"
            onclick="app.openModal('${area}', ${day}, 'actual', ${actualData ? actualData.id_schedule : null})"></td>`;
                        }

                        planRow.innerHTML = `
        ${index === 0 || this.areas[index - 1] !== area ? `
            <td class="area-col" rowspan="2">
                ${area}
                <button type="button"
                    class="btn btn-danger btn-xs ms-2 hapus_schedule"
                    data-id="${areaIdSchedule ?? ''}"
                    ${areaIdSchedule ? '' : 'disabled'}>
                    <i class="bi bi-trash-fill"></i>
                </button>
            </td>
        ` : ''}
        <td class="row-type">Plan</td>
        ${planCells}
    `;

                        actualRow.innerHTML = `
        <td class="row-type">Actual</td>
        ${actualCells}
    `;

                        tbody.appendChild(planRow);
                        tbody.appendChild(actualRow);
                    });
                },

                openModal(area, day, type, id_schedule) {
                    <?php if ($role == 'Administrator') : ?>
                        if (type == 'plan') {
                            $('.actual_container').css('display', 'none');
                            $('#btn_ubahdata').show();
                            $('#plan_nama_auditor').hide();
                            $('#edit_nama_auditor')
                                .show()
                                .select2({
                                    width: '100%',
                                    dropdownParent: $('#modal') // ganti dengan id modal kamu
                                });
                            $('#modal').modal('show');
                            const year = this.currentDate.getFullYear();
                            const month = this.currentDate.getMonth();
                            const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                            const dataKey = `${area}-${type}-${dateKey}`;
                            const id_jadwal = id_schedule;
                            // console.log(...);
                            console.log('Opening modal:', dateKey, area, type, id_jadwal);
                            $('#selectedArea').val(area);
                            $('#selectedDate').val(dateKey);
                            $('#selectedType').val(type);
                            $('#data_id_schedule').val(id_jadwal);
                            $.ajax({
                                url: '<?= base_url('sendData') ?>',
                                type: 'POST',
                                data: {
                                    keterangan: 'get_data_schedule',
                                    type: 'plan',
                                    id_schedule: id_jadwal,
                                },
                                dataType: 'json',
                                success: function(response) {
                                    console.log('sukses ambil data');
                                    $('#edit_nama_auditor').attr('disabled', false);
                                    $('#edit_tanggal_patrol').attr('disabled', false);
                                    $('#edit_nama_auditor').html(response.schedule.nama_auditor_plan);
                                    const dateObj = parseDdMmYyyy(response.schedule.tanggal_patrol); // "23/12/2025"

                                    // misal ini untuk modal edit
                                    $('#modal').modal('show');

                                    $('#modal').one('shown.bs.modal', function() {
                                        // pastikan dpEdit sudah dibuat di handler shown.bs.modal
                                        dpEdit.selectDate(dateObj, {
                                            silent: true
                                        });
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error adding temuan patrol:', error);
                                },

                            });
                        } else if (type == 'actual') {
                            $('.actual_container').css('display', 'block');
                            $('#btn_ubahdata').hide();
                            $('#plan_nama_auditor').show();
                            $('#edit_nama_auditor').select2('destroy').hide();


                            $('#modal').modal('show');
                            const year = this.currentDate.getFullYear();
                            const month = this.currentDate.getMonth();
                            const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                            const dataKey = `${area}-${type}-${dateKey}`;
                            const id_jadwal = id_schedule;
                            // console.log(...);
                            console.log('Opening modal:', dateKey, area, type, id_jadwal);
                            $('#selectedArea').val(area);
                            $('#selectedDate').val(dateKey);
                            $('#selectedType').val(type);
                            $('#data_id_schedule').val(id_jadwal);
                            $.ajax({
                                url: '<?= base_url('sendData') ?>',
                                type: 'POST',
                                data: {
                                    keterangan: 'get_data_schedule',
                                    type: 'actual',
                                    id_schedule: id_jadwal,
                                },
                                dataType: 'json',
                                success: function(response) {
                                    console.log('sukses ambil data');

                                    $('#edit_tanggal_patrol').attr('disabled', true);

                                    $('#plan_nama_auditor').val(response.schedule.nama_auditor_plan);
                                    $('#actual_nama_auditor').val(response.schedule.nama_auditor_actual);

                                    const dateObj = parseDdMmYyyy(response.schedule.tanggal_actual); // "23/12/2025"

                                    // misal ini untuk modal edit
                                    $('#modal').modal('show');

                                    $('#modal').one('shown.bs.modal', function() {
                                        // pastikan dpEdit sudah dibuat di handler shown.bs.modal
                                        dpEdit.selectDate(dateObj, {
                                            silent: true
                                        });
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error adding temuan patrol:', error);
                                },

                            });
                        }
                    <?php endif; ?>
                },

                prevMonth() {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                    // Buat variabel untuk bulan dan tahun
                    const month = this.currentDate.getMonth() + 1; // +1 karena getMonth() return 0-11
                    const year = this.currentDate.getFullYear();
                    $('#loadingIndicator').show(); // Sebelum AJAX
                    // Log bulan dan tahun
                    console.log('Bulan:', month);
                    console.log('Tahun:', year);
                    console.log(`${month}/${year}`);

                    // Kirim data menggunakan AJAX
                    $.ajax({
                        url: '<?= base_url('sendData') ?>', // Ganti dengan URL endpoint Anda
                        type: 'POST',
                        data: {
                            keterangan: 'ambil_data_schedule',
                            month: month,
                            year: year
                        },
                        success: function(response) {
                            $('#loadingIndicator').hide(); // Di success dan error
                            // Pastikan response.schedule_data adalah array
                            const data = Array.isArray(response.schedule_data) ? response.schedule_data : [];

                            app.scheduleData = data;

                            // Ambil area unik dari data
                            app.areas = [...new Set(data.map(item => item.area))];

                            // Reload data ke object app.data
                            app.loadScheduleData();

                            // Render ulang tabel
                            app.render();

                            console.log('Data berhasil ditampilkan:', data);
                        },

                        error: function(xhr, status, error) {
                            console.error('Error mengirim data:', error);
                        }
                    });
                    this.render();
                },

                nextMonth() {
                    // Geser bulan ke depan
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);

                    // Ambil bulan & tahun
                    const month = this.currentDate.getMonth() + 1;
                    const year = this.currentDate.getFullYear();

                    $('#loadingIndicator').show();

                    console.log('Bulan:', month);
                    console.log('Tahun:', year);
                    console.log(`${month}/${year}`);

                    // AJAX request
                    $.ajax({
                        url: '<?= base_url('sendData') ?>',
                        type: 'POST',
                        data: {
                            keterangan: 'ambil_data_schedule', // beda dari prev
                            month: month,
                            year: year
                        },
                        success: function(response) {
                            $('#loadingIndicator').hide();

                            const data = Array.isArray(response.schedule_data) ? response.schedule_data : [];

                            app.scheduleData = data;

                            // Ambil area unik
                            app.areas = [...new Set(data.map(item => item.area))];

                            // Load ulang ke object app.data
                            app.loadScheduleData();

                            // Render ulang tabel
                            app.render();

                            console.log('Data berhasil ditampilkan:', data);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error mengirim data:', error);
                        }
                    });

                    this.render();
                }

            };

            app.init();
            console.log('RAW scheduleData sample:', this.scheduleData?.[0]);
            console.log('RAW scheduleData keys:', this.scheduleData?.[0] ? Object.keys(this.scheduleData[0]) : null);

            let datepickerInstance;

            function parseDdMmYyyy(tglStr) {
                if (!tglStr) return null;
                const [dd, mm, yyyy] = tglStr.split('/').map(Number);
                return new Date(yyyy, mm - 1, dd);
            }
            // Inisialisasi saat modal dibuka
            $('#modal_tambahschedule').on('shown.bs.modal', function() {
                if (!datepickerInstance) {
                    datepickerInstance = new AirDatepicker('#tanggal_patrol', {
                        locale: {
                            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                            daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                            today: 'Hari Ini',
                            clear: 'Hapus',
                            dateFormat: 'dd/MM/yyyy',
                            timeFormat: 'HH:mm',
                            firstDay: 1
                        },
                        dateFormat: 'dd/MM/yyyy',
                        autoClose: true,
                        position: 'bottom left',
                        container: '#modal_tambahschedule', // Penting untuk modal!
                        buttons: ['today', 'clear']
                        // minDate: new Date(), // Uncomment jika perlu
                    });
                }
            });

            // Destroy datepicker saat modal ditutup (opsional, untuk cleanup)
            $('#modal_tambahschedule').on('hidden.bs.modal', function() {
                if (datepickerInstance) {
                    datepickerInstance.destroy();
                    datepickerInstance = null;
                }
            });

            $('#btn_tambahSchedule').click(function(e) {
                e.preventDefault();
                // cegah double submit
                var $btn = $(this);
                if ($btn.prop('disabled')) return;
                $btn.prop('disabled', true);

                var tanggal_patrol = $('#tanggal_patrol').val();
                var auditors = $('#list_auditor').val(); // array
                var deptId = $('#list_dept').val();
                var seksiId = $('#list_seksi').val();

                var payload = {
                    keterangan: 'tambah_schedule_patrol',
                    tanggal_patrol: tanggal_patrol,
                    deptId: $('#list_dept').val(),
                    seksiId: $('#list_seksi').val()
                };
                // kirim sebagai auditor[]
                auditors.forEach(function(val, i) {
                    payload['auditor[' + i + ']'] = val;
                });
                if (!tanggal_patrol || !deptId || !seksiId || !auditors) {
                    alert('Isi data yang kosong!');
                    $btn.prop('disabled', false);
                    return;
                }
                // console.log('list Auditor:', auditor);
                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: payload,
                    success: function(response) {
                        alert('Schedule berhasil ditambahkan!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding temuan patrol:', error);
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });


            });
            $('#btn_ubahdata').click(function() {

                var auditors = $('#edit_nama_auditor').val(); // array
                var tanggal_patrol = $('#edit_tanggal_patrol').val();
                var id_schedule = $('#data_id_schedule').val();
                var payload = {
                    keterangan: 'edit_schedule',
                    id_schedule: id_schedule,
                    tanggal_patrol: tanggal_patrol,

                };
                // kirim sebagai auditor[]
                auditors.forEach(function(val, i) {
                    payload['auditor[' + i + ']'] = val;
                });
                if (!tanggal_patrol || !auditors) {
                    alert('Isi data yang kosong!');
                    $btn.prop('disabled', false);
                    return;
                }
                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: payload,
                    success: function(response) {
                        if (response.status === 'error') {
                            alert(response.message);
                            return;
                        } else {
                            alert(response.message);
                            location.reload();

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding temuan patrol:', error);
                    },
                });
            });
        </script>
</body>

</html>