<div class="header">
  <div class="header-inner">
    <div class="header-brand">
      <h1>Modul Katalog Produk</h1>
      <nav class="nav-links" aria-label="Navigasi utama">
        <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">Katalog</a>

        @can('view-stock-report')
          <a href="{{ route('laporan.stok') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">Laporan Stok</a>
        @endcan

        @can('manage-users')
          <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Manajemen User</a>
        @endcan
      </nav>
    </div>

    <div class="header-right">
      @auth
        <div class="account-dropdown" id="accountDropdown">
          <button type="button" class="account-trigger" data-account-toggle aria-expanded="false" aria-controls="accountMenu">
            <span>{{ auth()->user()->name }}</span>
            <span class="role-tag">{{ auth()->user()->role->label() }}</span>
            <span class="caret" aria-hidden="true">▾</span>
          </button>

          <div id="accountMenu" class="account-menu" role="menu">
            <a href="{{ route('profil.edit') }}" class="account-item" role="menuitem">Profil Saya</a>
            <div class="account-divider"></div>
            <form action="{{ route('logout') }}" method="post">
              @csrf
              <button type="submit" class="account-item account-item-danger" role="menuitem">Keluar</button>
            </form>
          </div>
        </div>
      @else
        <div class="nav-links">
          <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Masuk</a>
          <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">Daftar</a>
        </div>
      @endauth
    </div>
  </div>
</div>

@if (auth()->check() && auth()->user()->isAdmin())
  <div id="confirmModal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="confirmModalTitle">
    <div class="modal-box">
      <h3 id="confirmModalTitle" class="modal-title">Konfirmasi Hapus</h3>
      <p id="confirmModalMessage" class="modal-text">Yakin ingin melanjutkan?</p>
      <div class="modal-actions">
        <button type="button" id="confirmModalCancel" class="btn-secondary">Batal</button>
        <button type="button" id="confirmModalOk" class="btn-hapus">Ya, Hapus</button>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('confirmModal');
    if (!modal) return;

    const msgEl = document.getElementById('confirmModalMessage');
    const btnOk = document.getElementById('confirmModalOk');
    const btnCancel = document.getElementById('confirmModalCancel');
    let pendingForm = null;
    let pendingBtn = null;

    document.addEventListener('submit', function (e) {
      const form = e.target.closest('form[data-confirm]');
      if (!form) return;

      if (form.dataset.confirmed === '1') {
        delete form.dataset.confirmed;
        return;
      }

      e.preventDefault();
      pendingForm = form;
      pendingBtn = e.submitter || null;
      msgEl.textContent = form.getAttribute('data-confirm') || 'Yakin ingin melanjutkan?';
      modal.classList.add('show');
      btnCancel.focus();
    });

    function closeModal() {
      modal.classList.remove('show');
      pendingForm = null;
      pendingBtn = null;
    }

    btnCancel.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
    });

    btnOk.addEventListener('click', function () {
      if (!pendingForm) return;
      const formToSubmit = pendingForm;
      const submitter = pendingBtn;
      closeModal();
      formToSubmit.dataset.confirmed = '1';
      if (submitter && formToSubmit.requestSubmit) {
        formToSubmit.requestSubmit(submitter);
      } else {
        HTMLFormElement.prototype.submit.call(formToSubmit);
      }
    });
  });
  </script>
@endif

<script>
(function () {
  // Dropdown Akun Header
  document.addEventListener('click', function (e) {
    const toggle = e.target.closest('[data-account-toggle]');
    const dropdown = document.getElementById('accountDropdown');
    const menu = document.getElementById('accountMenu');
    if (!dropdown || !menu) return;

    if (toggle) {
      const isOpen = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      return;
    }

    if (!e.target.closest('#accountDropdown')) {
      menu.classList.remove('open');
      const btn = dropdown.querySelector('[data-account-toggle]');
      if (btn) btn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const menu = document.getElementById('accountMenu');
      const btn = document.querySelector('[data-account-toggle]');
      if (menu) menu.classList.remove('open');
      if (btn) btn.setAttribute('aria-expanded', 'false');
    }
  });

  // Toggle Ikon Mata Password
  const ICON_EYE = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
  const ICON_EYE_OFF = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

  window.setPasswordToggleState = function (btn, show) {
    btn.innerHTML = show ? ICON_EYE_OFF : ICON_EYE;
    btn.setAttribute('aria-pressed', show ? 'true' : 'false');
    btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
  };

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
      window.setPasswordToggleState(btn, false);
    });
  });

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-toggle-password]');
    if (!btn) return;
    const input = document.getElementById(btn.getAttribute('data-toggle-password'));
    if (!input) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    window.setPasswordToggleState(btn, show);
  });
})();
</script>