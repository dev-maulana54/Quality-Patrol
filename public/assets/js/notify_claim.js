// ========== NOTIFY.JS PLUGIN ==========
const Notify = (function () {
  let overlay = null;
  let modal = null;
  let toastContainer = null;
  let currentResolve = null;

  // Initialize elements on first use
  function initElements() {
    if (!toastContainer) {
      toastContainer = document.createElement("div");
      toastContainer.className = "notify-toast-container";
      document.body.appendChild(toastContainer);
    }

    if (!overlay) {
      overlay = document.createElement("div");
      overlay.className = "notify-overlay";

      modal = document.createElement("div");
      modal.className = "notify-modal";

      overlay.appendChild(modal);
      document.body.appendChild(overlay);
    }
  }

  const icons = {
    success: `<svg fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`,
    error: `<svg fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>`,
    warning: `<svg fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`,
    info: `<svg fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    question: `<svg fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
  };

  const toastIcons = {
    success: `<svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>`,
    error: `<svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>`,
    warning: `<svg fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>`,
    info: `<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>`,
  };

  function fire(options = {}) {
    initElements(); // Initialize elements if not exist

    return new Promise((resolve) => {
      currentResolve = resolve;

      const {
        type = "info",
        title = "",
        text = "",
        showCancelButton = false,
        showDenyButton = false,
        confirmButtonText = "OK",
        cancelButtonText = "Batal",
        denyButtonText = "Tolak",
        input = null,
        inputPlaceholder = "",
        showLoading = false,
        timer = null,
        allowOutsideClick = true,
      } = options;

      let html = "";

      if (showLoading) {
        html = `
              <div class="notify-loading"></div>
              <div class="notify-title">${title || "Loading..."}</div>
              ${text ? `<div class="notify-text">${text}</div>` : ""}
            `;
      } else {
        html = `
              <div class="notify-icon ${type}">${icons[type] || icons.info}</div>
              <div class="notify-title">${title}</div>
              ${text ? `<div class="notify-text">${text}</div>` : ""}
              ${input ? `<input type="${input}" class="notify-input" placeholder="${inputPlaceholder}" id="notifyInput">` : ""}
              <div class="notify-buttons">
                ${showDenyButton ? `<button class="notify-btn notify-btn-deny" onclick="Notify.handleDeny()">${denyButtonText}</button>` : ""}
                ${showCancelButton ? `<button class="notify-btn notify-btn-cancel" onclick="Notify.handleCancel()">${cancelButtonText}</button>` : ""}
                ${!showLoading ? `<button class="notify-btn notify-btn-confirm" onclick="Notify.handleConfirm()">${confirmButtonText}</button>` : ""}
              </div>
            `;
      }

      modal.innerHTML = html;
      overlay.classList.add("active");

      if (input) {
        setTimeout(() => {
          const inputEl = document.getElementById("notifyInput");
          if (inputEl) inputEl.focus();
        }, 300);
      }

      if (timer) {
        setTimeout(() => {
          close();
          resolve({ isConfirmed: false, isDismissed: true, dismiss: "timer" });
        }, timer);
      }

      if (allowOutsideClick) {
        overlay.onclick = (e) => {
          if (e.target === overlay) {
            close();
            resolve({
              isConfirmed: false,
              isDismissed: true,
              dismiss: "backdrop",
            });
          }
        };
      }
    });
  }

  function close() {
    overlay.classList.remove("active");
  }

  function handleConfirm() {
    const inputEl = document.getElementById("notifyInput");
    const value = inputEl ? inputEl.value : undefined;
    close();
    if (currentResolve) {
      currentResolve({
        isConfirmed: true,
        isDenied: false,
        isDismissed: false,
        value,
      });
    }
  }

  function handleCancel() {
    close();
    if (currentResolve) {
      currentResolve({
        isConfirmed: false,
        isDenied: false,
        isDismissed: true,
        dismiss: "cancel",
      });
    }
  }

  function handleDeny() {
    close();
    if (currentResolve) {
      currentResolve({
        isConfirmed: false,
        isDenied: true,
        isDismissed: false,
      });
    }
  }

  function toast(options = {}) {
    initElements(); // Initialize elements if not exist

    const {
      type = "info",
      title = "",
      message = "",
      duration = 4000,
      showProgress = true,
    } = options;

    const toastEl = document.createElement("div");
    toastEl.className = "notify-toast";
    toastEl.style.position = "relative";
    toastEl.innerHTML = `
          <div class="notify-toast-icon ${type}">${toastIcons[type] || toastIcons.info}</div>
          <div class="notify-toast-content">
            <div class="notify-toast-title">${title}</div>
            ${message ? `<div class="notify-toast-message">${message}</div>` : ""}
          </div>
          <button class="notify-toast-close" onclick="this.parentElement.classList.add('removing'); setTimeout(() => this.parentElement.remove(), 300)">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
          ${showProgress ? `<div class="notify-toast-progress ${type}" style="width: 100%"></div>` : ""}
        `;

    toastContainer.appendChild(toastEl);

    requestAnimationFrame(() => {
      toastEl.classList.add("active");
    });

    if (showProgress) {
      const progress = toastEl.querySelector(".notify-toast-progress");
      progress.style.transitionDuration = duration + "ms";
      requestAnimationFrame(() => {
        progress.style.width = "0%";
      });
    }

    setTimeout(() => {
      toastEl.classList.add("removing");
      setTimeout(() => toastEl.remove(), 400);
    }, duration);
  }

  // Quick methods
  function success(title, text) {
    return fire({ type: "success", title, text });
  }

  function error(title, text) {
    return fire({ type: "error", title, text });
  }

  function warning(title, text) {
    return fire({ type: "warning", title, text });
  }

  function info(title, text) {
    return fire({ type: "info", title, text });
  }

  function question(title, text) {
    return fire({ type: "question", title, text, showCancelButton: true });
  }

  return {
    fire,
    close,
    handleConfirm,
    handleCancel,
    handleDeny,
    toast,
    success,
    error,
    warning,
    info,
    question,
  };
})();

