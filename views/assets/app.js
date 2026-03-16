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

  // Image Preview Logic
  document.querySelectorAll('[data-image-preview]').forEach((input) => {
    input.addEventListener('change', (e) => {
      const file = e.target.files[0];
      const container = e.target.closest('[data-field]');
      if (!container) return;

      const previewContainer = container.querySelector('[data-preview-container]');
      const previewImg = container.querySelector('[data-preview-img]');
      const placeholder = container.querySelector('[data-upload-placeholder]');
      const currentImgContainer = container.querySelector('[data-current-img-container]');

      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (event) => {
          if (previewImg) previewImg.src = event.target.result;
          if (previewContainer) {
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex');
          }
          if (placeholder) placeholder.classList.add('hidden');
          if (currentImgContainer) currentImgContainer.classList.add('opacity-50');
        };
        reader.readAsDataURL(file);
      }
    });
  });

  // Form Handling
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

    form.addEventListener('reset', () => {
      form.querySelectorAll('[data-preview-container]').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('flex');
      });
      form.querySelectorAll('[data-upload-placeholder]').forEach(el => el.classList.remove('hidden'));
      form.querySelectorAll('[data-current-img-container]').forEach(el => el.classList.remove('opacity-50'));
      form.querySelectorAll('[data-error]').forEach(el => el.classList.add('hidden'));
      form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
    });
  });

  // Toggle Logic
  document.querySelectorAll('[data-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const targetId = toggle.getAttribute('data-toggle');
      const target = document.querySelector(targetId);
      if (!target) return;
      target.classList.toggle('hidden');
    });
  });
})();
