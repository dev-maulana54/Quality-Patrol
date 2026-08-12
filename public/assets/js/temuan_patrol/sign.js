// Default Config
const defaultConfig = {
  app_name: "Quality Patrol",
  dashboard_title: "Summary Overview",
  footer_text: "© 2025 Quality Patrol — All rights reserved",
  primary_color: "#0d6efd",
  background_color: "#f8f9fa",
  card_background: "#ffffff",
  text_color: "#212529",
  font_family: "Poppins",
};
// Initialize Date Picker
function initializeDatePicker() {
  flatpickr(".auditDate", {
    locale: "id",
    dateFormat: "d M Y",
    altInput: true,
    altFormat: "d F Y",
    defaultDate: "today",
    minDate: "2020-01-01",
    maxDate: new Date().fp_incr(365), // 1 year from today
    allowInput: false,
    clickOpens: true,
    theme: "material_blue",
    animate: true,
    position: "auto",
    onReady: function (selectedDates, dateStr, instance) {
      // Add custom styling to the calendar
      instance.calendarContainer.style.boxShadow =
        "0 15px 35px rgba(13, 110, 253, 0.2)";
      instance.calendarContainer.style.borderRadius = "12px";
      instance.calendarContainer.style.border =
        "1px solid rgba(13, 110, 253, 0.1)";
    },
    onChange: function (selectedDates, dateStr, instance) {
      // Validate field when date is selected
      const field = document.getElementById("auditDate");
      validateField(field);

      // Add visual feedback
      field.style.borderColor = "#198754";
      field.style.boxShadow = "0 0 0 3px rgba(25, 135, 84, 0.1)";

      setTimeout(() => {
        field.style.borderColor = "";
        field.style.boxShadow = "";
      }, 1000);
    },
  });
}
// Validate individual field
function validateField(field) {
  const value = field.value.trim();
  const fieldName = field.previousElementSibling.textContent
    .replace(/[^\w\s]/gi, "")
    .trim();

  // Remove existing error message
  const existingError = field.parentNode.querySelector(".error-message");
  if (existingError) {
    existingError.remove();
  }

  // Reset classes - no visual border changes during blur validation
  field.classList.remove("is-valid", "is-invalid");

  if (field.hasAttribute("required") && !value) {
    // Show error message only, no border styling
    showFieldError(field, `${fieldName} wajib diisi`);
    return false;
  } else if (value) {
    // Additional validation based on field type
    if (field.id === "auditDate") {
      // For flatpickr, the value is already formatted and validated
      // Just check if it's not empty since flatpickr handles date validation
      if (!value) {
        showFieldError(field, "Tanggal audit wajib dipilih");
        return false;
      }
    }

    if (
      field.type === "text" &&
      field.id === "auditorName" &&
      value.length < 2
    ) {
      showFieldError(field, "Nama auditor minimal 2 karakter");
      return false;
    }

    if (field.type === "text" && field.id === "picName" && value.length < 2) {
      showFieldError(field, "Nama PIC minimal 2 karakter");
      return false;
    }

    // Field is valid - no visual indication during blur
    return true;
  }

  return true;
}
// Chart instances
let barChart, lineChart, donutChart, areaChart;

// Theme state
let isDarkTheme = false;

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  setupEventListeners();
  initializeDatePicker();
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

// Update chart colors for theme
function updateChartColors() {
  const gridColor = isDarkTheme ? "#2a2a2a" : "#e9ecef";
  const tickColor = isDarkTheme ? "#b0b0b0" : "#495057";
  const legendColor = isDarkTheme ? "#b0b0b0" : "#495057";

  [barChart, lineChart, areaChart].forEach((chart) => {
    if (chart && chart.options.scales) {
      chart.options.scales.y.grid.color = gridColor;
      chart.options.scales.y.ticks.color = tickColor;
      chart.options.scales.x.ticks.color = tickColor;
      chart.update();
    }
  });

  if (donutChart && donutChart.options.plugins.legend) {
    donutChart.options.plugins.legend.labels.color = legendColor;
    donutChart.update();
  }
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

    updateChartColors();

    localStorage.setItem("theme", isDarkTheme ? "dark" : "light");
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
          if (window.QPDB && typeof window.QPDB.logoutUser === "function") {
            window.QPDB.logoutUser();
          }
          if (window.location.protocol === "file:") {
            var path = window.location.pathname;
            var isSub = path.indexOf("/admin/") !== -1 || path.indexOf("/users/") !== -1 || path.indexOf("/mobile/") !== -1;
            window.location.href = isSub ? "../auth/login.html" : "app/Views/auth/login.html";
          } else {
            window.location.href = typeof baseurl !== "undefined" ? baseurl + "/logout" : "logout";
          }
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

// $("#modal_tambahdata .select2").select2({
//   dropdownParent: $("#modal_tambahdata"),
// });
// $("#modal_editdata .select2").select2({
//   dropdownParent: $("#modal_editdata"),
// });
