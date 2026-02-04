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
                <div class="flex justify-center items-center min-h-screen">
                    <button class="bg-blue-600 text-white px-12 py-6 rounded-xl text-2xl font-bold hover:bg-blue-700 startaudit" style="margin-top: -200px;">
                        <i class="fas fa-clipboard-check text-lg"></i> Mulai Quality Patrol
                    </button>
                </div>

            </div><!-- Data Patrol Page -->




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



        $('.startaudit').on('click', function() {
            window.location.href = "<?= base_url('temuan_patrol/start_audit') ?>";
        });


        // Show Detail Modal


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