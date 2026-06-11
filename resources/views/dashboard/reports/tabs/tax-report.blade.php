{{-- TAX REPORT TAB --}}
<div id="tab-tax report" class="report-tab-content d-none">
    <div class="d-flex flex-column" style="min-height:100vh;padding:24px;background:#fff;border:1px solid #e5e7eb;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h2 style="font-weight:700;color:#1f2937;font-size:22px;margin:0;">Tax Report</h2>
                <p style="font-size:12px;color:#6b7280;margin:4px 0 0;">Sales tax in, purchase tax out, and net tax for selected period.</p>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div style="display:flex;align-items:center;gap:8px;background:#f1f5f9;border-radius:6px;padding:6px 12px;">
                    <span style="font-size:12px;color:#6b7280;">From</span>
                    <input type="date" id="tr-from" style="border:none;outline:none;font-size:13px;color:#374151;background:transparent;font-weight:500;" value="{{ date('Y-m-d', strtotime('first day of this month')) }}">
                    <span style="font-size:12px;color:#9ca3af;">to</span>
                    <input type="date" id="tr-to" style="border:none;outline:none;font-size:13px;color:#374151;background:transparent;font-weight:500;" value="{{ date('Y-m-d') }}">
                    <button id="tr-apply" style="font-size:12px;padding:5px 12px;background:#2563eb;color:#fff;border:none;border-radius:4px;cursor:pointer;">Apply</button>
                </div>
                <button id="tr-excel-btn" title="Export Excel" style="width:38px;height:38px;border-radius:50%;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                    <i class="fa-solid fa-file-excel" style="color:#10b981;font-size:17px;"></i>
                </button>
                <button id="tr-print-btn" title="Print" style="width:38px;height:38px;border-radius:50%;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                    <i class="fa-solid fa-print" style="color:#4b5563;font-size:17px;"></i>
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 mb-3">
            <div style="min-width:180px;flex:1;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px 16px;">
                <div style="font-size:12px;color:#15803d;font-weight:600;">Total Tax In</div>
                <div id="tr-total-in" style="font-size:22px;color:#166534;font-weight:700;margin-top:3px;">Rs 0.00</div>
            </div>
            <div style="min-width:180px;flex:1;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;">
                <div style="font-size:12px;color:#b91c1c;font-weight:600;">Total Tax Out</div>
                <div id="tr-total-out" style="font-size:22px;color:#991b1b;font-weight:700;margin-top:3px;">Rs 0.00</div>
            </div>
            <div style="min-width:180px;flex:1;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:14px 16px;">
                <div style="font-size:12px;color:#1d4ed8;font-weight:600;">Net Tax</div>
                <div id="tr-net-tax" style="font-size:22px;color:#1e40af;font-weight:700;margin-top:3px;">Rs 0.00</div>
            </div>
        </div>

        <div id="tr-loading" class="d-none text-center py-5">
            <div class="spinner-border text-primary"><span class="visually-hidden">Loading...</span></div>
        </div>

        <div id="tr-table-wrap" class="table-responsive" style="border:1px solid #e5e7eb;border-radius:8px;overflow:auto;">
            <table id="tr-table" style="width:100%;border-collapse:collapse;min-width:920px;">
                <thead style="background:#f3f4f6;">
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:left;">Date</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:left;">Bill No</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:left;">Party</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:left;">Type</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:right;">Taxable Amount</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:right;">Tax Rate</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:right;">Tax Amount</th>
                    </tr>
                </thead>
                <tbody id="tr-body">
                    <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:40px;font-size:14px;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PRINT MODAL --}}
