{{-- resources/views/dashboard/reports/partials/_party-report-scripts.blade.php --}}

<script>
// =============================================
// UTILITY HELPERS
// =============================================
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

const fmt      = new Intl.NumberFormat('en-PK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
const fmtNum   = v  => fmt.format(Number(v) || 0);
const fmtDate  = ds => {
    if (!ds) return '—';
    const d = new Date(ds);
    return isNaN(d) ? ds : d.toLocaleDateString('en-GB');
};
const escHtml  = s  => String(s ?? '').replace(/[&<>"']/g, c => (
    { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[c]
));

function showToast(msg, type = 'danger') {
    let c = document.getElementById('toast-container');
    if (!c) {
        c = document.createElement('div');
        c.id = 'toast-container';
        c.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:9999;min-width:260px';
        document.body.appendChild(c);
    }
    const t = document.createElement('div');
    t.className = `alert alert-${type} alert-dismissible shadow`;
    t.innerHTML = `${escHtml(msg)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    c.appendChild(t);
    setTimeout(() => t.remove(), 4000);
}

function setLoading(tbodyId, cols) {
    const el = document.getElementById(tbodyId);
    if (el) el.innerHTML = `<tr><td colspan="${cols}" class="text-center py-4">
        <div class="spinner-border spinner-border-sm text-primary me-2"></div>Loading...
    </td></tr>`;
}

function setEmpty(tbodyId, cols, msg = 'No records found.') {
    const el = document.getElementById(tbodyId);
    if (el) el.innerHTML = `<tr><td colspan="${cols}" class="text-center text-muted py-4">
        <i class="bi bi-inbox fs-4 d-block mb-1"></i>${escHtml(msg)}
    </td></tr>`;
}

function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    const rows  = Array.from(table.querySelectorAll('tr'));
    const csv   = rows.map(row =>
        Array.from(row.querySelectorAll('th,td'))
            .filter(cell => !cell.classList.contains('d-none'))
            .map(cell => `"${cell.innerText.replace(/"/g, '""')}"`)
            .join(',')
    ).join('\n');
    const blob  = new Blob([csv], { type: 'text/csv' });
    const url   = URL.createObjectURL(blob);
    const a     = Object.assign(document.createElement('a'), { href: url, download: filename });
    document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
}

function printSection(contentId, title = '') {
    const el = document.getElementById(contentId);
    if (!el) return;
    const w = window.open('', '_blank', 'width=900,height=700');
    w.document.write(`<!DOCTYPE html><html><head>
        <title>${escHtml(title)}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>@media print { body { -webkit-print-color-adjust: exact; } }</style>
    </head><body class="p-4">${el.innerHTML}</body></html>`);
    w.document.close();
    w.onload = () => { w.focus(); w.print(); };
}

function initResizableTable(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const headers = table.querySelectorAll('thead th');
    headers.forEach((th, index) => {
        if (index === headers.length - 1 || th.querySelector('.report-col-resizer')) return;
        th.style.minWidth = th.offsetWidth + 'px';
        const handle = document.createElement('span');
        handle.className = 'report-col-resizer';
        th.appendChild(handle);

        handle.addEventListener('mousedown', function (event) {
            event.preventDefault();
            const startX = event.pageX;
            const startWidth = th.offsetWidth;

            function onMove(moveEvent) {
                const nextWidth = Math.max(70, startWidth + (moveEvent.pageX - startX));
                th.style.width = nextWidth + 'px';
                th.style.minWidth = nextWidth + 'px';
            }

            function onUp() {
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
            }

            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
        });
    });
}

// =============================================
// POPULATE DROPDOWNS FROM SERVER DATA
// =============================================
(function populateDropdowns() {
    const priCat = document.getElementById('pri-category');
    if (priCat && priCat.options.length <= 1) {
        @foreach($categories ?? [] as $cat)
        priCat.appendChild(Object.assign(document.createElement('option'), {
            value: '{{ $cat->id }}',
            textContent: '{{ addslashes($cat->name) }}'
        }));
        @endforeach
    }

    const priItem = document.getElementById('pri-item');
    if (priItem && priItem.options.length <= 1) {
        @foreach($items ?? [] as $item)
        const _opt_{{ $item->id }} = document.createElement('option');
        _opt_{{ $item->id }}.value = '{{ $item->id }}';
        _opt_{{ $item->id }}.textContent = '{{ addslashes($item->name) }}';
        _opt_{{ $item->id }}.dataset.category = '{{ $item->category_id ?? '' }}';
        priItem.appendChild(_opt_{{ $item->id }});
        @endforeach
    }
})();

