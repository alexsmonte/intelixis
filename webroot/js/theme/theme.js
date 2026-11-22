document.addEventListener("DOMContentLoaded", function () {
  "use strict";

  // Feather Icon Init Js
  feather.replace();

  // =================================
  // Tooltip
  // =================================
  const tooltipTriggerList = Array.from(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.forEach((tooltipTriggerEl) => {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // =================================
  // Popover
  // =================================
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
  // =================================
  // Hide preloader
  // =================================
  var preloader = document.querySelector(".preloader");
  if (preloader) {
    preloader.style.display = "none";
  }
  // =================================
  // Increment & Decrement
  // =================================
  var quantityButtons = document.querySelectorAll(".minus, .add");
  if (quantityButtons) {
    quantityButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        var qtyInput = this.closest("div").querySelector(".qty");
        var currentVal = parseInt(qtyInput.value);
        var isAdd = this.classList.contains("add");

        if (!isNaN(currentVal)) {
          qtyInput.value = isAdd
            ? ++currentVal
            : currentVal > 0
            ? --currentVal
            : currentVal;
        }
      });
    });
  }
  // =================================
  // Fixed header
  // =================================
  window.addEventListener("scroll", function () {
    var topbar = document.querySelector(".topbar");
    if (topbar) {
      if (window.scrollY >= 60) {
        topbar.classList.add("shadow-sm");
      } else {
        topbar.classList.remove("shadow-sm");
      }
    }
  });

  // =================================
  // Premium Custom Form Validation Tooltips (fixes select and masked required fields)
  // =================================
  (function () {
    let tooltipEl = null;
    let activeEl = null;
    let firstInvalidEl = null;
    let timeoutId = null;

    function createTooltip() {
      if (tooltipEl) return tooltipEl;
      
      tooltipEl = document.createElement('div');
      tooltipEl.id = 'custom-validation-tooltip';
      tooltipEl.style.cssText = `
        position: absolute;
        display: none;
        background: #ffffff;
        color: #212529;
        border: 1px solid #dee2e6;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border-radius: 8px;
        padding: 8px 14px;
        z-index: 999999;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 13px;
        pointer-events: none;
        align-items: center;
        transition: opacity 0.15s ease-in-out;
        opacity: 0;
      `;
      
      tooltipEl.innerHTML = `
        <div style="display: flex; align-items: center;">
          <span style="display: inline-flex; align-items: center; justify-content: center; background: #ff9800; color: #ffffff; width: 18px; height: 18px; border-radius: 4px; font-weight: bold; margin-right: 8px; font-size: 11px;">!</span>
          <span class="tooltip-text" style="font-weight: 500;"></span>
        </div>
        <div class="tooltip-arrow" style="position: absolute; bottom: -5px; left: 24px; width: 8px; height: 8px; background: #ffffff; border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6; transform: rotate(45deg);"></div>
      `;
      
      document.body.appendChild(tooltipEl);
      return tooltipEl;
    }

    function showTooltip(el) {
      const tooltip = createTooltip();
      const textEl = tooltip.querySelector('.tooltip-text');
      textEl.textContent = el.validationMessage || 'Campo obrigatório';
      
      tooltip.style.display = 'block';
      
      // Measure size
      const tooltipHeight = tooltip.offsetHeight;
      const rect = el.getBoundingClientRect();
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
      
      // Position above the input field
      tooltip.style.top = (rect.top + scrollTop - tooltipHeight - 8) + 'px';
      tooltip.style.left = (rect.left + scrollLeft) + 'px';
      
      // Fade in
      setTimeout(() => {
        tooltip.style.opacity = '1';
      }, 10);
      
      activeEl = el;
      
      // Listeners to close it
      const hide = () => {
        hideTooltip();
        el.removeEventListener('input', hide);
        el.removeEventListener('change', hide);
        el.removeEventListener('blur', hide);
        el.removeEventListener('keydown', hide);
      };
      
      el.addEventListener('blur', hide);
      el.addEventListener('keydown', hide);
      
      // Bind input and change with delay to avoid instant dismissal on mask focus events
      setTimeout(() => {
        if (activeEl === el) {
          el.addEventListener('input', hide);
          el.addEventListener('change', hide);
        }
      }, 150);
    }

    function hideTooltip() {
      if (tooltipEl) {
        tooltipEl.style.opacity = '0';
        setTimeout(() => {
          if (tooltipEl.style.opacity === '0') {
            tooltipEl.style.display = 'none';
          }
        }, 150);
      }
      activeEl = null;
    }

    // Intercept native validation failures
    document.addEventListener('invalid', function (e) {
      const el = e.target;
      
      // Prevent browser's default validation speech bubble
      e.preventDefault();

      if (!firstInvalidEl) {
        firstInvalidEl = el;
      }

      if (timeoutId) clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        if (firstInvalidEl) {
          const targetEl = firstInvalidEl;
          firstInvalidEl = null;

          // Focus the element
          targetEl.focus();
          
          // Show the custom tooltip
          showTooltip(targetEl);
        }
      }, 50);
    }, true);
  })();
});
