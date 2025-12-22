// Default Config
const defaultConfig = {
  app_name: "BestTeamV",
  dashboard_title: "Dashboard Overview",
  footer_text: "© 2025 BestTeamV — All rights reserved",
  primary_color: "#0d6efd",
  background_color: "#f8f9fa",
  card_background: "#ffffff",
  text_color: "#212529",
  font_family: "Poppins",
};

// Chart instances
// let barChart, lineChart, donutChart, areaChart;

// Theme state
let isDarkTheme = false;

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.getElementById("mainContent");
  const sidebarToggle = document.getElementById("sidebarToggle");
  if (sidebar && mainContent && sidebarToggle) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("collapsed");
      mainContent.classList.toggle("expanded");

      // kalau pakai chart JS (Apex/Highcharts/dll) dan perlu di-resize,
      // panggil fungsi redraw chart di sini
      // contoh:
      // clusteredChart.resize();
      // barChartStacked.resize();
      // barChart_stacked_area.resize();
    });
  }
  // initializeCharts();
  initializeDataTable();
  setupEventListeners();
  renderClusteredChart();
  if (window.elementSdk) {
    window.elementSdk.init({
      defaultConfig: defaultConfig,
      onConfigChange: onConfigChange,
      mapToCapabilities: mapToCapabilities,
      mapToEditPanelValues: mapToEditPanelValues,
    });
  }
});

// Config change handler
async function onConfigChange(config) {
  const appName = config.app_name || defaultConfig.app_name;
  const dashboardTitle =
    config.dashboard_title || defaultConfig.dashboard_title;
  const footerText = config.footer_text || defaultConfig.footer_text;
  const fontFamily = config.font_family || defaultConfig.font_family;

  document.getElementById("appName").textContent = appName;
  document.getElementById("dashboardTitle").textContent = dashboardTitle;
  document.getElementById("footerText").textContent = footerText;

  document.body.style.fontFamily = `${fontFamily}, sans-serif`;
}

// Capabilities mapping
function mapToCapabilities(config) {
  return {
    recolorables: [
      {
        get: () => config.primary_color || defaultConfig.primary_color,
        set: (value) => {
          if (window.elementSdk) {
            window.elementSdk.config.primary_color = value;
            window.elementSdk.setConfig({
              primary_color: value,
            });
          }
        },
      },
    ],
    borderables: [],
    fontEditable: {
      get: () => config.font_family || defaultConfig.font_family,
      set: (value) => {
        if (window.elementSdk) {
          window.elementSdk.config.font_family = value;
          window.elementSdk.setConfig({
            font_family: value,
          });
        }
      },
    },
    fontSizeable: undefined,
  };
}

// Edit panel values mapping
function mapToEditPanelValues(config) {
  return new Map([
    ["app_name", config.app_name || defaultConfig.app_name],
    [
      "dashboard_title",
      config.dashboard_title || defaultConfig.dashboard_title,
    ],
    ["footer_text", config.footer_text || defaultConfig.footer_text],
  ]);
}

// Initialize Charts
// function initializeCharts() {
//   const chartColors = {
//     primary: "#0d6efd",
//     success: "#198754",
//     warning: "#ffc107",
//     danger: "#dc3545",
//     info: "#0dcaf0",
//   };

//   // Bar Chart - Temuan Per Area
//   const barCtx = document.getElementById("barChart").getContext("2d");
//   barChart = new Chart(barCtx, {
//     type: "bar",
//     data: {
//       labels: [
//         "Produksi A",
//         "Produksi B",
//         "Produksi C",
//         "Gudang",
//         "Kantor",
//         "Maintenance",
//       ],
//       datasets: [
//         {
//           label: "Jumlah Temuan",
//           data: [12, 19, 8, 15, 6, 10],
//           backgroundColor: chartColors.primary,
//           borderRadius: 8,
//         },
//       ],
//     },
//     options: {
//       responsive: true,
//       maintainAspectRatio: false,
//       plugins: {
//         legend: {
//           display: false,
//         },
//       },
//       scales: {
//         y: {
//           beginAtZero: true,
//           grid: {
//             color: isDarkTheme ? "#2a2a2a" : "#e9ecef",
//           },
//           ticks: {
//             color: isDarkTheme ? "#b0b0b0" : "#495057",
//           },
//         },
//         x: {
//           grid: {
//             display: false,
//           },
//           ticks: {
//             color: isDarkTheme ? "#b0b0b0" : "#495057",
//           },
//         },
//       },
//     },
//   });

