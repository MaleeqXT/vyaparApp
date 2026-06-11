{{-- TAX RATE REPORT TAB --}}
<div id="tab-tax rate report" class="report-tab-content d-none">
    <div class="d-flex flex-column" style="min-height:100vh;padding:24px;background:#fff;border:1px solid #e5e7eb;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h2 style="font-weight:700;color:#1f2937;font-size:22px;margin:0;">Tax Rate Report</h2>
                <p style="font-size:12px;color:#6b7280;margin:4px 0 0;">Tax grouped by rate and transaction type.</p>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div style="display:flex;align-items:center;gap:8px;background:#f1f5f9;border-radius:6px;padding:6px 12px;">
                    <span style="font-size:12px;color:#6b7280;">From</span>
                    <input type="date" id="trr-from" style="border:none;outline:none;font-size:13px;color:#374151;background:transparent;font-weight:500;" value="{{ date('Y-m-d', strtotime('first day of this month')) }}">
                    <span style="font-size:12px;color:#9ca3af;">to</span>
                    <input type="date" id="trr-to" style="border:none;outline:none;font-size:13px;color:#374151;background:transparent;font-weight:500;" value="{{ date('Y-m-d') }}">
                    <button id="trr-apply" style="font-size:12px;padding:5px 12px;background:#2563eb;color:#fff;border:none;border-radius:4px;cursor:pointer;">Apply</button>
                </div>
                <button id="trr-excel-btn" title="Export Excel" style="width:38px;height:38px;border-radius:50%;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                    <i class="fa-solid fa-file-excel" style="color:#10b981;font-size:17px;"></i>
                </button>
            </div>
        </div>

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:14px 16px;margin-bottom:16px;max-width:320px;">
            <div style="font-size:12px;color:#1d4ed8;font-weight:600;">Total Tax</div>
            <div id="trr-total-tax" style="font-size:22px;color:#1e40af;font-weight:700;margin-top:3px;">Rs 0.00</div>
        </div>

        <div id="trr-loading" class="d-none text-center py-5">
            <div class="spinner-border text-primary"><span class="visually-hidden">Loading...</span></div>
        </div>

        <div id="trr-table-wrap" class="table-responsive" style="border:1px solid #e5e7eb;border-radius:8px;overflow:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:720px;">
                <thead style="background:#f3f4f6;">
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:left;">Type</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:right;">Tax Rate</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:right;">Taxable Amount</th>
                        <th style="padding:11px 14px;font-size:12px;font-weight:700;color:#6b7280;text-align:right;">Tax Amount</th>
                    </tr>
                </thead>
                <tbody id="trr-body">
                    <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:40px;font-size:14px;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function(){
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
  function setLoading(isLoading){
    el('trr-loading').classList.toggle('d-none', !isLoading);
    el('trr-table-wrap').classList.toggle('d-none', isLoading);
  }
  function renderEmpty(msg){
    el('trr-body').innerHTML = '<tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:40px;font-size:14px;">' + esc(msg) + '</td></tr>';
    el('trr-total-tax').textContent = 'Rs 0.00';
  }
  function renderRows(rows, summary){
    el('trr-total-tax').textContent = fmt(summary.total_tax);
    if (!rows.length) {
      renderEmpty('No tax rate data found for this period.');
      return;
    }
    el('trr-body').innerHTML = rows.map(function(r){
      var typeColor = String(r.type || '').toLowerCase() === 'sale' ? '#16a34a' : '#dc2626';
      return '<tr style="border-bottom:1px solid #f3f4f6;">'
        + '<td style="padding:12px 14px;font-size:13px;color:' + typeColor + ';font-weight:700;">' + esc(r.type || '-') + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:#374151;text-align:right;">' + fmtRate(r.tax_rate) + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:#374151;text-align:right;">' + fmt(r.taxable_amount) + '</td>'
        + '<td style="padding:12px 14px;font-size:13px;color:#111827;font-weight:700;text-align:right;">' + fmt(r.tax_amount) + '</td>'
        + '</tr>';
    }).join('');
  }
  function load(){
    var params = new URLSearchParams();
    if (el('trr-from').value) params.append('from', el('trr-from').value);
    if (el('trr-to').value) params.append('to', el('trr-to').value);
    setLoading(true);
    fetch('/dashboard/reports/tax-rate-report?' + params.toString(), {headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}})
      .then(function(r){ if (!r.ok) throw new Error(r.status); return r.json(); })
      .then(function(data){ renderRows(data.rows || [], data); })
      .catch(function(e){ console.error(e); renderEmpty('Failed to load tax rate report.'); })
      .finally(function(){ setLoading(false); });
  }
  el('trr-apply')?.addEventListener('click', load);
  el('trr-excel-btn')?.addEventListener('click', function(){
    var params = new URLSearchParams();
    if (el('trr-from').value) params.append('from', el('trr-from').value);
    if (el('trr-to').value) params.append('to', el('trr-to').value);
    window.location.href = '/dashboard/reports/tax-rate-report/export?' + params.toString();
  });
  document.querySelectorAll('[data-target="tax rate report"]').forEach(function(link){
    link.addEventListener('click', function(){ setTimeout(load, 50); });
  });
  setTimeout(function(){
    if (el('tab-tax rate report') && !el('tab-tax rate report').classList.contains('d-none')) load();
  }, 150);
})();
</script>