// Cascade: filter items when category changes
(function() {
    const catEl  = document.getElementById('pri-category');
    const itemEl = document.getElementById('pri-item');
    if (!catEl || !itemEl) return;
    const allItemOpts = Array.from(itemEl.options).slice(1).map(o => o.cloneNode(true));
    catEl.addEventListener('change', function () {
        const catId = this.value;
        while (itemEl.options.length > 1) itemEl.remove(1);
        allItemOpts.forEach(opt => {
            if (!catId || opt.dataset.category === catId) itemEl.appendChild(opt.cloneNode(true));
        });
        itemEl.value = '';
    });
})();

// =============================================
// ALL PARTIES TAB
// =============================================
(function allParties() {
    const tbody    = document.getElementById('ap-tbody');
    const typeEl   = document.getElementById('ap-type-filter');
    const groupEl  = document.getElementById('ap-group-filter');
    const searchEl = document.getElementById('ap-search');
    const exportBtn= document.getElementById('ap-excel-btn');
    const printBtn = document.getElementById('ap-print-btn');
    const totalRecEl = document.getElementById('ap-total-receivable');
    const totalPayEl = document.getElementById('ap-total-payable');
    if (!tbody) return;

    function renderRows(rows) {
        if (!rows.length) { setEmpty('ap-tbody', 8); return; }
        let totalRec = 0, totalPay = 0;
        tbody.innerHTML = rows.map((p) => {
            const rec = Number(p.receivable_balance ?? 0);
            const pay = Number(p.payable_balance ?? 0);
            totalRec += rec;
            totalPay += pay;
            const creditLimit = p.credit_limit_enabled ? fmtNum(p.credit_limit_amount ?? 0) : '—';
            return `<tr>
                <td><input type="checkbox"></td>
                <td class="fw-semibold">${escHtml(p.name)}</td>
                <td>${escHtml(p.party_group ?? 'Ungrouped')}</td>
                <td>${escHtml(p.email ?? '—')}</td>
                <td>${escHtml(p.phone ?? '—')}</td>
                <td class="text-end text-success">${rec > 0 ? fmtNum(rec) : '—'}</td>
                <td class="text-end text-danger">${pay > 0 ? fmtNum(pay) : '—'}</td>
                <td class="text-end">${creditLimit}</td>
            </tr>`;
        }).join('');
        if (totalRecEl) totalRecEl.textContent = 'Rs ' + fmtNum(totalRec);
        if (totalPayEl) totalPayEl.textContent = 'Rs ' + fmtNum(totalPay);
    }

    function load() {
        const type   = typeEl?.value ?? '';
        const group  = groupEl?.value ?? '';
        const search = searchEl?.value ?? '';
        setLoading('ap-tbody', 8);
        fetch(`/dashboard/reports/all-parties?type=${encodeURIComponent(type)}&party_group=${encodeURIComponent(group)}&search=${encodeURIComponent(search)}`, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error('Server error ' + r.status); return r.json(); })
        .then(data => renderRows(Array.isArray(data) ? data : (data.parties ?? data.data ?? [])))
        .catch(err => {
            showToast('Failed to load parties: ' + err.message);
            setEmpty('ap-tbody', 8, 'Error loading data.');
        });
    }

    typeEl?.addEventListener('change', load);
    groupEl?.addEventListener('change', load);
    let searchTimer;
    searchEl?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(load, 350);
    });
    exportBtn?.addEventListener('click', () => exportTableToCSV('ap-table', 'all-parties.csv'));
    printBtn?.addEventListener('click',  () => printSection('tab-All Parties', 'All Parties'));

    document.querySelectorAll('.reports-nav .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (link.dataset.target === 'All Parties') load();
        });
    });

    load();
    initResizableTable('ap-table');
})();