//   // Line Chart - Trend Audit
//   const lineCtx = document.getElementById("lineChart").getContext("2d");
//   lineChart = new Chart(lineCtx, {
//     type: "line",
//     data: {
//       labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"],
//       datasets: [
//         {
//           label: "Jumlah Audit",
//           data: [65, 78, 90, 81, 96, 105],
//           borderColor: chartColors.success,
//           backgroundColor: "rgba(25, 135, 84, 0.1)",
//           tension: 0.4,
//           fill: true,
//         },
//       ],
//     },
//     options: {
//       responsive: true,
//       maintainAspectRatio: false,
//       plugins: {
//         legend: {
//           display: false,
//         },
//       },
//       scales: {
//         y: {
//           beginAtZero: true,
//           grid: {
//             color: isDarkTheme ? "#2a2a2a" : "#e9ecef",
//           },
//           ticks: {
//             color: isDarkTheme ? "#b0b0b0" : "#495057",
//           },
//         },
//         x: {
//           grid: {
//             display: false,
//           },
//           ticks: {
//             color: isDarkTheme ? "#b0b0b0" : "#495057",
//           },
//         },
//       },
//     },
//   });

//   // Donut Chart - Status Temuan
//   const donutCtx = document.getElementById("donutChart").getContext("2d");
//   donutChart = new Chart(donutCtx, {
//     type: "doughnut",
//     data: {
//       labels: ["Selesai", "Proses", "Pending"],
//       datasets: [
//         {
//           data: [55, 30, 15],
//           backgroundColor: [
//             chartColors.success,
//             chartColors.warning,
//             chartColors.danger,
//           ],
//           borderWidth: 0,
//         },
//       ],
//     },
//     options: {
//       responsive: true,
//       maintainAspectRatio: false,
//       plugins: {
//         legend: {
//           position: "bottom",
//           labels: {
//             color: isDarkTheme ? "#b0b0b0" : "#495057",
//             padding: 15,
//             font: {
//               size: 12,
//             },
//           },
//         },
//       },
//     },
//   });

//   // Area Chart - Perkembangan Audit
//   const areaCtx = document.getElementById("areaChart").getContext("2d");
//   areaChart = new Chart(areaCtx, {
//     type: "line",
//     data: {
//       labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
//       datasets: [
//         {
//           label: "Audit Selesai",
//           data: [20, 35, 45, 60],
//           borderColor: chartColors.info,
//           backgroundColor: "rgba(13, 202, 240, 0.2)",
//           fill: true,
//           tension: 0.4,
//         },
//       ],
//     },
//     options: {
//       responsive: true,
//       maintainAspectRatio: false,
//       plugins: {
//         legend: {
//           display: false,
//         },
//       },
//       scales: {
//         y: {
//           beginAtZero: true,
//           grid: {
//             color: isDarkTheme ? "#2a2a2a" : "#e9ecef",
//           },
//           ticks: {
//             color: isDarkTheme ? "#b0b0b0" : "#495057",
//           },
//         },
//         x: {
//           grid: {
//             display: false,
//           },
//           ticks: {
//             color: isDarkTheme ? "#b0b0b0" : "#495057",
//           },
//         },
//       },
//     },
//   });
// }

// Update chart colors for theme
// function updateChartColors() {
//   const gridColor = isDarkTheme ? "#2a2a2a" : "#e9ecef";
//   const tickColor = isDarkTheme ? "#b0b0b0" : "#495057";
//   const legendColor = isDarkTheme ? "#b0b0b0" : "#495057";

//   [barChart, lineChart, areaChart].forEach((chart) => {
//     if (chart && chart.options.scales) {
//       chart.options.scales.y.grid.color = gridColor;
//       chart.options.scales.y.ticks.color = tickColor;
//       chart.options.scales.x.ticks.color = tickColor;
//       chart.update();
//     }
//   });

//   if (donutChart && donutChart.options.plugins.legend) {
//     donutChart.options.plugins.legend.labels.color = legendColor;
//     donutChart.update();
//   }
// }

