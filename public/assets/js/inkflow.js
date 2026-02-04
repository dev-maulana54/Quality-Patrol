// ========================================
// INKFLOW SIGNATURE PLUGIN - v1.0
// ========================================

const InkFlow = (function () {
  "use strict";

  // Default configuration
  const defaults = {
    title: "Tanda Tangan Digital",
    subtitle: "Silakan tanda tangani di area yang disediakan",
    width: 350, // Bisa di-override saat memanggil InkFlow.show()
    height: 300, // Bisa di-override saat memanggil InkFlow.show()
    penColor: "#000000",
    penWidth: 2,
    backgroundColor: "#ffffff",
    data: null,
    showColorPicker: true,
    showThicknessSlider: true,
    colors: ["#000000", "#2563eb", "#dc2626", "#059669", "#7c3aed"],
    approveText: "Approve & Sign",
    cancelText: "Batal",
    clearText: "Hapus",
    onApprove: null,
    onCancel: null,
    closeOnOverlay: false,
  };

  let currentOverlay = null;
  let canvas = null;
  let ctx = null;
  let isDrawing = false;
  let lastX = 0;
  let lastY = 0;
  let currentColor = "#000000";
  let currentWidth = 2;
  let hasSignature = false;
  let points = [];
  let autoSmooth = true;
  let stabilizationEnabled = true;
  let stabilizationBuffer = [];

  // Initialize canvas
  function initCanvas(canvasElement, options) {
    canvas = canvasElement;
    ctx = canvas.getContext("2d");

    // Set canvas size
    canvas.width = options.width;
    canvas.height = options.height;

    // Fill background
    ctx.fillStyle = options.backgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Set drawing properties
    ctx.strokeStyle = options.penColor;
    ctx.lineWidth = options.penWidth;
    ctx.lineCap = "round";
    ctx.lineJoin = "round";

    currentColor = options.penColor;
    currentWidth = options.penWidth;
    hasSignature = false;
  }

  // Get mouse/touch position
  function getPosition(e) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;

    if (e.touches && e.touches.length > 0) {
      return {
        x: (e.touches[0].clientX - rect.left) * scaleX,
        y: (e.touches[0].clientY - rect.top) * scaleY,
      };
    }
    return {
      x: (e.clientX - rect.left) * scaleX,
      y: (e.clientY - rect.top) * scaleY,
    };
  }

  // Apply stabilization to smooth out hand tremors in real-time
  function getStabilizedPosition(currentPos) {
    if (!stabilizationEnabled) {
      return currentPos;
    }

    // Add to buffer
    stabilizationBuffer.push(currentPos);

    // Keep buffer size limited (last 5 points for smoothing)
    const bufferSize = 5;
    if (stabilizationBuffer.length > bufferSize) {
      stabilizationBuffer.shift();
    }

    // If buffer is too small, return current position
    if (stabilizationBuffer.length < 3) {
      return currentPos;
    }

    // Calculate weighted average (more weight to recent points)
    let totalWeight = 0;
    let weightedX = 0;
    let weightedY = 0;

    for (let i = 0; i < stabilizationBuffer.length; i++) {
      const weight = (i + 1) / stabilizationBuffer.length; // Linear weight
      weightedX += stabilizationBuffer[i].x * weight;
      weightedY += stabilizationBuffer[i].y * weight;
      totalWeight += weight;
    }

    return {
      x: weightedX / totalWeight,
      y: weightedY / totalWeight,
    };
  }

  // Smooth line using Catmull-Rom spline interpolation
  function smoothLine(pts) {
    if (pts.length < 4) return pts;

    const smoothed = [];
    const tension = 0.5;

    // Add first point
    smoothed.push(pts[0]);

    // For each segment
    for (let i = 0; i < pts.length - 1; i++) {
      const p0 = pts[Math.max(i - 1, 0)];
      const p1 = pts[i];
      const p2 = pts[i + 1];
      const p3 = pts[Math.min(i + 2, pts.length - 1)];

      // Number of interpolation steps
      const steps = 10;

      for (let t = 0; t < steps; t++) {
        const t1 = t / steps;
        const t2 = t1 * t1;
        const t3 = t2 * t1;

        // Catmull-Rom formula
        const x =
          0.5 *
          (2 * p1.x +
            (-p0.x + p2.x) * t1 +
            (2 * p0.x - 5 * p1.x + 4 * p2.x - p3.x) * t2 +
            (-p0.x + 3 * p1.x - 3 * p2.x + p3.x) * t3);

        const y =
          0.5 *
          (2 * p1.y +
            (-p0.y + p2.y) * t1 +
            (2 * p0.y - 5 * p1.y + 4 * p2.y - p3.y) * t2 +
            (-p0.y + 3 * p1.y - 3 * p2.y + p3.y) * t3);

        smoothed.push({ x, y });
      }
    }

    // Add last point
    smoothed.push(pts[pts.length - 1]);

    return smoothed;
  }

  // Redraw canvas with smoothed lines
  function redrawSmoothed() {
    if (points.length < 10) return; // Need enough points for smoothing

    console.log("Smoothing with", points.length, "points");

    // Clear and redraw background
    ctx.fillStyle = defaults.backgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Sample points - reduce density for better smoothing
    const sampledPoints = [];
    const sampleRate = Math.max(1, Math.floor(points.length / 50));
    for (let i = 0; i < points.length; i += sampleRate) {
      sampledPoints.push(points[i]);
    }
    // Always include last point
    if (sampledPoints[sampledPoints.length - 1] !== points[points.length - 1]) {
      sampledPoints.push(points[points.length - 1]);
    }

    console.log("Sampled to", sampledPoints.length, "points");

    // Smooth the sampled points
    const smoothedPoints = smoothLine(sampledPoints);

    console.log("Smoothed to", smoothedPoints.length, "points");

    // Draw smoothed line
    if (smoothedPoints.length > 0) {
      ctx.beginPath();
      ctx.moveTo(smoothedPoints[0].x, smoothedPoints[0].y);

      for (let i = 1; i < smoothedPoints.length; i++) {
        ctx.lineTo(smoothedPoints[i].x, smoothedPoints[i].y);
      }

      ctx.stroke();
    }

    console.log("Smoothing complete!");

    // Show visual feedback
    const feedback = document.createElement("div");
    feedback.style.cssText = `
          position: absolute;
          top: 10px;
          right: 10px;
          background: linear-gradient(135deg, #10b981, #059669);
          color: white;
          padding: 8px 16px;
          border-radius: 8px;
          font-size: 0.85rem;
          font-weight: 600;
          box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
          z-index: 10;
          pointer-events: none;
          opacity: 0;
          transition: opacity 0.3s ease;
        `;
    feedback.innerHTML = "✨ Dirapikan otomatis!";
    canvas.parentElement.appendChild(feedback);

    // Fade in
    requestAnimationFrame(() => {
      feedback.style.opacity = "1";
    });

    // Fade out and remove
    setTimeout(() => {
      feedback.style.opacity = "0";
      setTimeout(() => {
        if (feedback.parentElement) {
          feedback.remove();
        }
      }, 300);
    }, 1700);
  }

  // Start drawing
  function startDrawing(e) {
    e.preventDefault();
    isDrawing = true;
    const pos = getPosition(e);
    lastX = pos.x;
    lastY = pos.y;
    points = [{ x: pos.x, y: pos.y }];
    stabilizationBuffer = [{ x: pos.x, y: pos.y }]; // Reset buffer

    // Add signing class
    canvas.parentElement.classList.add("signing");
    canvas.parentElement.style.borderColor = currentColor;

    // Hide placeholder
    const placeholder = canvas.parentElement.querySelector(
      ".signature-placeholder",
    );
    if (placeholder) {
      placeholder.classList.add("hidden");
    }
  }

  // Draw
  function draw(e) {
    if (!isDrawing) return;
    e.preventDefault();

    hasSignature = true;
    const rawPos = getPosition(e);

    // Apply stabilization
    const pos = getStabilizedPosition(rawPos);

    points.push({ x: pos.x, y: pos.y });

    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();

    lastX = pos.x;
    lastY = pos.y;

    // Enable approve button
    updateApproveButton();
  }

  // Stop drawing
  function stopDrawing(e) {
    if (!isDrawing) return;
    e.preventDefault();
    isDrawing = false;

    // Remove signing class
    canvas.parentElement.classList.remove("signing");

    // Clear stabilization buffer
    stabilizationBuffer = [];

    // Apply smoothing after drawing
    if (autoSmooth && points.length > 3) {
      setTimeout(() => {
        redrawSmoothed();
      }, 100);
    }

    points = [];
  }

  // Clear canvas
  function clearCanvas() {
    if (!canvas || !ctx) return;

    ctx.fillStyle = defaults.backgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    hasSignature = false;

    // Show placeholder
    const placeholder = canvas.parentElement.querySelector(
      ".signature-placeholder",
    );
    if (placeholder) {
      placeholder.classList.remove("hidden");
    }

    updateApproveButton();
    toast("Tanda tangan dihapus", "info");
  }

  // Change pen color
  function changePenColor(color) {
    currentColor = color;
    ctx.strokeStyle = color;

    // Update active button
    document.querySelectorAll(".signature-color-btn").forEach((btn) => {
      btn.classList.remove("active");
      if (btn.dataset.color === color) {
        btn.classList.add("active");
      }
    });
  }

  // Change pen width
  function changePenWidth(width) {
    currentWidth = width;
    ctx.lineWidth = width;
  }

  // Toggle stabilization
  function toggleStabilization() {
    stabilizationEnabled = !stabilizationEnabled;

    // Update UI
    const toggleContainer = document.querySelector(
      ".signature-toggle-container",
    );
    const toggleSwitch = document.querySelector(".signature-toggle-switch");

    if (stabilizationEnabled) {
      toggleContainer.classList.add("active");
      toggleSwitch.classList.add("active");
      toast("Line stabilization aktif", "info");
    } else {
      toggleContainer.classList.remove("active");
      toggleSwitch.classList.remove("active");
      toast("Line stabilization nonaktif", "info");
    }
  }

  // Update approve button state
  function updateApproveButton() {
    const approveBtn = document.querySelector(".signature-btn-approve");
    if (approveBtn) {
      approveBtn.disabled = !hasSignature;
    }
  }

  // Get signature as base64
  function getSignatureData() {
    if (!canvas) return null;
    return canvas.toDataURL("image/png");
  }

  // Create modal HTML
  function createModal(options) {
    const opts = { ...defaults, ...options };

    const overlay = document.createElement("div");
    overlay.className = "signature-overlay";

    // Info card HTML
    let dataHtml = "";
    if (opts.data && typeof opts.data === "object") {
      const rows = Object.entries(opts.data)
        .map(
          ([label, value]) => `
            <div class="signature-info-row">
              <span class="signature-info-label">${label}</span>
              <span class="signature-info-value">${value}</span>
            </div>
          `,
        )
        .join("");

      dataHtml = `
            <div class="signature-info-card">
              ${rows}
            </div>
          `;
    }

    // Color picker HTML
    let colorPickerHtml = "";
    if (opts.showColorPicker) {
      const colorButtons = opts.colors
        .map(
          (color, index) => `
            <button class="signature-color-btn ${index === 0 ? "active" : ""}" 
                    style="background: ${color}" 
                    data-color="${color}"
                    onclick="window.signaturePadChangeColor('${color}')">
            </button>
          `,
        )
        .join("");

      colorPickerHtml = `
            <div class="signature-color-picker">
              ${colorButtons}
            </div>
          `;
    }

    // Thickness slider HTML
    let thicknessHtml = "";
    if (opts.showThicknessSlider) {
      thicknessHtml = `
            <div class="signature-thickness-slider">
              <label class="signature-thickness-label">
                Ketebalan: <span id="thickness-value">${opts.penWidth}</span>px
              </label>
              <input type="range" min="1" max="8" value="${opts.penWidth}" 
                     oninput="window.signaturePadChangeWidth(this.value)">
            </div>
          `;
    }

    // Stabilization toggle HTML
    const stabilizationHtml = `
          <div class="signature-toggle-container active" onclick="window.signaturePadToggleStabilization()">
            <div class="signature-toggle-switch active">
              <div class="signature-toggle-knob"></div>
            </div>
            <span class="signature-toggle-label">Line Stabilization</span>
          </div>
        `;

    overlay.innerHTML = `
          <div class="signature-modal">
            <div class="signature-header">
              <h2 class="signature-title">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                ${opts.title}
              </h2>
              <p class="signature-subtitle">${opts.subtitle}</p>
            </div>
            
            <div class="signature-body">
              ${dataHtml}
              
              <div class="signature-toolbar">
                ${colorPickerHtml}
                ${thicknessHtml}
                ${stabilizationHtml}
              </div>
              
              <div class="signature-canvas-wrapper">
                <canvas class="signature-canvas"></canvas>
                <div class="signature-placeholder">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                  </svg>
                  <div class="signature-placeholder-text">Tanda Tangan Disini</div>
                  <div class="signature-placeholder-subtext">Gunakan mouse atau touch</div>
                </div>
              </div>
            </div>
            
            <div class="signature-footer">
              <button class="signature-btn signature-btn-clear" onclick="window.signaturePadClear()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                ${opts.clearText}
              </button>
              <button class="signature-btn signature-btn-cancel" data-action="cancel">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                ${opts.cancelText}
              </button>
              <button class="signature-btn signature-btn-approve" data-action="approve" disabled>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                ${opts.approveText}
              </button>
            </div>
          </div>
        `;

    // Initialize canvas
    const canvasElement = overlay.querySelector(".signature-canvas");
    initCanvas(canvasElement, opts);

    // Add drawing event listeners
    canvasElement.addEventListener("mousedown", startDrawing);
    canvasElement.addEventListener("mousemove", draw);
    canvasElement.addEventListener("mouseup", stopDrawing);
    canvasElement.addEventListener("mouseout", stopDrawing);

    // Touch events
    canvasElement.addEventListener("touchstart", startDrawing);
    canvasElement.addEventListener("touchmove", draw);
    canvasElement.addEventListener("touchend", stopDrawing);

    // Button event listeners
    overlay
      .querySelector('[data-action="approve"]')
      .addEventListener("click", () => {
        const signatureData = getSignatureData();
        close();
        if (typeof opts.onApprove === "function") {
          opts.onApprove(signatureData);
        }
      });

    overlay
      .querySelector('[data-action="cancel"]')
      .addEventListener("click", () => {
        close();
        if (typeof opts.onCancel === "function") {
          opts.onCancel();
        }
      });

    if (opts.closeOnOverlay) {
      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) {
          close();
          if (typeof opts.onCancel === "function") {
            opts.onCancel();
          }
        }
      });
    }

    // Escape key
    const escHandler = (e) => {
      if (e.key === "Escape") {
        close();
        if (typeof opts.onCancel === "function") {
          opts.onCancel();
        }
        document.removeEventListener("keydown", escHandler);
      }
    };
    document.addEventListener("keydown", escHandler);

    return overlay;
  }

  // Show modal
  function show(options) {
    if (currentOverlay) {
      close();
    }

    currentOverlay = createModal(options);
    document.body.appendChild(currentOverlay);

    requestAnimationFrame(() => {
      currentOverlay.classList.add("active");
    });

    return {
      close: close,
    };
  }

  // Close modal
  function close() {
    if (currentOverlay) {
      currentOverlay.classList.remove("active");
      setTimeout(() => {
        if (currentOverlay && currentOverlay.parentNode) {
          currentOverlay.parentNode.removeChild(currentOverlay);
        }
        currentOverlay = null;
        canvas = null;
        ctx = null;
        hasSignature = false;
      }, 300);
    }
  }

  // Show toast
  function toast(message, type = "success") {
    const toastEl = document.createElement("div");
    toastEl.className = `signature-toast ${type}`;

    const icons = {
      success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>`,
      error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>`,
      info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                 </svg>`,
    };

    toastEl.innerHTML = `${icons[type] || icons.info}<span>${message}</span>`;
    document.body.appendChild(toastEl);

    requestAnimationFrame(() => {
      toastEl.classList.add("show");
    });

    setTimeout(() => {
      toastEl.classList.remove("show");
      setTimeout(() => {
        if (toastEl.parentNode) {
          toastEl.parentNode.removeChild(toastEl);
        }
      }, 400);
    }, 3000);
  }

  // Expose methods to window for onclick handlers
  window.signaturePadChangeColor = changePenColor;
  window.signaturePadChangeWidth = function (width) {
    changePenWidth(parseFloat(width));
    document.getElementById("thickness-value").textContent = width;
  };
  window.signaturePadClear = clearCanvas;
  window.signaturePadToggleStabilization = toggleStabilization;

  // Public API
  return {
    show,
    close,
    toast,
    defaults,
  };
})();

// Make globally available
window.InkFlow = InkFlow;

// ========================================
// MAIN SIGNATURE PAD (AUTO-RENDER)
// ========================================

let mainCanvas = null;
let mainCtx = null;
let mainIsDrawing = false;
let mainLastX = 0;
let mainLastY = 0;
let mainCurrentColor = "#000000";
let mainCurrentWidth = 2;
let mainHasSignature = false;
let mainPoints = [];
let mainStabilizationEnabled = true;
let mainStabilizationBuffer = [];

function mainGetPosition(e) {
  const rect = mainCanvas.getBoundingClientRect();
  const scaleX = mainCanvas.width / rect.width;
  const scaleY = mainCanvas.height / rect.height;

  if (e.touches && e.touches.length > 0) {
    return {
      x: (e.touches[0].clientX - rect.left) * scaleX,
      y: (e.touches[0].clientY - rect.top) * scaleY,
    };
  }
  return {
    x: (e.clientX - rect.left) * scaleX,
    y: (e.clientY - rect.top) * scaleY,
  };
}

function mainGetStabilizedPosition(currentPos) {
  if (!mainStabilizationEnabled) {
    return currentPos;
  }

  mainStabilizationBuffer.push(currentPos);

  if (mainStabilizationBuffer.length > 5) {
    mainStabilizationBuffer.shift();
  }

  if (mainStabilizationBuffer.length < 3) {
    return currentPos;
  }

  let totalWeight = 0;
  let weightedX = 0;
  let weightedY = 0;

  for (let i = 0; i < mainStabilizationBuffer.length; i++) {
    const weight = (i + 1) / mainStabilizationBuffer.length;
    weightedX += mainStabilizationBuffer[i].x * weight;
    weightedY += mainStabilizationBuffer[i].y * weight;
    totalWeight += weight;
  }

  return {
    x: weightedX / totalWeight,
    y: weightedY / totalWeight,
  };
}

function mainStartDrawing(e) {
  e.preventDefault();
  mainIsDrawing = true;
  const pos = mainGetPosition(e);
  mainLastX = pos.x;
  mainLastY = pos.y;
  mainPoints = [{ x: pos.x, y: pos.y }];
  mainStabilizationBuffer = [{ x: pos.x, y: pos.y }];

  mainCanvas.parentElement.classList.add("signing");
  mainCanvas.parentElement.style.borderColor = mainCurrentColor;

  document.getElementById("main-placeholder").classList.add("hidden");
}

function mainDraw(e) {
  if (!mainIsDrawing) return;
  e.preventDefault();

  mainHasSignature = true;
  const rawPos = mainGetPosition(e);
  const pos = mainGetStabilizedPosition(rawPos);

  mainPoints.push({ x: pos.x, y: pos.y });

  mainCtx.beginPath();
  mainCtx.moveTo(mainLastX, mainLastY);
  mainCtx.lineTo(pos.x, pos.y);
  mainCtx.stroke();

  mainLastX = pos.x;
  mainLastY = pos.y;

  mainUpdateButtons();
}

function mainStopDrawing(e) {
  if (!mainIsDrawing) return;
  e.preventDefault();
  mainIsDrawing = false;

  mainCanvas.parentElement.classList.remove("signing");
  mainStabilizationBuffer = [];

  if (mainPoints.length > 3) {
    setTimeout(() => {
      mainRedrawSmoothed();
    }, 100);
  }

  mainPoints = [];
}

function mainRedrawSmoothed() {
  if (mainPoints.length < 10) return;

  mainCtx.fillStyle = "#ffffff";
  mainCtx.fillRect(0, 0, mainCanvas.width, mainCanvas.height);

  const sampledPoints = [];
  const sampleRate = Math.max(1, Math.floor(mainPoints.length / 50));
  for (let i = 0; i < mainPoints.length; i += sampleRate) {
    sampledPoints.push(mainPoints[i]);
  }
  if (
    sampledPoints[sampledPoints.length - 1] !==
    mainPoints[mainPoints.length - 1]
  ) {
    sampledPoints.push(mainPoints[mainPoints.length - 1]);
  }

  const smoothedPoints = smoothLine(sampledPoints);

  if (smoothedPoints.length > 0) {
    mainCtx.beginPath();
    mainCtx.moveTo(smoothedPoints[0].x, smoothedPoints[0].y);

    for (let i = 1; i < smoothedPoints.length; i++) {
      mainCtx.lineTo(smoothedPoints[i].x, smoothedPoints[i].y);
    }

    mainCtx.stroke();
  }
}

function mainUpdateButtons() {
  document.getElementById("main-download-btn").disabled = !mainHasSignature;
  document.getElementById("main-copy-btn").disabled = !mainHasSignature;
}

function mainPadChangeColor(color) {
  mainCurrentColor = color;
  mainCtx.strokeStyle = color;

  document.querySelectorAll(".main-color-btn").forEach((btn) => {
    btn.classList.remove("active");
    if (btn.dataset.color === color) {
      btn.classList.add("active");
    }
  });
}

function mainPadChangeWidth(width) {
  mainCurrentWidth = parseFloat(width);
  mainCtx.lineWidth = mainCurrentWidth;
  document.getElementById("main-thickness-value").textContent = width + "px";
}

function mainPadToggleStabilization() {
  mainStabilizationEnabled = !mainStabilizationEnabled;

  const toggleContainer = document.querySelector(".main-toggle-container");
  const toggleSwitch = document.querySelector(".main-toggle-switch");

  if (mainStabilizationEnabled) {
    toggleContainer.classList.add("active");
    toggleSwitch.classList.add("active");
    InkFlow.toast("Stabilization aktif", "info");
  } else {
    toggleContainer.classList.remove("active");
    toggleSwitch.classList.remove("active");
    InkFlow.toast("Stabilization nonaktif", "info");
  }
}

function mainPadClear() {
  if (!mainCanvas || !mainCtx) return;

  mainCtx.fillStyle = "#ffffff";
  mainCtx.fillRect(0, 0, mainCanvas.width, mainCanvas.height);
  mainHasSignature = false;

  document.getElementById("main-placeholder").classList.remove("hidden");
  mainUpdateButtons();

  InkFlow.toast("Canvas dibersihkan", "info");
}

function mainPadDownload() {
  if (!mainHasSignature) return;

  const link = document.createElement("a");
  link.download = `signature-${Date.now()}.png`;
  link.href = mainCanvas.toDataURL("image/png");
  link.click();

  InkFlow.toast("Signature berhasil didownload!", "success");
}

function mainPadCopy() {
  if (!mainHasSignature) return;

  const signatureData = mainCanvas.toDataURL("image/png");

  // Copy to clipboard
  navigator.clipboard
    .writeText(signatureData)
    .then(() => {
      InkFlow.toast("Base64 disalin ke clipboard!", "success");
    })
    .catch(() => {
      // Fallback: show in modal
      const modal = document.createElement("div");
      modal.style.cssText = `
          position: fixed;
          inset: 0;
          background: rgba(0,0,0,0.8);
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 10000;
          padding: 20px;
        `;
      modal.innerHTML = `
          <div style="background: white; padding: 24px; border-radius: 16px; max-width: 600px; width: 100%;">
            <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 12px;">Base64 Signature Data</h3>
            <textarea readonly style="width: 100%; height: 200px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-family: monospace; font-size: 0.85rem;">${signatureData}</textarea>
            <button onclick="this.parentElement.parentElement.remove()" style="margin-top: 16px; padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Tutup</button>
          </div>
        `;
      document.body.appendChild(modal);
      modal.querySelector("textarea").select();
    });
}

// ========================================
// RENDER IN MODAL FUNCTIONS
// ========================================

function renderInModal(containerId, options = {}) {
  const container = document.getElementById(containerId);
  if (!container) {
    console.error("Container not found:", containerId);
    return null;
  }

  const opts = {
    width: 600,
    height: 300,
    penColor: "#000000",
    penWidth: 2,
    backgroundColor: "#ffffff",
    showColorPicker: true,
    showThicknessSlider: true,
    colors: ["#000000", "#2563eb", "#dc2626", "#059669", "#7c3aed"],
    ...options,
  };

  // Create canvas and controls
  const wrapper = document.createElement("div");
  wrapper.className = "inline-signature-wrapper";
  wrapper.style.cssText = "width: 100%;";

  // Toolbar
  let colorPickerHtml = "";
  if (opts.showColorPicker) {
    const colorButtons = opts.colors
      .map(
        (color, index) => `
          <button class="main-color-btn ${index === 0 ? "active" : ""}" 
                  style="background: ${color}" 
                  data-color="${color}"
                  data-container="${containerId}">
          </button>
        `,
      )
      .join("");

    colorPickerHtml = `
          <div class="flex gap-2 items-center bg-white/5 px-4 py-3 rounded-xl border border-white/10">
            ${colorButtons}
          </div>
        `;
  }

  let thicknessHtml = "";
  if (opts.showThicknessSlider) {
    thicknessHtml = `
          <div class="flex items-center gap-3 bg-white/5 px-4 py-3 rounded-xl border border-white/10">
            <span class=" text-sm font-semibold">Ketebalan:</span>
            <input type="range" min="1" max="8" value="${opts.penWidth}" class="w-32 inline-thickness-slider" data-container="${containerId}">
            <span class=" text-sm font-bold w-8 inline-thickness-value">${opts.penWidth}px</span>
          </div>
        `;
  }

  wrapper.innerHTML = `
        <div class="flex gap-4 items-center justify-center flex-wrap mb-6">
          ${colorPickerHtml}
          ${thicknessHtml}
          <div class="main-toggle-container active bg-white/5 px-4 py-3 rounded-xl border border-white/10" data-container="${containerId}" style="border-color: rgb(0 0 0) !important;">
            <div class="main-toggle-switch active">
              <div class="main-toggle-knob"></div>
            </div>
            <span class="main-toggle-label  text-sm font-semibold">Stabilization</span>
          </div>
        </div>

        <div class="main-canvas-wrapper mx-auto" style="max-width: 100%; width: 100%;">
          <canvas class="signature-canvas inline-signature-canvas" data-container="${containerId}"></canvas>
          <div class="signature-placeholder inline-signature-placeholder">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <div class="signature-placeholder-text">✍️ Tanda Tangan Disini</div>
            <div class="signature-placeholder-subtext">Langsung tulis di modal ini</div>
          </div>
        </div>

        <div class="flex gap-4 justify-center mt-6">
          <button class="px-6 py-3 bg-white/10 hover:bg-white/20  rounded-xl font-bold transition-all border border-white/20 flex items-center gap-2 inline-clear-btn" data-container="${containerId}" style="border-color: rgb(0 0 0) !important;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus
          </button>
        </div>
      `;

  container.appendChild(wrapper);

  // Initialize canvas with responsive size
  const canvas = wrapper.querySelector(".inline-signature-canvas");
  const ctx = canvas.getContext("2d");

  // Get actual container width for responsive sizing
  const canvasWrapper = canvas.parentElement;
  const containerWidth = canvasWrapper.getBoundingClientRect().width;

  // Use container width if smaller than requested width
  const finalWidth = Math.min(opts.width, containerWidth);
  const aspectRatio = opts.height / opts.width;
  const finalHeight = finalWidth * aspectRatio;

  canvas.width = finalWidth;
  canvas.height = finalHeight;

  ctx.fillStyle = opts.backgroundColor;
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  ctx.strokeStyle = opts.penColor;
  ctx.lineWidth = opts.penWidth;
  ctx.lineCap = "round";
  ctx.lineJoin = "round";

  // Handle window resize to make canvas responsive
  const handleResize = () => {
    const newContainerWidth = canvasWrapper.getBoundingClientRect().width;
    const newFinalWidth = Math.min(opts.width, newContainerWidth);
    const newFinalHeight = newFinalWidth * aspectRatio;

    // Only resize if dimensions changed significantly
    if (Math.abs(canvas.width - newFinalWidth) > 10) {
      // Save current drawing
      const imageData = canvas.toDataURL("image/png");

      // Resize canvas
      canvas.width = newFinalWidth;
      canvas.height = newFinalHeight;

      // Restore background
      ctx.fillStyle = opts.backgroundColor;
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // Restore drawing if exists
      if (state.hasSignature) {
        const img = new Image();
        img.onload = () => {
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
        img.src = imageData;
      }

      // Restore drawing settings
      ctx.strokeStyle = state.currentColor;
      ctx.lineWidth = state.currentWidth;
      ctx.lineCap = "round";
      ctx.lineJoin = "round";
    }
  };

  window.addEventListener("resize", handleResize);

  // State
  const state = {
    isDrawing: false,
    lastX: 0,
    lastY: 0,
    currentColor: opts.penColor,
    currentWidth: opts.penWidth,
    hasSignature: false,
    points: [],
    stabilizationEnabled: true,
    stabilizationBuffer: [],
  };

  // Helper functions
  const getPosition = (e) => {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;

    if (e.touches && e.touches.length > 0) {
      return {
        x: (e.touches[0].clientX - rect.left) * scaleX,
        y: (e.touches[0].clientY - rect.top) * scaleY,
      };
    }
    return {
      x: (e.clientX - rect.left) * scaleX,
      y: (e.clientY - rect.top) * scaleY,
    };
  };

  const getStabilizedPosition = (currentPos) => {
    if (!state.stabilizationEnabled) return currentPos;

    state.stabilizationBuffer.push(currentPos);
    if (state.stabilizationBuffer.length > 5) {
      state.stabilizationBuffer.shift();
    }
    if (state.stabilizationBuffer.length < 3) return currentPos;

    let totalWeight = 0;
    let weightedX = 0;
    let weightedY = 0;

    for (let i = 0; i < state.stabilizationBuffer.length; i++) {
      const weight = (i + 1) / state.stabilizationBuffer.length;
      weightedX += state.stabilizationBuffer[i].x * weight;
      weightedY += state.stabilizationBuffer[i].y * weight;
      totalWeight += weight;
    }

    return {
      x: weightedX / totalWeight,
      y: weightedY / totalWeight,
    };
  };

  const updateButtons = () => {
    // No buttons to update anymore
  };

  const startDrawing = (e) => {
    e.preventDefault();
    state.isDrawing = true;
    const pos = getPosition(e);
    state.lastX = pos.x;
    state.lastY = pos.y;
    state.points = [{ x: pos.x, y: pos.y }];
    state.stabilizationBuffer = [{ x: pos.x, y: pos.y }];

    canvas.parentElement.classList.add("signing");
    canvas.parentElement.style.borderColor = state.currentColor;
    wrapper
      .querySelector(".inline-signature-placeholder")
      .classList.add("hidden");
  };

  const draw = (e) => {
    if (!state.isDrawing) return;
    e.preventDefault();

    state.hasSignature = true;
    const rawPos = getPosition(e);
    const pos = getStabilizedPosition(rawPos);

    state.points.push({ x: pos.x, y: pos.y });

    ctx.beginPath();
    ctx.moveTo(state.lastX, state.lastY);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();

    state.lastX = pos.x;
    state.lastY = pos.y;

    updateButtons();
  };

  const stopDrawing = (e) => {
    if (!state.isDrawing) return;
    e.preventDefault();
    state.isDrawing = false;
    canvas.parentElement.classList.remove("signing");
    state.stabilizationBuffer = [];
    state.points = [];
  };

  // Event listeners
  canvas.addEventListener("mousedown", startDrawing);
  canvas.addEventListener("mousemove", draw);
  canvas.addEventListener("mouseup", stopDrawing);
  canvas.addEventListener("mouseout", stopDrawing);
  canvas.addEventListener("touchstart", startDrawing);
  canvas.addEventListener("touchmove", draw);
  canvas.addEventListener("touchend", stopDrawing);

  // Color buttons
  wrapper.querySelectorAll(".main-color-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      state.currentColor = btn.dataset.color;
      ctx.strokeStyle = state.currentColor;
      wrapper
        .querySelectorAll(".main-color-btn")
        .forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });

  // Thickness slider
  const thicknessSlider = wrapper.querySelector(".inline-thickness-slider");
  if (thicknessSlider) {
    thicknessSlider.addEventListener("input", (e) => {
      state.currentWidth = parseFloat(e.target.value);
      ctx.lineWidth = state.currentWidth;
      wrapper.querySelector(".inline-thickness-value").textContent =
        e.target.value + "px";
    });
  }

  // Stabilization toggle
  wrapper
    .querySelector(".main-toggle-container")
    .addEventListener("click", () => {
      state.stabilizationEnabled = !state.stabilizationEnabled;
      const toggle = wrapper.querySelector(".main-toggle-container");
      const toggleSwitch = wrapper.querySelector(".main-toggle-switch");

      if (state.stabilizationEnabled) {
        toggle.classList.add("active");
        toggleSwitch.classList.add("active");
        InkFlow.toast("Stabilization aktif", "info");
      } else {
        toggle.classList.remove("active");
        toggleSwitch.classList.remove("active");
        InkFlow.toast("Stabilization nonaktif", "info");
      }
    });

  // Clear button
  wrapper.querySelector(".inline-clear-btn").addEventListener("click", () => {
    ctx.fillStyle = opts.backgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    state.hasSignature = false;
    wrapper
      .querySelector(".inline-signature-placeholder")
      .classList.remove("hidden");
    updateButtons();
    InkFlow.toast("Canvas dibersihkan", "info");
  });

  // Return API
  return {
    getSignature: () =>
      state.hasSignature ? canvas.toDataURL("image/png") : null,
    clear: () => {
      ctx.fillStyle = opts.backgroundColor;
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      state.hasSignature = false;
      wrapper
        .querySelector(".inline-signature-placeholder")
        .classList.remove("hidden");
      updateButtons();
    },
    hasSignature: () => state.hasSignature,
  };
}

// Expose to window
window.InkFlowRender = renderInModal;

// ========================================
// DEMO FUNCTIONS
// ========================================

function demoBasic() {
  InkFlow.show({
    title: "Basic Signature",
    subtitle: "Tanda tangani untuk melanjutkan",
    width: 600,
    height: 250,
    onApprove: (signature) => {
      InkFlow.toast("Tanda tangan berhasil disimpan!", "success");
      console.log("Signature data:", signature);
    },
    onCancel: () => {
      InkFlow.toast("Dibatalkan", "error");
    },
  });
}

function demoDocument() {
  InkFlow.show({
    title: "Approval Dokumen",
    subtitle: "Mohon review dan tanda tangani dokumen berikut",
    width: 700,
    height: 320,
    data: {
      "Nama Dokumen": "Surat Perjanjian Kerja",
      Nomor: "DOC-2024-001",
      Tanggal: new Date().toLocaleDateString("id-ID"),
      Status: "Menunggu Approval",
    },
    penColor: "#059669",
    onApprove: (signature) => {
      InkFlow.toast("Dokumen berhasil di-approve!", "success");
      console.log("Document signed:", signature);
    },
  });
}

function demoPayment() {
  InkFlow.show({
    title: "Payment Approval",
    subtitle: "Tanda tangani untuk menyetujui pembayaran",
    width: 750,
    height: 330,
    data: {
      Jumlah: "Rp 2.500.000",
      Penerima: "PT XYZ Indonesia",
      Bank: "BCA - 1234567890",
      Keterangan: "Invoice #INV-2024-042",
      "Tanggal Transfer": new Date().toLocaleDateString("id-ID"),
    },
    penColor: "#7c3aed",
    approveText: "Approve & Transfer",
    onApprove: (signature) => {
      InkFlow.toast("Pembayaran berhasil disetujui!", "success");
      console.log("Payment approved with signature:", signature);
    },
  });
}

function demoContract() {
  InkFlow.show({
    title: "Tanda Tangan Kontrak",
    subtitle: "Dengan menandatangani, Anda menyetujui semua ketentuan",
    width: 800,
    height: 350,
    data: {
      "Jenis Kontrak": "Kontrak Pekerjaan",
      Periode: "1 Tahun",
      Mulai: "1 Januari 2024",
      Berakhir: "31 Desember 2024",
      "Pihak Pertama": "PT ABC Company",
      "Pihak Kedua": "John Doe",
    },
    penColor: "#ea580c",
    approveText: "Setuju & Tanda Tangan",
    onApprove: (signature) => {
      InkFlow.toast("Kontrak berhasil ditandatangani!", "success");
      console.log("Contract signed:", signature);
    },
  });
}

function demoCustom() {
  InkFlow.show({
    title: "Custom Signature Pad",
    subtitle: "Pilih warna dan ketebalan sesuai keinginan Anda",
    width: 650,
    height: 280,
    showColorPicker: true,
    showThicknessSlider: true,
    colors: [
      "#000000",
      "#2563eb",
      "#dc2626",
      "#059669",
      "#7c3aed",
      "#ea580c",
      "#0891b2",
    ],
    penWidth: 3,
    onApprove: (signature) => {
      InkFlow.toast("Signature saved!", "success");
      console.log("Custom signature:", signature);
    },
  });
}

function demoExport() {
  InkFlow.show({
    title: "Export Signature",
    subtitle: "Tanda tangan akan disimpan sebagai gambar PNG",
    width: 700,
    height: 300,
    penColor: "#2563eb",
    approveText: "Export as Image",
    onApprove: (signature) => {
      // Create download link
      const link = document.createElement("a");
      link.download = `signature-${Date.now()}.png`;
      link.href = signature;
      link.click();

      InkFlow.toast("Signature berhasil di-download!", "success");
    },
  });
}

// Modal Render Demo
let modalSignaturePad = null;

function demoRenderInModal() {
  const modal = document.getElementById("custom-modal");
  const container = document.getElementById("modal-signature-container");

  // Clear container
  container.innerHTML = "";

  // Show modal
  modal.style.display = "flex";

  // Render signature pad in modal
  setTimeout(() => {
    modalSignaturePad = InkFlowRender("modal-signature-container", {
      width: 700,
      height: 300,
      penColor: "#dc2626",
      penWidth: 3,
      showColorPicker: true,
      showThicknessSlider: true,
    });
  }, 100);
}

function closeCustomModal() {
  const modal = document.getElementById("custom-modal");
  modal.style.display = "none";
  modalSignaturePad = null;
}

function getModalSignature() {
  if (!modalSignaturePad) {
    InkFlow.toast("Signature pad belum di-render", "error");
    return;
  }

  if (!modalSignaturePad.hasSignature()) {
    InkFlow.toast("Belum ada tanda tangan", "error");
    return;
  }

  const signature = modalSignaturePad.getSignature();
  console.log("Modal signature data:", signature);
  InkFlow.toast("Signature berhasil diambil! Cek console.", "success");

  // Optional: download
  const link = document.createElement("a");
  link.download = `modal-signature-${Date.now()}.png`;
  link.href = signature;
  link.click();
}
