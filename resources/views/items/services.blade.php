@extends('layouts.app')

@section('title', 'Items')
@section('page', 'items')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.il-page {
    display: flex; flex-direction: column;
    height: 100vh; max-height: 100vh;
    background: #fff; overflow: hidden;
}

/* â”€â”€ TOP TABS â”€â”€ */
.il-tabs {
    display: flex; border-bottom: 1px solid #e5e7eb;
    flex-shrink: 0; background: #fff;
}
.il-tab {
    flex: 1; text-align: center; padding: 16px 0;
    font-size: 13px; font-weight: 600; letter-spacing: .06em;
    color: #9ca3af; cursor: pointer;
    border-bottom: 2px solid transparent; transition: all .15s;
    user-select: none;
}
.il-tab:hover { color: #4b5563; }
.il-tab.active { color: #2563eb !important; border-bottom-color: #2563eb !important; }

/* â”€â”€ BODY â”€â”€ */
.il-body { display: flex; flex: 1; min-height: 0; overflow: hidden; }

/* â”€â”€ LEFT PANEL â”€â”€ */
.il-left {
    width: 320px; flex-shrink: 0;
    border-right: 1px solid #e5e7eb;
    display: flex; flex-direction: column; background: #fff;
}

/* Bulk banner */
.bulk-banner {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px; background: #f0f4ff;
    border-bottom: 1px solid #e5e7eb; cursor: pointer;
    flex-shrink: 0;
}
.bulk-banner:hover { background: #e8effe; }
.bulk-banner-icon {
    width: 32px; height: 32px; background: #e53e3e; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.bulk-banner-text { flex: 1; }
.bulk-banner-title { font-size: 12px; font-weight: 700; color: #1a1a1a; }
.bulk-banner-sub { font-size: 11px; color: #6b7280; }

.il-left-toolbar {
    display: flex; align-items: center; gap: 8px;
    padding: 12px 14px; border-bottom: 1px solid #f3f4f6; flex-shrink: 0;
}
.il-search-btn {
    width: 34px; height: 34px; border: 1.5px solid #e5e7eb;
    border-radius: 6px; background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; transition: border-color .15s;
}
.il-search-btn:hover { border-color: #93c5fd; }
.il-search-wrap { flex: 1; position: relative; display: none; }
.il-search-wrap.open { display: block; }
.il-search-input {
    width: 100%; border: 1.5px solid #2563eb; border-radius: 6px;
    padding: 7px 10px 7px 30px; font-size: 13px; outline: none; color: #374151;
}
.il-search-icon { position: absolute; left: 9px; top: 50%; transform: translateY(-50%); }

.il-add-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f59e0b; color: #fff; border: none;
    border-radius: 6px; padding: 8px 16px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .15s; white-space: nowrap; height: 36px;
    margin-left: auto;
}
.il-add-btn:hover { background: #d97706; }

/* Three dots */
.bulk-more-wrap { position: relative; flex-shrink: 0; }
.bulk-more-btn {
    background: none; border: 1.5px solid #e5e7eb;
    border-radius: 6px; width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #6b7280; transition: border-color .15s;
}
.bulk-more-btn:hover { border-color: #93c5fd; }
.bulk-dd {
    position: absolute; top: calc(100% + 4px); right: 0;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 6px; box-shadow: 0 6px 20px rgba(0,0,0,.12);
    z-index: 500; min-width: 190px; display: none;
}
.bulk-dd.open { display: block; }
.bulk-dd-item {
    padding: 11px 16px; cursor: pointer; font-size: 13px; color: #374151;
}
.bulk-dd-item:hover { background: #f9fafb; }

.il-list-header {
    display: flex; align-items: center;
    padding: 9px 14px; background: #f9fafb;
    border-bottom: 1px solid #f3f4f6;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em; color: #9ca3af;
}
.col-item { flex: 1; display: flex; align-items: center; gap: 6px; }
.col-price-hdr { width: 80px; text-align: right; }

.il-list { flex: 1; overflow-y: auto; }
.il-list::-webkit-scrollbar { width: 4px; }
.il-list::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

.il-item-row {
    display: flex; align-items: center; padding: 12px 14px;
    border-bottom: 1px solid #f8f9fa; cursor: pointer; transition: background .12s;
    position: relative;
}
.il-item-row:hover { background: #f9fafb; }
.il-item-row.active { background: #eff6ff; }
.il-item-dot { width: 8px; height: 8px; border-radius: 50%; background: #9ca3af; margin-right: 8px; flex-shrink: 0; }
.il-item-name { flex: 1; font-size: 14px; color: #111827; font-weight: 500; }
.il-item-price { width: 70px; text-align: right; font-size: 13px; color: #16a34a; font-weight: 600; }

.il-item-more-wrap { position: relative; width: 24px; height: 24px; flex-shrink: 0; }
.il-item-more-btn {
    width: 24px; height: 24px; display: flex; align-items: center;
    justify-content: center; color: #9ca3af; cursor: pointer;
    border-radius: 4px; background: none; border: none;
}
.il-item-more-btn:hover { background: #f3f4f6; color: #374151; }
.il-item-dd {
    position: absolute; right: 0; top: calc(100% + 2px);
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 6px; box-shadow: 0 6px 20px rgba(0,0,0,.12);
    z-index: 600; min-width: 140px; display: none;
}
.il-item-dd.open { display: block; }
.il-item-dd-item { padding: 11px 16px; cursor: pointer; font-size: 13px; color: #374151; }
.il-item-dd-item:hover { background: #f9fafb; }
.il-item-dd-item.danger { color: #ef4444; }
.il-item-dd-item.danger:hover { background: #fef2f2; }

/* â”€â”€ RIGHT PANEL â”€â”€ */
.il-right { flex: 1; display: flex; flex-direction: column; background: #fff; min-width: 0; }

.il-no-selection {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center; color: #9ca3af; gap: 12px;
}
.il-no-sel-icon {
    width: 64px; height: 64px; background: #f3f4f6; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

.il-detail-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 24px 12px; border-bottom: 1px solid #f3f4f6; flex-shrink: 0;
}
.il-detail-name-row { display: flex; align-items: center; gap: 10px; position: relative; }
.il-detail-name { font-size: 17px; font-weight: 700; color: #111827; }
.il-icon-btn {
    background: none; border: none; cursor: pointer;
    color: #6b7280; padding: 4px; border-radius: 4px; transition: color .12s;
}
.il-icon-btn:hover { color: #2563eb; }

.il-stats {
    display: flex; align-items: stretch;
    border-bottom: 1px solid #f3f4f6; flex-shrink: 0;
}
.il-stat-left {
    display: flex; flex-direction: column;
    padding: 10px 24px; gap: 5px; flex: 1; justify-content: center;
}
.il-stat-item { display: flex; align-items: center; gap: 6px; }
.il-stat-label { font-size: 12px; color: #6b7280; font-weight: 500; text-transform: uppercase; letter-spacing: .04em; }
.il-stat-value { font-size: 13px; font-weight: 700; color: #16a34a; }

.il-share-popup {
    position: absolute; top: calc(100% + 6px); left: 0;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 8px; box-shadow: 0 6px 24px rgba(0,0,0,.15);
    z-index: 700; display: none; padding: 12px 8px; min-width: 260px;
}
.il-share-popup.open { display: flex; gap: 4px; }
.il-share-option {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 10px 14px; cursor: pointer; border-radius: 6px; flex: 1;
    font-size: 11px; color: #374151; font-weight: 500; transition: background .12s;
}
.il-share-option:hover { background: #f3f4f6; }
.il-share-option .share-icon {
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.share-email { background: #fff0f0; color: #e53e3e; }
.share-sms   { background: #f0fdf4; color: #16a34a; }
.share-wa    { background: #f0fdf4; color: #25d366; }
.share-copy  { background: #f5f3ff; color: #7c3aed; }

.il-txn-section { flex: 1; display: flex; flex-direction: column; min-height: 0; overflow: hidden; }
.il-txn-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px 10px; border-bottom: 1px solid #f0f0f0; flex-shrink: 0;
}
.il-txn-title { font-size: 12px; font-weight: 700; letter-spacing: .08em; color: #374151; text-transform: uppercase; }
.il-txn-right { display: flex; align-items: center; gap: 8px; }
.il-txn-search {
    border: 1px solid #e5e7eb; border-radius: 6px;
    padding: 7px 10px 7px 34px; font-size: 13px; outline: none; width: 260px; color: #374151;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%23b0b8c4' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath stroke-linecap='round' d='M21 21l-4.35-4.35'/%3E%3C/svg%3E") no-repeat 11px center;
}
.il-txn-search::placeholder { color: #b0b8c4; }
.il-txn-search:focus { border-color: #2563eb; outline: none; }

.il-export-btn {
    background: none; border: none; cursor: pointer; padding: 2px;
    display: flex; align-items: center; justify-content: center;
}
.il-export-btn .excel-icon {
    width: 26px; height: 26px; background: #ffffff; border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 13px; font-weight: 800;
}

.il-tbl-wrap { flex: 1; overflow-y: auto; overflow-x: auto; }
.il-tbl-wrap::-webkit-scrollbar { width: 4px; height: 4px; }
.il-tbl-wrap::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

.il-tbl { width: 100%; border-collapse: collapse; table-layout: fixed; }
.il-tbl th {
    padding: 12px 16px; font-size: 12px; font-weight: 500;
    text-transform: capitalize; color: #9ca3af;
    background: #f9fafb; border-bottom: 1px solid #ebebeb;
    border-right: 1px solid #d1d5db;
    text-align: left; white-space: nowrap;
    position: relative; overflow: hidden; user-select: none;
}
.il-tbl th[data-col="dot"] { padding: 0; }
.il-tbl th .th-inner { display: inline-flex; align-items: center; gap: 3px; cursor: pointer; }
.th-sort-arrow {
    display: inline-flex; align-items: center;
    color: #4a4a4a; flex-shrink: 0; font-size: 10px; font-style: normal;
    opacity: 0; transition: opacity .1s; line-height: 1;
}
.il-tbl th.sort-asc  .th-sort-arrow,
.il-tbl th.sort-desc .th-sort-arrow { opacity: 1; }
.th-sort-arrow::after { content: 'â†‘'; }
.il-tbl th.sort-desc .th-sort-arrow::after { content: 'â†“'; }
.il-tbl th .th-filter-icon {
    color: #b8bec7; flex-shrink: 0; cursor: pointer; transition: color .15s; font-size: 10px;
}
.il-tbl th .th-filter-icon:hover  { color: #e53e3e; }
.il-tbl th .th-filter-icon.active { color: #e53e3e; }

.col-resize-handle {
    position: absolute; right: 0; top: 0; bottom: 0;
    width: 5px; cursor: col-resize; z-index: 10;
}
.col-resize-handle:hover,
.col-resize-handle.resizing { background: #2563eb; opacity: .4; }

.il-tbl td {
    padding: 12px 10px; font-size: 13px; color: #374151; font-weight: 400;
    border-bottom: 1px solid #f3f4f6; vertical-align: middle;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.il-tbl td.td-dot { padding: 0 0 0 10px; width: 28px; vertical-align: middle; }
.il-tbl td.td-price { color: #16a34a; }
.il-tbl td.td-status { color: #9ca3af; }
.il-tbl td.td-actions { padding: 2px 4px; width: 36px; }
.il-tbl tbody tr:hover td { background: #fafafa; }
.il-tbl tbody tr.txn-selected td { background: #dbeafe; }
.il-tbl tbody tr:last-child td { border-bottom: none; }

.il-row-menu-wrap { position: relative; }
.il-row-menu-btn {
    background: none; border: none; cursor: pointer; color: #9ca3af;
    padding: 4px 6px; border-radius: 4px; font-size: 18px; line-height: 1;
}
.il-row-menu-btn:hover { color: #374151; background: #f3f4f6; }
.il-row-menu {
    position: fixed; background: #fff; border: 1px solid #e5e7eb;
    border-radius: 6px; box-shadow: 0 6px 20px rgba(0,0,0,.12);
    z-index: 9000; min-width: 150px; display: none;
}
.il-row-menu.open { display: block; }
.il-row-menu-item { padding: 11px 16px; cursor: pointer; font-size: 13px; color: #374151; }
.il-row-menu-item:hover { background: #f9fafb; }
.il-row-menu-item.danger { color: #ef4444; }
.il-row-menu-item.danger:hover { background: #fef2f2; }

.col-filter-dd {
    display: none; position: fixed;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,.15);
    z-index: 9999; min-width: 220px; padding: 16px 16px 12px;
}
.col-filter-dd.open { display: block; }
.cfd-title { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 12px; }
.cfd-cb-row {
    display: flex; align-items: center; gap: 10px;
    padding: 7px 2px; font-size: 13px; color: #374151; cursor: pointer;
}
.cfd-cb-row input[type=checkbox] { width: 15px; height: 15px; accent-color: #2563eb; flex-shrink: 0; }
.cfd-select {
    width: 100%; border: 1.5px solid #e5e7eb; border-radius: 6px;
    padding: 9px 10px; font-size: 13px; color: #374151;
    background: #fff; outline: none; cursor: pointer; margin-bottom: 10px;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px;
}
.cfd-input {
    width: 100%; border: 1.5px solid #e5e7eb; border-radius: 6px;
    padding: 9px 10px; font-size: 13px; color: #374151;
    outline: none; box-sizing: border-box;
}
.cfd-input:focus { border-color: #2563eb; }
.cfd-input::placeholder { color: #9ca3af; }
.cfd-date-lbl { font-size: 11px; color: #9ca3af; margin-bottom: 6px; }
.cfd-actions { display: flex; gap: 8px; margin-top: 14px; }
.cfd-clear {
    flex: 1; border: 1.5px solid #e5e7eb; background: #fff;
    border-radius: 20px; padding: 8px 0; font-size: 12px;
    color: #6b7280; cursor: pointer; font-weight: 500;
}
.cfd-apply {
    flex: 1; border: none; background: #e53e3e;
    border-radius: 20px; padding: 8px 0; font-size: 12px;
    color: #fff; cursor: pointer; font-weight: 600;
}
.cfd-clear:hover { background: #f3f4f6; }
.cfd-apply:hover { background: #c53030; }

.il-empty-wrap { flex: 1; display: flex; align-items: center; justify-content: center; background: #fff; }
.il-empty-content { display: flex; flex-direction: column; align-items: center; gap: 18px; text-align: center; }
.il-illustration { width: 220px; height: 220px; background: #dbeafe; border-radius: 50%; position: relative; }
.il-icon-card {
    position: absolute; background: #fff;
    border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.13);
    display: flex; align-items: center; justify-content: center;
}

/* Bulk modal */
.bulk-overlay {
    position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,.45);
    display: none; align-items: center; justify-content: center;
}
.bulk-overlay.open { display: flex; }
.bulk-modal {
    background: #fff; border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,.2);
    width: 500px; max-width: 95vw;
    animation: popIn .15s ease-out;
}
@keyframes popIn { from{opacity:0;transform:scale(.96)} to{opacity:1;transform:scale(1)} }
.bulk-modal-hdr {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px; border-bottom: 1px solid #f3f4f6;
}
.bulk-modal-title { font-size: 17px; font-weight: 700; color: #111827; }
.bulk-modal-close {
    background: none; border: none; cursor: pointer;
    font-size: 20px; color: #9ca3af; width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center; border-radius: 5px;
}
.bulk-modal-close:hover { background: #f3f4f6; color: #374151; }
.bulk-modal-search {
    width: 100%; border: 1.5px solid #3b82f6; border-radius: 7px;
    padding: 10px 14px 10px 36px; font-size: 13px; outline: none;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%239ca3af' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath stroke-linecap='round' d='M21 21l-4.35-4.35'/%3E%3C/svg%3E") no-repeat 11px center;
}
.bulk-modal-search:focus { border-color: #2563eb; }
.bulk-modal-search::placeholder { color: #9ca3af; }
.bulk-info-bar {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 24px; background: #f0f7ff; border-top: 1px solid #f3f4f6;
    font-size: 12px; color: #374151;
}
.bulk-empty {
    text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px;
}
.bulk-table th, .bulk-table td { border-bottom: 1px solid #f3f4f6; }
.bulk-table tbody tr:last-child td { border-bottom: none; }
.bulk-edit-field {
    border: 1.5px solid #d1d5db; border-radius: 6px;
    padding: 8px 10px; font-size: 13px; color: #374151;
    outline: none; background: #fff; width: 100%;
}
.bulk-edit-field:focus { border-color: #2563eb; }
.bulk-row-editor {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 16px; border-bottom: 1px solid #f3f4f6;
}
.bulk-row-editor input { flex: 1; }
.bulk-col-item { flex: 2; }
.u-overlay {
    position: fixed; inset: 0; z-index: 2200;
    background: rgba(0,0,0,.45);
    display: none; align-items: center; justify-content: center;
}
.u-overlay.open { display: flex; }
.u-mbox {
    width: 620px; max-width: 96vw;
    background: #fff; border-radius: 10px;
    box-shadow: 0 12px 42px rgba(0,0,0,.22);
    animation: popIn .15s ease-out;
}
.u-mhdr, .u-mfoot {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #f3f4f6;
}
.u-mfoot {
    border-bottom: none;
    border-top: 1px solid #f3f4f6;
    justify-content: flex-end;
    gap: 10px;
}
.u-mbody { padding: 18px 22px; }
.u-mclose {
    background: none; border: none; cursor: pointer;
    color: #6b7280; font-size: 18px; line-height: 1;
}
.u-mlabel {
    display: block; margin-bottom: 8px;
    color: #374151; font-size: 13px; font-weight: 700;
}
.conv-select, .conv-rate {
    width: 100%;
    border: 1.5px solid #d1d5db; border-radius: 8px;
    padding: 10px 12px; font-size: 13px; color: #374151;
    background: #fff;
}
.conv-eq {
    font-size: 18px; font-weight: 700; color: #374151;
}
.u-mbtn {
    border: none; border-radius: 7px;
    padding: 10px 22px; font-size: 13px; font-weight: 700;
    cursor: pointer;
}
.u-mbtn-new { background: #f3f4f6; color: #374151; }
.u-mbtn-save { background: #e53e3e; color: #fff; }

#delete-overlay {
    position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,.45);
    display: none; align-items: center; justify-content: center;
}
#delete-overlay.open { display: flex; }
#delete-modal {
    background: #fff; border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,.25);
    width: 420px; max-width: 95vw; animation: popIn .15s ease-out;
}
.del-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px 14px; background: #e8f0fb; border-radius: 8px 8px 0 0;
}
.del-header-title { font-size: 15px; font-weight: 700; color: #1a2a4a; }
.del-header-close {
    background: none; border: none; cursor: pointer;
    font-size: 18px; color: #6b7280; line-height: 1;
    width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 4px;
}
.del-header-close:hover { background: #d1d5db; color: #111; }
.del-body { padding: 22px 24px 20px; }
.del-body p { font-size: 14px; font-weight: 600; color: #1a2a4a; }
.del-footer { display: flex; justify-content: flex-end; gap: 12px; padding: 14px 20px 18px; }
.del-btn-yes, .del-btn-no {
    background: #5b9bd5; border: none; border-radius: 5px;
    padding: 9px 28px; font-size: 14px; font-weight: 600; color: #fff; cursor: pointer;
}
.del-btn-yes:hover, .del-btn-no:hover { background: #3a7bbf; }

#toast {
    position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(20px);
    background: #111827; color: #fff; padding: 10px 22px;
    border-radius: 8px; font-size: 13px; font-weight: 500;
    opacity: 0; transition: all .25s; z-index: 9999; pointer-events: none;
}
#toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>
@endpush

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="il-page">

    {{-- TOP TABS --}}
    <div class="il-tabs">
        <div class="il-tab" onclick="window.location.href='{{ route("items") }}'">PRODUCTS</div>
        <div class="il-tab active" onclick="window.location.href='{{ route("items.services") }}'">SERVICES</div>
        <div class="il-tab" onclick="window.location.href='{{ route("items.category") }}'">CATEGORY</div>
        <div class="il-tab" onclick="window.location.href='{{ route("items.units") }}'">UNITS</div>
    </div>

    @if(count($services) === 0)

    <div class="il-empty-wrap">
        <div class="il-empty-content">
            <div class="il-illustration">
                <div class="il-icon-card" style="width:52px;height:52px;top:14px;left:50%;transform:translateX(-50%);font-size:26px;">ðŸ§º</div>
                <div class="il-icon-card" style="width:48px;height:48px;top:50%;left:8px;transform:translateY(-50%);font-size:22px;">ðŸ–¨ï¸</div>
                <div class="il-icon-card" style="width:48px;height:48px;top:50%;right:8px;transform:translateY(-50%);font-size:22px;">ðŸ«–</div>
                <div class="il-icon-card" style="width:46px;height:46px;bottom:24px;left:26px;font-size:20px;">ðŸ§µ</div>
                <div class="il-icon-card" style="width:46px;height:46px;bottom:24px;right:26px;font-size:20px;">ðŸ“¦</div>
                <div class="il-icon-card" style="width:54px;height:54px;top:50%;left:50%;transform:translate(-50%,-50%);font-size:26px;box-shadow:0 4px 16px rgba(0,0,0,.16);z-index:2;border-radius:12px;">ðŸ“‹</div>
            </div>
            <p style="font-size:14px;color:#6b7280;max-width:420px;line-height:1.65;">
                Add services you provide to your customers and create Sale invoices for them faster.
            </p>
            <button onclick="window.location.href='{{ route("items.create") }}?type=service'" style="display:inline-flex;align-items:center;background:#f59e0b;color:#fff;border:none;border-radius:6px;padding:13px 32px;font-size:14px;font-weight:600;cursor:pointer;">
                Add Your First Service
            </button>
        </div>
    </div>

    @else

    <div class="il-body">

        {{-- LEFT PANEL --}}
        <div class="il-left">

            {{-- Bulk Items Update Banner --}}
            <div class="bulk-banner" onclick="openBulkModal('bulk-update')">
                <div class="bulk-banner-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="bulk-banner-text">
                    <div class="bulk-banner-title">Bulk Items Update</div>
                    <div class="bulk-banner-sub">Update/Edit multiple items at a time.</div>
                </div>
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>

            <div class="il-left-toolbar">
                <button class="il-search-btn" onclick="toggleSearch()" title="Search">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                </button>
                <div class="il-search-wrap" id="search-wrap">
                    <svg class="il-search-icon" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                    <input type="text" class="il-search-input" id="search-input" placeholder="Search services..." oninput="filterItems()"/>
                </div>
                <button class="il-add-btn" onclick="window.location.href='{{ route("items.create") }}?type=service'">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.8"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                    Add Service
                </button>

                {{-- Three dots --}}
                <div class="bulk-more-wrap">
                    <button class="bulk-more-btn" onclick="toggleBulkDD(event)" title="More options">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                        </svg>
                    </button>
                    <div class="bulk-dd" id="bulk-dd">
                        <div class="bulk-dd-item" onclick="openBulkModal('inactive')">Bulk Inactive</div>
                        <div class="bulk-dd-item" onclick="openBulkModal('active')">Bulk Active</div>
                        <div class="bulk-dd-item" onclick="openBulkModal('bulk-update')">Bulk Update Items</div>
                        <div class="bulk-dd-item" onclick="openBulkModal('bulk-assign-unit')">Assign Unit</div>
                        <div class="bulk-dd-item" onclick="openBulkModal('bulk-assign-code')">Bulk Assign Code</div>
                    </div>
                </div>
            </div>

            <div class="il-list-header">
                <span class="col-item">
                    ITEM
                    <i class="fa-solid fa-filter" style="color:#e53e3e;font-size:11px;cursor:pointer;"></i>
                </span>
                <span class="col-price-hdr">PRICE</span>
                <span style="width:24px;"></span>
            </div>

            <div class="il-list" id="items-list"></div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="il-right">

            <div class="il-no-selection" id="no-selection">
                <div class="il-no-sel-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div style="font-size:15px;color:#6b7280;font-weight:500;">Select a service to view details</div>
                <div style="font-size:13px;color:#9ca3af;">or add a new service using the button above</div>
            </div>

            <div id="item-detail" style="display:none;flex-direction:column;flex:1;min-height:0;">

                <div class="il-detail-header">
                    <div class="il-detail-name-row">
                        <span class="il-detail-name" id="detail-name">â€”</span>
                        <button class="il-icon-btn" title="Share/Export" onclick="toggleSharePopup(event)">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-4.553M19.553 5.447L15 5m4.553.447V10M4 12v7a1 1 0 001 1h14a1 1 0 001-1v-3M4 12V5a1 1 0 011-1h7"/></svg>
                        </button>
                        <div class="il-share-popup" id="share-popup" onclick="event.stopPropagation()">
                            <div class="il-share-option" onclick="shareVia('email')">
                                <div class="share-icon share-email"><i class="fa-regular fa-envelope"></i></div><span>EMAIL</span>
                            </div>
                            <div class="il-share-option" onclick="shareVia('sms')">
                                <div class="share-icon share-sms"><i class="fa-regular fa-message"></i></div><span>SMS</span>
                            </div>
                            <div class="il-share-option" onclick="shareVia('whatsapp')">
                                <div class="share-icon share-wa"><i class="fa-brands fa-whatsapp"></i></div>
                                <span>WHATSAPP</span>
                            </div>
                            <div class="il-share-option" onclick="shareVia('copy')">
                                <div class="share-icon share-copy"><i class="fa-solid fa-link"></i></div><span>COPY LINK</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="il-stats">
                    <div class="il-stat-left">
                        <div class="il-stat-item">
                            <span class="il-stat-label">SALE PRICE:</span>
                            <span class="il-stat-value" id="detail-sale">â€”</span>
                        </div>
                        <div class="il-stat-item">
                            <span class="il-stat-label">CATEGORY:</span>
                            <span class="il-stat-value" id="detail-category" style="color:#374151;">â€”</span>
                        </div>
                    </div>
                </div>

                <div class="il-txn-section">
                    <div class="il-txn-header">
                        <span class="il-txn-title">TRANSACTIONS</span>
                        <div class="il-txn-right">
                            <input type="text" class="il-txn-search" placeholder="Search transactions..." oninput="filterTxns(this.value)"/>
                            <button class="il-export-btn" title="Export to Excel" onclick="exportToExcel()">
                                <div class="excel-icon">📊</div>
                            </button>
                        </div>
                    </div>

                    <div class="il-tbl-wrap">
                        <table class="il-tbl" id="txn-table">
                            <thead>
                                <tr id="txn-thead-row">
                                    <th style="width:28px;padding:0;" data-col="dot"></th>
                                    <th data-col="date" style="width:88px;">
                                        <span class="th-inner" onclick="sortTxnCol('date')">
                                            DATE <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-date')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="date"></div>
                                    </th>
                                    <th data-col="invoice" style="width:88px;">
                                        <span class="th-inner" onclick="sortTxnCol('invoice')">
                                            INVOICE NO <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-invoice')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="invoice"></div>
                                    </th>
                                    <th data-col="type" style="width:72px;">
                                        <span class="th-inner" onclick="sortTxnCol('type')">
                                            TYPE <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-type')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="type"></div>
                                    </th>
                                    <th data-col="name" style="width:118px;">
                                        <span class="th-inner" onclick="sortTxnCol('name')">
                                            PARTY NAME <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-name')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="name"></div>
                                    </th>
                                    <th data-col="broker" style="width:180px;">
                                        <span class="th-inner" onclick="sortTxnCol('broker')">
                                            BROKER <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-broker')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="broker"></div>
                                    </th>
                                    <th data-col="qty" style="width:68px;">
                                        <span class="th-inner" onclick="sortTxnCol('qty')">
                                            TADDAT <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-qty')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="qty"></div>
                                    </th>
                                    <th data-col="net_w" style="width:78px;">
                                        <span class="th-inner" onclick="sortTxnCol('net_w')">
                                            NET W <span class="th-sort-arrow"></span>
                                        </span>
                                        <div class="col-resize-handle" data-col="net_w"></div>
                                    </th>
                                    <th data-col="amount" style="width:110px;" class="th-price-right">
                                        <span class="th-inner" onclick="sortTxnCol('amount')" style="justify-content:flex-end;width:100%;">
                                            AMOUNT <span class="th-sort-arrow"></span>
                                        </span>
                                        <div class="col-resize-handle" data-col="amount"></div>
                                    </th>
                                    <th data-col="price" style="width:92px;" class="th-price-right">
                                        <span class="th-inner" onclick="sortTxnCol('price')" style="justify-content:flex-end;width:100%;">
                                            PRICE/ UNIT <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-price')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="price"></div>
                                    </th>
                                    <th data-col="status" style="width:74px;">
                                        <span class="th-inner" onclick="sortTxnCol('status')">
                                            STATUS <span class="th-sort-arrow"></span>
                                            <i class="fa-solid fa-filter th-filter-icon" onclick="toggleColFilter(event,'cf-status')"></i>
                                        </span>
                                        <div class="col-resize-handle" data-col="status"></div>
                                    </th>
                                    <th style="width:40px;" data-col="actions"></th>
                                </tr>
                            </thead>
                            <tbody id="txn-tbody"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @endif

</div>

{{-- Toast --}}
<div id="toast"></div>

{{-- COLUMN FILTER DROPDOWNS --}}
<div class="col-filter-dd" id="cf-type" onclick="event.stopPropagation()">
    <div class="cfd-title">Select Category</div>
    <label class="cfd-cb-row"><input type="checkbox" value="Sale" onchange="applyColFilters()"> Sale</label>
    <label class="cfd-cb-row"><input type="checkbox" value="Sale (e-Invoice)" onchange="applyColFilters()"> Sale (e-Invoice)</label>
    <label class="cfd-cb-row"><input type="checkbox" value="Purchase" onchange="applyColFilters()"> Purchase</label>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-type')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>
<div class="col-filter-dd" id="cf-invoice" onclick="event.stopPropagation()">
    <div class="cfd-title">Select Category</div>
    <select class="cfd-select" id="cf-invoice-op"><option value="contains">Contains</option><option value="exact">Exact match</option></select>
    <input type="text" class="cfd-input" id="cf-invoice-val" placeholder="INVOICE/REF. NO" oninput="applyColFilters()"/>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-invoice')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>
<div class="col-filter-dd" id="cf-name" onclick="event.stopPropagation()">
    <div class="cfd-title">Select Category</div>
    <select class="cfd-select" id="cf-name-op"><option value="contains">Contains</option><option value="exact">Exact match</option></select>
    <input type="text" class="cfd-input" id="cf-name-val" placeholder="NAME" oninput="applyColFilters()"/>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-name')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>
<div class="col-filter-dd" id="cf-broker" onclick="event.stopPropagation()">
    <div class="cfd-title">Select Category</div>
    <select class="cfd-select" id="cf-broker-op"><option value="contains">Contains</option><option value="exact">Exact match</option></select>
    <input type="text" class="cfd-input" id="cf-broker-val" placeholder="BROKER NAME" oninput="applyColFilters()"/>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-broker')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>
<div class="col-filter-dd" id="cf-date" onclick="event.stopPropagation()">
    <div class="cfd-title">Select Category</div>
    <select class="cfd-select" id="cf-date-op"><option value="equal">Equal To</option><option value="before">Before</option><option value="after">After</option></select>
    <div class="cfd-date-lbl">Select Date</div>
    <input type="date" class="cfd-input" id="cf-date-val" oninput="applyColFilters()"/>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-date')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>
<div class="col-filter-dd" id="cf-qty" onclick="event.stopPropagation()">
    <div class="cfd-title">Select Category</div>
    <select class="cfd-select" id="cf-qty-op"><option value="equal">Equal to</option><option value="lt">Less Than</option><option value="gt">Greater Than</option></select>
    <input type="number" class="cfd-input" id="cf-qty-val" placeholder="QUANTITY" oninput="applyColFilters()"/>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-qty')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>
<div class="col-filter-dd" id="cf-price" onclick="event.stopPropagation()">
    <div class="cfd-title">Select Category</div>
    <select class="cfd-select" id="cf-price-op"><option value="equal">Equal to</option><option value="lt">Less Than</option><option value="gt">Greater Than</option></select>
    <input type="number" class="cfd-input" id="cf-price-val" placeholder="PRICE/ UNIT" step="0.01" oninput="applyColFilters()"/>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-price')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>
<div class="col-filter-dd" id="cf-status" onclick="event.stopPropagation()">
    <label class="cfd-cb-row"><input type="checkbox" value="Unpaid" onchange="applyColFilters()"> Unpaid</label>
    <label class="cfd-cb-row"><input type="checkbox" value="Partial" onchange="applyColFilters()"> Partial</label>
    <label class="cfd-cb-row"><input type="checkbox" value="Paid" onchange="applyColFilters()"> Paid</label>
    <label class="cfd-cb-row"><input type="checkbox" value="Cancelled" onchange="applyColFilters()"> Cancelled</label>
    <div class="cfd-actions">
        <button class="cfd-clear" onclick="clearColFilter('cf-status')">Clear</button>
        <button class="cfd-apply" onclick="applyColFilters();closeAllColFilters()">Apply</button>
    </div>
</div>

{{-- BULK MODAL --}}
<div class="bulk-overlay" id="bulk-overlay" onclick="if(event.target===this)closeBulkModal()">
    <div class="bulk-modal" onclick="event.stopPropagation()">
        <div class="bulk-modal-hdr">
            <span class="bulk-modal-title" id="bulk-modal-title">Bulk Action</span>
            <button class="bulk-modal-close" onclick="closeBulkModal()">âœ•</button>
        </div>

        <div id="bulk-status-view" style="display:none;">
            <div style="padding:14px 24px;">
                <input class="bulk-modal-search" id="bulk-search" placeholder="Search services..." oninput="renderBulkRows()"/>
            </div>
            <div style="max-height:300px;overflow-y:auto;border-top:1px solid #f3f4f6;">
                <table class="bulk-table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="width:44px;padding:10px 16px;">
                                <input type="checkbox" id="bulk-check-all" style="width:15px;height:15px;accent-color:#2563eb;" onchange="toggleAllBulk(this)">
                            </th>
                            <th style="padding:10px 16px;font-size:11px;color:#9ca3af;text-align:left;font-weight:700;letter-spacing:.06em;">ITEM</th>
                            <th style="width:100px;padding:10px 16px;font-size:11px;color:#9ca3af;text-align:right;font-weight:700;letter-spacing:.06em;">PRICE</th>
                        </tr>
                    </thead>
                    <tbody id="bulk-tbody"></tbody>
                </table>
            </div>
            <div class="bulk-info-bar">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
                <span id="bulk-info-text">Showing only active services</span>
            </div>
        </div>

        <div id="bulk-update-view" style="display:none;">
            <div style="padding:14px 24px;border-bottom:1px solid #f3f4f6;">
                <input class="bulk-modal-search" id="bulk-update-search" placeholder="Search services..." oninput="renderBulkEditRows()"/>
            </div>
            <div style="max-height:360px;overflow-y:auto;">
                <div id="bulk-edit-tbody"></div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding:14px 24px;border-top:1px solid #f3f4f6;">
            <button onclick="closeBulkModal()" style="background:#f3f4f6;border:none;border-radius:7px;padding:10px 24px;font-size:13px;font-weight:600;cursor:pointer;color:#374151;">Cancel</button>
            <button id="bulk-action-btn" style="background:#e53e3e;color:#fff;border:none;border-radius:7px;padding:10px 24px;font-size:13px;font-weight:700;cursor:pointer;" onclick="applyBulkAction()">Apply</button>
        </div>
    </div>
</div>

<div class="modal fade" id="itemTxnPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemTxnPreviewModalTitle">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height:70vh;">
                <iframe id="itemTxnPreviewFrame" src="about:blank" title="Transaction Preview" style="width:100%;height:100%;border:0;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" id="itemTxnPreviewOpenPdf">Open PDF</button>
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="itemTxnPreviewPrint">Print</button>
                <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemTxnHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemTxnHistoryModalTitle">Transaction History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="itemTxnHistoryModalBody" style="min-height:52vh;">
                <div class="text-center text-muted py-5">Loading...</div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="bulk-overlay" id="assign-code-overlay" onclick="if(event.target===this)closeAssignCodeModal()">
    <div class="bulk-modal" onclick="event.stopPropagation()">
        <div class="bulk-modal-hdr">
            <span class="bulk-modal-title">Bulk Assign Code</span>
            <button class="bulk-modal-close" onclick="closeAssignCodeModal()">✕</button>
        </div>
        <div style="padding:14px 24px;border-bottom:1px solid #f3f4f6;">
            <input class="bulk-modal-search" id="assign-code-search" placeholder="Search services..." oninput="renderAssignCodeRows()"/>
        </div>
        <div style="max-height:360px;overflow-y:auto;">
            <div id="assign-code-tbody"></div>
        </div>
        <div class="bulk-info-bar">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
            <span>Showing services that don't have item code</span>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;padding:14px 24px;border-top:1px solid #f3f4f6;">
            <button onclick="closeAssignCodeModal()" style="background:#f3f4f6;border:none;border-radius:7px;padding:10px 24px;font-size:13px;font-weight:600;cursor:pointer;color:#374151;">Cancel</button>
            <button id="assign-code-save-btn" style="background:#e53e3e;color:#fff;border:none;border-radius:7px;padding:10px 24px;font-size:13px;font-weight:700;cursor:pointer;" onclick="saveAssignedCodes()">Assign Code</button>
        </div>
    </div>
</div>

<div class="u-overlay" id="add-conv-overlay" onclick="if(event.target===this)closeAddConversion()">
    <div class="u-mbox">
        <div class="u-mhdr">
            <span>Assign Unit</span>
            <button class="u-mclose" onclick="closeAddConversion()">✕</button>
        </div>
        <div class="u-mbody">
            <div class="conv-fields" style="display:flex; align-items:flex-end; gap:10px; flex-wrap:nowrap;">
                <div style="flex:1;">
                    <label class="u-mlabel">Base Unit</label>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-size:14px;font-weight:700;color:#374151;">1</span>
                        <select class="conv-select" id="conv-base"></select>
                    </div>
                </div>
                <div style="display:flex; align-items:center; padding-bottom:11px;">
                    <span class="conv-eq">=</span>
                </div>
                <div style="width:80px;">
                    <label class="u-mlabel">Rate</label>
                    <input type="number" class="conv-rate" id="conv-rate-input" value="0" min="0" style="width:100%;"/>
                </div>
                <div style="flex:1;">
                    <label class="u-mlabel">Secondary Unit</label>
                    <select class="conv-select" id="conv-secondary"></select>
                </div>
            </div>
        </div>
        <div class="u-mfoot">
            <button class="u-mbtn u-mbtn-new" onclick="saveConversionAndNew()">SAVE & NEW</button>
            <button class="u-mbtn u-mbtn-save" onclick="saveConversion()">SAVE</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let allItems     = @json($services ?? []);
let storedUnits  = @json($units ?? []);
let transactions = {};
let selectedIdx  = null;
let txnSortCol   = null;
let txnSortAsc   = true;
let pendingBulkSelection = [];

/* â”€â”€ Column resize â”€â”€ */
(function initColResize() {
    let isResizing = false, startX = 0, startW = 0, th = null, handle = null;
    document.addEventListener('mousedown', function(e) {
        if (!e.target.classList.contains('col-resize-handle')) return;
        e.preventDefault();
        handle = e.target; th = handle.closest('th');
        isResizing = true; startX = e.clientX; startW = th.offsetWidth;
        handle.classList.add('resizing');
        document.body.style.cursor = 'col-resize'; document.body.style.userSelect = 'none';
    });
    document.addEventListener('mousemove', function(e) {
        if (!isResizing) return;
        const newW = Math.max(60, startW + (e.clientX - startX));
        th.style.width = newW + 'px'; th.style.minWidth = newW + 'px';
    });
    document.addEventListener('mouseup', function() {
        if (!isResizing) return;
        isResizing = false;
        if (handle) handle.classList.remove('resizing');
        document.body.style.cursor = ''; document.body.style.userSelect = '';
        handle = null; th = null;
    });
})();

function updateSortArrows(col, asc) {
    document.querySelectorAll('#txn-thead-row th').forEach(th => th.classList.remove('sort-asc','sort-desc'));
    if (!col) return;
    const th = document.querySelector(`#txn-thead-row th[data-col="${col}"]`);
    if (th) th.classList.add(asc ? 'sort-asc' : 'sort-desc');
}

document.addEventListener('DOMContentLoaded', () => {
    renderList();
    ensureValidSelection();
    document.addEventListener('click', () => {
        closeSharePopup();
        closeAllColFilters();
        document.getElementById('bulk-dd')?.classList.remove('open');
        document.querySelectorAll('.il-row-menu.open, .il-item-dd.open').forEach(m => m.classList.remove('open'));
    });
});

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2500);
}

/* â”€â”€ Bulk dropdown â”€â”€ */
function toggleBulkDD(e) {
    e.stopPropagation();
    document.getElementById('bulk-dd').classList.toggle('open');
}

/* â”€â”€ Bulk modal â”€â”€ */
/* â”€â”€ Bulk modal â”€â”€ */
let bulkModalType = null;
const BULK_STATUS_KEY = 'vyapar-service-inactive-items';
let inactiveItemIds = loadInactiveItemIds();
const bulkConfig = {
    'inactive':    { title: 'Bulk Inactive', btnLabel: 'Mark as Inactive', info: 'Showing only active services' },
    'active':      { title: 'Bulk Active', btnLabel: 'Mark as Active', info: 'Showing only inactive services' },
    'bulk-update': { title: 'Bulk Update Items', btnLabel: 'Save Changes', info: '' },
    'bulk-assign-unit': { title: 'Assign Unit', btnLabel: 'Next', info: 'Showing all services' },
    'bulk-assign-code': { title: 'Bulk Assign Code', btnLabel: 'Next', info: "Showing services that don't have item code" },
};

function getItemId(item, idx) {
    return String(item?.id ?? `idx-${idx}`);
}

function loadInactiveItemIds() {
    try {
        return JSON.parse(localStorage.getItem(BULK_STATUS_KEY) || '[]');
    } catch (error) {
        return [];
    }
}

function saveInactiveItemIds() {
    localStorage.setItem(BULK_STATUS_KEY, JSON.stringify(inactiveItemIds));
}

function isItemInactive(item, idx) {
    return inactiveItemIds.includes(getItemId(item, idx));
}

function setItemInactive(item, idx, inactive) {
    const itemId = getItemId(item, idx);
    inactiveItemIds = inactive
        ? Array.from(new Set([...inactiveItemIds, itemId]))
        : inactiveItemIds.filter(id => id !== itemId);
    saveInactiveItemIds();
}

function getVisibleServices() {
    const q = (document.getElementById('search-input')?.value || '').toLowerCase();
    return allItems
        .map((item, index) => ({ item, index }))
        .filter(({ item, index }) => !isItemInactive(item, index) && (item.name || '').toLowerCase().includes(q));
}

function getBulkItems() {
    return allItems
        .map((item, index) => ({ item, index }))
        .filter(({ item, index }) => {
            if (bulkModalType === 'inactive') return !isItemInactive(item, index);
            if (bulkModalType === 'active') return isItemInactive(item, index);
            if (bulkModalType === 'bulk-assign-code') return !(item.item_code || '').trim();
            return true;
        });
}

function openBulkModal(type) {
    bulkModalType = type;
    document.getElementById('bulk-dd')?.classList.remove('open');
    const cfg = bulkConfig[type] || { title: 'Bulk Action', btnLabel: 'Apply', info: 'Showing all services' };
    document.getElementById('bulk-modal-title').textContent = cfg.title;
    document.getElementById('bulk-action-btn').textContent  = cfg.btnLabel;

    const statusView = document.getElementById('bulk-status-view');
    const updateView = document.getElementById('bulk-update-view');

    if (type === 'bulk-update') {
        statusView.style.display = 'none';
        updateView.style.display = 'block';
        document.getElementById('bulk-update-search').value = '';
        renderBulkEditRows();
    } else {
        statusView.style.display = 'block';
        updateView.style.display = 'none';
        document.getElementById('bulk-info-text').textContent = cfg.info;
        document.getElementById('bulk-search').value = '';
        document.getElementById('bulk-check-all').checked = false;
        renderBulkRows();
    }

    document.getElementById('bulk-overlay').classList.add('open');
}

function closeBulkModal() {
    document.getElementById('bulk-overlay').classList.remove('open');
    bulkModalType = null;
    pendingBulkSelection = [];
}

function renderBulkRows() {
    const tbody = document.getElementById('bulk-tbody');
    if (!tbody) return;

    const search = (document.getElementById('bulk-search')?.value || '').toLowerCase();
    const rows = getBulkItems().filter(({ item }) => (item.name || '').toLowerCase().includes(search));

    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="3" class="bulk-empty">No services to show</td></tr>`;
        document.getElementById('bulk-check-all').checked = false;
        return;
    }

    tbody.innerHTML = rows.map(({ item, index }) => `
        <tr>
            <td style="width:44px;padding:10px 16px;">
                <input type="checkbox" data-idx="${index}" style="width:15px;height:15px;accent-color:#2563eb;">
            </td>
            <td style="font-size:14px;color:#111827;padding:10px 16px;">${esc(item.name)}</td>
            <td style="width:100px;text-align:right;font-size:14px;color:#16a34a;padding:10px 16px;">${item.sale_price ? 'Rs ' + parseFloat(item.sale_price).toFixed(2) : '—'}</td>
        </tr>`).join('');
}

function renderBulkEditRows() {
    const tbody = document.getElementById('bulk-edit-tbody');
    if (!tbody) return;

    const search = (document.getElementById('bulk-update-search')?.value || '').toLowerCase();
    const rows = allItems.map((item, index) => ({ item, index }))
        .filter(({ item }) => (item.name || '').toLowerCase().includes(search));

    if (!rows.length) {
        tbody.innerHTML = `<div class="bulk-empty">No services to show</div>`;
        return;
    }

    tbody.innerHTML = rows.map(({ item, index }) => {
        const itemId = item.id || index;
        return `
        <div class="bulk-row-editor">
            <input type="text" class="bulk-edit-field bulk-col-item" placeholder="Service Name" value="${esc(item.name)}" data-item-id="${itemId}" data-field="name"/>
            <input type="text" class="bulk-edit-field" placeholder="Item Code" value="${esc(item.item_code || '')}" data-item-id="${itemId}" data-field="item_code"/>
            <input type="text" class="bulk-edit-field" placeholder="Category" value="${esc(item.category || '')}" data-item-id="${itemId}" data-field="category"/>
            <input type="number" class="bulk-edit-field" placeholder="Sale Price" value="${item.sale_price || ''}" data-item-id="${itemId}" data-field="sale_price" step="0.01" min="0"/>
            <input type="number" class="bulk-edit-field" placeholder="Purchase Price" value="${item.purchase_price || ''}" data-item-id="${itemId}" data-field="purchase_price" step="0.01" min="0"/>
            <input type="text" class="bulk-edit-field" placeholder="Location" value="${esc(item.location || '')}" data-item-id="${itemId}" data-field="location"/>
        </div>`;
    }).join('');
}

function toggleAllBulk(el) {
    document.querySelectorAll('#bulk-tbody input[type=checkbox]').forEach(cb => cb.checked = el.checked);
}

function applyBulkAction() {
    if (bulkModalType === 'bulk-update') {
        applyBulkUpdate();
        return;
    }

    const selectedIndexes = [...document.querySelectorAll('#bulk-tbody input[type=checkbox]:checked')]
        .map(cb => Number(cb.dataset.idx))
        .filter(idx => !Number.isNaN(idx));

    if (!selectedIndexes.length) {
        showToast('Please select at least one service.');
        return;
    }

    if (bulkModalType === 'bulk-assign-unit' || bulkModalType === 'bulk-assign-code') {
        pendingBulkSelection = selectedIndexes;
        const nextType = bulkModalType;
        document.getElementById('bulk-overlay').classList.remove('open');
        bulkModalType = null;
        if (nextType === 'bulk-assign-unit') {
            openAssignUnitModal();
        } else {
            openAssignCodeModal();
        }
        return;
    }

    const makeInactive = bulkModalType === 'inactive';
    selectedIndexes.forEach(idx => setItemInactive(allItems[idx], idx, makeInactive));
    renderBulkRows();
    renderList();
    ensureValidSelection();
    showToast(makeInactive ? 'Selected services marked inactive.' : 'Selected services marked active.');
}

function applyBulkUpdate() {
    const updates = {};
    document.querySelectorAll('#bulk-edit-tbody input[data-field]').forEach(input => {
        const itemId = input.dataset.itemId;
        const field = input.dataset.field;
        const value = input.value;
        if (!itemId) return;
        if (!updates[itemId]) updates[itemId] = {};
        updates[itemId][field] = value === '' ? null : value;
    });

    if (!Object.keys(updates).length) {
        showToast('No changes to save.');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) { showToast('CSRF token missing.'); return; }

    const saveBtn = document.getElementById('bulk-action-btn');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    const requests = Object.entries(updates).map(([itemId, fields]) =>
        fetch(`{{ url('dashboard/items') }}/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ ...fields, _method: 'PUT' })
        }).then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok || data.success === false) {
                throw new Error(data.message || `Failed to update service ${itemId}.`);
            }
            return { itemId, fields, item: data.item || null };
        })
    );

    Promise.all(requests)
    .then(results => {
        results.forEach(({ itemId, fields, item }) => {
            const idx = allItems.findIndex(entry => String(entry.id) === String(itemId));
            if (idx >= 0) allItems[idx] = { ...allItems[idx], ...fields, ...(item || {}) };
        });
        showToast('Services updated successfully!');
        closeBulkModal();
        renderList();
        ensureValidSelection();
    })
    .catch(error => showToast(error.message || 'Failed to update services.'))
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Save Changes';
    });
}

function submitBulkItemUpdates(updates, successMessage, onDone) {
    if (!updates.length) {
        showToast('No changes to save.');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        showToast('CSRF token missing.');
        return;
    }

    const requests = updates.map(({ itemId, fields }) =>
        fetch(`{{ url('dashboard/items') }}/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ ...fields, _method: 'PUT' })
        }).then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok || data.success === false) {
                throw new Error(data.message || `Failed to update service ${itemId}.`);
            }
            return { itemId, fields, item: data.item || null };
        })
    );

    Promise.all(requests)
        .then(results => {
            results.forEach(({ itemId, fields, item }) => {
                const idx = allItems.findIndex(entry => String(entry.id) === String(itemId));
                if (idx >= 0) {
                    allItems[idx] = { ...allItems[idx], ...fields, ...(item || {}) };
                }
            });
            showToast(successMessage);
            renderList();
            ensureValidSelection();
            if (typeof onDone === 'function') onDone();
        })
        .catch(error => showToast(error.message || 'Failed to update services.'));
}

function populateUnitSelectOptions() {
    const baseSelect = document.getElementById('conv-base');
    const secondarySelect = document.getElementById('conv-secondary');
    if (!baseSelect || !secondarySelect) return;

    const options = storedUnits.map(unit => {
        const label = unit.short_name ? `${unit.name} (${unit.short_name})` : unit.name;
        return `<option value="${esc(unit.short_name || unit.name)}">${esc(label)}</option>`;
    }).join('');

    baseSelect.innerHTML = options;
    secondarySelect.innerHTML = options;

    if (storedUnits.length > 1) {
        secondarySelect.selectedIndex = 1;
    }
}

function openAssignUnitModal() {
    if (!pendingBulkSelection.length) return showToast('Please select at least one service.');
    populateUnitSelectOptions();
    document.getElementById('conv-rate-input').value = 0;
    document.getElementById('add-conv-overlay').classList.add('open');
}

function closeAddConversion() {
    document.getElementById('add-conv-overlay').classList.remove('open');
}

function saveConversionAndNew() {
    saveAssignedUnit(false);
}

function saveConversion() {
    saveAssignedUnit(true);
}

function saveAssignedUnit(closeAfterSave = true) {
    const baseUnit = document.getElementById('conv-base')?.value || '';
    const secondaryUnit = document.getElementById('conv-secondary')?.value || '';
    const conversionRate = parseFloat(document.getElementById('conv-rate-input')?.value || 0);

    if (!baseUnit) return showToast('Please select a base unit.');

    const updates = pendingBulkSelection
        .map(idx => ({
            itemId: allItems[idx]?.id,
            fields: {
                unit: baseUnit,
                secondary_unit: secondaryUnit || null,
                unit_conversion_rate: Number.isNaN(conversionRate) ? 0 : conversionRate
            }
        }))
        .filter(row => row.itemId);

    submitBulkItemUpdates(updates, 'Units assigned successfully!', () => {
        if (closeAfterSave) {
            closeAddConversion();
            pendingBulkSelection = [];
        }
    });
}

function openAssignCodeModal() {
    if (!pendingBulkSelection.length) return showToast('Please select at least one service.');
    document.getElementById('assign-code-search').value = '';
    renderAssignCodeRows();
    document.getElementById('assign-code-overlay').classList.add('open');
}

function closeAssignCodeModal() {
    document.getElementById('assign-code-overlay').classList.remove('open');
    pendingBulkSelection = [];
}

function renderAssignCodeRows() {
    const tbody = document.getElementById('assign-code-tbody');
    if (!tbody) return;

    const search = (document.getElementById('assign-code-search')?.value || '').toLowerCase();
    const rows = pendingBulkSelection
        .map(index => ({ item: allItems[index], index }))
        .filter(({ item }) => item && !(item.item_code || '').trim())
        .filter(({ item }) => (item.name || '').toLowerCase().includes(search));

    if (!rows.length) {
        tbody.innerHTML = `<div class="bulk-empty">No services to show</div>`;
        return;
    }

    tbody.innerHTML = rows.map(({ item, index }) => `
        <div class="bulk-row-editor">
            <input type="text" class="bulk-edit-field bulk-col-item" value="${esc(item.name)}" readonly />
            <input type="text" class="bulk-edit-field" placeholder="Enter Item Code" data-assign-code-idx="${index}" value="${esc(item.item_code || '')}" />
        </div>
    `).join('');
}

function saveAssignedCodes() {
    const updates = [...document.querySelectorAll('[data-assign-code-idx]')]
        .map(input => ({
            itemId: allItems[Number(input.dataset.assignCodeIdx)]?.id,
            fields: { item_code: input.value.trim() || null }
        }))
        .filter(row => row.itemId);

    submitBulkItemUpdates(updates, 'Item codes assigned successfully!', () => {
        closeAssignCodeModal();
    });
}
/* â”€â”€ Share popup â”€â”€ */
function toggleSharePopup(e) { e.stopPropagation(); document.getElementById('share-popup').classList.toggle('open'); }
function closeSharePopup() { document.getElementById('share-popup')?.classList.remove('open'); }
function shareVia(method) {
    closeSharePopup();
    const name = allItems[selectedIdx]?.name || 'service';
    if (method === 'copy') { navigator.clipboard.writeText(window.location.href).then(() => showToast('Link copied!')); }
    else if (method === 'email') { window.open(`mailto:?subject=Service: ${name}&body=${window.location.href}`); }
    else if (method === 'whatsapp') { window.open(`https://wa.me/?text=Service: ${name} - ${window.location.href}`); }
    else if (method === 'sms') { window.open(`sms:?body=Service: ${name}`); }
}

/* â”€â”€ Search â”€â”€ */
/* â”€â”€ Search â”€â”€ */
function toggleSearch() {
    const w = document.getElementById('search-wrap');
    w.classList.toggle('open');
    if (w.classList.contains('open')) document.getElementById('search-input').focus();
}
function filterItems() {
    renderList();
}

/* â”€â”€ Render list â”€â”€ */
function ensureValidSelection() {
    const visibleItems = getVisibleServices();
    if (!visibleItems.length) {
        selectedIdx = null;
        document.getElementById('no-selection').style.display = 'flex';
        document.getElementById('item-detail').style.display  = 'none';
        return;
    }
    if (selectedIdx === null || !visibleItems.some(({ index }) => index === selectedIdx)) {
        selectItem(visibleItems[0].index);
    }
}

function renderList(items = getVisibleServices()) {
    const c = document.getElementById('items-list');
    if (!c) return;
    if (!items.length) {
        c.innerHTML = `<div style="padding:32px 16px;text-align:center;color:#9ca3af;font-size:13px;">No services found</div>`;
        return;
    }
    c.innerHTML = items.map(({ item, index }) => `
        <div class="il-item-row ${selectedIdx === index ? 'active' : ''}" onclick="selectItem(${index})">
            <span class="il-item-dot"></span>
            <span class="il-item-name">${esc(item.name)}</span>
            <span class="il-item-price">${item.sale_price ? 'Rs ' + parseFloat(item.sale_price).toFixed(2) : 'â€”'}</span>
            <div class="il-item-more-wrap" onclick="event.stopPropagation()">
                <button class="il-item-more-btn" onclick="toggleItemDD(event,${index})" title="Options">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                    </svg>
                </button>
                <div class="il-item-dd" id="item-dd-${index}">
                    <div class="il-item-dd-item" onclick="editItemNav(${index})">View/Edit</div>
                    <div class="il-item-dd-item danger" onclick="deleteItem(${index})">Delete</div>
                </div>
            </div>
        </div>
    `).join('');
}

function toggleItemDD(e, i) {
    e.stopPropagation();
    document.querySelectorAll('.il-item-dd.open').forEach(d => d.classList.remove('open'));
    document.getElementById(`item-dd-${i}`).classList.toggle('open');
}
function editItemNav(i) { window.location.href = '{{ url("dashboard/items") }}/' + (allItems[i].id || i) + '/edit'; }

/* â”€â”€ Delete service â”€â”€ */
function deleteItem(i) {
    const item = allItems[i];
    if (!item) return;
    document.querySelectorAll('.il-item-dd.open').forEach(d => d.classList.remove('open'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) { showToast('CSRF token missing.'); return; }
    const formData = new FormData();
    formData.append('_method', 'DELETE');
    formData.append('_token', csrfToken);
    fetch(`{{ url("dashboard/items") }}/${item.id}`, { method: 'POST', headers: { 'Accept': 'application/json' }, body: formData })
    .then(async r => {
        if (r.ok) {
            const deletedId = getItemId(item, i);
            allItems.splice(i, 1);
            inactiveItemIds = inactiveItemIds.filter(id => id !== deletedId);
            saveInactiveItemIds();
            selectedIdx = null;
            document.getElementById('no-selection').style.display = 'flex';
            document.getElementById('item-detail').style.display  = 'none';
            renderList();
            ensureValidSelection();
            showToast('Service deleted successfully');
        } else {
            let msg = 'Failed to delete service';
            try { const data = await r.json(); if (data.message) msg = data.message; } catch(e) {}
            showToast(msg);
        }
    })
    .catch(() => showToast('Network error. Please try again.'));
}

/* â”€â”€ Select item â”€â”€ */
function selectItem(idx) {
    selectedIdx = idx;
    const item = allItems[idx];
    renderList();
    document.getElementById('no-selection').style.display = 'none';
    const detail = document.getElementById('item-detail');
    detail.style.display = 'flex';
    document.getElementById('detail-name').textContent     = item.name;
    document.getElementById('detail-sale').textContent     = item.sale_price ? 'Rs ' + parseFloat(item.sale_price).toFixed(2) : 'â€”';
    document.getElementById('detail-category').textContent = (item.category && item.category.name) ? item.category.name : (item.category || 'â€”');
    renderTxns(idx);
    loadTransactions(idx);
}
/* â”€â”€ Transactions â”€â”€ */
function getCurrentTxnRows(idx) {
    const searchValue = (document.querySelector('.il-txn-search')?.value || '').toLowerCase().trim();
    let rows = (transactions[idx] || []).map((txn, originalIndex) => ({ txn, originalIndex }));

    if (searchValue) {
        rows = rows.filter(({ txn }) => [
            txn.type,
            txn.invoice,
            txn.name,
            txn.details,
            txn.status,
            txn.raw_type
        ].some(value => String(value || '').toLowerCase().includes(searchValue)));
    }

    const typeChecked = [...document.querySelectorAll('#cf-type input[type=checkbox]:checked')].map(c => c.value.toLowerCase());
    if (typeChecked.length) {
        rows = rows.filter(({ txn }) => typeChecked.includes(String(txn.type || '').toLowerCase()));
    }

    const invoiceOp = document.getElementById('cf-invoice-op')?.value;
    const invoiceVal = (document.getElementById('cf-invoice-val')?.value || '').toLowerCase();
    if (invoiceVal) {
        rows = rows.filter(({ txn }) => {
            const value = String(txn.invoice || '').toLowerCase();
            return invoiceOp === 'exact' ? value === invoiceVal : value.includes(invoiceVal);
        });
    }

    const nameOp = document.getElementById('cf-name-op')?.value;
    const nameVal = (document.getElementById('cf-name-val')?.value || '').toLowerCase();
    if (nameVal) {
        rows = rows.filter(({ txn }) => {
            const value = String(txn.name || '').toLowerCase();
            return nameOp === 'exact' ? value === nameVal : value.includes(nameVal);
        });
    }

    const brokerOp = document.getElementById('cf-broker-op')?.value;
    const brokerVal = (document.getElementById('cf-broker-val')?.value || '').toLowerCase();
    if (brokerVal) {
        rows = rows.filter(({ txn }) => {
            const value = String(txn.broker || '').toLowerCase();
            return brokerOp === 'exact' ? value === brokerVal : value.includes(brokerVal);
        });
    }

    const dateOp = document.getElementById('cf-date-op')?.value;
    const dateVal = document.getElementById('cf-date-val')?.value;
    if (dateVal) {
        rows = rows.filter(({ txn }) => {
            if (!txn.date) return false;
            const parts = String(txn.date).split('/');
            const normalized = parts.length === 3 ? `${parts[2]}-${parts[1]}-${parts[0]}` : String(txn.date);
            if (dateOp === 'before') return normalized < dateVal;
            if (dateOp === 'after') return normalized > dateVal;
            return normalized === dateVal;
        });
    }

    const qtyOp = document.getElementById('cf-qty-op')?.value;
    const qtyVal = document.getElementById('cf-qty-val')?.value;
    if (qtyVal !== '' && qtyVal !== undefined) {
        const number = parseFloat(qtyVal);
        if (!Number.isNaN(number)) {
            rows = rows.filter(({ txn }) => {
                const value = parseFloat(txn.qty || 0);
                if (qtyOp === 'lt') return value < number;
                if (qtyOp === 'gt') return value > number;
                return value === number;
            });
        }
    }

    const priceOp = document.getElementById('cf-price-op')?.value;
    const priceVal = document.getElementById('cf-price-val')?.value;
    if (priceVal !== '' && priceVal !== undefined) {
        const number = parseFloat(priceVal);
        if (!Number.isNaN(number)) {
            rows = rows.filter(({ txn }) => {
                const value = parseFloat(txn.price || 0);
                if (priceOp === 'lt') return value < number;
                if (priceOp === 'gt') return value > number;
                return value === number;
            });
        }
    }

    const statusChecked = [...document.querySelectorAll('#cf-status input[type=checkbox]:checked')].map(c => c.value.toLowerCase());
    if (statusChecked.length) {
        rows = rows.filter(({ txn }) => statusChecked.includes(String(txn.status || '').toLowerCase()));
    }

    if (txnSortCol) {
        rows.sort((a, b) => {
            const txA = a.txn;
            const txB = b.txn;
            let valA = '';
            let valB = '';

            switch (txnSortCol) {
                case 'qty':
                    valA = parseFloat(txA.qty || 0);
                    valB = parseFloat(txB.qty || 0);
                    break;
                case 'price':
                    valA = parseFloat(txA.price || 0);
                    valB = parseFloat(txB.price || 0);
                    break;
                case 'amount':
                    valA = parseFloat(txA.amount || 0);
                    valB = parseFloat(txB.amount || 0);
                    break;
                case 'net_w':
                    valA = parseFloat(txA.net_w || 0);
                    valB = parseFloat(txB.net_w || 0);
                    break;
                case 'date':
                    valA = String(txA.date || '');
                    valB = String(txB.date || '');
                    break;
                case 'invoice':
                    valA = String(txA.invoice || '');
                    valB = String(txB.invoice || '');
                    break;
                case 'name':
                    valA = String(txA.name || '');
                    valB = String(txB.name || '');
                    break;
                case 'broker':
                    valA = String(txA.broker || '');
                    valB = String(txB.broker || '');
                    break;
                case 'type':
                    valA = String(txA.type || '');
                    valB = String(txB.type || '');
                    break;
                case 'status':
                    valA = String(txA.status || '');
                    valB = String(txB.status || '');
                    break;
            }

            if (typeof valA === 'number' && typeof valB === 'number') {
                return txnSortAsc ? valA - valB : valB - valA;
            }

            return txnSortAsc
                ? String(valA).localeCompare(String(valB))
                : String(valB).localeCompare(String(valA));
        });
    }

    return rows;
}

function renderTxns(idx) {
    const tbody = document.getElementById('txn-tbody');
    const rows = getCurrentTxnRows(idx);
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="12" style="text-align:center;color:#9ca3af;padding:48px 0;font-size:13px;">No transactions to show</td></tr>`;
        return;
    }

    const statusColor = { Paid: '#22c55e', Partial: '#f59e0b', Unpaid: '#ef4444' };

    tbody.innerHTML = rows.map(({ txn: t, originalIndex: ti }) => `
        <tr id="txn-row-${idx}-${ti}" onclick="openTxnAction(${idx},${ti},'edit')" style="cursor:pointer;user-select:none;">
            <td class="td-dot"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#111111;"></span></td>
            <td class="td-date">${esc(t.date)}</td>
            <td>${esc(t.invoice || '')}</td>
            <td>${esc(t.type)}</td>
            <td class="td-name">${esc(t.name || '')}</td>
            <td class="td-broker">${esc(t.broker || '')}</td>
            <td>${t.qty || '—'}</td>
            <td class="td-netw" style="color:${(t.status || 'Unpaid') === 'Unpaid' ? '#ef4444' : '#374151'};">${parseFloat(t.net_w || 0).toFixed(2)} kg</td>
            <td class="td-price" style="color:${(t.status || 'Unpaid') === 'Paid' ? '#22c55e' : (t.status || 'Unpaid') === 'Unpaid' ? '#ef4444' : '#9ca3af'};">${t.amount !== undefined && t.amount !== null ? 'Rs ' + parseFloat(t.amount || 0).toFixed(2) : '—'}</td>
            <td class="td-price" style="color:${(t.status || 'Unpaid') === 'Unpaid' ? '#ef4444' : '#374151'};">${t.price ? 'Rs ' + parseFloat(t.price).toFixed(2) : '—'}</td>
            <td class="td-status" style="font-weight:500;color:${statusColor[t.status || 'Unpaid'] || '#9ca3af'};">${esc(t.status || '—')}</td>
            <td class="td-actions">
                <div class="il-row-menu-wrap">
                    <button class="il-row-menu-btn" onclick="toggleRowMenu(event,'row-menu-${idx}-${ti}')">⋮</button>
                    <div class="il-row-menu" id="row-menu-${idx}-${ti}">
                        ${buildTxnMenu(idx, ti, t)}
                    </div>
                </div>
            </td>
        </tr>`).join('');
}

function selectTxnRow(idx, ti) {
    document.querySelectorAll('#txn-tbody tr').forEach(r => r.classList.remove('txn-selected'));
    const row = document.getElementById(`txn-row-${idx}-${ti}`);
    if (row) row.classList.add('txn-selected');
}

function loadTransactions(idx) {
    const item = allItems[idx];
    if (!item || !item.id) {
        transactions[idx] = [];
        if (selectedIdx === idx) renderTxns(idx);
        return;
    }

    fetch(`{{ url("dashboard/items") }}/${item.id}/transactions`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async response => {
        const data = await response.json().catch(() => []);
        if (!response.ok) {
            throw new Error('Failed to load transactions.');
        }
        transactions[idx] = Array.isArray(data) ? data : [];
        if (selectedIdx === idx) renderTxns(idx);
    })
    .catch(() => {
        transactions[idx] = [];
        if (selectedIdx === idx) renderTxns(idx);
    });
}
function filterTxns(q) { renderTxns(selectedIdx); }
function sortTxnCol(col) {
    if (txnSortCol === col) { txnSortAsc = !txnSortAsc; } else { txnSortCol = col; txnSortAsc = true; }
    updateSortArrows(col, txnSortAsc);
    renderTxns(selectedIdx);
}
function toggleRowMenu(e, id) {
    e.stopPropagation();
    const btn = e.currentTarget; const rect = btn.getBoundingClientRect();
    document.querySelectorAll('.il-row-menu.open').forEach(m => { if(m.id!==id) m.classList.remove('open'); });
    const menu = document.getElementById(id); const isOpen = menu.classList.contains('open');
    menu.classList.remove('open');
    if (!isOpen) {
        menu.style.visibility = 'hidden';
        menu.style.top = '0px';
        menu.style.left = '0px';
        menu.classList.add('open');
        requestAnimationFrame(() => {
            const mRect = menu.getBoundingClientRect();
            const viewportHeight = window.innerHeight || document.documentElement.clientHeight || 0;
            const spaceBelow = viewportHeight - rect.bottom;
            const shouldOpenUp = spaceBelow < mRect.height + 12;
            const top = shouldOpenUp
                ? (rect.top + window.scrollY - mRect.height - 2)
                : (rect.bottom + window.scrollY + 2);
            let left = rect.right - mRect.width;
            if (left < 4) left = 4;
            menu.style.top = `${Math.max(4, top)}px`;
            menu.style.left = `${left}px`;
            menu.style.visibility = 'visible';
        });
    }
}
function getTxn(idx, ti) {
    return transactions[idx]?.[ti] || null;
}
function getTxnActionLinks(txn) {
    if (!txn || !txn.id) return {};
    const base = `{{ url('dashboard') }}`;
    const type = String(txn.raw_type || '').toLowerCase();
    const id = encodeURIComponent(txn.id);
    const links = { edit: null, delete: null, cancel: null, duplicate: null, convert_return: null, pdf: null, preview: null, print: null, payment_history: null, history: null };

    if (type === 'invoice' || type === 'pos') {
        links.edit = `${base}/sales/${id}/edit`;
        links.delete = `${base}/sales/${id}`;
        links.cancel = `${base}/sales/${id}/cancel`;
        links.duplicate = `${base}/sales/${id}/duplicate`;
        links.convert_return = `${base}/sale-return/create?sale_id=${id}`;
        links.pdf = `${base}/invoice/download-pdf?sale_id=${id}`;
        links.preview = `${base}/invoice/modal-preview?sale_id=${id}`;
        links.print = `${base}/invoice/modal-preview?sale_id=${id}&print=1`;
        links.payment_history = `${base}/sales/${id}/payment-history`;
        links.history = `${base}/sales/${id}/bank-history`;
        return links;
    }
    if (type === 'estimate') {
        links.edit = `${base}/estimates/${id}/edit`;
        links.delete = `${base}/estimates/${id}`;
        links.duplicate = `${base}/estimates/${id}/convert-to-sale`;
        links.pdf = `${base}/invoice/download-pdf?sale_id=${id}`;
        links.preview = `${base}/invoice/modal-preview?sale_id=${id}`;
        links.print = `${base}/invoice/modal-preview?sale_id=${id}&print=1`;
        return links;
    }
    if (type === 'proforma' || type === 'proforma_invoice') {
        links.edit = `${base}/proforma-invoice/${id}/edit`;
        links.delete = `${base}/proforma-invoice/${id}`;
        links.duplicate = `${base}/proforma-invoice/${id}/convert-to-sale`;
        links.pdf = `${base}/invoice/download-pdf?sale_id=${id}`;
        links.preview = `${base}/invoice/modal-preview?sale_id=${id}`;
        links.print = `${base}/invoice/modal-preview?sale_id=${id}&print=1`;
        return links;
    }
    if (type === 'sale_return') {
        links.edit = `${base}/sale-return/${id}/edit`;
        links.delete = `${base}/sale-return/${id}`;
        links.duplicate = `${base}/sale-return/${id}/duplicate`;
        links.pdf = `${base}/invoice/download-pdf?sale_id=${id}`;
        links.preview = `${base}/invoice/modal-preview?sale_id=${id}`;
        links.print = `${base}/invoice/modal-preview?sale_id=${id}&print=1`;
        return links;
    }
    if (type === 'delivery_challan') {
        links.edit = `${base}/delivery-challan/${id}/edit`;
        links.delete = `${base}/delivery-challan/${id}`;
        links.duplicate = `${base}/delivery-challans/${id}/convert-to-sale`;
        links.pdf = `${base}/invoice/download-pdf?sale_id=${id}&doc=delivery_challan`;
        links.preview = `${base}/invoice/modal-preview?sale_id=${id}&doc=delivery_challan`;
        links.print = `${base}/invoice/modal-preview?sale_id=${id}&doc=delivery_challan&print=1`;
        return links;
    }
    if (type === 'sale_order') {
        links.edit = `${base}/sale-orders/${id}/edit`;
        links.duplicate = `${base}/sale-orders/${id}/convert-to-sale`;
        links.pdf = `${base}/invoice/download-pdf?sale_id=${id}`;
        links.preview = `${base}/invoice/modal-preview?sale_id=${id}`;
        links.print = `${base}/invoice/modal-preview?sale_id=${id}&print=1`;
        return links;
    }
    return links;
}
function buildTxnMenu(idx, ti, txn) {
    const links = getTxnActionLinks(txn);
    const items = [];
    if (links.edit) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'edit')">View/Edit</div>`);
    if (links.cancel) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'cancel')">Cancel Invoice</div>`);
    if (links.delete) items.push(`<div class="il-row-menu-item danger" onclick="event.stopPropagation(); deleteTxn(${idx},${ti})">Delete</div>`);
    if (links.duplicate) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'duplicate')">Duplicate</div>`);
    if (links.pdf) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'pdf')">Open PDF</div>`);
    if (links.preview) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'preview')">Preview</div>`);
    if (links.print) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'print')">Print</div>`);
    if (links.convert_return) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'convert_return')">Convert To Return</div>`);
    if (links.payment_history) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'payment_history')">Payment History</div>`);
    if (links.history) items.push(`<div class="il-row-menu-item" onclick="event.stopPropagation(); openTxnAction(${idx},${ti},'history')">View History</div>`);
    return items.join('');
}

function openUrlInNewTab(url) {
    const win = window.open(url, '_blank', 'noopener');
    if (!win) showToast('Please allow popups for this action.');
}

const itemTxnPreviewModalEl = document.getElementById('itemTxnPreviewModal');
const itemTxnPreviewModal = itemTxnPreviewModalEl ? bootstrap.Modal.getOrCreateInstance(itemTxnPreviewModalEl) : null;
const itemTxnPreviewFrame = document.getElementById('itemTxnPreviewFrame');
const itemTxnPreviewModalTitle = document.getElementById('itemTxnPreviewModalTitle');
const itemTxnPreviewOpenPdfBtn = document.getElementById('itemTxnPreviewOpenPdf');
const itemTxnPreviewPrintBtn = document.getElementById('itemTxnPreviewPrint');
const itemTxnHistoryModalEl = document.getElementById('itemTxnHistoryModal');
const itemTxnHistoryModal = itemTxnHistoryModalEl ? bootstrap.Modal.getOrCreateInstance(itemTxnHistoryModalEl) : null;
const itemTxnHistoryModalTitle = document.getElementById('itemTxnHistoryModalTitle');
const itemTxnHistoryModalBody = document.getElementById('itemTxnHistoryModalBody');

function openItemTxnPreview(url, title, options = {}) {
    if (!url) {
        showToast('Preview is not available for this transaction.');
        return;
    }

    if (!itemTxnPreviewModal || !itemTxnPreviewFrame) {
        openUrlInNewTab(options.pdfUrl || url);
        return;
    }

    itemTxnPreviewModalTitle.textContent = title || 'Preview';
    itemTxnPreviewFrame.src = url;
    itemTxnPreviewFrame.dataset.pdfUrl = options.pdfUrl || url;
    itemTxnPreviewFrame.dataset.printUrl = options.printUrl || '';
    itemTxnPreviewModal.show();
}

itemTxnPreviewOpenPdfBtn?.addEventListener('click', function () {
    const pdfUrl = itemTxnPreviewFrame?.dataset?.pdfUrl || itemTxnPreviewFrame?.src;
    if (!pdfUrl) return showToast('PDF is not available for this transaction.');
    openUrlInNewTab(pdfUrl);
});

itemTxnPreviewPrintBtn?.addEventListener('click', function () {
    const printUrl = itemTxnPreviewFrame?.dataset?.printUrl || itemTxnPreviewFrame?.dataset?.pdfUrl || itemTxnPreviewFrame?.src;
    if (!printUrl) return showToast('Print is not available for this transaction.');
    openUrlInNewTab(printUrl);
});

itemTxnPreviewModalEl?.addEventListener('hidden.bs.modal', function () {
    if (itemTxnPreviewFrame) {
        itemTxnPreviewFrame.src = 'about:blank';
        delete itemTxnPreviewFrame.dataset.pdfUrl;
        delete itemTxnPreviewFrame.dataset.printUrl;
    }
});
function renderTxnHistoryPlaceholder(message) {
    return `
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-5 text-muted" style="min-height:40vh;">
            <div style="font-size:56px;opacity:.15;line-height:1;">&#128196;</div>
            <div class="mt-3" style="font-size:26px;opacity:.18;">&#128196;&#128196;</div>
            <p class="mt-4 mb-0 fw-semibold" style="font-size:15px;color:#8b93a7;">${message}</p>
        </div>
    `;
}
function openTxnHistoryModal(url, title, mode = 'history') {
    if (!url || !itemTxnHistoryModal || !itemTxnHistoryModalBody) {
        return showToast('History is not available for this transaction.');
    }
    itemTxnHistoryModalTitle.textContent = title;
    itemTxnHistoryModalBody.innerHTML = `<div class="text-center text-muted py-5">Loading...</div>`;
    itemTxnHistoryModal.show();
    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(async response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (mode === 'payment' && Array.isArray(data.payments)) {
                if (!data.payments.length) {
                    itemTxnHistoryModalBody.innerHTML = renderTxnHistoryPlaceholder('No payment history found for this transaction.');
                    return;
                }
                const paymentRows = data.payments.map((payment, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${esc(payment.date || '-')}</td>
                        <td>${esc(payment.payment_type || '-')}</td>
                        <td>${esc(payment.bank_name || '-')}</td>
                        <td>${esc(payment.reference || '-')}</td>
                        <td class="text-end">Rs ${parseFloat(payment.amount || 0).toFixed(2)}</td>
                    </tr>
                `).join('');
                itemTxnHistoryModalBody.innerHTML = `
                    <div class="row g-3 mb-3">
                        <div class="col-md-4"><div class="border rounded-3 p-3"><div class="text-muted small">Invoice No.</div><div class="fw-semibold">${esc(data.bill_number || '-')}</div></div></div>
                        <div class="col-md-4"><div class="border rounded-3 p-3"><div class="text-muted small">Grand Total</div><div class="fw-semibold">Rs ${parseFloat(data.grand_total || 0).toFixed(2)}</div></div></div>
                        <div class="col-md-4"><div class="border rounded-3 p-3"><div class="text-muted small">Received / Balance</div><div class="fw-semibold">Rs ${parseFloat(data.received_amount || 0).toFixed(2)} / Rs ${parseFloat(data.balance || 0).toFixed(2)}</div></div></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>#</th><th>Date</th><th>Payment Type</th><th>Bank</th><th>Reference</th><th class="text-end">Amount</th></tr></thead>
                            <tbody>${paymentRows}</tbody>
                        </table>
                    </div>
                `;
                return;
            }
            if (Array.isArray(data.entries)) {
                if (!data.entries.length) {
                    itemTxnHistoryModalBody.innerHTML = renderTxnHistoryPlaceholder('No history found for this transaction.');
                    return;
                }
                const entryRows = data.entries.map((entry, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${esc(entry.date || '-')}</td>
                        <td>${esc(entry.type || '-')}</td>
                        <td>${esc(entry.bank_name || '-')}</td>
                        <td>${esc(entry.reference || '-')}</td>
                        <td class="text-end">Rs ${parseFloat(entry.amount || 0).toFixed(2)}</td>
                    </tr>
                `).join('');
                itemTxnHistoryModalBody.innerHTML = `
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>#</th><th>Date</th><th>Type</th><th>Bank</th><th>Reference</th><th class="text-end">Amount</th></tr></thead>
                            <tbody>${entryRows}</tbody>
                        </table>
                    </div>
                `;
                return;
            }
            itemTxnHistoryModalBody.innerHTML = renderTxnHistoryPlaceholder('No edits have been made to this transaction.');
        })
        .catch(() => {
            itemTxnHistoryModalBody.innerHTML = renderTxnHistoryPlaceholder('Unable to load history right now.');
        });
}
async function cancelTxn(url, label) {
    if (!url) return showToast('Cancel is not available for this transaction.');
    if (!confirm(`Cancel ${label || 'this invoice'}?`)) return;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) return showToast('CSRF token missing.');
    const response = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    }).catch(() => null);
    if (!response) return showToast('Network error while cancelling transaction.');
    if (response.ok) {
        showToast('Invoice cancelled successfully.');
        window.location.reload();
        return;
    }
    showToast('Unable to cancel this invoice.');
}
function openPrintView(url) {
    const existingFrame = document.getElementById('txn-print-frame');
    if (existingFrame) existingFrame.remove();
    const frame = document.createElement('iframe');
    frame.id = 'txn-print-frame';
    frame.style.position = 'fixed';
    frame.style.right = '0';
    frame.style.bottom = '0';
    frame.style.width = '0';
    frame.style.height = '0';
    frame.style.border = '0';
    frame.style.visibility = 'hidden';
    frame.onload = () => {
        const doPrint = () => {
            try {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            } catch (error) {
                showToast('Unable to open print dialog.');
            }
        };
        setTimeout(doPrint, 400);
        setTimeout(doPrint, 1200);
    };
    frame.src = url;
    document.body.appendChild(frame);
}
function openTxnAction(idx, ti, action) {
    const txn = getTxn(idx, ti);
    if (!txn) {
        showToast('Transaction not found.');
        return;
    }
    document.querySelectorAll('.il-row-menu.open').forEach(menu => menu.classList.remove('open'));
    const links = getTxnActionLinks(txn);
    const url = links[action];
    if (!url) {
        showToast('This action is not available for this transaction.');
        return;
    }
    if (action === 'cancel') {
        cancelTxn(url, txn.invoice || txn.id);
        return;
    }
    if (action === 'payment_history') {
        openTxnHistoryModal(url, `Payment History - ${txn.invoice || txn.id}`, 'payment');
        return;
    }
    if (action === 'history') {
        openTxnHistoryModal(url, `View History - ${txn.invoice || txn.id}`, 'history');
        return;
    }
    if (action === 'edit' || action === 'duplicate' || action === 'convert_return') {
        window.location.href = url;
        return;
    }
    if (action === 'pdf') {
        openUrlInNewTab(url);
        return;
    }
    if (action === 'preview') {
        openItemTxnPreview(url, `Preview - ${txn.invoice || txn.id}`, {
            pdfUrl: links.pdf,
            printUrl: links.print
        });
        return;
    }
    if (action === 'print') {
        openUrlInNewTab(url);
        return;
    }
    openUrlInNewTab(url);
}
function viewHistory(idx, ti) {
    openTxnAction(idx, ti, 'history');
}
function deleteTxn(idx, ti) {
    const txn = getTxn(idx, ti);
    if (!txn) { showToast('Transaction not found.'); return; }
    if (!confirm('Delete this transaction?')) return;
    const links = getTxnActionLinks(txn);
    if (!links.delete) {
        showToast('Delete is not available for this transaction.');
        return;
    }
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) { showToast('CSRF token missing.'); return; }
    const formData = new FormData();
    formData.append('_method', 'DELETE');
    fetch(links.delete, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: formData
    })
    .then(async response => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || 'Failed to delete transaction.');
        transactions[idx].splice(ti, 1);
        selectItem(idx);
        showToast(data.message || 'Transaction deleted');
    })
    .catch(error => showToast(error.message || 'Failed to delete transaction.'));
}
/* â”€â”€ Column filters â”€â”€ */
function toggleColFilter(e, id) {
    e.stopPropagation();
    const rect = e.currentTarget.getBoundingClientRect();
    const dd   = document.getElementById(id);
    const isOpen = dd.classList.contains('open');
    closeAllColFilters();
    if (!isOpen) {
        dd.style.top = (rect.bottom + 6) + 'px'; dd.style.left = rect.left + 'px'; dd.style.right = 'auto';
        dd.classList.add('open');
        const ddRect = dd.getBoundingClientRect();
        if (ddRect.right > window.innerWidth - 8) dd.style.left = (window.innerWidth - ddRect.width - 8) + 'px';
    }
}
function closeAllColFilters() { document.querySelectorAll('.col-filter-dd.open').forEach(d => d.classList.remove('open')); }
function clearColFilter(id) {
    const dd = document.getElementById(id);
    dd.querySelectorAll('input[type=checkbox]').forEach(c => c.checked = false);
    dd.querySelectorAll('input[type=text], input[type=number], input[type=date]').forEach(i => i.value = '');
    dd.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    applyColFilters();
}
function applyColFilters() { if (selectedIdx !== null) renderTxns(selectedIdx); }

/* â”€â”€ Export â”€â”€ */
function exportToExcel() {
    if (selectedIdx === null) { showToast('Please select a service first.'); return; }
    const item = allItems[selectedIdx];
    const rows = getCurrentTxnRows(selectedIdx);
    const txns = rows.map(({ txn }) => txn);
    const stockQty = typeof getTotalQty === 'function' ? getTotalQty(selectedIdx) : (item.stock_qty ?? item.opening_qty ?? 0);
    const stockValue = parseFloat(item.purchase_price || 0) * parseFloat(stockQty || 0);
    const wb = XLSX.utils.book_new();
    const summaryData = [
        ['Service Summary'], [],
        ['Service Name', item.name || '—'],
        ['Item Type', item.type || 'service'],
        ['Category', item.category || item.category_name || '—'],
        ['Item Code', item.item_code || '—'],
        ['Unit', item.unit || '—'],
        ['Secondary Unit', item.secondary_unit || '—'],
        ['Conversion Rate', item.unit_conversion_rate || '—'],
        ['Sale Price', item.sale_price !== undefined && item.sale_price !== null ? 'Rs ' + parseFloat(item.sale_price || 0).toFixed(2) : '—'],
        ['Purchase Price', item.purchase_price !== undefined && item.purchase_price !== null ? 'Rs ' + parseFloat(item.purchase_price || 0).toFixed(2) : '—'],
        ['Opening Qty', item.opening_qty ?? '—'],
        ['Stock Quantity', stockQty],
        ['Stock Value', 'Rs ' + stockValue.toFixed(2)],
        ['Location', item.location || '—'],
        ['Description', item.description || '—'],
        ['Status', item.is_active === false || item.is_active === 0 ? 'Inactive' : 'Active'],
        ['Exported On', new Date().toLocaleDateString('en-GB')],
    ];
    const txnHeader = ['#', 'Date', 'Invoice/Ref.', 'Type', 'Party/Name', 'Broker', 'Quantity', 'Unit', 'Net W', 'Amount (Rs)', 'Price/Unit (Rs)', 'Status'];
    const txnRows = txns.length ? txns.map((t, i) => [
        i + 1,
        t.date || '—',
        t.invoice || '—',
        t.type || '—',
        t.name || t.details || '—',
        t.broker || '—',
        t.qty ?? '—',
        t.unit || '—',
        t.net_w !== undefined && t.net_w !== null ? parseFloat(t.net_w || 0).toFixed(2) : '—',
        t.amount !== undefined && t.amount !== null ? parseFloat(t.amount || 0).toFixed(2) : '—',
        t.price !== undefined && t.price !== null ? parseFloat(t.price || 0).toFixed(2) : '—',
        t.status || '—'
    ]) : [['No transactions recorded']];
    const wsSummary = XLSX.utils.aoa_to_sheet(summaryData);
    wsSummary['!cols'] = [{ wch: 22 }, { wch: 34 }];
    XLSX.utils.book_append_sheet(wb, wsSummary, 'Summary');
    const wsTxn = XLSX.utils.aoa_to_sheet([txnHeader, ...txnRows]);
    wsTxn['!cols'] = [{wch:4},{wch:14},{wch:16},{wch:18},{wch:26},{wch:24},{wch:10},{wch:10},{wch:12},{wch:16},{wch:16},{wch:12}];
    XLSX.utils.book_append_sheet(wb, wsTxn, 'Transactions');
    const dateStr = new Date().toISOString().slice(0,10);
    const safeName = (item.name||'service').replace(/[^a-zA-Z0-9_\-]/g,'_');
    XLSX.writeFile(wb, `${safeName}_${dateStr}.xlsx`);
    showToast(`Downloaded: ${safeName}_${dateStr}.xlsx`);
}

function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
</script>
@endpush
