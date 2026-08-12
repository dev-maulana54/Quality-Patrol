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

<body class="light-theme">
    <!-- Header -->
    <header class="topbar">
      <div class="topbar-left">
        <a class="topbar-brand" href="<?= base_url('summary') ?>" aria-label="Quality Patrol">
          <span class="topbar-mark"><i class="bi bi-shield-check"></i></span>
          <span id="appName">Quality Patrol</span>
        </a>
        <nav class="topbar-nav d-none d-md-flex">
          <a class="topbar-nav-link active" href="<?= base_url('summary') ?>"><i class="bi bi-house-door"></i> Home</a>
          <a class="topbar-nav-link" href="<?= base_url('temuan_patrol/daftar_temuan') ?>"><i class="bi bi-list-check"></i> Daftar Temuan</a>
          <?php if (isset($role) && $role === 'Administrator') : ?>
          <div class="dropdown d-inline-block">
            <a class="topbar-nav-link dropdown-toggle" href="#" id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-database"></i> Master Data
            </a>
            <ul class="dropdown-menu shadow-sm" aria-labelledby="masterDataDropdown">
              <li><a class="dropdown-item py-2" href="<?= base_url('admin/mdata_user') ?>"><i class="bi bi-people me-2 text-primary"></i> User</a></li>
              <li><a class="dropdown-item py-2" href="<?= base_url('admin/mdata_departemen') ?>"><i class="bi bi-building me-2 text-primary"></i> Departemen</a></li>
            </ul>
          </div>
          <?php endif; ?>
        </nav>
      </div>
      <div class="topbar-actions">
        <div class="topbar-user">
          <span id="topbarUserName" class="topbar-user-name"><?= isset($nama) ? $nama : 'User'; ?></span>
          <div class="profile-dropdown">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode(isset($nama) ? $nama : 'User'); ?>&amp;background=0d6efd&amp;color=fff&amp;size=128" alt="Profil pengguna" class="profile-img" id="profileImg" />
            <div class="dropdown-menu dropdown-menu-end shadow-sm" id="profileDropdown">
              <div class="d-md-none border-bottom pb-2 mb-2 px-2">
                <a class="dropdown-item py-1" href="<?= base_url('summary') ?>"><i class="bi bi-house-door me-2"></i> Home</a>
                <a class="dropdown-item py-1" href="<?= base_url('temuan_patrol/daftar_temuan') ?>"><i class="bi bi-list-check me-2"></i> Daftar Temuan</a>
                <?php if (isset($role) && $role === 'Administrator') : ?>
                <div class="dropdown-header px-0 text-muted fw-bold small mt-1">MASTER DATA</div>
                <a class="dropdown-item py-1 ps-3" href="<?= base_url('admin/mdata_user') ?>"><i class="bi bi-people me-2"></i> User</a>
                <a class="dropdown-item py-1 ps-3" href="<?= base_url('admin/mdata_departemen') ?>"><i class="bi bi-building me-2"></i> Departemen</a>
                <?php endif; ?>
              </div>
              <button class="topbar-menu-action theme-toggle" id="themeToggle" type="button"><i class="bi bi-sun-fill me-2"></i><span>Ubah tema</span></button>
              <a href="javascript:void(0)" class="dropdown-item text-danger fw-semibold" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </div>
          </div>
        </div>
      </div>
    </header>
    </div><!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Hero Button -->
        <div class="d-flex justify-content-end mb-4">
            <button class="startaudit" style="
                background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
                color: #fff;
                border: none;
                border-radius: 14px;
                font-weight: 700;
                font-size: 1rem;
                padding: 14px 28px;
                box-shadow: 0 8px 20px rgba(79,70,229,0.25);
                transition: all 0.3s ease;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 10px;
            ">
                <i class="bi bi-clipboard-data" style="font-size:1.2rem;"></i> Mulai Quality Patrol
            </button>
        </div>
        <h2 class="page-title" id="dashboardTitle">Dashboard Overview</h2>

        <!-- ===== FILTER CARD UNIFIED ===== -->
        <div class="filter-unified-card">
            <div class="filter-section-title">
                <i class="bi bi-funnel-fill"></i> Filter Data
            </div>
            <div class="row g-3 align-items-end">
                <!-- Tahun -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="filter-label"><i class="bi bi-calendar3 me-1"></i> Tahun</label>
                    <select class="form-select" id="list_year" style="border-radius:10px;height:40px;border:1.5px solid #e2e8f0;font-size:14px;">
                        <option value="">- Semua Tahun -</option>
                        <?php
                        $year_now = date('Y');
                        $year_end = $year_now - 5;
                        for ($y = $year_now; $y >= $year_end; $y--) : ?>
                            <option value="<?= $y ?>" <?= ($y == $year_now) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <!-- Dari Tanggal -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="filter-label"><i class="bi bi-calendar-event me-1"></i> Dari Tanggal</label>
                    <input type="text" class="form-control tanggalpickr" id="startDate" placeholder="Pilih tanggal" readonly style="border-radius:10px;height:40px;border:1.5px solid #e2e8f0;font-size:14px;">
                </div>
                <!-- Sampai Tanggal -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="filter-label"><i class="bi bi-calendar-check me-1"></i> Sampai Tanggal</label>
                    <input type="text" class="form-control tanggalpickr" id="endDate" placeholder="Pilih tanggal" readonly style="border-radius:10px;height:40px;border:1.5px solid #e2e8f0;font-size:14px;">
                </div>
                <!-- Departemen -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="filter-label"><i class="bi bi-building me-1"></i> Departemen</label>
                    <select class="form-select" id="list_dept" style="border-radius:10px;height:40px;border:1.5px solid #e2e8f0;font-size:14px;">
                        <option value="" selected>- Semua Departemen -</option>
                        <?php if (!empty($data_dept)): ?>
                            <?php foreach ($data_dept as $dept) : ?>
                                <option value="<?= $dept['id_departement'] ?>" data-departement="<?= $dept['departement'] ?>"><?= $dept['departement'] ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="Produksi 1">Produksi 1</option>
                            <option value="Produksi 2">Produksi 2</option>
                            <option value="QA">QA</option>
                            <option value="QC">QC</option>
                            <option value="Procurement">Procurement</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Logistic">Logistic</option>
                            <option value="Safety &amp; Health">Safety &amp; Health</option>
                        <?php endif; ?>
                    </select>
                </div>
                <!-- Tombol Aksi -->
                <div class="col-12 col-md-2 d-flex gap-2 align-items-end">
                    <button class="btn-filter-apply flex-fill" id="filterBtn_all">
                        <i class="bi bi-funnel-fill me-1"></i> Terapkan
                    </button>
                    <button class="btn-filter-reset" id="resetBtn_all" title="Reset Semua Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===== CHARTS ===== -->
        <div class="row">
            <!-- Chart 1: Per Tahun -->
            <div class="col-lg-12">
                <div class="chart-card" style="margin-bottom:24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="chart-section-header">Temuan Tahun <span id="tahun_xxx"><?= date('Y') ?></span></div>
                            <p class="chart-section-sub">Distribusi temuan per bulan berdasarkan status — klik kolom untuk lihat detail</p>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div id="clustered_chart"></div>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Per Departemen -->
            <div class="col-lg-12">
                <div class="chart-card" style="margin-bottom:24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="chart-section-header">Temuan per Departemen</div>
                            <p class="chart-section-sub">Persentase status temuan berdasarkan area — klik untuk lihat detail</p>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div id="barChartStacked"></div>
                    </div>
                </div>
            </div>

            <!-- Chart 3: Pie + Stacked Bar Area -->
            <div class="col-lg-12">
                <div class="chart-card" style="margin-bottom:24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="chart-section-header">Temuan Area: <span id="area_xxx">Semua Departemen</span></div>
                            <p class="chart-section-sub">Distribusi status temuan berdasarkan departemen yang dipilih</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="chart-container">
                                <div id="piechart_area"></div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="chart-container">
                                <div id="barChart_stacked_area"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div><!-- Footer -->
    <!-- Modal Detail Temuan -->
    <div class="modal fade" id="modalDetailTemuan" tabindex="-1" aria-labelledby="modalDetailTemuanLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailTemuanLabel">Detail Temuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="filterInfo" class="mb-3"></div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tableDetailTemuan">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Patrol</th>
                                    <th>Departemen</th>
                                    <th>Section</th>
                                    <th>Auditor</th>
                                    <th>Auditee</th>
                                    <th>Status</th>
                                    <th>Deskripsi Temuan</th>
                                    <th>Action</th>
                                    <th>Due Date</th>
                                    <th>Evidence</th>
                                </tr>
                            </thead>
                            <tbody id="detailTemuanBody">
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="<?= base_url() ?>assets/js/highchart/highcharts.js"></script>
    <script src="<?= base_url() ?>assets/js/highchart/modules/exporting.js"></script>


    <script src="<?= base_url() ?>assets/js/highchart/modules/accessibility.js"></script>

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
        $('.startaudit').on('click', function() {
            window.location.href = "<?= base_url('temuan_patrol/start_audit') ?>";
        });
        let currentDeptFilter = null;
        let currentDeptName = 'Semua Departemen';

        function renderClusteredChart() {
            const isDarkTheme_chart = localStorage.getItem("theme") === "dark";

            // Data Chart Per Tahun
            Highcharts.chart('clustered_chart', {
                chart: {
                    backgroundColor: 'transparent',
                    marginRight: 40,
                },
                title: false,
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    lineColor: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff",
                    labels: {
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    }
                },

                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Temuan',
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    },
                    labels: {
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    }
                },

                legend: {
                    itemStyle: {
                        color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                    }
                },
                plotOptions: {
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function() {
                                    const seriesName = this.series.name;
                                    const bulanNama = this.category;

                                    const bulanMap = {
                                        'Jan': 1,
                                        'Feb': 2,
                                        'Mar': 3,
                                        'Apr': 4,
                                        'Mei': 5,
                                        'Jun': 6,
                                        'Jul': 7,
                                        'Agu': 8,
                                        'Sep': 9,
                                        'Okt': 10,
                                        'Nov': 11,
                                        'Des': 12
                                    };

                                    const statusMap = {
                                        'Open': 3,
                                        'In Progress': 2,
                                        'Close': 1,
                                        'Cancel': 4
                                    };

                                    const bulan = bulanMap[bulanNama];
                                    const tahun = $('#list_year').val() || new Date().getFullYear();
                                    const status = (seriesName === 'Total Temuan') ? null : statusMap[seriesName];

                                    loadDetailTemuan(tahun, bulan, status, seriesName, bulanNama);
                                }
                            }
                        }
                    }
                },
                series: [{
                        type: 'column',
                        name: 'Open',
                        data: <?= json_encode($open) ?>,
                        color: '#686B6F'
                    },
                    {
                        type: 'column',
                        name: 'In Progress',
                        data: <?= json_encode($progress) ?>,
                        color: '#FFC005'
                    },
                    {
                        type: 'column',
                        name: 'Close',
                        data: <?= json_encode($close) ?>,
                        color: '#57e26e'
                    },
                    {
                        type: 'column',
                        name: 'Cancel',
                        data: <?= json_encode($cancel) ?>,
                        color: '#DF3545'
                    },
                    {
                        type: 'spline',
                        name: 'Total Temuan',
                        data: <?= json_encode($total) ?>,
                        color: isDarkTheme_chart ? '#ffffff' : '#000000',
                        marker: {
                            enabled: true,
                            lineWidth: 1,
                            lineColor: isDarkTheme_chart ? '#ffffff' : '#000000',
                            fillColor: isDarkTheme_chart ? '#ffffff' : '#000000'
                        }
                    }
                ]
            });

            Highcharts.chart('barChartStacked', {
                chart: {
                    backgroundColor: 'transparent',
                    type: 'column',
                    marginRight: 40,
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true
                },
                title: false,

                xAxis: {
                    categories: <?= json_encode($areaNames) ?>,

                    labels: {
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    },
                    lineColor: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff",
                },

                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Temuan',
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    },
                    labels: {
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    }
                },

                legend: {
                    itemStyle: {
                        color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                    }
                },

                tooltip: {
                    shared: true,
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>'
                },

                plotOptions: {
                    series: {
                        animation: {
                            duration: 800
                        }
                    },
                    column: {
                        stacking: 'percent',
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                if (this.percentage === 0) {
                                    return null;
                                }
                                return Highcharts.numberFormat(this.percentage, 0) + '%';
                            },
                            style: {
                                color: isDarkTheme_chart ? "#ffffff" : "#000000"
                            }
                        },
                        point: {
                            events: {
                                click: function() {
                                    const areaName = this.category;
                                    const seriesName = this.series.name;

                                    const statusMap = {
                                        'Open': 3,
                                        'In Progress': 2,
                                        'Close': 1,
                                        'Cancel': 4
                                    };

                                    const status = statusMap[seriesName];
                                    const startDate = $('#startDate').val();
                                    const endDate = $('#endDate').val();

                                    loadDetailTemuanByArea(areaName, status, seriesName, startDate, endDate);
                                }
                            }
                        }
                    }
                },

                // 🔥 DATA TEMUAN BY AREA (contoh data, silakan ganti)
                series: [

                    {

                        name: 'Open',
                        data: <?= json_encode($total_open) ?>,
                        color: '#686B6F'

                    },

                    {
                        name: 'In Progress',
                        data: <?= json_encode($total_progress) ?>,
                        color: '#FFC005'
                    },
                    {
                        name: 'Close',
                        data: <?= json_encode($total_close) ?>,
                        color: '#57e26e'
                    },
                    {
                        name: 'Cancel',
                        data: <?= json_encode($total_cancel) ?>,
                        color: '#DF3545'
                    }
                ]
            });


            const colors = {
                open: isDarkTheme_chart ? '#686B6F' : '#686B6F',
                progress: isDarkTheme_chart ? '#FFC005' : '#eccb67ff',
                close: isDarkTheme_chart ? '#4cd964' : '#66FF99',
                cancel: isDarkTheme_chart ? '#DF3545' : '#ff6b6b',
                text: isDarkTheme_chart ? '#ffffff' : '#4b3d3dff'
            };

            Highcharts.chart('piechart_area', {
                chart: {
                    type: 'pie',
                    backgroundColor: 'transparent',
                    marginRight: 40
                },

                title: false,
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true
                },

                tooltip: {
                    useHTML: true,
                    formatter: function() {
                        return `
                <b>${this.point.name}</b><br>
                Jumlah: <b>${this.point.y}</b><br>
                Persentase: <b>${this.point.percentage.toFixed(1)}%</b>
            `;
                    }
                },

                plotOptions: {
                    pie: {
                        size: '90%',
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            distance: -40,
                            format: '{point.percentage:.1f}%',
                            style: {
                                fontSize: '16px',
                                fontWeight: 'bold',
                                color: colors.text
                            }
                        },
                        showInLegend: true,
                        point: {
                            events: {
                                click: function() {
                                    const statusName = this.name;

                                    const statusMap = {
                                        'Open': 3,
                                        'In Progress': 2,
                                        'Close': 1,
                                        'Cancel': 4
                                    };

                                    const status = statusMap[statusName];

                                    loadDetailTemuanByDept(currentDeptFilter, currentDeptName, status, statusName);
                                }
                            }
                        }
                    }
                },

                legend: {
                    enabled: true,
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal',
                    itemStyle: {
                        color: colors.text,
                        fontSize: '14px'
                    }
                },

                series: [{
                    name: 'Persentase',
                    colorByPoint: true,
                    data: [{
                            name: 'Open',
                            y: <?= json_encode($t_open_year) ?>,
                            color: colors.open
                        },
                        {
                            name: 'In Progress',
                            y: <?= json_encode($t_progress_year) ?>,
                            color: colors.progress
                        },
                        {
                            name: 'Close',
                            y: <?= json_encode($t_close_year) ?>,
                            color: colors.close
                        },
                        {
                            name: 'Cancel',
                            y: <?= json_encode($t_cancel_year) ?>,
                            color: colors.cancel
                        }
                    ]
                }]
            });


            Highcharts.chart('barChart_stacked_area', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                    animation: {
                        duration: 800 // dalam milidetik (0.8 detik)
                    }
                },

                title: false,
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    labels: {
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    },
                    lineColor: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                },

                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Temuan',
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    },
                    labels: {
                        style: {
                            color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                        }
                    },
                    stackLabels: {
                        enabled: false
                    }
                },

                legend: {
                    itemStyle: {
                        color: isDarkTheme_chart ? "#ffffff" : "#4b3d3dff"
                    }
                },

                tooltip: {
                    shared: true
                },

                plotOptions: {
                    series: {
                        animation: {
                            duration: 800
                        }
                    },
                    column: {
                        stacking: 'normal',
                        borderWidth: 0,
                        borderRadiusTopLeft: 10,
                        borderRadiusTopRight: 10,
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function() {
                                    const bulanNama = this.category;
                                    const statusName = this.series.name;

                                    const bulanMap = {
                                        'Jan': 1,
                                        'Feb': 2,
                                        'Mar': 3,
                                        'Apr': 4,
                                        'Mei': 5,
                                        'Jun': 6,
                                        'Jul': 7,
                                        'Agu': 8,
                                        'Sep': 9,
                                        'Okt': 10,
                                        'Nov': 11,
                                        'Des': 12
                                    };

                                    const statusMap = {
                                        'Open': 3,
                                        'In Progress': 2,
                                        'Close': 1,
                                        'Cancel': 4
                                    };

                                    const bulan = bulanMap[bulanNama];
                                    const status = statusMap[statusName];

                                    loadDetailTemuanByDeptMonth(
                                        currentDeptFilter,
                                        currentDeptName,
                                        bulan,
                                        bulanNama,
                                        status,
                                        statusName
                                    );
                                }
                            }
                        }
                    }
                },

                series: [{
                        name: 'Open',
                        data: <?= json_encode($open) ?>,
                        color: isDarkTheme_chart ? '#686B6F' : '#686B6F'
                    },
                    {
                        name: 'In Progress',
                        data: <?= json_encode($progress) ?>,
                        color: isDarkTheme_chart ? '#FFC005' : '#eccb67ff'
                    },
                    {
                        name: 'Close',
                        data: <?= json_encode($close) ?>,
                        color: isDarkTheme_chart ? '#4cd964' : '#66FF99'
                    },
                    {
                        name: 'Cancel',
                        data: <?= json_encode($cancel) ?>,
                        color: isDarkTheme_chart ? '#DF3545' : '#ff6b6b'
                    }
                ]
            });
        }

        function loadDetailTemuanByArea(areaName, status, statusText, startDate, endDate) {

            $('#modalDetailTemuanLabel').text('Detail Temuan Area');

            $('#filterInfo').html(`
        <div class="alert alert-info mb-2">
            Area <strong>${areaName}</strong> - Status <strong>${statusText}</strong><br>
            Periode: <strong>${startDate || '-'} s/d ${endDate || '-'}</strong>
        </div>
    `);

            resetDetailTemuanTable();

            $('#detailTemuanBody').html(`
        <tr>
            <td colspan="11" class="text-center">
                Loading...
                <div class="spinner-border spinner-border-sm ms-2"></div>
            </td>
        </tr>
    `);

            const modal = new bootstrap.Modal(document.getElementById('modalDetailTemuan'));
            modal.show();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    keterangan: 'getDetailTemuanByArea',
                    area: areaName,
                    status: status,
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {

                    let html = '';

                    resetDetailTemuanTable();

                    if (!response.data || response.data.length === 0) {
                        html = `
                    <tr>
                        <td colspan="11" class="text-center">Data tidak ditemukan</td>
                    </tr>
                `;
                        $('#detailTemuanBody').html(html);
                        return;
                    }

                    response.data.forEach((item, index) => {

                        let evidenceHtml = '-';

                        if (item.evidence_file) {
                            let fileUrl = "<?= base_url('uploads/findings_evidence/') ?>" + item.evidence_file;

                            evidenceHtml = `
                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    `;
                        }

                        html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.tanggal_patrol ?? '-'}</td>
                        <td>${item.departement_name ?? '-'}</td>
                        <td>${item.section_name ?? '-'}</td>
                        <td>${item.auditor_name ?? '-'}</td>
                        <td>${item.nama_auditee ?? '-'}</td>
                        <td>${item.status_label ?? '-'}</td>
                        <td>${item.deskripsi_temuan ?? '-'}</td>
                        <td>${item.action ?? '-'}</td>
                        <td>${item.due_date ?? '-'}</td>
                        <td>${evidenceHtml}</td>
                    </tr>
                `;
                    });

                    $('#detailTemuanBody').html(html);

                    let table = $('#tableDetailTemuan').DataTable({
                        destroy: true,
                        responsive: true,
                        pageLength: 10
                    });

                    table.on('order.dt search.dt draw.dt', function() {
                        let info = table.page.info();
                        table.column(0, {
                            page: 'current'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = info.start + i + 1;
                        });
                    }).draw();
                }
            });
        }

        function loadDetailTemuanByDept(deptId, deptName, status, statusText) {
            const isAllDept = !deptId;

            $('#modalDetailTemuanLabel').text('Detail Temuan');

            $('#filterInfo').html(`
        <div class="alert alert-info mb-2">
            ${isAllDept
                ? `Semua Departemen - Status <strong>${statusText}</strong>`
                : `Departemen <strong>${deptName}</strong> - Status <strong>${statusText}</strong>`
            }
        </div>
    `);

            resetDetailTemuanTable();

            $('#detailTemuanBody').html(`
        <tr>
            <td colspan="11" class="text-center">
                Loading...
                <div class="spinner-border spinner-border-sm ms-2"></div>
            </td>
        </tr>
    `);

            const modal = new bootstrap.Modal(document.getElementById('modalDetailTemuan'));
            modal.show();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    keterangan: 'getDetailTemuanByDept',
                    dept: deptId,
                    status: status
                },
                success: function(response) {
                    let html = '';

                    resetDetailTemuanTable();

                    if (!response || !response.data || response.data.length === 0) {
                        $('#detailTemuanBody').html(`
                    <tr>
                        <td colspan="11" class="text-center">Data tidak ditemukan</td>
                    </tr>
                `);
                        return;
                    }

                    response.data.forEach((item, index) => {
                        let evidenceHtml = '-';

                        if (item.evidence_file) {
                            let fileUrl = "<?= base_url('uploads/findings_evidence/') ?>" + item.evidence_file;
                            evidenceHtml = `
                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    `;
                        }

                        html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.tanggal_patrol ?? '-'}</td>
                        <td>${item.departement_name ?? '-'}</td>
                        <td>${item.section_name ?? '-'}</td>
                        <td>${item.auditor_name ?? '-'}</td>
                        <td>${item.nama_auditee ?? '-'}</td>
                        <td>${item.status_label ?? '-'}</td>
                        <td>${item.deskripsi_temuan ?? '-'}</td>
                        <td>${item.action ?? '-'}</td>
                        <td>${item.due_date ?? '-'}</td>
                        <td>${evidenceHtml}</td>
                    </tr>
                `;
                    });

                    $('#detailTemuanBody').html(html);

                    let table = $('#tableDetailTemuan').DataTable({
                        destroy: true,
                        responsive: true,
                        autoWidth: false,
                        pageLength: 10,
                        lengthMenu: [
                            [10, 25, 50, 100],
                            [10, 25, 50, 100]
                        ],
                        ordering: true,
                        searching: true,
                        paging: true,
                        info: true,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                            zeroRecords: "Data tidak ditemukan",
                            emptyTable: "Data tidak tersedia",
                            paginate: {
                                first: "Awal",
                                last: "Akhir",
                                next: "Berikutnya",
                                previous: "Sebelumnya"
                            }
                        },
                        columnDefs: [{
                            targets: 0,
                            orderable: false,
                            searchable: false
                        }]
                    });

                    table.on('order.dt search.dt draw.dt', function() {
                        let info = table.page.info();
                        table.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'current'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = info.start + i + 1;
                        });
                    }).draw();
                },
                error: function(xhr, statusText, error) {
                    resetDetailTemuanTable();

                    $('#detailTemuanBody').html(`
                <tr>
                    <td colspan="11" class="text-center text-danger">
                        Gagal mengambil data
                    </td>
                </tr>
            `);

                    console.error('Error detail temuan by dept:', error);
                }
            });
        }

        function resetDetailTemuanTable() {
            if ($.fn.DataTable.isDataTable('#tableDetailTemuan')) {
                $('#tableDetailTemuan').DataTable().clear().destroy();
            }
        }

        function loadDetailTemuan(tahun, bulan, status, statusText, bulanNama) {
            let labelStatus = (status === null || status === undefined) ? 'Semua Status' : statusText;

            $('#modalDetailTemuanLabel').text('Detail Temuan');
            $('#filterInfo').html(`
        <div class="alert alert-info mb-2">
            Menampilkan data <strong>${labelStatus}</strong> bulan <strong>${bulanNama}</strong> tahun <strong>${tahun}</strong>
        </div>
    `);

            resetDetailTemuanTable();

            $('#detailTemuanBody').html(`
        <tr>
            <td colspan="11" class="text-center">
                Loading...
                <div class="spinner-border spinner-border-sm ms-2" role="status"></div>
            </td>
        </tr>
    `);

            const modal = new bootstrap.Modal(document.getElementById('modalDetailTemuan'));
            modal.show();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    keterangan: 'getDetailTemuanChart',
                    tahun: tahun,
                    bulan: bulan,
                    status: status
                },
                success: function(response) {
                    let html = '';

                    resetDetailTemuanTable();

                    if (!response || !response.data || response.data.length === 0) {
                        html = `
                    <tr>
                        <td colspan="11" class="text-center">Data tidak ditemukan</td>
                    </tr>
                `;
                        $('#detailTemuanBody').html(html);
                        return;
                    }

                    response.data.forEach((item, index) => {
                        let evidenceHtml = '-';

                        if (item.evidence_file) {
                            let fileUrl = "<?= base_url('uploads/findings_evidence/') ?>" + item.evidence_file;

                            evidenceHtml = `
        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary">
            <i class="bi bi-eye"></i> View
        </a>
    `;
                        }

                        html += `
<tr>
    <td>${index + 1}</td>
    <td>${item.tanggal_patrol ?? '-'}</td>
    <td>${item.departement_name ?? '-'}</td>
    <td>${item.section_name ?? '-'}</td>
    <td>${item.auditor_name ?? '-'}</td>
    <td>${item.nama_auditee ?? '-'}</td>
    <td>${item.status_label ?? '-'}</td>
    <td>${item.deskripsi_temuan ?? '-'}</td>
    <td>${item.action ?? '-'}</td>
    <td>${item.due_date ?? '-'}</td>
    <td>${evidenceHtml}</td>
</tr>
`;
                    });

                    $('#detailTemuanBody').html(html);

                    let table = $('#tableDetailTemuan').DataTable({
                        destroy: true,
                        responsive: true,
                        autoWidth: false,
                        pageLength: 10,
                        lengthMenu: [
                            [10, 25, 50, 100],
                            [10, 25, 50, 100]
                        ],
                        ordering: true,
                        searching: true,
                        paging: true,
                        info: true,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                            zeroRecords: "Data tidak ditemukan",
                            emptyTable: "Data tidak tersedia",
                            paginate: {
                                first: "Awal",
                                last: "Akhir",
                                next: "Berikutnya",
                                previous: "Sebelumnya"
                            }
                        },
                        columnDefs: [{
                            targets: 0,
                            orderable: false,
                            searchable: false
                        }]
                    });

                    table.on('order.dt search.dt draw.dt', function() {
                        let info = table.page.info();
                        table.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'current'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = info.start + i + 1;
                        });
                    }).draw();
                },
                error: function(xhr, statusText, error) {
                    resetDetailTemuanTable();

                    $('#detailTemuanBody').html(`
                <tr>
                    <td colspan="11" class="text-center text-danger">
                        Gagal mengambil data
                    </td>
                </tr>
            `);

                    console.error('Error detail temuan:', error);
                }
            });
        }

        function loadDetailTemuanByDeptMonth(deptId, deptName, bulan, bulanNama, status, statusText) {
            const isAllDept = !deptId;
            const tahun = new Date().getFullYear();

            $('#modalDetailTemuanLabel').text('Detail Temuan');

            $('#filterInfo').html(`
        <div class="alert alert-info mb-2">
            ${isAllDept
                ? `Semua Departemen`
                : `Departemen <strong>${deptName}</strong>`
            }
            - Bulan <strong>${bulanNama}</strong>
            - Status <strong>${statusText}</strong>
        </div>
    `);

            resetDetailTemuanTable();

            $('#detailTemuanBody').html(`
        <tr>
            <td colspan="11" class="text-center">
                Loading...
                <div class="spinner-border spinner-border-sm ms-2"></div>
            </td>
        </tr>
    `);

            const modal = new bootstrap.Modal(document.getElementById('modalDetailTemuan'));
            modal.show();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    keterangan: 'getDetailTemuanByDeptMonth',
                    dept: deptId,
                    bulan: bulan,
                    status: status,
                    tahun: tahun
                },
                success: function(response) {
                    let html = '';

                    resetDetailTemuanTable();

                    if (!response || !response.data || response.data.length === 0) {
                        $('#detailTemuanBody').html(`
                    <tr>
                        <td colspan="11" class="text-center">Data tidak ditemukan</td>
                    </tr>
                `);
                        return;
                    }

                    response.data.forEach((item, index) => {
                        let evidenceHtml = '-';

                        if (item.evidence_file) {
                            let fileUrl = "<?= base_url('uploads/findings_evidence/') ?>" + item.evidence_file;
                            evidenceHtml = `
                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    `;
                        }

                        html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.tanggal_patrol ?? '-'}</td>
                        <td>${item.departement_name ?? '-'}</td>
                        <td>${item.section_name ?? '-'}</td>
                        <td>${item.auditor_name ?? '-'}</td>
                        <td>${item.nama_auditee ?? '-'}</td>
                        <td>${item.status_label ?? '-'}</td>
                        <td>${item.deskripsi_temuan ?? '-'}</td>
                        <td>${item.action ?? '-'}</td>
                        <td>${item.due_date ?? '-'}</td>
                        <td>${evidenceHtml}</td>
                    </tr>
                `;
                    });

                    $('#detailTemuanBody').html(html);

                    let table = $('#tableDetailTemuan').DataTable({
                        destroy: true,
                        responsive: true,
                        autoWidth: false,
                        pageLength: 10,
                        lengthMenu: [
                            [10, 25, 50, 100],
                            [10, 25, 50, 100]
                        ],
                        ordering: true,
                        searching: true,
                        paging: true,
                        info: true,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                            zeroRecords: "Data tidak ditemukan",
                            emptyTable: "Data tidak tersedia",
                            paginate: {
                                first: "Awal",
                                last: "Akhir",
                                next: "Berikutnya",
                                previous: "Sebelumnya"
                            }
                        },
                        columnDefs: [{
                            targets: 0,
                            orderable: false,
                            searchable: false
                        }]
                    });

                    table.on('order.dt search.dt draw.dt', function() {
                        let info = table.page.info();
                        table.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'current'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = info.start + i + 1;
                        });
                    }).draw();
                },
                error: function(xhr, statusText, error) {
                    resetDetailTemuanTable();

                    $('#detailTemuanBody').html(`
                <tr>
                    <td colspan="11" class="text-center text-danger">
                        Gagal mengambil data
                    </td>
                </tr>
            `);

                    console.error('Error detail temuan by dept month:', error);
                }
            });
        }
        $('.select2').select2();
        flatpickr(".tanggalpickr", {
            locale: "id",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F Y",
            allowInput: false,
            clickOpens: true,
            onReady: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.style.boxShadow = "0 15px 35px rgba(13, 110, 253, 0.2)";
                instance.calendarContainer.style.borderRadius = "12px";
                instance.calendarContainer.style.border = "1px solid rgba(13, 110, 253, 0.1)";
            }
        });

        // Reset Filter Tahun
        $('#resetBtn_year').click(function () {
            $('#list_year').val('').trigger('change');
            $('#filterBtn_year').click();
        });

        // Reset Filter Range Tanggal
        $('#resetBtn_rangeDate').click(function () {
            if (document.getElementById('startDate')._flatpickr) {
                document.getElementById('startDate')._flatpickr.clear();
            } else {
                $('#startDate').val('');
            }
            if (document.getElementById('endDate')._flatpickr) {
                document.getElementById('endDate')._flatpickr.clear();
            } else {
                $('#endDate').val('');
            }
            $('#filterBtn_rangeDate').click();
        });

        // Reset Filter Departemen
        $('#resetBtn_dept').click(function () {
            $('#list_dept').val('').trigger('change');
            $('#filterBtn_dept').click();
        });

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

                    $('#tahun_xxx').text(val);
                    // cari chart yang sudah dirender (tidak menambahkan var baru untuk chart render)
                    var chart = Highcharts.charts.find(function(c) {
                        return c && c.renderTo && c.renderTo.id === 'clustered_chart';
                    });

                    if (!chart) {
                        console.warn('Chart "clustered_chart" tidak ditemukan.');
                        return;
                    }

                    // jika server mengirim categories (opsional), update xAxis
                    if (Array.isArray(response.categories)) {
                        chart.xAxis[0].setCategories(response.categories, false); // false -> jangan redraw dulu
                    }

                    // update tiap series sesuai urutan: Open, In Progress, Close, Total Temuan
                    // pastikan server mengirim array numeric, bukan string
                    try {
                        if (Array.isArray(response.open)) {
                            chart.series[0].setData(response.open, false);
                        }
                        if (Array.isArray(response.progress)) {
                            chart.series[1].setData(response.progress, false);
                        }
                        if (Array.isArray(response.close)) {
                            chart.series[2].setData(response.close, false);
                        }
                        if (Array.isArray(response.cancel)) {
                            chart.series[3].setData(response.cancel, false);
                        }
                        if (Array.isArray(response.total)) {
                            chart.series[4].setData(response.total, false);
                        }
                        // redraw sekali saja
                        chart.redraw();
                    } catch (e) {
                        console.error('Gagal update series chart:', e);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching seksi data:', error);
                }
            });
        });
        $('#filterBtn_rangeDate').click(function() {
            $(this).attr('disabled', true);
            $('#filterBtn_rangeDate').html('Loading ... <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

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
                    $('#filterBtn_rangeDate').attr('disabled', false);
                    $('#filterBtn_rangeDate').html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');
                    var chart = Highcharts.charts.find(function(c) {
                        return c && c.renderTo && c.renderTo.id === 'barChartStacked';
                    });

                    if (!chart) {
                        console.warn('Chart "barChartStacked" tidak ditemukan.');
                        return;
                    }

                    // kalau server kirim categories, update xAxis
                    if (Array.isArray(response.categories)) {
                        chart.xAxis[0].setCategories(response.categories, false);
                    }

                    try {
                        if (Array.isArray(response.open_count)) {
                            chart.series[0].setData(response.open_count, false);
                        }
                        if (Array.isArray(response.progress_count)) {
                            chart.series[1].setData(response.progress_count, false);
                        }
                        if (Array.isArray(response.close_count)) {
                            chart.series[2].setData(response.close_count, false);
                        }
                        if (Array.isArray(response.cancel_count)) {
                            chart.series[3].setData(response.cancel_count, false);
                        }

                        // if (Array.isArray(response.total)) {
                        //     chart.series[3].setData(response.total, false);
                        // }

                        chart.redraw();
                    } catch (e) {
                        console.error('Gagal update series chart:', e);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data range date:', error);
                }
            });
        });
        $('#filterBtn_dept').click(function() {
            var dept = $('#list_dept').val();
            var deptName = $('#list_dept option:selected').data('departement') || $('#list_dept option:selected').text();

            $(this).attr('disabled', true);
            $('#filterBtn_dept').html('Loading ... <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');

            currentDeptFilter = dept ? dept : null;
            currentDeptName = dept ? deptName : 'Semua Departemen';

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'getData_filter_dept',
                    dept: dept,
                },
                dataType: 'json',
                success: function(response) {
                    $('#filterBtn_dept').attr('disabled', false);
                    $('#filterBtn_dept').html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');

                    $('#area_xxx').text(currentDeptName);

                    var barChart = Highcharts.charts.find(function(c) {
                        return c && c.renderTo && c.renderTo.id === 'barChart_stacked_area';
                    });

                    if (barChart) {
                        if (Array.isArray(response.categories)) {
                            barChart.xAxis[0].setCategories(response.categories, false);
                        }

                        if (Array.isArray(response.open)) {
                            barChart.series[0].setData(response.open, false);
                        }
                        if (Array.isArray(response.progress)) {
                            barChart.series[1].setData(response.progress, false);
                        }
                        if (Array.isArray(response.close)) {
                            barChart.series[2].setData(response.close, false);
                        }
                        if (Array.isArray(response.cancel)) {
                            barChart.series[3].setData(response.cancel, false);
                        }

                        barChart.redraw();
                    }

                    var pieChart = Highcharts.charts.find(function(c) {
                        return c && c.renderTo && c.renderTo.id === 'piechart_area';
                    });

                    if (pieChart) {
                        pieChart.update({
                            plotOptions: {
                                pie: {
                                    showInLegend: true,
                                    dataLabels: {
                                        enabled: true,
                                        format: '{point.name}: {point.percentage:.1f}%'
                                    }
                                }
                            },
                            legend: {
                                enabled: true
                            }
                        }, false);

                        var pieData = [{
                                name: 'Open',
                                y: parseInt(response.open_count) || 0,
                                color: '#686B6F'
                            },
                            {
                                name: 'In Progress',
                                y: parseInt(response.progress_count) || 0,
                                color: '#FFC005'
                            },
                            {
                                name: 'Close',
                                y: parseInt(response.close_count) || 0,
                                color: '#66ff99'
                            },
                            {
                                name: 'Cancel',
                                y: parseInt(response.cancel_count) || 0,
                                color: '#ff6666'
                            }
                        ].filter(p => p.y > 0);

                        if (pieData.length === 0) {
                            pieData = [{
                                name: 'No Data',
                                y: 1,
                                dataLabels: {
                                    enabled: false
                                },
                                showInLegend: false
                            }];
                        }

                        pieChart.series[0].setData(pieData, true);
                    }
                },
                error: function(xhr, status, error) {
                    $('#filterBtn_dept').attr('disabled', false);
                    $('#filterBtn_dept').html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');
                    console.error('Error fetching data range date:', error);
                }
            });
        });
    </script>
</body>

</html>