// ========== DEMO FUNCTIONS ==========
function demoSuccess() {
  Notify.fire({
    type: "success",
    title: "Berhasil!",
    text: "Data Anda telah berhasil disimpan ke database.",
  });
}

function demoError() {
  Notify.fire({
    type: "error",
    title: "Oops!",
    text: "Terjadi kesalahan saat memproses permintaan Anda.",
  });
}

function demoWarning() {
  Notify.fire({
    type: "warning",
    title: "Perhatian!",
    text: "Sesi Anda akan berakhir dalam 5 menit.",
  });
}

function demoInfo() {
  Notify.fire({
    type: "info",
    title: "Informasi",
    text: "Fitur baru telah ditambahkan! Coba sekarang.",
  });
}

function demoQuestion() {
  Notify.fire({
    type: "question",
    title: "Apakah Anda yakin?",
    text: "Aksi ini tidak dapat dibatalkan.",
    showCancelButton: true,
  }).then((result) => {
    if (result.isConfirmed) {
      Notify.toast({
        type: "success",
        title: "Dikonfirmasi!",
        message: "Anda memilih Ya",
      });
    } else if (result.isDismissed) {
      Notify.toast({
        type: "info",
        title: "Dibatalkan",
        message: "Anda membatalkan aksi",
      });
    }
  });
}

function demoLoading() {
  Notify.fire({
    showLoading: true,
    title: "Memproses...",
    text: "Mohon tunggu sebentar",
    allowOutsideClick: false,
  });

  setTimeout(() => {
    Notify.close();
    Notify.fire({
      type: "success",
      title: "Selesai!",
      text: "Proses telah berhasil diselesaikan.",
    });
  }, 2000);
}

function demoConfirm() {
  Notify.fire({
    type: "warning",
    title: "Hapus Data?",
    text: "Data yang dihapus tidak dapat dikembalikan!",
    showCancelButton: true,
    confirmButtonText: "Ya, Hapus!",
    cancelButtonText: "Tidak, Batalkan",
  }).then((result) => {
    if (result.isConfirmed) {
      Notify.fire({
        type: "success",
        title: "Terhapus!",
        text: "Data berhasil dihapus.",
      });
    }
  });
}

function demoInput() {
  Notify.fire({
    type: "question",
    title: "Masukkan nama Anda",
    input: "text",
    inputPlaceholder: "Ketik nama di sini...",
    showCancelButton: true,
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      Notify.fire({
        type: "success",
        title: "Halo!",
        text: `Selamat datang, ${result.value}!`,
      });
    }
  });
}

function demoThreeButtons() {
  Notify.fire({
    type: "question",
    title: "Simpan perubahan?",
    text: "Pilih aksi yang ingin Anda lakukan",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonText: "Simpan",
    denyButtonText: "Jangan Simpan",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      Notify.toast({
        type: "success",
        title: "Tersimpan!",
        message: "Perubahan disimpan",
      });
    } else if (result.isDenied) {
      Notify.toast({
        type: "warning",
        title: "Tidak Disimpan",
        message: "Perubahan dibuang",
      });
    }
  });
}

function demoAutoClose() {
  Notify.fire({
    type: "info",
    title: "Auto Close",
    text: "Dialog ini akan tertutup dalam 3 detik...",
    timer: 3000,
  });
}

function toastSuccess() {
  Notify.toast({
    type: "success",
    title: "Berhasil!",
    message: "Aksi berhasil dilakukan",
  });
}

function toastError() {
  Notify.toast({
    type: "error",
    title: "Error!",
    message: "Terjadi kesalahan",
  });
}

function toastWarning() {
  Notify.toast({
    type: "warning",
    title: "Peringatan",
    message: "Harap perhatikan ini",
  });
}

function toastInfo() {
  Notify.toast({
    type: "info",
    title: "Info",
    message: "Ini adalah informasi penting",
  });
}

// ========== ELEMENT SDK ==========

async function onConfigChange(config) {
  const title = document.getElementById("demoTitle");
  if (title) {
    title.textContent = config.demo_title || defaultConfig.demo_title;
  }
}

function mapToCapabilities(config) {
  return {
    recolorables: [],
    borderables: [],
    fontEditable: undefined,
    fontSizeable: undefined,
  };
}

function mapToEditPanelValues(config) {
  return new Map([
    ["demo_title", config.demo_title || defaultConfig.demo_title],
  ]);
}

if (window.elementSdk) {
  window.elementSdk.init({
    defaultConfig,
    onConfigChange,
    mapToCapabilities,
    mapToEditPanelValues,
  });
}
