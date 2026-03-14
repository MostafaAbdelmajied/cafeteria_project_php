(() => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  const setError = (field, message) => {
    const container = field.closest('[data-field]') || field.parentElement;
    if (!container) return;
    const errorEl = container.querySelector('[data-error]');
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.classList.toggle('hidden', !message);
    }
    field.classList.toggle('border-red-500', !!message);
    field.classList.toggle('ring-red-200', !!message);
  };

  const validateField = (field) => {
    const value = (field.value || '').trim();
    const isRequired = field.hasAttribute('required');
    if (isRequired && !value) {
      return 'This field is required.';
    }

    const type = field.getAttribute('data-validate');
    if (type === 'email' && value && !emailRegex.test(value)) {
      return 'Please enter a valid email address.';
    }

    if (type === 'number' && value) {
      const min = parseFloat(field.getAttribute('data-min') || '0');
      const num = parseFloat(value);
      if (Number.isNaN(num) || num < min) {
        return `Please enter a number greater than or equal to ${min}.`;
      }
    }

    if (type === 'min' && value) {
      const minLen = parseInt(field.getAttribute('data-min') || '1', 10);
      if (value.length < minLen) {
        return `Please enter at least ${minLen} characters.`;
      }
    }

    if (type === 'match' && value) {
      const target = document.querySelector(field.getAttribute('data-match') || '');
      if (target && value !== (target.value || '').trim()) {
        return 'Passwords do not match.';
      }
    }

    return '';
  };

  document.querySelectorAll('[data-validate-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      const fields = form.querySelectorAll('input, textarea, select');
      let hasError = false;

      fields.forEach((field) => {
        if (field.disabled || field.type === 'file') return;
        const message = validateField(field);
        setError(field, message);
        if (message) {
          hasError = true;
        }
      });

      const cartRequired = form.hasAttribute('data-require-cart');
      if (cartRequired) {
        const cartScope = form.closest('[data-cart]');
        const cartItems = cartScope ? cartScope.querySelectorAll('[data-cart-item]') : [];
        if (!cartItems.length) {
          hasError = true;
        }
      }

      const alertEl = form.querySelector('[data-form-alert]');
      if (alertEl) {
        alertEl.classList.toggle('hidden', !hasError);
        if (cartRequired && hasError && !form.querySelector('[data-error]')?.textContent) {
          alertEl.textContent = 'Please add at least one item to the cart.';
        }
      }

      if (hasError) {
        event.preventDefault();
      }
    });
  });

  document.querySelectorAll('[data-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const targetId = toggle.getAttribute('data-toggle');
      const target = document.querySelector(targetId);
      if (!target) return;
      target.classList.toggle('hidden');
    });
  });
})();
