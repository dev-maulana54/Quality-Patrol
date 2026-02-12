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
        <div class="flex justify-center items-center mt-1">
            <button class="bg-blue-600 text-white px-12 py-6 rounded-xl text-2xl font-bold hover:bg-blue-700 startaudit">
                <i class="bi bi-clipboard-data"></i> Mulai Quality Patrol
            </button>
        </div>
        <h2 class="page-title" id="dashboardTitle">Dashboard Overview</h2><!-- Charts Row 1 -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="chart-card" style="margin-bottom: 25px;">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="tahun" class="form-label" style="font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-calendar-event" style="color: #0d6efd;"></i> Tahun
                            </label>
                            <select class="form-select select2" id="list_year" style="width:100%;">




                                <option value="">- Pilih Tahun -</option>
                                <?php
                                $year_now = date('Y'); // 2025 (sesuai tahun server)
                                $year_end = $year_now - 5; // 2025 - 5 = 2020

                                for ($y = $year_now; $y >= $year_end; $y--) : ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>

                                <?php endfor; ?>
                                <!-- <option value="">2024</option> -->
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary w-100 btn-sm" id="filterBtn_year" style="height: 50px;"> <i class="bi bi-funnel-fill me-2"></i> Terapkan Filter </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h3 class="card-title">Temuan Tahun <span id="tahun_xxx">{All}</span></h3>
                            <div class="chart-container">
                                <div id="clustered_chart"></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-lg-12 col-md-12">
                <div class="chart-card" style="margin-bottom: 25px;">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="startDate" class="form-label" style="font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-calendar-event" style="color: #0d6efd;"></i> Tanggal Mulai
                            </label>
                            <input type="text" class="form-control tanggalpickr" id="startDate" placeholder="Pilih tanggal mulai" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="endDate" class="form-label" style="font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-calendar-check" style="color: #0d6efd;"></i> Tanggal Akhir </label>
                            <input type="text" class="form-control tanggalpickr" id="endDate" placeholder="Pilih tanggal akhir" readonly>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" id="filterBtn_rangeDate" style="height: 50px;">
                                <i class="bi bi-funnel-fill me-2"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">

                            <h3 class="card-title">Temuan Quality Patrol</h3>
                            <div class="chart-container">
                                <div id="barChartStacked"></div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-12 col-md-12">
                <div class="chart-card" style="margin-bottom: 25px;">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="Departement" class="form-label" style="font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-calendar-event" style="color: #0d6efd;"></i> Departement
                            </label>
                            <select class="form-select select2" id="list_dept" style="width:100%;">
                                <?php foreach ($data_dept as $dept) : ?>
                                    <option value="<?= $dept['id_departement'] ?>" data-departement="<?= $dept['departement'] ?>"><?= $dept['departement'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary w-100 btn-sm" id="filterBtn_dept" style="height: 50px;"> <i class="bi bi-funnel-fill me-2"></i> Terapkan Filter </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <h3 class="card-title">Temuan Area <span id="area_xxx">{All}</span></h3>
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
        $('.startaudit').on('click', function() {
            window.location.href = "<?= base_url('temuan_patrol/start_audit') ?>";
        });

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
                                    alert("Bulan: " + this.category + "\nTotal Temuan: " + this.y);
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
                            duration: 800 // 0.8 detik, boleh diganti 500 / 1000 dll
                        }
                    },
                    column: {
                        stacking: 'percent',
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                // jangan tampilkan kalau 0%
                                if (this.percentage === 0) {
                                    return null;
                                }
                                return Highcharts.numberFormat(this.percentage, 0) + '%';
                            },
                            style: {
                                color: isDarkTheme_chart ? "#ffffff" : "#000000"
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

                        showInLegend: true
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
                            y: <?= json_encode($t_progress_year) ?>,
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
                    column: {
                        stacking: 'normal',
                        borderWidth: 0,
                        borderRadiusTopLeft: 10,
                        borderRadiusTopRight: 10
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




        $('.select2').select2();
        flatpickr(".tanggalpickr", {
            locale: "id",
            dateFormat: "d M Y",
            altInput: true,
            altFormat: "d F Y",
            // defaultDate: "today",

            allowInput: false,
            clickOpens: true,
            theme: "material_blue",
            animate: true,
            position: "auto",
            onReady: function(selectedDates, dateStr, instance) {
                // Add custom styling to the calendar
                instance.calendarContainer.style.boxShadow =
                    "0 15px 35px rgba(13, 110, 253, 0.2)";
                instance.calendarContainer.style.borderRadius = "12px";
                instance.calendarContainer.style.border =
                    "1px solid rgba(13, 110, 253, 0.1)";
            },
            onChange: function(selectedDates, dateStr, instance) {
                // Validate field when date is selected
                const field = document.getElementById("auditDate");
                // validateField(field);

                // Add visual feedback
                field.style.borderColor = "#198754";
                field.style.boxShadow = "0 0 0 3px rgba(25, 135, 84, 0.1)";

                setTimeout(() => {
                    field.style.borderColor = "";
                    field.style.boxShadow = "";
                }, 1000);
            },
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
            var dept = $('#list_dept').val(); // ambil dept yang dipilih
            $(this).attr('disabled', true);
            $('#filterBtn_dept').html('Loading ... <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    keterangan: 'getData_filter_dept',
                    dept: dept, // <--- kirim dept ke server

                },
                dataType: 'json',
                success: function(response) {
                    $('#filterBtn_dept').attr('disabled', false);
                    $('#filterBtn_dept').html('<i class="bi bi-funnel-fill me-2"></i> Terapkan Filter');

                    // ====== BAR CHART (PER BULAN) ======
                    var barChart = Highcharts.charts.find(function(c) {
                        return c && c.renderTo && c.renderTo.id === 'barChart_stacked_area';
                    });

                    if (!barChart) {
                        console.warn('Chart "barChart_stacked_area" tidak ditemukan.');
                    } else {
                        // kalau server kirim categories (misal: nama bulan spesifik), update xAxis
                        if (Array.isArray(response.categories)) {
                            barChart.xAxis[0].setCategories(response.categories, false);
                        }

                        try {
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
                        } catch (e) {
                            console.error('Gagal update series bar chart:', e);
                        }
                    }

                    // ====== PIE CHART (TOTAL DI AREA/DEPT) ======
                    var pieChart = Highcharts.charts.find(function(c) {
                        return c && c.renderTo && c.renderTo.id === 'piechart_area';
                    });

                    if (!pieChart) {
                        console.warn('Chart "piechart_area" tidak ditemukan.');
                    } else {

                        // (opsional tapi disarankan) pastikan pie punya dataLabels & legend aktif
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

                        // Build data + HILANGKAN yang y=0 (ini yang bikin % & legend ikut gak tampil)
                        var pieData = [{
                                name: 'Open',
                                y: parseInt(response.open_count) || 0,
                                color: '#686B6F' // sesuaikan dengan warna Open kamu
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


                        // Kalau semua 0, kasih fallback biar chart gak kosong (opsional)
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

                        pieChart.series[0].setData(pieData, true); // true = redraw
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