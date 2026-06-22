(() => {
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];

  $('#year').textContent = new Date().getFullYear();
  $('#started_at').value = Math.floor(Date.now() / 1000).toString();

  const toggle = $('.nav-toggle');
  const menu = $('#nav-menu');
  toggle.addEventListener('click', () => {
    const open = menu.classList.toggle('open');
    toggle.setAttribute('aria-expanded', String(open));
  });
  $$('#nav-menu a').forEach((link) => link.addEventListener('click', () => {
    menu.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
  }));

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.16 });
  $$('.reveal').forEach((el) => observer.observe(el));

  if (matchMedia('(pointer:fine) and (min-width:861px)').matches) {
    $$('[data-product-card]').forEach((card) => {
      card.addEventListener('pointermove', (event) => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--x', `${event.clientX - rect.left}px`);
        card.style.setProperty('--y', `${event.clientY - rect.top}px`);
      });
    });
  }

  const form = $('#contact-form');
  const productSelect = $('#product');
  const productNote = $('#product-note');
  const status = $('#form-status');
  const requiredFields = ['name', 'email', 'message', 'consent'].map((id) => $(`#${id}`));

  function focusFirstEmptyRequired() {
    const empty = requiredFields.find((field) => field.type === 'checkbox' ? !field.checked : !field.value.trim());
    if (empty) empty.focus({ preventScroll: true });
  }

  $$('.product-cta').forEach((button) => {
    button.addEventListener('click', () => {
      const product = button.dataset.product;
      productSelect.value = product;
      productNote.hidden = false;
      productNote.textContent = `Produit sélectionné : ${product}. Vous pouvez compléter la demande pour recevoir une proposition adaptée.`;
      $('#contact').scrollIntoView({ behavior: 'smooth', block: 'start' });
      setTimeout(focusFirstEmptyRequired, 550);
    });
  });

  function setStatus(message, type) {
    status.textContent = message;
    status.className = `form-status ${type || ''}`.trim();
  }

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    setStatus('', '');
    if (!form.checkValidity()) {
      form.reportValidity();
      setStatus('Merci de compléter les champs obligatoires correctement.', 'error');
      focusFirstEmptyRequired();
      return;
    }
    const submit = form.querySelector('button[type="submit"]');
    submit.disabled = true;
    setStatus('Envoi en cours...', '');
    try {
      const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'Erreur serveur.');
      form.reset();
      $('#started_at').value = Math.floor(Date.now() / 1000).toString();
      productNote.hidden = true;
      setStatus(data.message || 'Votre demande a bien été envoyée.', 'success');
    } catch (error) {
      setStatus(error.message || 'Impossible d’envoyer le formulaire pour le moment.', 'error');
    } finally {
      submit.disabled = false;
    }
  });
})();
