<div id="tab-BalanceSheet" class="report-tab-content d-none balance-sheet-tab">
  <style>
    .balance-sheet-tab {
      background: #eef2f5;
      min-height: 100vh;
      color: #1f2937;
    }
    .bs-topbar {
      background: #f8fafc;
      border-bottom: 1px solid #d9e0e7;
      padding: 10px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
    }
    .bs-filter-row {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      font-size: 13px;
    }
    .bs-filter-row label {
      color: #374151;
      font-weight: 600;
      margin: 0;
    }
    .bs-filter-row .form-select,
    .bs-filter-row .form-control {
      height: 34px;
      border: 1px solid #cfd8e3;
      border-radius: 7px;
      font-size: 13px;
      box-shadow: none;
      background-color: #fff;
    }
    .bs-actions {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .bs-icon-btn {
      width: 34px;
      height: 34px;
      border-radius: 7px;
      border: 1px solid #d7dee8;
      background: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #4b5563;
    }
    .bs-icon-btn:hover {
      background: #f3f6f9;
    }
    .bs-view-toggle {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      font-size: 12px;
      color: #374151;
      margin-right: 8px;
    }
    .bs-switch {
      width: 44px;
      height: 22px;
      border-radius: 999px;
      border: 0;
      background: #cbd5e1;
      position: relative;
      cursor: pointer;
    }
    .bs-switch::after {
      content: "";
      width: 16px;
      height: 16px;
      background: #2563eb;
      border-radius: 50%;
      position: absolute;
      top: 3px;
      left: 4px;
      transition: left .18s ease;
    }
    .bs-switch.vertical::after {
      left: 24px;
    }
    .bs-titlebar {
      padding: 14px 16px;
      background: #eef2f5;
      border-bottom: 1px solid #dfe5ec;
    }
    .bs-titlebar h4 {
      margin: 0 0 18px;
      font-size: 20px;
      font-weight: 700;
      color: #1f2937;
    }
    .bs-as-on {
      font-size: 14px;
      font-weight: 700;
      color: #374151;
      margin: 0;
    }
    .bs-warning {
      margin: 12px 16px 0;
      border: 1px solid #f4c7c7;
      background: #fef2f2;
      color: #991b1b;
      border-radius: 7px;
      padding: 10px 12px;
      font-size: 13px;
      display: none;
    }
    .bs-report-shell {
      margin: 0;
      background: #fff;
      border-top: 1px solid #dfe5ec;
      border-bottom: 1px solid #dfe5ec;
      min-height: calc(100vh - 210px);
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    }
    .bs-report-shell.vertical {
      grid-template-columns: 1fr;
    }
    .bs-panel {
      min-width: 0;
      border-right: 1px solid #dfe5ec;
      display: flex;
      flex-direction: column;
    }
    .bs-panel:last-child {
      border-right: 0;
    }
    .bs-table-head {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 135px;
      background: #f3f6f9;
      color: #6b7280;
      text-transform: uppercase;
      font-size: 11px;
      font-weight: 700;
      border-bottom: 1px solid #dfe5ec;
    }
    .bs-table-head span {
      padding: 11px 12px;
    }
    .bs-section-title {
      font-size: 16px;
      font-weight: 700;
      padding: 14px 12px 12px;
      color: #1f2937;
    }
    .bs-tree {
      padding-bottom: 72px;
    }
    .bs-row {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 135px;
      align-items: center;
      min-height: 43px;
      border-bottom: 1px dashed #e5e7eb;
      font-size: 13px;
    }
    .bs-row:hover {
      background: #f8fafc;
    }
    .bs-account {
      min-width: 0;
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 12px;
      color: #374151;
      font-weight: 600;
    }
    .bs-row.child .bs-account {
      color: #4b5563;
      font-weight: 500;
    }
    .bs-row.leaf .bs-account {
      font-weight: 500;
    }
    .bs-name {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .bs-amount {
      padding: 10px 12px;
      text-align: right;
      color: #374151;
      font-weight: 700;
    }
    .bs-toggle-btn {
      border: 0;
      background: transparent;
      color: #1d79a8;
      width: 18px;
      height: 18px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 18px;
    }
    .bs-dot {
      color: #6b7280;
      width: 18px;
      text-align: center;
      flex: 0 0 18px;
      font-size: 16px;
      line-height: 1;
    }
    .bs-total-bar {
      position: sticky;
      bottom: 0;
      display: grid;
      grid-template-columns: minmax(0, 1fr) 135px;
      background: #d9eef9;
      border-top: 1px solid #b9dff3;
      color: #1d4ed8;
      font-weight: 800;
      font-size: 14px;
      margin-top: auto;
      z-index: 4;
    }
    .bs-total-bar span {
      padding: 12px;
    }
    .bs-total-bar span:last-child {
      text-align: right;
    }
    .bs-loading {
      padding: 36px;
      text-align: center;
      color: #64748b;
      font-size: 13px;
    }
    @media (max-width: 900px) {
      .bs-report-shell {
        grid-template-columns: 1fr;
      }
      .bs-panel {
        border-right: 0;
        border-bottom: 1px solid #dfe5ec;
      }
      .bs-table-head,
      .bs-row,
      .bs-total-bar {
        grid-template-columns: minmax(0, 1fr) 110px;
      }
    }
    @media print {
      body * {
        visibility: hidden;
      }
      #tab-BalanceSheet,
      #tab-BalanceSheet * {
        visibility: visible;
      }
      #tab-BalanceSheet {
        position: absolute;
        inset: 0;
        background: #fff;
      }
      .reports-sidebar,
      .bs-actions,
      .bs-filter-row,
      .bs-view-toggle {
        display: none !important;
      }
      .bs-report-shell {
        min-height: auto;
      }
      .bs-total-bar {
        position: static;
      }
    }
  </style>

  <div class="bs-titlebar">
    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
      <h4>Balance Sheet</h4>
      <div class="bs-actions">
        <span class="bs-view-toggle">
          <span>Horizontal</span>
          <button type="button" id="bsViewSwitch" class="bs-switch" aria-label="Toggle balance sheet view"></button>
          <span>Vertical</span>
        </span>
        <button type="button" class="bs-icon-btn" id="bsPdfBtn" title="Export PDF">
          <i class="fa-solid fa-file-pdf" style="color:#c0392b;"></i>
        </button>
        <button type="button" class="bs-icon-btn" id="bsExcelBtn" title="Export Excel">
          <i class="fa-solid fa-file-excel" style="color:#15803d;"></i>
        </button>
        <button type="button" class="bs-icon-btn" id="bsPrintBtn" title="Print">
          <i class="fa-solid fa-print"></i>
        </button>
      </div>
    </div>
  </div>

  <div class="bs-topbar">
    <div class="bs-filter-row">
      <label for="bsPeriod">Period :</label>
      <select id="bsPeriod" class="form-select" style="width: 132px;">
        <option value="today">Today</option>
        <option value="this_month">This Month</option>
        <option value="this_year" selected>This Year</option>
        <option value="custom">Custom</option>
      </select>
      <input type="date" id="bsFrom" class="form-control" style="width: 145px;">
      <span class="fw-semibold text-muted">To</span>
      <input type="date" id="bsTo" class="form-control" style="width: 145px;">
      <button type="button" id="bsApplyBtn" class="btn btn-sm btn-primary px-3">Apply</button>
    </div>
  </div>

  <div class="bs-titlebar" style="padding-top: 12px;">
    <p class="bs-as-on" id="bsAsOn">Balance Sheet as on --</p>
  </div>

  <div class="bs-warning" id="bsMismatchWarning"></div>

  <div class="bs-report-shell" id="bsReportShell">
    <section class="bs-panel">
      <div class="bs-table-head"><span>Account</span><span class="text-end">Amount</span></div>
      <div class="bs-section-title">Equities &amp; Liabilities</div>
      <div class="bs-tree" id="bsLiabilitiesTree">
        <div class="bs-loading">Loading...</div>
      </div>
      <div class="bs-total-bar"><span>Total Equities &amp; Liabilities</span><span id="bsLiabilitiesTotal">0</span></div>
    </section>

    <section class="bs-panel">
      <div class="bs-table-head"><span>Account</span><span class="text-end">Amount</span></div>
      <div class="bs-section-title">Assets</div>
      <div class="bs-tree" id="bsAssetsTree">
        <div class="bs-loading">Loading...</div>
      </div>
      <div class="bs-total-bar"><span>Total Assets</span><span id="bsAssetsTotal">0</span></div>
    </section>
  </div>

  <script>
  (function () {
    const reportUrl = @json(url('/dashboard/reports/balance-sheet'));
    const state = { data: null, collapsed: new Set(), view: 'horizontal' };
    const $ = id => document.getElementById(id);
    const fmt = value => Number(value || 0).toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    const ymd = d => d.toISOString().slice(0, 10);
    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, ch => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[ch]));

    function setPeriodDates(period) {
      const now = new Date();
      let from = new Date(now);
      let to = new Date(now);
      if (period === 'this_month') {
        from = new Date(now.getFullYear(), now.getMonth(), 1);
      } else if (period === 'this_year') {
        from = new Date(now.getFullYear(), 0, 1);
      }
      $('bsFrom').value = ymd(from);
      $('bsTo').value = ymd(to);
    }

    function rowHtml(node, level) {
      const children = Array.isArray(node.children) ? node.children : [];
      const hasChildren = children.length > 0;
      const collapsed = state.collapsed.has(node.id);
      const indent = 12 + (level * 24);
      const rowClass = level > 0 ? 'child' : '';
      const leafClass = hasChildren ? '' : 'leaf';
      let html = `
        <div class="bs-row ${rowClass} ${leafClass}" data-node-id="${escapeHtml(node.id)}">
          <div class="bs-account" style="padding-left:${indent}px;">
            ${hasChildren
              ? `<button type="button" class="bs-toggle-btn" data-bs-toggle-node="${escapeHtml(node.id)}"><i class="fa-solid ${collapsed ? 'fa-chevron-right' : 'fa-chevron-down'}"></i></button>`
              : '<span class="bs-dot">&bull;</span>'}
            <span class="bs-name" title="${escapeHtml(node.label)}">${escapeHtml(node.label)}</span>
          </div>
          <div class="bs-amount">${fmt(node.amount)}</div>
        </div>`;
      if (hasChildren && !collapsed) {
        html += children.map(child => rowHtml(child, level + 1)).join('');
      }
      return html;
    }

    function render() {
      if (!state.data) return;
      $('bsAsOn').textContent = 'Balance Sheet as on ' + state.data.period.as_on_label;
      $('bsLiabilitiesTree').innerHTML = state.data.tree.equities_liabilities.map(node => rowHtml(node, 0)).join('');
      $('bsAssetsTree').innerHTML = state.data.tree.assets.map(node => rowHtml(node, 0)).join('');
      $('bsLiabilitiesTotal').textContent = fmt(state.data.totals.equities_liabilities);
      $('bsAssetsTotal').textContent = fmt(state.data.totals.assets);

      const warn = $('bsMismatchWarning');
      if (state.data.totals.is_balanced) {
        warn.style.display = 'none';
        warn.textContent = '';
      } else {
        warn.style.display = 'block';
        warn.textContent = 'Warning: Assets and Equities & Liabilities do not match. Difference: Rs ' + fmt(state.data.totals.mismatch);
      }
    }

    function loadBalanceSheet() {
      const from = $('bsFrom').value;
      const to = $('bsTo').value;
      $('bsLiabilitiesTree').innerHTML = '<div class="bs-loading">Loading...</div>';
      $('bsAssetsTree').innerHTML = '<div class="bs-loading">Loading...</div>';

      fetch(reportUrl + '?from=' + encodeURIComponent(from) + '&to=' + encodeURIComponent(to), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      })
        .then(response => response.json())
        .then(data => {
          if (!data.success) throw new Error('Could not load balance sheet.');
          state.data = data;
          render();
        })
        .catch(() => {
          $('bsLiabilitiesTree').innerHTML = '<div class="bs-loading text-danger">Could not load report.</div>';
          $('bsAssetsTree').innerHTML = '<div class="bs-loading text-danger">Could not load report.</div>';
        });
    }

    function flatten(nodes, prefix = '') {
      return nodes.flatMap(node => {
        const row = [[prefix + node.label, fmt(node.amount)]];
        const children = Array.isArray(node.children) ? node.children : [];
        return row.concat(flatten(children, prefix + '  '));
      });
    }

    function exportCsv() {
      if (!state.data) return;
      const rows = [
        ['Balance Sheet as on ' + state.data.period.as_on_label, ''],
        [],
        ['Equities & Liabilities', 'Amount'],
        ...flatten(state.data.tree.equities_liabilities),
        ['Total Equities & Liabilities', fmt(state.data.totals.equities_liabilities)],
        [],
        ['Assets', 'Amount'],
        ...flatten(state.data.tree.assets),
        ['Total Assets', fmt(state.data.totals.assets)]
      ];
      const csv = rows.map(row => row.map(cell => '"' + String(cell).replace(/"/g, '""') + '"').join(',')).join('\n');
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'Balance_Sheet_' + $('bsTo').value + '.csv';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    }

    function exportPdf() {
      window.print();
    }

    document.addEventListener('click', function (event) {
      const btn = event.target.closest('[data-bs-toggle-node]');
      if (!btn) return;
      const nodeId = btn.dataset.bsToggleNode;
      if (state.collapsed.has(nodeId)) state.collapsed.delete(nodeId);
      else state.collapsed.add(nodeId);
      render();
    });

    $('bsPeriod').addEventListener('change', function () {
      if (this.value !== 'custom') {
        setPeriodDates(this.value);
        loadBalanceSheet();
      }
    });
    ['bsFrom', 'bsTo'].forEach(id => {
      $(id).addEventListener('change', function () {
        $('bsPeriod').value = 'custom';
      });
    });
    $('bsApplyBtn').addEventListener('click', loadBalanceSheet);
    $('bsExcelBtn').addEventListener('click', exportCsv);
    $('bsPdfBtn').addEventListener('click', exportPdf);
    $('bsPrintBtn').addEventListener('click', () => window.print());
    $('bsViewSwitch').addEventListener('click', function () {
      const vertical = state.view !== 'vertical';
      state.view = vertical ? 'vertical' : 'horizontal';
      this.classList.toggle('vertical', vertical);
      $('bsReportShell').classList.toggle('vertical', vertical);
    });

    setPeriodDates('this_year');
    const tabEl = $('tab-BalanceSheet');
    if (tabEl) {
      new MutationObserver(function () {
        if (!tabEl.classList.contains('d-none') && !tabEl.dataset.loaded) {
          tabEl.dataset.loaded = '1';
          loadBalanceSheet();
        }
      }).observe(tabEl, { attributes: true, attributeFilter: ['class'] });
    }
  })();
  </script>
</div>
