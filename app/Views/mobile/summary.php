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
                <div class="flex justify-center items-center mt-5">
                    <button class="bg-blue-600 text-white px-12 py-6 rounded-xl text-2xl font-bold hover:bg-blue-700 startaudit">
                        <i class="fas fa-clipboard-check text-lg"></i> Mulai Quality Patrol
                    </button>
                </div>
                <div class="px-4 py-4"><!-- Poster Banner -->

                    <div class="mb-6 rounded-2xl overflow-hidden shadow-xl" style="height:400px;">
                        <img
                            src="<?= base_url() ?>assets/banner2.png"
                            alt="Safety First Banner"
                            class="w-full h-full object-fill block" />
                    </div>


                    <h2 class="text-lg font-semibold text-dark mb-1">Dashboard</h2>
                    <p class="text-sm text-gray mb-4" id="dashboardSubtitle">Monitoring Real-time</p><!-- Summary Cards -->
                    <div class="scroll-container mb-6">
                        <div class="scroll-item summary-card rounded-xl p-4 text-white shadow-lg" style="min-width: 140px;">
                            <div class="flex flex-col"><i class="fas fa-folder-open text-2xl mb-2 opacity-80"></i> <span class="text-sm opacity-90">Total Open</span> <span class="text-2xl font-bold mt-1"><?= array_sum($total_open); ?>

                                </span>
                            </div>
                        </div>
                        <div class="scroll-item summary-card rounded-xl p-4 text-white shadow-lg" style="min-width: 140px;">
                            <div class="flex flex-col"><i class="fas fa-spinner text-2xl mb-2 opacity-80"></i> <span class="text-sm opacity-90">Total In Progress</span> <span class="text-2xl font-bold mt-1"><?= array_sum($total_progress); ?></span>
                            </div>
                        </div>
                        <div class="scroll-item summary-card rounded-xl p-4 text-white shadow-lg" style="min-width: 140px;">
                            <div class="flex flex-col"><i class="fas fa-check-circle text-2xl mb-2 opacity-80"></i> <span class="text-sm opacity-90">Total Close</span> <span class="text-2xl font-bold mt-1"><?= array_sum($total_close); ?></span>
                            </div>
                        </div>
                        <div class="scroll-item summary-card rounded-xl p-4 text-white shadow-lg" style="min-width: 140px;">
                            <div class="flex flex-col"><i class="fas fa-user-tie text-2xl mb-2 opacity-80"></i> <span class="text-sm opacity-90">Total Cancel</span> <span class="text-2xl font-bold mt-1"><?= array_sum($total_cancel); ?></span>
                            </div>
                        </div>
                        <div class="scroll-item summary-card rounded-xl p-4 text-white shadow-lg" style="min-width: 140px;">
                            <div class="flex flex-col"><i class="fas fa-users text-2xl mb-2 opacity-80"></i> <span class="text-sm opacity-90">Total Temuan</span> <span class="text-2xl font-bold mt-1"><?= array_sum($total_temuan); ?></span>
                            </div>
                        </div>
                    </div><!-- Charts Section -->
                    <div class="space-y-4"><!-- Combined Chart -->
                        <div class="card-bg card-animated rounded-xl p-5 shadow-md hover:shadow-xl">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center"><i class="fas fa-chart-line text-white"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-dark">Tren Bulanan</h3>
                                </div>
                                <button class="text-sm text-blue-600 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-all" onclick="toggleFilter('filter1')">
                                    <i class="fas fa-filter mr-1"></i>Filter
                                </button>
                            </div>
                            <div id="filter1" class="hidden mb-3 p-3 bg-gray-50 rounded-lg space-y-2">
                                <div>
                                    <label class="block text-xs font-semibold text-dark mb-1">Periode</label>
                                    <select class="w-full text-sm card-bg border border-gray-300 rounded px-2 py-1 text-dark" id="list_year">
                                        <option>- Pilih Tahun -</option>
                                        <?php
                                        $year_now = date('Y'); // 2025 (sesuai tahun server)
                                        $year_end = $year_now - 5; // 2025 - 5 = 2020

                                        for ($y = $year_now; $y >= $year_end; $y--) : ?>
                                            <option value="<?= $y ?>"><?= $y ?></option>

                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <button class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold mt-2" id="filterBtn_year"> Terapkan Filter </button>
                            </div>
                            <div class="chart-container">
                                <div class="chart-wrapper">
                                    <canvas id="combinedChart"></canvas>
                                </div>
                            </div>
                        </div><!-- Stacked Bar by Area -->
                        <div class="card-bg card-animated rounded-xl p-5 shadow-md hover:shadow-xl">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center"><i class="fas fa-map-marker-alt text-white"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-dark">Temuan per Area</h3>
                                </div>
                                <button class="text-sm text-blue-600 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-all" onclick="toggleFilter('filter2')">
                                    <i class="fas fa-filter mr-1"></i>Filter
                                </button>
                            </div>
                            <div id="filter2" class="hidden mb-3 p-3 bg-gray-50 rounded-lg space-y-2">

                                <!-- Filter Date From -->
                                <div>
                                    <label class="block text-xs font-semibold text-dark mb-1">
                                        Dari Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        class="w-full text-sm card-bg border border-gray-300 rounded px-2 py-1 text-dark" id="startDate">
                                </div>

                                <!-- Filter Date To -->
                                <div>
                                    <label class="block text-xs font-semibold text-dark mb-1">
                                        Sampai Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        class="w-full text-sm card-bg border border-gray-300 rounded px-2 py-1 text-dark" id="endDate">
                                </div>

                                <!-- Button Apply Filter -->
                                <button
                                    class="btn w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold mt-2" id="filterBtn_rangeDate">
                                    Terapkan Filter
                                </button>

                            </div>

                            <div class="chart-container">
                                <div class="chart-wrapper">
                                    <canvas id="areaChart"></canvas>
                                </div>
                            </div>
                        </div><!-- Pie Chart -->
                        <div class="card-bg card-animated rounded-xl p-5 shadow-md hover:shadow-xl">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center"><i class="fas fa-chart-pie text-white"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-dark">Status per Departement</h3>
                                </div><button class="text-sm text-blue-600 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-all" onclick="toggleFilter('filter3')"> <i class="fas fa-filter mr-1"></i>Filter </button>
                            </div>
                            <div id="filter3" class="hidden mb-3 p-3 bg-gray-50 rounded-lg space-y-2">

                                <div>
                                    <label class="block text-xs font-semibold text-dark mb-1">Area</label>
                                    <select class="w-full text-sm card-bg border border-gray-300 rounded px-2 py-1 text-dark" id="list_dept">
                                        <option value="" disabled selected>- Pilih Departement -</option>
                                        <?php foreach ($data_dept as $dept) : ?>
                                            <option value="<?= $dept['id_departement'] ?>" data-departement="<?= $dept['departement'] ?>"><?= $dept['departement'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold mt-2" id="filterBtn_dept"> Terapkan Filter </button>
                            </div>
                            <div class="chart-container" style="height: 280px;">
                                <canvas id="pieChart" style="max-height: 260px;"></canvas>
                            </div>
                        </div><!-- Stacked Bar by Month -->
                        <div class="card-bg card-animated rounded-xl p-5 shadow-md hover:shadow-xl">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center"><i class="fas fa-calendar-alt text-white"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-dark">Temuan Bulanan per Area</h3>
                                </div><button class="text-sm text-blue-600 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-all" onclick="toggleFilter('filter4')"> <i class="fas fa-filter mr-1"></i>Filter </button>
                            </div>
                            <div id="filter4" class="hidden mb-3 p-3 bg-gray-50 rounded-lg space-y-2">

                                <div>
                                    <label class="block text-xs font-semibold text-dark mb-1">Area</label>
                                    <select class="w-full text-sm card-bg border border-gray-300 rounded px-2 py-1 text-dark" id="list_dept2">
                                        <option value="" disabled selected>- Pilih Departement -</option>
                                        <?php foreach ($data_dept as $dept) : ?>
                                            <option value="<?= $dept['id_departement'] ?>" data-departement="<?= $dept['departement'] ?>"><?= $dept['departement'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold mt-2" id="filterBtn_dept2"> Terapkan Filter </button>
                            </div>
                            <div class="chart-container">
                                <div class="chart-wrapper">
                                    <canvas id="monthChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Data Patrol Page -->
            <div id="dataPatrolPage" class="page-content hidden">
                <div class="px-4 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-dark">Data Patrol</h2><button class="text-sm text-blue-600 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1" onclick="toggleFilter('patrolFilter')"> <i class="fas fa-filter"></i> Filter </button>
                    </div><!-- Filter Panel -->
                    <div id="patrolFilter" class="hidden mb-4 card-bg rounded-xl p-4 shadow-md filter-panel">
                        <h3 class="text-sm font-bold text-dark mb-3">Filter Data Patrol</h3>
                        <div class="space-y-3"><!-- Status Filter -->
                            <div><label class="block text-xs font-semibold text-dark mb-2">Status</label>
                                <div class="flex gap-3 flex-wrap"><label class="flex items-center text-xs"> <input type="checkbox" id="filterOpen" checked class="mr-1"> Open </label> <label class="flex items-center text-xs"> <input type="checkbox" id="filterProgress" checked class="mr-1"> In Progress </label> <label class="flex items-center text-xs"> <input type="checkbox" id="filterClose" checked class="mr-1"> Close </label>
                                </div>
                            </div><!-- Area Filter -->
                            <div><label for="filterArea" class="block text-xs font-semibold text-dark mb-1">Area</label> <select id="filterArea" class="w-full text-sm card-bg border border-gray-300 rounded-lg px-3 py-2 text-dark">
                                    <option value="">Semua Area</option>
                                    <option value="Warehouse">Warehouse</option>
                                    <option value="Production">Production</option>
                                    <option value="Office">Office</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Canteen">Canteen</option>
                                </select>
                            </div><!-- Auditor Filter -->
                            <div><label for="filterAuditor" class="block text-xs font-semibold text-dark mb-1">Auditor</label> <select id="filterAuditor" class="w-full text-sm card-bg border border-gray-300 rounded-lg px-3 py-2 text-dark">
                                    <option value="">Semua Auditor</option>
                                    <option value="Ahmad S.">Ahmad Santoso</option>
                                    <option value="Budi T.">Budi Trisno</option>
                                    <option value="Citra R.">Citra Rahayu</option>
                                    <option value="Deni H.">Deni Hermawan</option>
                                </select>
                            </div><!-- Priority Filter -->
                            <div><label for="filterPriority" class="block text-xs font-semibold text-dark mb-1">Prioritas</label> <select id="filterPriority" class="w-full text-sm card-bg border border-gray-300 rounded-lg px-3 py-2 text-dark">
                                    <option value="">Semua Prioritas</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div><!-- Date Range Filter -->
                            <div class="grid grid-cols-2 gap-2">
                                <div><label for="filterDateFrom" class="block text-xs font-semibold text-dark mb-1">Dari Tanggal</label> <input type="date" id="filterDateFrom" class="w-full text-xs card-bg border border-gray-300 rounded-lg px-3 py-2 text-dark">
                                </div>
                                <div><label for="filterDateTo" class="block text-xs font-semibold text-dark mb-1">Sampai Tanggal</label> <input type="date" id="filterDateTo" class="w-full text-xs card-bg border border-gray-300 rounded-lg px-3 py-2 text-dark">
                                </div>
                            </div><!-- Search Box -->
                            <div><label for="filterSearch" class="block text-xs font-semibold text-dark mb-1">Cari Temuan</label> <input type="text" id="filterSearch" placeholder="Ketik untuk mencari..." class="w-full text-sm card-bg border border-gray-300 rounded-lg px-3 py-2 text-dark">
                            </div><!-- Filter Buttons -->
                            <div class="flex gap-2 pt-2"><button onclick="resetPatrolFilter()" class="flex-1 border border-gray-300 text-dark rounded-lg py-2 text-sm font-semibold hover:bg-gray-50"> Reset </button> <button onclick="applyPatrolFilter()" class="flex-1 btn-primary text-white rounded-lg py-2 text-sm font-semibold"> Terapkan </button>
                            </div>
                        </div>
                    </div><!-- Patrol Cards -->
                    <div class="space-y-3" id="patrolCardsContainer"><!-- Card 1 -->
                        <div class="card-bg rounded-xl p-4 shadow-md">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2"><i class="fas fa-calendar text-blue-600 text-sm"></i> <span class="text-sm font-semibold text-dark">15 Januari 2024</span>
                                    </div>
                                    <h3 class="text-base font-bold text-dark mb-2">Kebersihan area kurang</h3>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray"><i class="fas fa-map-marker-alt w-4"></i> Warehouse</p>
                                        <p class="text-sm text-gray"><i class="fas fa-user-tie w-4"></i> Ahmad S.</p>
                                    </div>
                                </div><span class="status-badge status-open">Open</span>
                            </div><button onclick="showDetail(1)" class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold"> <i class="fas fa-info-circle mr-1"></i> Lihat Detail </button>
                        </div><!-- Card 2 -->
                        <div class="card-bg rounded-xl p-4 shadow-md">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2"><i class="fas fa-calendar text-blue-600 text-sm"></i> <span class="text-sm font-semibold text-dark">14 Januari 2024</span>
                                    </div>
                                    <h3 class="text-base font-bold text-dark mb-2">SOP tidak dipatuhi</h3>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray"><i class="fas fa-map-marker-alt w-4"></i> Production</p>
                                        <p class="text-sm text-gray"><i class="fas fa-user-tie w-4"></i> Budi T.</p>
                                    </div>
                                </div><span class="status-badge status-progress">In Progress</span>
                            </div><button onclick="showDetail(2)" class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold"> <i class="fas fa-info-circle mr-1"></i> Lihat Detail </button>
                        </div><!-- Card 3 -->
                        <div class="card-bg rounded-xl p-4 shadow-md">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2"><i class="fas fa-calendar text-blue-600 text-sm"></i> <span class="text-sm font-semibold text-dark">13 Januari 2024</span>
                                    </div>
                                    <h3 class="text-base font-bold text-dark mb-2">Peralatan rusak</h3>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray"><i class="fas fa-map-marker-alt w-4"></i> Workshop</p>
                                        <p class="text-sm text-gray"><i class="fas fa-user-tie w-4"></i> Citra R.</p>
                                    </div>
                                </div><span class="status-badge status-close">Close</span>
                            </div><button onclick="showDetail(3)" class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold"> <i class="fas fa-info-circle mr-1"></i> Lihat Detail </button>
                        </div><!-- Card 4 -->
                        <div class="card-bg rounded-xl p-4 shadow-md">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2"><i class="fas fa-calendar text-blue-600 text-sm"></i> <span class="text-sm font-semibold text-dark">12 Januari 2024</span>
                                    </div>
                                    <h3 class="text-base font-bold text-dark mb-2">APAR expired</h3>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray"><i class="fas fa-map-marker-alt w-4"></i> Office</p>
                                        <p class="text-sm text-gray"><i class="fas fa-user-tie w-4"></i> Deni H.</p>
                                    </div>
                                </div><span class="status-badge status-open">Open</span>
                            </div><button onclick="showDetail(4)" class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold"> <i class="fas fa-info-circle mr-1"></i> Lihat Detail </button>
                        </div><!-- Card 5 -->
                        <div class="card-bg rounded-xl p-4 shadow-md">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2"><i class="fas fa-calendar text-blue-600 text-sm"></i> <span class="text-sm font-semibold text-dark">11 Januari 2024</span>
                                    </div>
                                    <h3 class="text-base font-bold text-dark mb-2">Kebocoran pipa air</h3>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray"><i class="fas fa-map-marker-alt w-4"></i> Canteen</p>
                                        <p class="text-sm text-gray"><i class="fas fa-user-tie w-4"></i> Ahmad S.</p>
                                    </div>
                                </div><span class="status-badge status-close">Close</span>
                            </div><button onclick="showDetail(5)" class="w-full btn-primary text-white rounded-lg py-2 text-sm font-semibold"> <i class="fas fa-info-circle mr-1"></i> Lihat Detail </button>
                        </div>
                    </div>
                </div>
            </div><!-- Schedule Page -->
            <div id="schedulePage" class="page-content hidden">
                <div class="px-4 py-4">
                    <h2 class="text-lg font-semibold text-dark mb-4">Jadwal Patrol</h2><!-- Calendar -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4"><!-- Calendar Header -->
                        <div class="flex items-center justify-between mb-4"><button id="prevMonth" class="w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center"> <i class="fas fa-chevron-left text-dark"></i> </button>
                            <h3 class="text-lg font-bold text-dark" id="currentMonth">Januari 2024</h3><button id="nextMonth" class="w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center"> <i class="fas fa-chevron-right text-dark"></i> </button>
                        </div><!-- Calendar Grid -->
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div class="text-center text-xs font-bold text-gray py-2">
                                Min
                            </div>
                            <div class="text-center text-xs font-bold text-gray py-2">
                                Sen
                            </div>
                            <div class="text-center text-xs font-bold text-gray py-2">
                                Sel
                            </div>
                            <div class="text-center text-xs font-bold text-gray py-2">
                                Rab
                            </div>
                            <div class="text-center text-xs font-bold text-gray py-2">
                                Kam
                            </div>
                            <div class="text-center text-xs font-bold text-gray py-2">
                                Jum
                            </div>
                            <div class="text-center text-xs font-bold text-gray py-2">
                                Sab
                            </div>
                        </div>
                        <div id="calendarDays" class="grid grid-cols-7 gap-1"><!-- Days will be populated by JavaScript -->
                        </div>
                    </div><!-- Legend -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-sm font-bold text-dark mb-3">Keterangan</h3>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-blue-500"></div><span class="text-xs text-gray">Jadwal Patrol (Planned)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-green-500"></div><span class="text-xs text-gray">Patrol Selesai (Actual)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-yellow-500"></div><span class="text-xs text-gray">Menunggu Pelaksanaan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- UI Elements Page -->
            <div id="uiElementsPage" class="page-content hidden">
                <div class="px-4 py-4">
                    <h2 class="text-lg font-semibold text-dark mb-4">UI Elements</h2><!-- General Colors Section -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-palette text-blue-600"></i> General Colors</h3>
                        <div class="space-y-3"><!-- Primary Colors -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Primary Colors</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-blue-600"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Blue</p><code class="text-xs text-gray">bg-blue-600</code>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-red-600"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Red</p><code class="text-xs text-gray">bg-red-600</code>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-green-600"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Green</p><code class="text-xs text-gray">bg-green-600</code>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-yellow-500"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Yellow</p><code class="text-xs text-gray">bg-yellow-500</code>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- Gray Scale -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Gray Scale</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-gray-900 border"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Gray 900</p><code class="text-xs text-gray">bg-gray-900</code>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-gray-600"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Gray 600</p><code class="text-xs text-gray">bg-gray-600</code>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-gray-300 border"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Gray 300</p><code class="text-xs text-gray">bg-gray-300</code>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 border"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-dark">Gray 100</p><code class="text-xs text-gray">bg-gray-100</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- Alerts Section -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-bell text-blue-600"></i> Alerts</h3>
                        <div class="space-y-3"><!-- Success Alert -->
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-start gap-3"><i class="fas fa-check-circle text-lg mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-sm">Success Alert</p>
                                    <p class="text-xs">Operation completed successfully!</p>
                                </div>
                            </div><!-- Info Alert -->
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg flex items-start gap-3"><i class="fas fa-info-circle text-lg mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-sm">Info Alert</p>
                                    <p class="text-xs">Here is some important information.</p>
                                </div>
                            </div><!-- Warning Alert -->
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg flex items-start gap-3"><i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-sm">Warning Alert</p>
                                    <p class="text-xs">Please proceed with caution.</p>
                                </div>
                            </div><!-- Error Alert -->
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-start gap-3"><i class="fas fa-times-circle text-lg mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-sm">Error Alert</p>
                                    <p class="text-xs">Something went wrong!</p>
                                </div>
                            </div>
                        </div>
                    </div><!-- Icons Section -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-icons text-blue-600"></i> Icons List</h3>
                        <div class="grid grid-cols-4 gap-4">
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-home text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-home</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-user text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-user</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-cog text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-cog</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-bell text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-bell</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-search text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-search</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-heart text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-heart</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-star text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-star</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-calendar text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-calendar</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-envelope text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-envelope</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-phone text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-phone</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-camera text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-camera</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-file text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-file</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-download text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-download</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-upload text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-upload</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-trash text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-trash</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-edit text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-edit</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-check text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-check</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-times text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-times</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-plus text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-plus</code>
                            </div>
                            <div class="flex flex-col items-center gap-2"><i class="fas fa-minus text-2xl text-blue-600"></i> <code class="text-xs text-gray text-center">fa-minus</code>
                            </div>
                        </div>
                    </div><!-- Buttons Section -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-mouse-pointer text-blue-600"></i> Buttons</h3>
                        <div class="space-y-3"><!-- Solid Buttons -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Solid Buttons</p>
                                <div class="flex flex-wrap gap-2"><button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700"> Primary </button> <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700"> Success </button> <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700"> Danger </button> <button class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700"> Secondary </button>
                                </div>
                            </div><!-- Outline Buttons -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Outline Buttons</p>
                                <div class="flex flex-wrap gap-2"><button class="border-2 border-blue-600 text-blue-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50"> Primary </button> <button class="border-2 border-green-600 text-green-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-50"> Success </button> <button class="border-2 border-red-600 text-red-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-50"> Danger </button>
                                </div>
                            </div><!-- Icon Buttons -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Icon Buttons</p>
                                <div class="flex flex-wrap gap-2"><button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 flex items-center gap-2"> <i class="fas fa-download"></i> Download </button> <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 flex items-center gap-2"> <i class="fas fa-check"></i> Approve </button> <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 flex items-center gap-2"> <i class="fas fa-trash"></i> Delete </button>
                                </div>
                            </div><!-- Button Sizes -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Button Sizes</p>
                                <div class="flex flex-wrap items-center gap-2"><button class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-semibold hover:bg-blue-700"> Small </button> <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700"> Medium </button> <button class="bg-blue-600 text-white px-6 py-3 rounded-lg text-base font-semibold hover:bg-blue-700"> Large </button>
                                </div>
                            </div>
                        </div>
                    </div><!-- Form Elements Section -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-edit text-blue-600"></i> Form Elements with Plugins</h3>
                        <form class="space-y-4" onsubmit="event.preventDefault(); showFormAlert();"><!-- Text Input -->
                            <div><label for="textInput" class="block text-sm font-semibold text-dark mb-1">Text Input</label> <input type="text" id="textInput" placeholder="Enter text here..." class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm focus:border-blue-500 focus:outline-none">
                            </div><!-- Email Input -->
                            <div><label for="emailInput" class="block text-sm font-semibold text-dark mb-1">Email Input</label> <input type="email" id="emailInput" placeholder="example@email.com" class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm focus:border-blue-500 focus:outline-none">
                            </div><!-- Date Picker with Flatpickr -->
                            <div><label for="datePickerInput" class="block text-sm font-semibold text-dark mb-1"> <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Date Picker (Flatpickr) </label> <input type="text" id="datePickerInput" placeholder="Select a date..." class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm focus:border-blue-500 focus:outline-none">
                            </div><!-- Date Range Picker -->
                            <div><label for="dateRangeInput" class="block text-sm font-semibold text-dark mb-1"> <i class="fas fa-calendar-week text-blue-600 mr-1"></i> Date Range Picker </label> <input type="text" id="dateRangeInput" placeholder="Select date range..." class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm focus:border-blue-500 focus:outline-none">
                            </div><!-- Select Dropdown with Choices.js -->
                            <div><label for="selectInput" class="block text-sm font-semibold text-dark mb-1"> <i class="fas fa-list text-blue-600 mr-1"></i> Searchable Select (Choices.js) </label> <select id="selectInput" class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm focus:border-blue-500 focus:outline-none">
                                    <option value="">Choose an option...</option>
                                    <option value="warehouse">Warehouse A</option>
                                    <option value="production">Production Line 1</option>
                                    <option value="office">Office Area</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="canteen">Canteen</option>
                                </select>
                            </div><!-- Multi Select with Choices.js -->
                            <div><label for="multiSelectInput" class="block text-sm font-semibold text-dark mb-1"> <i class="fas fa-tags text-blue-600 mr-1"></i> Multi Select (Choices.js) </label> <select id="multiSelectInput" multiple class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm">
                                    <option value="safety">Safety Issues</option>
                                    <option value="cleanliness">Cleanliness</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="sop">SOP Violation</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div><!-- Textarea -->
                            <div><label for="textareaInput" class="block text-sm font-semibold text-dark mb-1">Textarea</label> <textarea id="textareaInput" rows="4" placeholder="Enter your message..." class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm focus:border-blue-500 focus:outline-none resize-none"></textarea>
                            </div><!-- Checkbox -->
                            <div><label class="flex items-center gap-2"> <input type="checkbox" class="w-4 h-4 text-blue-600"> <span class="text-sm text-dark">I agree to the terms and conditions</span> </label>
                            </div><!-- Radio Buttons -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Priority Level</p>
                                <div class="space-y-2"><label class="flex items-center gap-2"> <input type="radio" name="radio" value="high" class="w-4 h-4 text-blue-600"> <span class="text-sm text-dark">High Priority</span> </label> <label class="flex items-center gap-2"> <input type="radio" name="radio" value="medium" class="w-4 h-4 text-blue-600" checked> <span class="text-sm text-dark">Medium Priority</span> </label> <label class="flex items-center gap-2"> <input type="radio" name="radio" value="low" class="w-4 h-4 text-blue-600"> <span class="text-sm text-dark">Low Priority</span> </label>
                                </div>
                            </div><!-- Submit Button --> <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700"> <i class="fas fa-paper-plane mr-1"></i> Submit Form </button>
                        </form>
                    </div><!-- Glide.js Slider Demo -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4 plugin-card">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-images text-blue-600"></i> Touch Slider (Glide.js)</h3>
                        <div class="glide rounded-lg overflow-hidden">
                            <div class="glide__track" data-glide-el="track">
                                <ul class="glide__slides">
                                    <li class="glide__slide">
                                        <div class="bg-gradient-to-br from-blue-500 to-blue-700 h-48 flex items-center justify-center text-white rounded-lg">
                                            <div class="text-center"><i class="fas fa-shield-alt text-5xl mb-3"></i>
                                                <p class="text-xl font-bold">Safety First</p>
                                                <p class="text-sm opacity-90">Keselamatan adalah prioritas</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="glide__slide">
                                        <div class="bg-gradient-to-br from-green-500 to-green-700 h-48 flex items-center justify-center text-white rounded-lg">
                                            <div class="text-center"><i class="fas fa-broom text-5xl mb-3"></i>
                                                <p class="text-xl font-bold">Cleanliness</p>
                                                <p class="text-sm opacity-90">Kebersihan lingkungan kerja</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="glide__slide">
                                        <div class="bg-gradient-to-br from-orange-500 to-orange-700 h-48 flex items-center justify-center text-white rounded-lg">
                                            <div class="text-center"><i class="fas fa-tools text-5xl mb-3"></i>
                                                <p class="text-xl font-bold">Maintenance</p>
                                                <p class="text-sm opacity-90">Perawatan peralatan rutin</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="glide__slide">
                                        <div class="bg-gradient-to-br from-purple-500 to-purple-700 h-48 flex items-center justify-center text-white rounded-lg">
                                            <div class="text-center"><i class="fas fa-clipboard-check text-5xl mb-3"></i>
                                                <p class="text-xl font-bold">SOP Compliance</p>
                                                <p class="text-sm opacity-90">Kepatuhan terhadap prosedur</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="glide__slide">
                                        <div class="bg-gradient-to-br from-pink-500 to-pink-700 h-48 flex items-center justify-center text-white rounded-lg">
                                            <div class="text-center"><i class="fas fa-users text-5xl mb-3"></i>
                                                <p class="text-xl font-bold">Teamwork</p>
                                                <p class="text-sm opacity-90">Kerjasama tim yang solid</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="glide__arrows" data-glide-el="controls"><button class="glide__arrow glide__arrow--left" data-glide-dir="<"> <i class="fas fa-chevron-left"></i> </button> <button class="glide__arrow glide__arrow--right" data-glide-dir=">"> <i class="fas fa-chevron-right"></i> </button>
                            </div>
                            <div class="glide__bullets" data-glide-el="controls[nav]"><button class="glide__bullet" data-glide-dir="=0"></button> <button class="glide__bullet" data-glide-dir="=1"></button> <button class="glide__bullet" data-glide-dir="=2"></button> <button class="glide__bullet" data-glide-dir="=3"></button> <button class="glide__bullet" data-glide-dir="=4"></button>
                            </div>
                        </div>
                        <p class="text-xs text-gray mt-3 text-center"><i class="fas fa-hand-pointer mr-1"></i> Swipe, click arrows, or use bullets to navigate</p>
                    </div><!-- Touch Gesture Demo -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4 plugin-card">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-hand-paper text-blue-600"></i> Touch Gestures (Hammer.js)</h3>
                        <div id="gestureDemo" class="gesture-demo bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg p-8 text-center text-white cursor-move">
                            <div class="gesture-feedback" id="gestureFeedback"><i class="fas fa-hand-paper text-5xl mb-3"></i>
                                <p class="text-xl font-bold mb-2">Try Touch Gestures!</p>
                                <p class="text-sm opacity-90">Tap, Double Tap, Swipe, or Press &amp; Hold</p>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div class="card-bg border border-gray-300 rounded p-2 text-center"><i class="fas fa-hand-pointer text-blue-600"></i>
                                <p class="text-dark font-semibold mt-1">Tap</p>
                            </div>
                            <div class="card-bg border border-gray-300 rounded p-2 text-center"><i class="fas fa-hand-pointer text-blue-600"></i>
                                <p class="text-dark font-semibold mt-1">Double Tap</p>
                            </div>
                            <div class="card-bg border border-gray-300 rounded p-2 text-center"><i class="fas fa-hand-rock text-blue-600"></i>
                                <p class="text-dark font-semibold mt-1">Press</p>
                            </div>
                            <div class="card-bg border border-gray-300 rounded p-2 text-center"><i class="fas fa-arrows-alt text-blue-600"></i>
                                <p class="text-dark font-semibold mt-1">Swipe</p>
                            </div>
                        </div>
                        <div id="gestureLog" class="mt-3 p-3 bg-gray-100 rounded-lg">
                            <p class="text-xs font-semibold text-dark mb-1"><i class="fas fa-terminal mr-1"></i> Gesture Log:</p>
                            <p id="gestureLogText" class="text-xs text-gray">Waiting for gesture...</p>
                        </div>
                    </div><!-- Plugin Information -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-plug text-blue-600"></i> Installed Plugins</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200"><i class="fas fa-calendar-alt text-2xl text-blue-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-dark">Flatpickr</p>
                                    <p class="text-xs text-gray">Lightweight date &amp; time picker with mobile optimization</p>
                                    <div class="flex gap-2 mt-2"><span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Date Picker</span> <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Range</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg border border-green-200"><i class="fas fa-list text-2xl text-green-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-dark">Choices.js</p>
                                    <p class="text-xs text-gray">Configurable select boxes with search, tags &amp; multi-select</p>
                                    <div class="flex gap-2 mt-2"><span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Select</span> <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Search</span> <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Multi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-purple-50 rounded-lg border border-purple-200"><i class="fas fa-images text-2xl text-purple-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-dark">Glide.js</p>
                                    <p class="text-xs text-gray">Lightweight, dependency-free slider with touch &amp; keyboard support</p>
                                    <div class="flex gap-2 mt-2"><span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">Carousel</span> <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">Lightweight</span> <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">Fast</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-orange-50 rounded-lg border border-orange-200"><i class="fas fa-hand-paper text-2xl text-orange-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-dark">Hammer.js</p>
                                    <p class="text-xs text-gray">Touch gesture library: tap, swipe, pinch, pan &amp; rotate</p>
                                    <div class="flex gap-2 mt-2"><span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded">Gestures</span> <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded">Multi-touch</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- PDF Viewer Section -->
                    <div class="card-bg rounded-xl p-4 shadow-md mb-4">
                        <h3 class="text-base font-bold text-dark mb-3 flex items-center gap-2"><i class="fas fa-file-pdf text-blue-600"></i> PDF Viewer</h3>
                        <div class="space-y-3"><!-- File Upload -->
                            <div><label for="pdfUpload" class="block text-sm font-semibold text-dark mb-2">Upload PDF File</label> <input type="file" id="pdfUpload" accept=".pdf" class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="handlePDFUpload(event)">
                            </div><!-- PDF Display Area -->
                            <div id="pdfDisplayArea" class="hidden">
                                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"><i class="fas fa-file-pdf text-6xl text-red-500 mb-4"></i>
                                    <p class="text-sm font-semibold text-dark mb-2" id="pdfFileName">document.pdf</p>
                                    <div class="flex gap-2 justify-center mt-4"><button onclick="viewPDF()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 flex items-center gap-2"> <i class="fas fa-eye"></i> View PDF </button> <button onclick="downloadPDF()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 flex items-center gap-2"> <i class="fas fa-download"></i> Download </button>
                                    </div>
                                </div>
                            </div><!-- Sample PDFs -->
                            <div>
                                <p class="text-sm font-semibold text-dark mb-2">Sample Documents</p>
                                <div class="space-y-2">
                                    <div class="card-bg border border-gray-300 rounded-lg p-3 flex items-center justify-between">
                                        <div class="flex items-center gap-3"><i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                            <div>
                                                <p class="text-sm font-semibold text-dark">Audit Report 2024.pdf</p>
                                                <p class="text-xs text-gray">2.4 MB</p>
                                            </div>
                                        </div><button onclick="showPDFInfo('Audit Report 2024')" class="text-blue-600 hover:text-blue-700"> <i class="fas fa-eye"></i> </button>
                                    </div>
                                    <div class="card-bg border border-gray-300 rounded-lg p-3 flex items-center justify-between">
                                        <div class="flex items-center gap-3"><i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                            <div>
                                                <p class="text-sm font-semibold text-dark">Safety Guidelines.pdf</p>
                                                <p class="text-xs text-gray">1.8 MB</p>
                                            </div>
                                        </div><button onclick="showPDFInfo('Safety Guidelines')" class="text-blue-600 hover:text-blue-700"> <i class="fas fa-eye"></i> </button>
                                    </div>
                                    <div class="card-bg border border-gray-300 rounded-lg p-3 flex items-center justify-between">
                                        <div class="flex items-center gap-3"><i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                            <div>
                                                <p class="text-sm font-semibold text-dark">SOP Document.pdf</p>
                                                <p class="text-xs text-gray">3.1 MB</p>
                                            </div>
                                        </div><button onclick="showPDFInfo('SOP Document')" class="text-blue-600 hover:text-blue-700"> <i class="fas fa-eye"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Profile Page -->
            <div id="profilePage" class="page-content hidden">
                <div class="px-4 py-4">
                    <h2 class="text-lg font-semibold text-dark mb-4">Profile</h2>
                    <div class="card-bg rounded-xl p-6 shadow-md mb-4">
                        <div class="flex flex-col items-center mb-6">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-3xl font-bold mb-3">
                                JD
                            </div>
                            <h3 class="text-xl font-bold text-dark">John Doe</h3>
                            <p class="text-sm text-gray">john.doe@company.com</p>
                        </div>
                        <div class="space-y-4">
                            <div><label class="block text-sm font-semibold text-dark mb-2">Role</label> <select id="roleSelect" class="w-full card-bg border border-gray-300 rounded-lg px-4 py-2 text-dark">
                                    <option value="admin">Admin</option>
                                    <option value="auditor">Auditor</option>
                                    <option value="auditee">Auditee</option>
                                </select>
                            </div>
                            <div class="pt-4 border-t"><button id="logoutBtn" class="w-full btn-primary text-white rounded-lg py-3 font-semibold flex items-center justify-center gap-2"> <i class="fas fa-sign-out-alt"></i> Logout </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Bottom Navigation -->
        <div class="fixed bottom-0 left-0 right-0 navbar-bg shadow-lg">
            <div class="flex justify-between items-center px-2 py-2">
                <button class="nav-item active flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="dashboard">
                    <i class="fas fa-home text-lg"></i> <span class="text-xs whitespace-nowrap">Dashboard</span>
                </button>
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="dataPatrol">
                    <i class="fas fa-clipboard-list text-lg"></i> <span class="text-xs whitespace-nowrap">Data</span>
                </button>
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="schedule">
                    <i class="fas fa-calendar text-lg"></i> <span class="text-xs whitespace-nowrap">Schedule</span>
                </button>
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="uiElements">
                    <i class="fas fa-palette text-lg"></i> <span class="text-xs whitespace-nowrap">UI</span>
                </button>
                <button class="nav-item flex flex-col items-center gap-1 px-2 py-2 flex-1" data-page="profile">
                    <i class="fas fa-user text-lg"></i> <span class="text-xs whitespace-nowrap">Profile</span>
                </button>
            </div>
        </div><!-- Logout Modal -->
        <div id="logoutModal" class="modal-overlay hidden">
            <div class="modal-content">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4"><i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-2">Konfirmasi Logout</h3>
                    <p class="text-gray mb-6">Apakah Anda yakin ingin keluar dari aplikasi?</p>
                    <div class="flex gap-3 w-full"><button id="cancelLogout" class="flex-1 border border-gray-300 text-dark rounded-lg py-2 font-semibold"> Batal </button> <button id="confirmLogout" class="flex-1 bg-red-600 text-white rounded-lg py-2 font-semibold hover:bg-red-700"> Logout </button>
                    </div>
                </div>
            </div>
        </div><!-- Detail Patrol Modal -->
        <div id="detailModal" class="modal-overlay hidden">
            <div class="modal-content" style="max-width: 500px; max-height: 90%; overflow-y: auto;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-dark">Detail Temuan</h3><button onclick="closeDetail()" class="text-gray-500 hover:text-gray-700"> <i class="fas fa-times text-xl"></i> </button>
                </div>
                <div id="detailContent" class="space-y-4"><!-- Content will be populated by JavaScript -->
                </div>
                <div class="flex gap-3 mt-6"><button onclick="closeDetail()" class="flex-1 border border-gray-300 text-dark rounded-lg py-2 font-semibold"> Tutup </button> <button class="flex-1 btn-primary text-white rounded-lg py-2 font-semibold"> <i class="fas fa-edit mr-1"></i> Edit </button>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
        const patrolData = {
            1: {
                tanggal: "15 Januari 2024",
                temuan: "Kebersihan area kurang",
                area: "Warehouse",
                auditor: "Ahmad Santoso",
                auditee: "Tim Produksi A",
                deskripsi: "Area warehouse bagian utara terlihat kotor dengan banyak debu menumpuk di rak penyimpanan. Lantai belum dibersihkan dan terdapat tumpukan kardus bekas di sudut ruangan.",
                tindakan: "Lakukan pembersihan menyeluruh area warehouse, buang kardus bekas, dan jadwalkan cleaning rutin setiap hari",
                deadline: "20 Januari 2024",
                prioritas: "Medium",
                status: "Open",
                timeline: [{
                        event: "Patrol",
                        due: "15 Jan 2024",
                        actual: "15 Jan 2024 14:30",
                        status: "complete"
                    },
                    {
                        event: "Menjawab Temuan",
                        due: "17 Jan 2024",
                        actual: "-",
                        status: "pending"
                    },
                    {
                        event: "Mengumpulkan Evidence",
                        due: "20 Jan 2024",
                        actual: "-",
                        status: "pending"
                    },
                    {
                        event: "Status Auditor",
                        actual: "Open",
                        status: "open",
                        keterangan: "Menunggu response dari auditee untuk tindakan perbaikan"
                    }
                ]
            },
            2: {
                tanggal: "14 Januari 2024",
                temuan: "SOP tidak dipatuhi",
                area: "Production",
                auditor: "Budi Trisno",
                auditee: "Quality Control Department",
                deskripsi: "Beberapa operator tidak mengikuti prosedur standar dalam proses quality check. Dokumentasi tidak lengkap dan ada beberapa step yang dilewati.",
                tindakan: "Training ulang SOP kepada semua operator, perkuat monitoring supervisor, dan update checklist quality control",
                deadline: "18 Januari 2024",
                prioritas: "High",
                status: "In Progress",
                timeline: [{
                        event: "Patrol",
                        due: "14 Jan 2024",
                        actual: "14 Jan 2024 09:15",
                        status: "complete"
                    },
                    {
                        event: "Menjawab Temuan",
                        due: "15 Jan 2024",
                        actual: "15 Jan 2024 11:00",
                        status: "complete"
                    },
                    {
                        event: "Mengumpulkan Evidence",
                        due: "18 Jan 2024",
                        actual: "-",
                        status: "progress"
                    },
                    {
                        event: "Status Auditor",
                        actual: "Open",
                        status: "open",
                        keterangan: "Evidence belum lengkap, perlu foto dokumentasi training dan update checklist"
                    }
                ]
            },
            3: {
                tanggal: "13 Januari 2024",
                temuan: "Peralatan rusak",
                area: "Workshop",
                auditor: "Citra Rahayu",
                auditee: "Maintenance Department",
                deskripsi: "Mesin gerinda di workshop rusak dan tidak dapat digunakan. Kabel power ada yang terkelupas dan berbahaya.",
                tindakan: "Perbaikan mesin gerinda telah selesai dilakukan, kabel power diganti baru, dan telah dilakukan testing keamanan",
                deadline: "13 Januari 2024",
                prioritas: "High",
                status: "Close",
                timeline: [{
                        event: "Patrol",
                        due: "13 Jan 2024",
                        actual: "13 Jan 2024 10:00",
                        status: "complete"
                    },
                    {
                        event: "Menjawab Temuan",
                        due: "13 Jan 2024",
                        actual: "13 Jan 2024 13:30",
                        status: "complete"
                    },
                    {
                        event: "Mengumpulkan Evidence",
                        due: "13 Jan 2024",
                        actual: "13 Jan 2024 16:45",
                        status: "complete"
                    },
                    {
                        event: "Status Auditor",
                        actual: "Close",
                        status: "close",
                        tanggalClose: "13 Jan 2024 17:00"
                    }
                ]
            },
            4: {
                tanggal: "12 Januari 2024",
                temuan: "APAR expired",
                area: "Office",
                auditor: "Deni Hermawan",
                auditee: "HSE Team",
                deskripsi: "APAR di lantai 2 area office sudah melewati masa expired. Terakhir di-service 2 tahun yang lalu dan perlu segera diganti atau di-refill.",
                tindakan: "Koordinasi dengan vendor APAR untuk service dan refill unit yang expired",
                deadline: "17 Januari 2024",
                prioritas: "High",
                status: "Open",
                timeline: [{
                        event: "Patrol",
                        due: "12 Jan 2024",
                        actual: "12 Jan 2024 08:45",
                        status: "complete"
                    },
                    {
                        event: "Menjawab Temuan",
                        due: "14 Jan 2024",
                        actual: "14 Jan 2024 10:15",
                        status: "complete"
                    },
                    {
                        event: "Mengumpulkan Evidence",
                        due: "17 Jan 2024",
                        actual: "-",
                        status: "progress"
                    },
                    {
                        event: "Status Auditor",
                        actual: "Open",
                        status: "open",
                        keterangan: "Vendor sudah dihubungi tapi belum ada konfirmasi jadwal service. Perlu follow up segera"
                    }
                ]
            },
            5: {
                tanggal: "11 Januari 2024",
                temuan: "Kebocoran pipa air",
                area: "Canteen",
                auditor: "Ahmad Santoso",
                auditee: "Facility Management",
                deskripsi: "Terdapat kebocoran pada pipa air di area wastafel canteen. Air menetes terus menerus dan lantai menjadi basah.",
                tindakan: "Pipa telah diperbaiki dan diganti dengan yang baru. Lantai sudah dibersihkan dan tidak ada lagi kebocoran.",
                deadline: "11 Januari 2024",
                prioritas: "Medium",
                status: "Close",
                timeline: [{
                        event: "Patrol",
                        due: "11 Jan 2024",
                        actual: "11 Jan 2024 07:30",
                        status: "complete"
                    },
                    {
                        event: "Menjawab Temuan",
                        due: "11 Jan 2024",
                        actual: "11 Jan 2024 09:00",
                        status: "complete"
                    },
                    {
                        event: "Mengumpulkan Evidence",
                        due: "11 Jan 2024",
                        actual: "11 Jan 2024 14:20",
                        status: "complete"
                    },
                    {
                        event: "Status Auditor",
                        actual: "Close",
                        status: "close",
                        tanggalClose: "11 Jan 2024 15:00"
                    }
                ]
            }
        };

        // Data jadwal patrol untuk kalender
        const scheduleData = {
            "2024-01-08": {
                planned: "08 Januari 2024",
                actual: "08 Januari 2024 10:30",
                area: "Warehouse A & B",
                auditor: ["Ahmad Santoso", "Budi Trisno"],
                status: "completed"
            },
            "2024-01-11": {
                planned: "11 Januari 2024",
                actual: "11 Januari 2024 07:30",
                area: "Canteen",
                auditor: ["Ahmad Santoso"],
                status: "completed"
            },
            "2024-01-12": {
                planned: "12 Januari 2024",
                actual: "12 Januari 2024 08:45",
                area: "Office Area",
                auditor: ["Deni Hermawan"],
                status: "completed"
            },
            "2024-01-13": {
                planned: "13 Januari 2024",
                actual: "13 Januari 2024 10:00",
                area: "Workshop",
                auditor: ["Citra Rahayu"],
                status: "completed"
            },
            "2024-01-14": {
                planned: "14 Januari 2024",
                actual: "14 Januari 2024 09:15",
                area: "Production Line 1-3",
                auditor: ["Budi Trisno"],
                status: "completed"
            },
            "2024-01-15": {
                planned: "15 Januari 2024",
                actual: "15 Januari 2024 14:30",
                area: "Warehouse Storage",
                auditor: ["Ahmad Santoso", "Budi Trisno"],
                status: "completed"
            },
            "2024-01-18": {
                planned: "18 Januari 2024",
                actual: null,
                area: "Production Line 4-6",
                auditor: ["Citra Rahayu", "Deni Hermawan"],
                status: "scheduled"
            },
            "2024-01-22": {
                planned: "22 Januari 2024",
                actual: null,
                area: "Warehouse C & D",
                auditor: ["Ahmad Santoso"],
                status: "scheduled"
            },
            "2024-01-25": {
                planned: "25 Januari 2024",
                actual: null,
                area: "Office & Admin Area",
                auditor: ["Deni Hermawan", "Budi Trisno"],
                status: "scheduled"
            },
            "2024-01-29": {
                planned: "29 Januari 2024",
                actual: null,
                area: "All Area Review",
                auditor: ["Ahmad Santoso", "Citra Rahayu", "Budi Trisno"],
                status: "scheduled"
            },
            "2024-01-02": {
                planned: "02 Januari 2024",
                actual: null,
                area: "Production Area",
                auditor: ["Budi Trisno"],
                status: "scheduled"
            },
            "2024-01-05": {
                planned: "05 Januari 2024",
                actual: null,
                area: "Warehouse & Storage",
                auditor: ["Ahmad Santoso", "Deni Hermawan"],
                status: "scheduled"
            },
            "2024-01-16": {
                planned: "16 Januari 2024",
                actual: null,
                area: "Office & Meeting Room",
                auditor: ["Citra Rahayu"],
                status: "scheduled"
            },
            "2024-01-19": {
                planned: "19 Januari 2024",
                actual: null,
                area: "Workshop & Maintenance",
                auditor: ["Deni Hermawan", "Budi Trisno"],
                status: "scheduled"
            },
            "2024-01-23": {
                planned: "23 Januari 2024",
                actual: null,
                area: "Canteen & Rest Area",
                auditor: ["Ahmad Santoso"],
                status: "scheduled"
            },
            "2024-01-26": {
                planned: "26 Januari 2024",
                actual: null,
                area: "Parking & External Area",
                auditor: ["Citra Rahayu", "Deni Hermawan"],
                status: "scheduled"
            },
            "2024-01-30": {
                planned: "30 Januari 2024",
                actual: null,
                area: "Emergency Exit & Safety Equipment",
                auditor: ["Budi Trisno", "Ahmad Santoso"],
                status: "scheduled"
            }
        };

        async function onConfigChange(newConfig) {
            config = {
                ...config,
                ...newConfig
            };

            document.getElementById('appTitle').textContent = config.app_title || defaultConfig.app_title;
            document.getElementById('companyName').textContent = config.company_name || defaultConfig.company_name;
            document.getElementById('dashboardSubtitle').textContent = config.dashboard_subtitle || defaultConfig.dashboard_subtitle;
        }

        // Show Detail Modal
        function showDetail(id) {
            const data = patrolData[id];
            if (!data) return;

            const statusClass = data.status === 'Open' ? 'status-open' :
                data.status === 'In Progress' ? 'status-progress' : 'status-close';

            const priorityColor = data.prioritas === 'High' ? 'text-red-600' :
                data.prioritas === 'Medium' ? 'text-yellow-600' : 'text-green-600';

            // Generate timeline HTML
            let timelineHTML = '<div class="relative">';

            // Add continuous vertical line
            timelineHTML += '<div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-gray-300"></div>';

            data.timeline.forEach((item, index) => {
                let statusIcon = '';
                let statusColor = '';

                if (item.status === 'complete') {
                    statusIcon = '<div class="w-6 h-6 rounded-full bg-green-600 flex items-center justify-center"><i class="fas fa-check text-white text-xs"></i></div>';
                    statusColor = 'text-green-600';
                } else if (item.status === 'progress') {
                    statusIcon = '<div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center"><i class="fas fa-spinner fa-spin text-white text-xs"></i></div>';
                    statusColor = 'text-blue-600';
                } else if (item.status === 'pending') {
                    statusIcon = '<div class="w-6 h-6 rounded-full bg-gray-300 border-2 border-white"></div>';
                    statusColor = 'text-gray-400';
                } else if (item.status === 'open') {
                    statusIcon = '<div class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center"><i class="fas fa-exclamation text-white text-xs"></i></div>';
                    statusColor = 'text-yellow-600';
                } else if (item.status === 'close') {
                    statusIcon = '<div class="w-6 h-6 rounded-full bg-green-600 flex items-center justify-center"><i class="fas fa-check text-white text-xs"></i></div>';
                    statusColor = 'text-green-600';
                }

                const marginBottom = index < data.timeline.length - 1 ? 'mb-6' : '';

                timelineHTML += `
          <div class="flex items-start gap-3 relative ${marginBottom}">
            <div class="relative z-10">${statusIcon}</div>
            <div class="flex-1 pt-0.5">
              <p class="text-sm font-bold text-dark">${item.event}</p>
              <div class="mt-1 space-y-1">
                ${item.due ? `<p class="text-xs text-gray"><i class="fas fa-calendar text-blue-600 w-4"></i> Target: ${item.due}</p>` : ''}
                ${item.actual && item.actual !== '-' ? `<p class="text-xs ${statusColor}"><i class="fas fa-check text-green-600 w-4"></i> Actual: ${item.actual}</p>` : ''}
                ${item.actual === '-' ? `<p class="text-xs text-gray"><i class="far fa-clock text-gray-400 w-4"></i> Belum dilaksanakan</p>` : ''}
                ${item.keterangan ? `<p class="text-xs text-gray mt-2 p-2 bg-yellow-50 rounded border-l-2 border-yellow-400"><i class="fas fa-info-circle text-yellow-600"></i> ${item.keterangan}</p>` : ''}
                ${item.tanggalClose ? `<p class="text-xs text-green-600 font-semibold mt-2 p-2 bg-green-50 rounded border-l-2 border-green-400"><i class="fas fa-calendar-check"></i> Ditutup: ${item.tanggalClose}</p>` : ''}
              </div>
            </div>
          </div>
        `;
            });
            timelineHTML += '</div>';

            const detailContent = document.getElementById('detailContent');
            detailContent.innerHTML = `
        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Status</p>
          <span class="status-badge ${statusClass}">${data.status}</span>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Tanggal</p>
          <p class="text-sm font-semibold text-dark">${data.tanggal}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Temuan</p>
          <p class="text-sm font-semibold text-dark">${data.temuan}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Area</p>
          <p class="text-sm font-semibold text-dark">${data.area}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Auditor</p>
          <p class="text-sm font-semibold text-dark">${data.auditor}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Auditee</p>
          <p class="text-sm font-semibold text-dark">${data.auditee}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Deskripsi</p>
          <p class="text-sm text-dark leading-relaxed">${data.deskripsi}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Tindakan</p>
          <p class="text-sm text-dark leading-relaxed">${data.tindakan}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Deadline</p>
          <p class="text-sm font-semibold text-dark">${data.deadline}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Prioritas</p>
          <p class="text-sm font-bold ${priorityColor}">${data.prioritas}</p>
        </div>

        <div class="card-bg border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
          <div class="flex items-center gap-2 mb-3">
            <i class="fas fa-project-diagram text-blue-600 text-lg"></i>
            <h4 class="text-sm font-bold text-dark">Timeline Proses</h4>
          </div>
          ${timelineHTML}
        </div>
      `;

            document.getElementById('detailModal').classList.remove('hidden');
        }

        // Close Detail Modal
        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Calendar Functions
        let currentDate = new Date(2024, 0, 1); // Start with January 2024
        const TODAY_REFERENCE = new Date(2024, 0, 15); // Set today as Jan 15, 2024 for demo purposes

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';

            // Empty cells before first day
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'aspect-square';
                calendarDays.appendChild(emptyCell);
            }

            // Days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const scheduleInfo = scheduleData[dateStr];

                const dayCell = document.createElement('div');
                dayCell.className = 'aspect-square flex flex-col items-center justify-center rounded-lg text-sm font-semibold cursor-pointer transition-all';

                // Check if this day is today (using reference date)
                const cellDate = new Date(year, month, day);
                cellDate.setHours(0, 0, 0, 0);

                const todayRef = new Date(TODAY_REFERENCE);
                todayRef.setHours(0, 0, 0, 0);

                const isToday = cellDate.getTime() === todayRef.getTime();

                if (isToday) {
                    dayCell.classList.add('border-2', 'border-blue-500');
                }

                if (scheduleInfo) {
                    if (scheduleInfo.status === 'completed') {
                        // Patrol sudah selesai - HIJAU
                        dayCell.classList.add('bg-green-500', 'text-white', 'hover:bg-green-600');
                    } else if (scheduleInfo.status === 'scheduled') {
                        // Check if date has passed
                        if (cellDate.getTime() < todayRef.getTime()) {
                            // Jadwal sudah lewat tapi belum dilaksanakan - KUNING
                            dayCell.classList.add('bg-yellow-500', 'text-white', 'hover:bg-yellow-600');
                        } else {
                            // Jadwal yang akan datang - BIRU
                            dayCell.classList.add('bg-blue-500', 'text-white', 'hover:bg-blue-600');
                        }
                    }

                    dayCell.addEventListener('click', () => showScheduleDetail(dateStr, scheduleInfo));
                } else {
                    dayCell.classList.add('text-dark', 'hover:bg-gray-100');
                }

                dayCell.innerHTML = `
          <span class="text-base">${day}</span>
          ${scheduleInfo ? '<i class="fas fa-clipboard-check text-xs mt-1"></i>' : ''}
        `;

                calendarDays.appendChild(dayCell);
            }
        }
        $('.startaudit').on('click', function() {
            window.location.href = "<?= base_url('temuan_patrol/start_audit') ?>";
        });

        function showScheduleDetail(dateStr, scheduleInfo) {
            const statusBadge = scheduleInfo.status === 'completed' ?
                '<span class="status-badge status-close">Selesai</span>' :
                '<span class="status-badge status-progress">Terjadwal</span>';

            const actualInfo = scheduleInfo.actual ?
                `<div class="card-bg border-2 border-green-200 rounded-lg p-3 bg-green-50">
            <p class="text-xs text-gray mb-1">Actual Tanggal Patrol</p>
            <p class="text-sm font-bold text-green-700"><i class="fas fa-check-circle mr-1"></i>${scheduleInfo.actual}</p>
          </div>` :
                `<div class="card-bg border-2 border-yellow-200 rounded-lg p-3 bg-yellow-50">
            <p class="text-xs text-gray mb-1">Actual Tanggal Patrol</p>
            <p class="text-sm font-bold text-yellow-700"><i class="far fa-clock mr-1"></i>Belum dilaksanakan</p>
          </div>`;

            const detailContent = document.getElementById('detailContent');
            detailContent.innerHTML = `
        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Status</p>
          ${statusBadge}
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Tanggal Rencana Patrol</p>
          <p class="text-sm font-semibold text-dark"><i class="fas fa-calendar-alt text-blue-600 mr-1"></i>${scheduleInfo.planned}</p>
        </div>

        ${actualInfo}

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-1">Area Patrol</p>
          <p class="text-sm font-semibold text-dark"><i class="fas fa-map-marker-alt text-blue-600 mr-1"></i>${scheduleInfo.area}</p>
        </div>

        <div class="card-bg border border-gray-200 rounded-lg p-3">
          <p class="text-xs text-gray mb-2">Auditor</p>
          ${scheduleInfo.auditor.map(auditor => `
            <div class="flex items-center gap-2 mb-1">
              <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-user text-blue-600 text-xs"></i>
              </div>
              <span class="text-sm font-semibold text-dark">${auditor}</span>
            </div>
          `).join('')}
        </div>

        ${scheduleInfo.status === 'completed' ? `
          <div class="card-bg border-2 border-green-200 rounded-lg p-4 bg-green-50">
            <div class="flex items-center gap-2 mb-2">
              <i class="fas fa-check-circle text-green-600 text-lg"></i>
              <h4 class="text-sm font-bold text-dark">Patrol Completed</h4>
            </div>
            <p class="text-xs text-gray">Patrol telah selesai dilaksanakan dan laporan sudah tersedia.</p>
          </div>
        ` : `
          <div class="card-bg border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
            <div class="flex items-center gap-2 mb-2">
              <i class="fas fa-calendar-check text-blue-600 text-lg"></i>
              <h4 class="text-sm font-bold text-dark">Scheduled Patrol</h4>
            </div>
            <p class="text-xs text-gray">Patrol dijadwalkan akan dilaksanakan sesuai rencana. Pastikan auditor siap melakukan patrol.</p>
          </div>
        `}
      `;

            document.getElementById('detailModal').classList.remove('hidden');
        }

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            renderCalendar();
        }

        document.getElementById('prevMonth').addEventListener('click', () => changeMonth(-1));
        document.getElementById('nextMonth').addEventListener('click', () => changeMonth(1));

        function initCharts() {
            const textColor = isDarkMode ? '#e2e8f0' : '#1f2937';
            const gridColor = isDarkMode ? '#334155' : '#e5e7eb';
            const primaryColor = isDarkMode ? '#dc2626' : '#3b82f6';

            // Combined Chart
            const combinedCtx = document.getElementById('combinedChart').getContext('2d');
            if (charts.combined) charts.combined.destroy();
            charts.combined = new Chart(combinedCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                            type: 'bar',
                            label: 'Open',
                            data: <?= json_encode($open) ?>,
                            backgroundColor: '#686B6F',
                            borderRadius: 6
                        },
                        {
                            type: 'bar',
                            label: 'In Progress',
                            data: <?= json_encode($progress) ?>,
                            backgroundColor: '#FFC005',
                            borderRadius: 6
                        },
                        {
                            type: 'bar',
                            label: 'Close',
                            data: <?= json_encode($close) ?>,
                            backgroundColor: '#57e26e',
                            borderRadius: 6
                        },
                        {
                            type: 'bar',
                            label: 'Cancel',
                            data: <?= json_encode($cancel) ?>,
                            backgroundColor: '#DF3545',
                            borderRadius: 6
                        },
                        {
                            type: 'line',
                            label: 'Total Temuan',
                            data: <?= json_encode($total) ?>,
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

            // Area Chart
            const areaCtx = document.getElementById('areaChart').getContext('2d');
            if (charts.area) charts.area.destroy();
            charts.area = new Chart(areaCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($areaNames) ?>,
                    datasets: [{
                            label: 'Open',
                            data: <?= json_encode($total_open) ?>,
                            backgroundColor: '#686B6F'
                        },
                        {
                            label: 'In Progress',
                            data: <?= json_encode($total_progress) ?>,
                            backgroundColor: '#FFC005'
                        },
                        {
                            label: 'Close',
                            data: <?= json_encode($total_close) ?>,
                            backgroundColor: '#57e26e'
                        },
                        {
                            label: 'Cancel',
                            data: <?= json_encode($total_cancel) ?>,
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
                                    const percentage = ((context.parsed.y / total) * 100).toFixed(1);
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

            // Pie Chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            if (charts.pie) charts.pie.destroy();
            charts.pie = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Open', 'In Progress', 'Close', 'Cancel'],
                    datasets: [{
                        data: [<?= json_encode($t_open_year) ?>, <?= json_encode($t_progress_year) ?>, <?= json_encode($t_close_year) ?>, <?= json_encode($t_cancel_year) ?>],
                        backgroundColor: ['#686B6F', '#FFC005', '#57e26e', '#DF3545'],
                        borderWidth: 2,
                        borderColor: isDarkMode ? '#1e293b' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Month Chart
            const monthCtx = document.getElementById('monthChart').getContext('2d');
            if (charts.month) charts.month.destroy();
            charts.month = new Chart(monthCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                            label: 'Open',
                            data: <?= json_encode($open) ?>,
                            backgroundColor: '#fbbf24'
                        },
                        {
                            label: 'In Progress',
                            data: <?= json_encode($progress) ?>,
                            backgroundColor: primaryColor
                        },
                        {
                            label: 'Close',
                            data: <?= json_encode($close) ?>,
                            backgroundColor: '#10b981'
                        },
                        {
                            label: 'Cancel',
                            data: <?= json_encode($cancel) ?>,
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

                } else if (page == 'uiElements') {
                    window.location.href = "<?= base_url('uiElements') ?>";
                } else if (page == 'profile') {
                    window.location.href = "<?= base_url('profile') ?>";
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

            initCharts();
        });

        // Logout Modal
        document.getElementById('logoutBtn').addEventListener('click', () => {
            document.getElementById('logoutModal').classList.remove('hidden');
        });

        document.getElementById('cancelLogout').addEventListener('click', () => {
            document.getElementById('logoutModal').classList.add('hidden');
        });

        document.getElementById('confirmLogout').addEventListener('click', () => {
            document.getElementById('logoutModal').classList.add('hidden');
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.textContent = 'Logout berhasil!';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 2000);
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
            initCharts();
            renderCalendar();
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

            // Initialize Choices.js for single select
            const selectChoice = new Choices('#selectInput', {
                searchEnabled: true,
                searchPlaceholderValue: 'Search area...',
                itemSelectText: 'Press to select',
                noResultsText: 'No results found',
                noChoicesText: 'No choices available',
                shouldSort: false
            });

            // Initialize Choices.js for multi-select
            const multiSelectChoice = new Choices('#multiSelectInput', {
                removeItemButton: true,
                searchEnabled: true,
                searchPlaceholderValue: 'Search categories...',
                maxItemCount: 5,
                placeholder: true,
                placeholderValue: 'Select categories...',
                itemSelectText: 'Press to select',
                noResultsText: 'No results found'
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