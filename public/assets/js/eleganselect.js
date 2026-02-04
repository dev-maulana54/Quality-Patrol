/* ========================================
       SelectX Plugin JavaScript - Copy this section
       ======================================== */

class SelectX {
  constructor(selector, options = {}) {
    this.originalSelect =
      typeof selector === "string"
        ? document.querySelector(selector)
        : selector;

    if (!this.originalSelect || this.originalSelect.tagName !== "SELECT") {
      console.error("SelectX: Invalid selector or element");
      return;
    }

    this.options = {
      searchable: false,
      clearable: false,
      multiple: this.originalSelect.multiple || false,
      placeholder: "Pilih...",
      searchPlaceholder: "Cari...",
      noResultsText: "Tidak ada hasil",
      showDescription: false,
      theme: "light",
      size: "md",
      onChange: null,
      onOpen: null,
      onClose: null,
      ...options,
    };

    this.isOpen = false;
    this.selectedValues = [];
    this.highlightedIndex = -1;
    this.filteredOptions = [];

    this.init();
  }

  init() {
    this.buildDOM();
    this.bindEvents();
    this.syncFromOriginal();
  }

  buildDOM() {
    // Hide original select
    this.originalSelect.style.display = "none";

    // Create container
    this.container = document.createElement("div");
    this.container.className = "selectx-container";
    this.container.setAttribute("data-theme", this.options.theme);
    this.container.setAttribute("data-size", this.options.size);

    // Create trigger
    this.trigger = document.createElement("div");
    this.trigger.className = "selectx-trigger";
    this.trigger.setAttribute("tabindex", "0");
    this.trigger.setAttribute("role", "combobox");
    this.trigger.setAttribute("aria-haspopup", "listbox");
    this.trigger.setAttribute("aria-expanded", "false");

    if (this.originalSelect.disabled) {
      this.trigger.classList.add("disabled");
      this.trigger.removeAttribute("tabindex");
    }

    // Value display
    this.valueDisplay = document.createElement("div");
    this.valueDisplay.className = "selectx-value";

    // Clear button
    this.clearBtn = document.createElement("div");
    this.clearBtn.className = "selectx-clear";
    this.clearBtn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
    this.clearBtn.style.display = "none";

    // Arrow icon
    this.arrow = document.createElement("div");
    this.arrow.className = "selectx-icon";
    this.arrow.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>`;

    this.trigger.appendChild(this.valueDisplay);
    this.trigger.appendChild(this.clearBtn);
    this.trigger.appendChild(this.arrow);

    // Create dropdown
    this.dropdown = document.createElement("div");
    this.dropdown.className = "selectx-dropdown";
    this.dropdown.setAttribute("role", "listbox");

    // Search input (if searchable)
    if (this.options.searchable) {
      const searchWrapper = document.createElement("div");
      searchWrapper.className = "selectx-search-wrapper";

      this.searchInput = document.createElement("input");
      this.searchInput.type = "text";
      this.searchInput.className = "selectx-search";
      this.searchInput.placeholder = this.options.searchPlaceholder;
      this.searchInput.setAttribute("aria-label", "Search options");

      searchWrapper.appendChild(this.searchInput);
      this.dropdown.appendChild(searchWrapper);
    }

    // Options list
    this.optionsList = document.createElement("div");
    this.optionsList.className = "selectx-options";
    this.dropdown.appendChild(this.optionsList);

    // Assemble
    this.container.appendChild(this.trigger);
    this.container.appendChild(this.dropdown);
    this.originalSelect.parentNode.insertBefore(
      this.container,
      this.originalSelect.nextSibling,
    );

    this.renderOptions();
  }

  renderOptions(filter = "") {
    this.optionsList.innerHTML = "";
    this.filteredOptions = [];

    const options = Array.from(this.originalSelect.options);
    let hasResults = false;

    options.forEach((option, index) => {
      if (option.value === "" && !option.textContent.trim()) return;

      const text = option.textContent.trim();
      const searchText = text.toLowerCase();
      const filterLower = filter.toLowerCase();

      if (filter && !searchText.includes(filterLower)) return;

      hasResults = true;
      this.filteredOptions.push({ option, index });

      const optionEl = document.createElement("div");
      optionEl.className = "selectx-option";
      optionEl.setAttribute("data-value", option.value);
      optionEl.setAttribute("role", "option");

      if (option.disabled) {
        optionEl.classList.add("disabled");
      }

      if (this.selectedValues.includes(option.value)) {
        optionEl.classList.add("selected");
        optionEl.setAttribute("aria-selected", "true");
      }

      // Icon
      const icon = option.getAttribute("data-icon");
      if (icon || this.options.showDescription) {
        const iconEl = document.createElement("div");
        iconEl.className = "selectx-option-icon";
        iconEl.textContent = icon || text.charAt(0).toUpperCase();
        if (!icon) {
          iconEl.style.background = this.getRandomColor(option.value);
          iconEl.style.color = "white";
          iconEl.style.fontSize = "14px";
        }
        optionEl.appendChild(iconEl);
      }

      // Content
      const content = document.createElement("div");
      content.className = "selectx-option-content";

      const label = document.createElement("div");
      label.className = "selectx-option-label";
      label.textContent = text;
      content.appendChild(label);

      // Description
      const desc = option.getAttribute("data-desc");
      if (desc && this.options.showDescription) {
        const descEl = document.createElement("div");
        descEl.className = "selectx-option-description";
        descEl.textContent = desc;
        content.appendChild(descEl);
      }

      optionEl.appendChild(content);

      // Check mark
      const check = document.createElement("div");
      check.className = "selectx-check";
      check.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
      optionEl.appendChild(check);

      this.optionsList.appendChild(optionEl);
    });

    if (!hasResults) {
      const empty = document.createElement("div");
      empty.className = "selectx-empty";
      empty.textContent = this.options.noResultsText;
      this.optionsList.appendChild(empty);
    }
  }

  bindEvents() {
    // Toggle dropdown
    this.trigger.addEventListener("click", (e) => {
      if (this.originalSelect.disabled) return;
      if (e.target.closest(".selectx-clear")) return;
      this.toggle();
    });

    // Keyboard navigation
    this.trigger.addEventListener("keydown", (e) => {
      if (this.originalSelect.disabled) return;
      this.handleKeydown(e);
    });

    // Clear button
    this.clearBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      this.clear();
    });

    // Option click
    this.optionsList.addEventListener("click", (e) => {
      const optionEl = e.target.closest(".selectx-option");
      if (!optionEl || optionEl.classList.contains("disabled")) return;

      const value = optionEl.getAttribute("data-value");
      this.selectValue(value);
    });

    // Search input
    if (this.searchInput) {
      this.searchInput.addEventListener("input", (e) => {
        this.renderOptions(e.target.value);
        this.highlightedIndex = -1;
      });

      this.searchInput.addEventListener("keydown", (e) => {
        this.handleKeydown(e);
      });
    }

    // Click outside
    document.addEventListener("click", (e) => {
      if (!this.container.contains(e.target)) {
        this.close();
      }
    });
  }

  handleKeydown(e) {
    switch (e.key) {
      case "Enter":
      case " ":
        e.preventDefault();
        if (!this.isOpen) {
          this.open();
        } else if (this.highlightedIndex >= 0) {
          const option = this.filteredOptions[this.highlightedIndex];
          if (option && !option.option.disabled) {
            this.selectValue(option.option.value);
          }
        }
        break;
      case "Escape":
        this.close();
        break;
      case "ArrowDown":
        e.preventDefault();
        if (!this.isOpen) {
          this.open();
        } else {
          this.highlightNext();
        }
        break;
      case "ArrowUp":
        e.preventDefault();
        this.highlightPrev();
        break;
    }
  }

  highlightNext() {
    const options = this.optionsList.querySelectorAll(
      ".selectx-option:not(.disabled)",
    );
    this.highlightedIndex = Math.min(
      this.highlightedIndex + 1,
      options.length - 1,
    );
    this.updateHighlight();
  }

  highlightPrev() {
    this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
    this.updateHighlight();
  }

  updateHighlight() {
    const options = this.optionsList.querySelectorAll(".selectx-option");
    options.forEach((opt, i) => {
      opt.classList.toggle("highlighted", i === this.highlightedIndex);
    });

    if (this.highlightedIndex >= 0 && options[this.highlightedIndex]) {
      options[this.highlightedIndex].scrollIntoView({ block: "nearest" });
    }
  }

  toggle() {
    if (this.isOpen) {
      this.close();
    } else {
      this.open();
    }
  }

  open() {
    if (this.originalSelect.disabled) return;

    this.isOpen = true;
    this.trigger.classList.add("active");
    this.dropdown.classList.add("open");
    this.trigger.setAttribute("aria-expanded", "true");

    if (this.searchInput) {
      this.searchInput.value = "";
      this.renderOptions();
      setTimeout(() => this.searchInput.focus(), 50);
    }

    if (this.options.onOpen) {
      this.options.onOpen();
    }
  }

  close() {
    this.isOpen = false;
    this.trigger.classList.remove("active");
    this.dropdown.classList.remove("open");
    this.trigger.setAttribute("aria-expanded", "false");
    this.highlightedIndex = -1;

    if (this.options.onClose) {
      this.options.onClose();
    }
  }

  selectValue(value) {
    if (this.options.multiple) {
      const index = this.selectedValues.indexOf(value);
      if (index > -1) {
        this.selectedValues.splice(index, 1);
      } else {
        this.selectedValues.push(value);
      }
    } else {
      this.selectedValues = [value];
      this.close();
    }

    this.updateDisplay();
    this.updateOriginalSelect();
    this.renderOptions(this.searchInput ? this.searchInput.value : "");

    if (this.options.onChange) {
      this.options.onChange(
        this.options.multiple ? this.selectedValues : this.selectedValues[0],
      );
    }
  }

  updateDisplay() {
    this.valueDisplay.innerHTML = "";

    if (this.selectedValues.length === 0) {
      const placeholder = document.createElement("span");
      placeholder.className = "selectx-placeholder";
      placeholder.textContent = this.options.placeholder;
      this.valueDisplay.appendChild(placeholder);
      this.clearBtn.style.display = "none";
    } else if (this.options.multiple) {
      const tags = document.createElement("div");
      tags.className = "selectx-tags";

      this.selectedValues.forEach((value) => {
        const option = this.originalSelect.querySelector(
          `option[value="${value}"]`,
        );
        if (option) {
          const tag = document.createElement("span");
          tag.className = "selectx-tag";
          tag.innerHTML = `
                ${option.textContent}
                <span class="selectx-tag-remove" data-value="${value}">
                  <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </span>
              `;

          tag
            .querySelector(".selectx-tag-remove")
            .addEventListener("click", (e) => {
              e.stopPropagation();
              this.selectValue(value);
            });

          tags.appendChild(tag);
        }
      });

      this.valueDisplay.appendChild(tags);
      this.clearBtn.style.display = this.options.clearable ? "flex" : "none";
    } else {
      const value = this.selectedValues[0];
      const option = this.originalSelect.querySelector(
        `option[value="${value}"]`,
      );

      if (option) {
        const icon = option.getAttribute("data-icon");

        if (icon) {
          const iconSpan = document.createElement("span");
          iconSpan.textContent = icon;
          this.valueDisplay.appendChild(iconSpan);
        }

        const text = document.createElement("span");
        text.className = "selectx-selected-text";
        text.textContent = option.textContent;
        this.valueDisplay.appendChild(text);
      }

      this.clearBtn.style.display = this.options.clearable ? "flex" : "none";
    }
  }

  updateOriginalSelect() {
    const options = this.originalSelect.options;

    for (let i = 0; i < options.length; i++) {
      options[i].selected = this.selectedValues.includes(options[i].value);
    }

    // Dispatch change event
    this.originalSelect.dispatchEvent(new Event("change", { bubbles: true }));
  }

  syncFromOriginal() {
    const options = this.originalSelect.options;
    this.selectedValues = [];

    for (let i = 0; i < options.length; i++) {
      if (options[i].selected && options[i].value) {
        this.selectedValues.push(options[i].value);
      }
    }

    this.updateDisplay();
  }

  clear() {
    this.selectedValues = [];
    this.updateDisplay();
    this.updateOriginalSelect();
    this.renderOptions();

    if (this.options.onChange) {
      this.options.onChange(this.options.multiple ? [] : null);
    }
  }

  getValue() {
    return this.options.multiple ? this.selectedValues : this.selectedValues[0];
  }

  setValue(value) {
    if (this.options.multiple && Array.isArray(value)) {
      this.selectedValues = value;
    } else if (!this.options.multiple && typeof value === "string") {
      this.selectedValues = [value];
    }

    this.updateDisplay();
    this.updateOriginalSelect();
    this.renderOptions();
  }

  destroy() {
    this.originalSelect.style.display = "";
    this.container.remove();
  }

  getRandomColor(seed) {
    const colors = [
      "#6366f1",
      "#8b5cf6",
      "#ec4899",
      "#f43f5e",
      "#f97316",
      "#eab308",
      "#22c55e",
      "#14b8a6",
      "#06b6d4",
      "#3b82f6",
    ];
    let hash = 0;
    for (let i = 0; i < seed.length; i++) {
      hash = seed.charCodeAt(i) + ((hash << 5) - hash);
    }
    return colors[Math.abs(hash) % colors.length];
  }
}

// Auto-init with data attribute
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-selectx]").forEach((select) => {
    const options = JSON.parse(select.getAttribute("data-selectx") || "{}");
    new SelectX(select, options);
  });
});

/* ========================================
       End SelectX Plugin JavaScript
       ======================================== */
