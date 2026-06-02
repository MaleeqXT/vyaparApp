(function () {
  function initTransactionPasscodeGuard() {
    const configEl = document.getElementById('transactionPasscodeConfig');
    if (!configEl || window.requestTransactionPasscode) {
      return;
    }

    const enabled = String(configEl.dataset.enabled || '0') === '1';
    const verifyUrl = configEl.dataset.verifyUrl || '';
    const csrfToken = configEl.dataset.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const modalEl = document.getElementById('salePasscodeModal') || document.getElementById('globalTransactionPasscodeModal');
    const inputEl = document.getElementById('salePasscodeInput') || document.getElementById('globalTransactionPasscodeInput');
    const errorEl = document.getElementById('salePasscodeError') || document.getElementById('globalTransactionPasscodeError');
    const confirmBtn = document.getElementById('salePasscodeConfirm') || document.getElementById('globalTransactionPasscodeConfirm');
    const modal = modalEl && window.bootstrap ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;

    let pendingAction = null;

    document.querySelectorAll('input[type="search"], .search-input, [placeholder*="Search"], [placeholder*="search"]').forEach((input) => {
      input.setAttribute('autocomplete', 'off');
      input.setAttribute('autocapitalize', 'off');
      input.setAttribute('autocorrect', 'off');
      input.setAttribute('spellcheck', 'false');
    });

    function clearError() {
      if (!errorEl) return;
      errorEl.textContent = '';
      errorEl.classList.add('d-none');
    }

    function setError(message) {
      if (!errorEl) return;
      errorEl.textContent = message || 'Invalid passcode.';
      errorEl.classList.remove('d-none');
    }

    function runPendingAction() {
      const action = pendingAction;
      pendingAction = null;
      if (typeof action === 'function') {
        action();
      }
    }

    async function verifyAndContinue() {
      const passcode = String(inputEl?.value || '').trim();
      clearError();

      if (!/^\d{4}$/.test(passcode)) {
        setError('Enter a valid 4-digit passcode.');
        inputEl?.focus();
        return;
      }

      const payload = new URLSearchParams();
      payload.set('_token', csrfToken);
      payload.set('passcode', passcode);

      try {
        const response = await fetch(verifyUrl, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
          },
          credentials: 'same-origin',
          body: payload.toString(),
        });

        const text = await response.text();
        let data = {};
        try {
          data = text ? JSON.parse(text) : {};
        } catch (error) {
          data = {};
        }

        if (!response.ok) {
          throw new Error(data.message || `Request failed with status ${response.status}.`);
        }

        modal?.hide();
        runPendingAction();
      } catch (error) {
        setError(error.message || 'Invalid passcode.');
        inputEl?.focus();
      }
    }

    window.requestTransactionPasscode = function (actionFn) {
      if (!enabled || !verifyUrl || !modal) {
        if (typeof actionFn === 'function') actionFn();
        return;
      }

      pendingAction = actionFn;
      clearError();
      if (inputEl) inputEl.value = '';
      modal.show();
      window.setTimeout(() => inputEl?.focus(), 120);
    };

    window.transactionPasscodeNavigate = function (url) {
      window.requestTransactionPasscode(function () {
        window.location.href = url;
      });
      return false;
    };

    window.transactionPasscodeExecute = function (callbackName) {
      const args = Array.prototype.slice.call(arguments, 1);
      window.requestTransactionPasscode(function () {
        const callback = window[callbackName];
        if (typeof callback === 'function') {
          callback.apply(window, args);
        }
      });
      return false;
    };

    confirmBtn?.addEventListener('click', verifyAndContinue);
    inputEl?.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        verifyAndContinue();
      }
    });

    modalEl?.addEventListener('hidden.bs.modal', function () {
      pendingAction = null;
      clearError();
      if (inputEl) inputEl.value = '';
    });

    modalEl?.addEventListener('shown.bs.modal', function () {
      window.setTimeout(() => {
        document.querySelectorAll('input[type="search"], .search-input, [placeholder*="Search"], [placeholder*="search"]').forEach((input) => {
          if (input instanceof HTMLInputElement && input !== document.activeElement && input.matches(':not(:focus)')) {
            input.value = input.value && input.value.includes('@') ? '' : input.value;
          }
        });
      }, 120);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTransactionPasscodeGuard);
  } else {
    initTransactionPasscodeGuard();
  }
})();