<div class="modal fade" id="trPrintModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="border-radius:10px;overflow:hidden;">
      <div class="modal-header" style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-print me-2 text-secondary"></i>Print Preview - Tax Report</h5>
        <div class="d-flex gap-2 align-items-center">
          <button id="tr-do-print" class="btn btn-sm btn-primary px-3"><i class="fa-solid fa-print me-1"></i>Print</button>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
      </div>
      <div class="modal-body p-0" style="background:#e5e7eb;">
        <div id="tr-print-area" style="background:#fff;margin:24px auto;padding:40px 48px;max-width:960px;box-shadow:0 4px 20px rgba(0,0,0,.10);border-radius:8px;font-family:Inter,sans-serif;">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div>
              <h2 style="font-size:20px;font-weight:700;color:#111827;margin:0 0 4px;">Tax Report</h2>
              <p id="tr-p-range" style="font-size:12px;color:#9ca3af;margin:0;"></p>
            </div>
            <p style="font-size:12px;color:#9ca3af;margin:0;">Printed: <span id="tr-p-date"></span></p>
          </div>
          <div id="tr-p-summary" style="display:flex;gap:10px;margin-bottom:14px;"></div>
          <table style="width:100%;border-collapse:collapse;">
            <thead>
              <tr style="background:#f3f4f6;">
                <th style="padding:8px;font-size:11px;font-weight:700;color:#6b7280;border:1px solid #e5e7eb;">Date</th>
                <th style="padding:8px;font-size:11px;font-weight:700;color:#6b7280;border:1px solid #e5e7eb;">Bill No</th>
                <th style="padding:8px;font-size:11px;font-weight:700;color:#6b7280;border:1px solid #e5e7eb;">Party</th>
                <th style="padding:8px;font-size:11px;font-weight:700;color:#6b7280;border:1px solid #e5e7eb;">Type</th>
                <th style="padding:8px;font-size:11px;font-weight:700;color:#6b7280;text-align:right;border:1px solid #e5e7eb;">Taxable</th>
                <th style="padding:8px;font-size:11px;font-weight:700;color:#6b7280;text-align:right;border:1px solid #e5e7eb;">Rate</th>
                <th style="padding:8px;font-size:11px;font-weight:700;color:#6b7280;text-align:right;border:1px solid #e5e7eb;">Tax</th>
              </tr>
            </thead>
            <tbody id="tr-p-body"></tbody>
          </table>
          <p style="font-size:11px;color:#d1d5db;text-align:center;margin-top:24px;">Computer-generated report.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  var trData = [];
  var trSummary = {};
  var csrf = window.App?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';

  function el(id){ return document.getElementById(id); }
  function num(v){ return parseFloat(String(v || 0).replace(/,/g, '')) || 0; }
  function fmt(v){ return 'Rs ' + num(v).toLocaleString('en-PK', {minimumFractionDigits:2, maximumFractionDigits:2}); }
  function fmtRate(v){ return num(v).toLocaleString('en-PK', {maximumFractionDigits:2}) + '%'; }
  function esc(v){
    return String(v ?? '').replace(/[&<>"']/g, function(c){
      return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c];
    });
  }
  function fmtD(s){
    if (!s) return '';
    var d = new Date(s + 'T00:00:00');
    return isNaN(d) ? s : d.toLocaleDateString('en-GB');
  }

  function setLoading(isLoading){
    el('tr-loading').classList.toggle('d-none', !isLoading);
    el('tr-table-wrap').classList.toggle('d-none', isLoading);
  }

  function renderEmpty(msg){
    el('tr-body').innerHTML = '<tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:40px;font-size:14px;">' + esc(msg) + '</td></tr>';
    el('tr-total-in').textContent = 'Rs 0.00';
    el('tr-total-out').textContent = 'Rs 0.00';
    el('tr-net-tax').textContent = 'Rs 0.00';
    trData = [];
    trSummary = {};
  }

  function renderRows(rows, summary){
    trData = rows || [];
    trSummary = summary || {};

    el('tr-total-in').textContent = fmt(summary.sale_tax);
    el('tr-total-out').textContent = fmt(summary.purchase_tax);
    el('tr-net-tax').textContent = fmt(summary.net_tax);

    if (!trData.length) {
      renderEmpty('No tax data found for this period.');
      return;
    }

    el('tr-body').innerHTML = trData.map(function(r){
      var isSale = String(r.type || '').toLowerCase() === 'sale';
      var typeColor = isSale ? '#16a34a' : '#dc2626';
      var taxColor = isSale ? '#15803d' : '#b91c1c';
      return '<tr style="border-bottom:1px solid #f3f4f6;">'
        + '<td style="padding:12px 14px;font-size:13px;color:#374151;">' + esc(fmtD(r.date)) + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:#374151;">' + esc(r.bill_number || '-') + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:#111827;font-weight:600;">' + esc(r.party_name || 'Walk-in / Cash') + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:' + typeColor + ';font-weight:700;">' + esc(r.type || '-') + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:#374151;text-align:right;">' + fmt(r.total_amount) + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:#374151;text-align:right;">' + fmtRate(r.tax_rate) + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:' + taxColor + ';font-weight:700;text-align:right;">' + fmt(r.tax_amount) + '</td>'
        + '</tr>';
    }).join('');
  }

  function load(){
    var params = new URLSearchParams();
    if (el('tr-from').value) params.append('from', el('tr-from').value);
    if (el('tr-to').value) params.append('to', el('tr-to').value);
    setLoading(true);
    fetch('/dashboard/reports/tax-report?' + params.toString(), {headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}})
      .then(function(r){ if (!r.ok) throw new Error(r.status); return r.json(); })
      .then(function(data){ renderRows(data.rows || [], data); })
      .catch(function(e){ console.error(e); renderEmpty('Failed to load tax report.'); })
      .finally(function(){ setLoading(false); });
  }

  el('tr-apply')?.addEventListener('click', load);
  el('tr-excel-btn')?.addEventListener('click', function(){
    var params = new URLSearchParams();
    if (el('tr-from').value) params.append('from', el('tr-from').value);
    if (el('tr-to').value) params.append('to', el('tr-to').value);
    window.location.href = '/dashboard/reports/tax-report/export?' + params.toString();
  });

  el('tr-print-btn')?.addEventListener('click', function(){
    el('tr-p-date').textContent = new Date().toLocaleDateString('en-GB');
    el('tr-p-range').textContent = 'Period: ' + fmtD(el('tr-from').value) + ' to ' + fmtD(el('tr-to').value);
    el('tr-p-summary').innerHTML =
      '<div style="flex:1;padding:10px;border:1px solid #bbf7d0;background:#f0fdf4;font-size:12px;"><strong>Total Tax In</strong><br>' + esc(fmt(trSummary.sale_tax)) + '</div>'
      + '<div style="flex:1;padding:10px;border:1px solid #fecaca;background:#fef2f2;font-size:12px;"><strong>Total Tax Out</strong><br>' + esc(fmt(trSummary.purchase_tax)) + '</div>'
      + '<div style="flex:1;padding:10px;border:1px solid #bfdbfe;background:#eff6ff;font-size:12px;"><strong>Net Tax</strong><br>' + esc(fmt(trSummary.net_tax)) + '</div>';
    el('tr-p-body').innerHTML = trData.length ? trData.map(function(r){
      return '<tr>'
        + '<td style="padding:7px;font-size:11px;border:1px solid #e5e7eb;">' + esc(fmtD(r.date)) + '</td>'
        + '<td style="padding:7px;font-size:11px;border:1px solid #e5e7eb;">' + esc(r.bill_number || '-') + '</td>'
        + '<td style="padding:7px;font-size:11px;border:1px solid #e5e7eb;">' + esc(r.party_name || 'Walk-in / Cash') + '</td>'
        + '<td style="padding:7px;font-size:11px;border:1px solid #e5e7eb;">' + esc(r.type || '-') + '</td>'
        + '<td style="padding:7px;font-size:11px;text-align:right;border:1px solid #e5e7eb;">' + fmt(r.total_amount) + '</td>'
        + '<td style="padding:7px;font-size:11px;text-align:right;border:1px solid #e5e7eb;">' + fmtRate(r.tax_rate) + '</td>'
        + '<td style="padding:7px;font-size:11px;text-align:right;border:1px solid #e5e7eb;">' + fmt(r.tax_amount) + '</td>'
        + '</tr>';
    }).join('') : '<tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:14px;font-size:12px;border:1px solid #e5e7eb;">No data</td></tr>';
    new bootstrap.Modal(el('trPrintModal')).show();
  });

  el('tr-do-print')?.addEventListener('click', function(){
    var w = window.open('', '_blank', 'width=980,height=720');
    w.document.write('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Tax Report</title><style>*{box-sizing:border-box}body{font-family:Inter,Arial,sans-serif;padding:28px}table{width:100%;border-collapse:collapse}</style></head><body>' + el('tr-print-area').innerHTML + '</body></html>');
    w.document.close();
    w.focus();
    setTimeout(function(){ w.print(); w.close(); }, 400);
  });

  document.querySelectorAll('[data-target="tax report"]').forEach(function(link){
    link.addEventListener('click', function(){ setTimeout(load, 50); });
  });

  setTimeout(function(){
    if (el('tab-tax report') && !el('tab-tax report').classList.contains('d-none')) load();
  }, 150);
})();
</script>