// Initialize DataTable
function initializeDataTable() {
  $("#auditTable").DataTable({
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data per halaman",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
      infoFiltered: "(difilter dari _MAX_ total data)",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Selanjutnya",
        previous: "Sebelumnya",
      },
      zeroRecords: "Tidak ada data yang ditemukan",
    },
    pageLength: 5,
    lengthMenu: [
      [5, 10, 25, 50],
      [5, 10, 25, 50],
    ],
    order: [[1, "desc"]],
  });
}

// Setup Event Listeners
function setupEventListeners() {
  // Theme Toggle
  document.getElementById("themeToggle").addEventListener("click", function () {
    isDarkTheme = !isDarkTheme;
    document.body.classList.toggle("dark-theme");
    document.body.classList.toggle("light-theme");

    const icon = this.querySelector("i");
    if (isDarkTheme) {
      icon.classList.remove("bi-sun-fill");
      icon.classList.add("bi-moon-fill");
    } else {
      icon.classList.remove("bi-moon-fill");
      icon.classList.add("bi-sun-fill");
    }
    // alert("kamu klik");
    localStorage.setItem("theme", isDarkTheme ? "dark" : "light");
    // updateChartColors();
    renderClusteredChart();
  });

  // Profile Dropdown
  document.getElementById("profileImg").addEventListener("click", function (e) {
    e.stopPropagation();
    document.getElementById("profileDropdown").classList.toggle("show");
  });

  document.addEventListener("click", function () {
    document.getElementById("profileDropdown").classList.remove("show");
  });

  // Logout Button
  document.getElementById("logoutBtn").addEventListener("click", function (e) {
    e.preventDefault();
    const confirmLogout = document.createElement("div");
    confirmLogout.style.cssText =
      "position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); z-index: 9999; text-align: center;";
    confirmLogout.innerHTML = `
                    <h4 style="margin-bottom: 20px; color: #212529;">Konfirmasi Logout</h4>
                    <p style="margin-bottom: 25px; color: #6c757d;">Apakah Anda yakin ingin keluar?</p>
                    <button id="confirmYes" style="background: #dc3545; color: white; border: none; padding: 10px 25px; border-radius: 6px; margin-right: 10px; cursor: pointer; font-weight: 500;">Ya, Logout</button>
                    <button id="confirmNo" style="background: #6c757d; color: white; border: none; padding: 10px 25px; border-radius: 6px; cursor: pointer; font-weight: 500;">Batal</button>
                `;

    const backdrop = document.createElement("div");
    backdrop.style.cssText =
      "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998;";

    document.body.appendChild(backdrop);
    document.body.appendChild(confirmLogout);

    document
      .getElementById("confirmYes")
      .addEventListener("click", function () {
        const successMsg = document.createElement("div");
        successMsg.style.cssText =
          "position: fixed; top: 20px; right: 20px; background: #198754; color: white; padding: 15px 25px; border-radius: 8px; z-index: 10000; box-shadow: 0 4px 12px rgba(0,0,0,0.2);";
        successMsg.textContent = "Logout berhasil! Mengalihkan...";
        document.body.appendChild(successMsg);

        setTimeout(() => {
          window.location.href = "logout";
        }, 1000);

        backdrop.remove();
        confirmLogout.remove();
      });

    document.getElementById("confirmNo").addEventListener("click", function () {
      backdrop.remove();
      confirmLogout.remove();
    });

    backdrop.addEventListener("click", function () {
      backdrop.remove();
      confirmLogout.remove();
    });
  });

  // Mobile Toggle
  document
    .getElementById("mobileToggle")
    .addEventListener("click", function () {
      document.getElementById("sidebar").classList.toggle("show");
    });

  // Menu Items
  document.querySelectorAll(".menu-item").forEach((item) => {
    // item.addEventListener("click", function (e) {
    //   e.preventDefault();
    //     document
    //       .querySelectorAll(".menu-item")
    //       .forEach((mi) => mi.classList.remove("active"));
    //     this.classList.add("active");
    //   if (window.innerWidth <= 768) {
    //     document.getElementById("sidebar").classList.remove("show");
    //   }
    // });
  });

  // Load saved theme
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    document.getElementById("themeToggle").click();
  }
}