// =============================================
// PARTY STATEMENT TAB
// =============================================
(function partyStatement() {
    const tbody      = document.getElementById('ps-tbody');
    const partyEl    = document.getElementById('ps-party-select');
    const periodEl   = document.getElementById('ps-period');
    const fromEl     = document.getElementById('ps-date-from');
    const toEl       = document.getElementById('ps-date-to');
    const exportBtn  = document.getElementById('ps-excel-btn');
    const printBtn   = document.getElementById('ps-print-btn');
    const openingBalEl   = document.getElementById('ps-opening-bal');
    const closingBalEl   = document.getElementById('ps-closing-bal');
    const totalDebitEl   = document.getElementById('ps-total-debit');
    const totalCreditEl  = document.getElementById('ps-total-credit');
    const summaryBar     = document.getElementById('ps-summary-bar');
    const footSaleEl     = document.getElementById('ps-foot-sale');
    const footPurchaseEl = document.getElementById('ps-foot-purchase');
    const footMoneyInEl  = document.getElementById('ps-foot-moneyin');
    const footMoneyOutEl = document.getElementById('ps-foot-moneyout');
    const footReceivableEl = document.getElementById('ps-foot-receivable');
    const footPayableEl  = document.getElementById('ps-foot-payable');

    if (!tbody) return;

    function setPeriodDates() {
        const now = new Date();
        const start = new Date(now);
        const end = new Date(now);
        const period = periodEl?.value || 'this_month';

        if (period === 'this_month') {
            start.setDate(1);
        } else if (period === 'last_month') {
            start.setMonth(start.getMonth() - 1, 1);
            end.setMonth(start.getMonth() + 1, 0);
        } else if (period === 'this_quarter') {
            const quarterStartMonth = Math.floor(now.getMonth() / 3) * 3;
            start.setMonth(quarterStartMonth, 1);
            end.setMonth(quarterStartMonth + 3, 0);
        } else if (period === 'this_year') {
            start.setMonth(0, 1);
            end.setMonth(11, 31);
        } else {
            return;
        }

        const toInput = d => d.toISOString().slice(0, 10);
        fromEl.value = toInput(start);
        toEl.value = toInput(end);
    }

    function getViewMode() {
        return document.querySelector('input[name="psView"]:checked')?.value || 'vyapar';
    }

    function toggleStatementColumns(mode) {
        ['ps-col-total', 'ps-col-received', 'ps-col-txnbalance', 'ps-col-receivable', 'ps-col-payable']
            .forEach(id => document.getElementById(id)?.classList.toggle('d-none', mode !== 'vyapar'));
        ['ps-col-debit', 'ps-col-credit', 'ps-col-running']
            .forEach(id => document.getElementById(id)?.classList.toggle('d-none', mode !== 'accounting'));
    }

    function moneyCell(value, className = '') {
        const amount = Number(value || 0);
        return amount ? `<span class="party-report-money ${className}">Rs ${fmtNum(amount)}</span>` : '—';
    }

    function renderRows(rows, mode) {
        if (!rows.length) {
            setEmpty('ps-tbody', 13, 'No transactions found for this party in the selected period.');
            return;
        }

        tbody.innerHTML = rows.map((tx, index) => {
            const rowClass = index === 0 ? 'party-report-highlight' : '';
            const receivable = Number(tx.receivable_balance ?? 0);
            const payable = Number(tx.payable_balance ?? 0);
            return `<tr class="${rowClass}">
                <td>${fmtDate(tx.date)}</td>
                <td>${escHtml(tx.type ?? '—')}</td>
                <td>${escHtml(tx.reference ?? '—')}</td>
                <td>${escHtml(tx.payment_type ?? '—')}</td>
                <td class="text-end ${mode === 'vyapar' ? '' : 'd-none'}">${moneyCell(tx.total)}</td>
                <td class="text-end ${mode === 'vyapar' ? '' : 'd-none'}">${moneyCell(tx.received_paid)}</td>
                <td class="text-end ${mode === 'vyapar' ? '' : 'd-none'}">${moneyCell(tx.txn_balance)}</td>
                <td class="text-end ${mode === 'vyapar' ? '' : 'd-none'}">${receivable > 0 ? `<span class="party-report-positive">Rs ${fmtNum(receivable)}</span>` : '—'}</td>
                <td class="text-end ${mode === 'vyapar' ? '' : 'd-none'}">${payable > 0 ? `<span class="party-report-negative">Rs ${fmtNum(payable)}</span>` : '—'}</td>
                <td class="text-end ${mode === 'accounting' ? '' : 'd-none'}">${moneyCell(tx.debit)}</td>
                <td class="text-end ${mode === 'accounting' ? '' : 'd-none'}">${moneyCell(tx.credit)}</td>
                <td class="text-end ${mode === 'accounting' ? '' : 'd-none'}">${moneyCell(tx.running_balance)} ${tx.running_balance_label ? `<small>${escHtml(tx.running_balance_label)}</small>` : ''}</td>
                <td class="text-center">
                    ${tx.edit_url ? `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="${tx.edit_url}">View / Edit</a></li>
                            ${tx.preview_url ? `<li><a class="dropdown-item" href="${tx.preview_url}" target="_blank">Open Invoice</a></li>` : ''}
                        </ul>
                    </div>` : '—'}
                </td>
            </tr>`;
        }).join('');
    }

    function load() {
        const partyId = partyEl?.value;
        if (!partyId) {
            setEmpty('ps-tbody', 13, 'Please select a party to view statement.');
            return;
        }

        const from = fromEl?.value ?? '';
        const to = toEl?.value ?? '';
        const mode = getViewMode();

        toggleStatementColumns(mode);
        setLoading('ps-tbody', 13);

        fetch(`/dashboard/reports/party-statement/${encodeURIComponent(partyId)}?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error('Server error ' + r.status); return r.json(); })
        .then(data => {
            const rows = Array.isArray(data) ? data : (data.transactions ?? data.data ?? []);
            renderRows(rows, mode);

            if (summaryBar) summaryBar.classList.remove('d-none');
            if (openingBalEl) openingBalEl.textContent = `Rs ${fmtNum(data.opening_balance ?? 0)} ${data.opening_balance_label ? `(${data.opening_balance_label})` : ''}`;
            if (closingBalEl) closingBalEl.textContent = `Rs ${fmtNum(data.closing_balance ?? 0)} ${data.closing_balance_label ? `(${data.closing_balance_label})` : ''}`;
            if (totalDebitEl) totalDebitEl.textContent = 'Rs ' + fmtNum(data.total_debit ?? 0);
            if (totalCreditEl) totalCreditEl.textContent = 'Rs ' + fmtNum(data.total_credit ?? 0);
            if (footSaleEl) footSaleEl.textContent = 'Rs ' + fmtNum(data.total_sale ?? 0);
            if (footPurchaseEl) footPurchaseEl.textContent = 'Rs ' + fmtNum(data.total_purchase ?? 0);
            if (footMoneyInEl) footMoneyInEl.textContent = 'Rs ' + fmtNum(data.total_money_in ?? 0);
            if (footMoneyOutEl) footMoneyOutEl.textContent = 'Rs ' + fmtNum(data.total_money_out ?? 0);
            if (footReceivableEl) footReceivableEl.textContent = 'Rs ' + fmtNum(data.total_receivable ?? 0);
            if (footPayableEl) footPayableEl.textContent = 'Rs ' + fmtNum(data.total_payable ?? 0);
        })
        .catch(err => {
            showToast('Failed to load statement: ' + err.message);
            setEmpty('ps-tbody', 13, 'Error loading data.');
        });
    }

    periodEl?.addEventListener('change', () => {
        setPeriodDates();
        if (partyEl?.value) load();
    });
    partyEl?.addEventListener('change', load);
    fromEl?.addEventListener('change', () => { if (partyEl?.value) load(); });
    toEl?.addEventListener('change', () => { if (partyEl?.value) load(); });
    document.querySelectorAll('input[name="psView"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (partyEl?.value) load();
            else toggleStatementColumns(getViewMode());
        });
    });

    exportBtn?.addEventListener('click', () => exportTableToCSV('ps-table', 'party-statement.csv'));
    printBtn?.addEventListener('click', () => printSection('tab-Party Statement', 'Party Statement'));

    document.querySelectorAll('.reports-nav .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (link.dataset.target === 'Party Statement' && partyEl?.value) load();
        });
    });

    setPeriodDates();
    toggleStatementColumns(getViewMode());
    initResizableTable('ps-table');

    const initialPartyId = new URLSearchParams(window.location.search).get('party_id');
    if (initialPartyId && partyEl) {
        partyEl.value = initialPartyId;
        if (partyEl.value) {
            load();
        }
    }
})();

// =============================================
// PARTY REPORT BY ITEMS TAB
// =============================================
(function partyByItems() {
    const tbody     = document.getElementById('pri-tbody');
    const catEl     = document.getElementById('pri-category');
    const itemEl    = document.getElementById('pri-item');
    const fromEl    = document.getElementById('pri-date-from');
    const toEl      = document.getElementById('pri-date-to');
    const searchEl  = document.getElementById('pri-search');
    const exportBtn = document.getElementById('pri-excel-btn');
    const printBtn  = document.getElementById('pri-print-btn');

    const totalSaleQtyEl  = document.getElementById('pri-total-sale-qty');
    const totalSaleAmtEl  = document.getElementById('pri-total-sale-amt');
    const totalPurQtyEl   = document.getElementById('pri-total-pur-qty');
    const totalPurAmtEl   = document.getElementById('pri-total-pur-amt');

    if (!tbody) return;

    function buildPrintMeta() {
        const categoryText = catEl?.selectedOptions?.[0]?.textContent?.trim() || 'All Categories';
        const itemText     = itemEl?.selectedOptions?.[0]?.textContent?.trim() || 'All Items';
        const fromText     = fromEl?.value || '';
        const toText       = toEl?.value || '';
        return `
            <div class="text-center fw-bold text-decoration-underline mb-4" style="font-size:22px;">Party By Item Report</div>
            <div class="fw-semibold mb-3" style="font-size:16px;">Item category: ${escHtml(categoryText)}</div>
            <div class="fw-semibold mb-3" style="font-size:16px;">Item name: ${escHtml(itemText)}</div>
            <div class="fw-semibold mb-4" style="font-size:16px;">Duration: From ${escHtml(fromText || '01/06/2026')} to ${escHtml(toText || '30/06/2026')}</div>
        `;
    }

    function load() {
        const category = catEl?.value ?? '';
        const itemId   = itemEl?.value ?? '';
        const from     = fromEl?.value ?? '';
        const to       = toEl?.value ?? '';
        const search   = searchEl?.value ?? '';
        setLoading('pri-tbody', 7);

        const params = new URLSearchParams({ category, item_id: itemId, from, to, search });

        fetch(`/dashboard/reports/party-report-by-items?${params}`, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error('Server error ' + r.status); return r.json(); })
        .then(data => {
            const rows = Array.isArray(data) ? data : (data.rows ?? data.data ?? []);
            if (!rows.length) { setEmpty('pri-tbody', 7, 'No records found for selected filters.'); return; }

            let tSaleQty = 0, tSaleAmt = 0, tPurQty = 0, tPurAmt = 0;
            tbody.innerHTML = rows.map(r => {
                tSaleQty += Number(r.sale_qty ?? 0);
                tSaleAmt += Number(r.sale_amount ?? 0);
                tPurQty  += Number(r.purchase_qty ?? 0);
                tPurAmt  += Number(r.purchase_amount ?? 0);
                return `<tr>
                    <td class="fw-semibold">${escHtml(r.party_name ?? '�')}</td>
                    <td>${escHtml(r.category_name ?? 'Uncategorized')}</td>
                    <td>${escHtml(r.item_name ?? '�')}</td>
                    <td class="text-end">${fmtNum(r.sale_qty ?? 0)}</td>
                    <td class="text-end">Rs ${fmtNum(r.sale_amount ?? 0)}</td>
                    <td class="text-end">${fmtNum(r.purchase_qty ?? 0)}</td>
                    <td class="text-end">Rs ${fmtNum(r.purchase_amount ?? 0)}</td>
                </tr>`;
            }).join('');
            if (totalSaleQtyEl) totalSaleQtyEl.textContent = fmtNum(tSaleQty);
            if (totalSaleAmtEl) totalSaleAmtEl.textContent = 'Rs ' + fmtNum(tSaleAmt);
            if (totalPurQtyEl) totalPurQtyEl.textContent  = fmtNum(tPurQty);
            if (totalPurAmtEl) totalPurAmtEl.textContent  = 'Rs ' + fmtNum(tPurAmt);
        })
        .catch(err => {
            showToast('Failed to load data: ' + err.message);
            setEmpty('pri-tbody', 7, 'Error loading data.');
        });
    }

    [catEl, itemEl].forEach(el => el?.addEventListener('change', load));
    let searchTimer;
    searchEl?.addEventListener('input', () => { clearTimeout(searchTimer); searchTimer = setTimeout(load, 350); });
    fromEl?.addEventListener('change', load);
    toEl?.addEventListener('change', load);

    exportBtn?.addEventListener('click', () => exportTableToCSV('pri-table', 'party-items-report.csv'));
    printBtn?.addEventListener('click',  () => printReport('pri-table', 'Party By Item Report', buildPrintMeta()));

    document.querySelectorAll('.reports-nav .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (link.dataset.target === 'Party Report by Items') load();
        });
    });

    load();
})();

// =============================================
// TAB SWITCHING — only for data-target links
// =============================================
document.querySelectorAll('.reports-nav .nav-link').forEach(link => {
    link.addEventListener('click', function () {
        if (!this.dataset.target) return;

        document.querySelectorAll('.reports-nav .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        const target = this.dataset.target;
        document.querySelectorAll('.report-tab-content').forEach(tab => {
            tab.classList.toggle('d-none', tab.id !== `tab-${target}`);
        });
    });
});
</script>
