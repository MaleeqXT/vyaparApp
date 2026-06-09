{{-- EXPENSE FORM — fixed overlay --}}
<div id="expenseFormPage" style="display:none;">
  <div style="display:flex; align-items:center; background:#fff; border-bottom:1px solid #e0e0e0; flex-shrink:0;">
    <div class="form-tabs-bar" id="formTabsBar" style="flex:1; border-bottom:none;"></div>
    <button onclick="tryCloseEntireForm()" style="background:none; border:none; cursor:pointer; color:#555; font-size:20px; padding:0 16px; line-height:1; flex-shrink:0; margin-left:auto;" title="Close">&#x2715;</button>
  </div>

  <div class="form-body">
    <div style="display:flex; align-items:center; gap:18px; margin-bottom:18px;">
      <div class="form-title" style="margin:0;">Expense</div>
      <label id="expenseTaxSwitchWrap" style="display:none; align-items:center; gap:10px; margin:0; font-size:13px; font-weight:700; color:#2563eb;">
        <span>TAX</span>
        <input type="checkbox" id="expenseTaxSwitch" onchange="toggleExpenseTax(this.checked)" style="width:18px; height:18px; accent-color:#2563eb;">
      </label>
    </div>

    <div class="form-top-row" style="display:grid; grid-template-columns:minmax(0, 1fr) 300px; gap:22px; align-items:start;">
      <div style="display:flex; flex-direction:column; gap:12px; min-width:0;">
        <div id="expensePartyWrap" style="display:none;">
          <div class="expense-party-picker" style="position:relative;">
            <label style="display:block; font-size:12px; font-weight:600; color:#1a1f36; margin-bottom:4px;">Party*</label>
            <input type="text" id="expensePartySearch" placeholder="Search party..." autocomplete="off"
              style="width:100%; border:1px solid #cbd5e1; border-radius:8px; min-height:40px; padding:8px 12px; font-size:13px; outline:none; background:#fff;"
              oninput="filterExpensePartyDropdown()" onfocus="openExpensePartyDropdown()" onkeydown="if(event.key==='ArrowDown'){ event.preventDefault(); openExpensePartyDropdown(); }">
            <input type="hidden" id="expensePartyId">
            <div id="expensePartyBalance" style="margin-top:4px; font-size:11px; font-weight:700; color:#14b8a6;"></div>
            <div id="expensePartyMenu" style="display:none; position:absolute; left:0; right:0; top:calc(100% + 4px); background:#fff; border:1px solid #d7e0ea; border-radius:8px; box-shadow:0 10px 30px rgba(15,23,42,.12); z-index:300; max-height:280px; overflow:auto;">
              <div style="padding:10px 12px; font-size:12px; font-weight:700; color:#64748b; border-bottom:1px solid #eef2f7;">Party Name / Balance</div>
              <div id="expensePartyOptions"></div>
            </div>
          </div>
        </div>

        <div class="form-cat-wrap" id="formCatWrap">
          <div class="form-cat-select" id="formCatSelectBtn" onclick="toggleCatDropdown(event)">
            <span class="form-cat-label">Expense Category*</span>
            <span id="formCatLabel"></span>
            <i class="bi bi-chevron-down" style="font-size:11px;color:#555;"></i>
          </div>
          <div class="form-cat-dropdown" id="formCatDropdown">
            <div class="cat-dd-add-row" onclick="openAddCatModal()">
              <i class="bi bi-plus-circle-fill text-primary"></i> Add Expense Category
            </div>
            <div id="formCatOptions"></div>
          </div>
        </div>
      </div>

      <div class="form-date-wrap" id="formDateWrap">
        <div class="form-exp-no-row">
          <span class="form-exp-no-label">Expense No</span>
          <input type="text" class="form-exp-no-input" id="formExpNoInput" placeholder="">
        </div>
        <div class="form-date-row">
          <span>Date</span>
          <span class="form-date-val" id="formDateVal"></span>
          <span class="form-date-icon" onclick="toggleCalendar(event)"><i class="bi bi-calendar3"></i></span>
        </div>
        <div class="calendar-popup" id="calendarPopup">
          <div class="cal-header">
            <button class="cal-nav" onclick="calNav(-1)">&#9664;</button>
            <span id="calMonthLabel"></span>
            <button class="cal-nav" onclick="calNav(1)">&#9654;</button>
          </div>
          <div class="cal-grid" id="calGrid"></div>
        </div>
      </div>
    </div>

    <div class="form-items-wrap">
      <table class="form-items-table">
        <thead>
          <tr>
            <th class="col-hash">#</th>
            <th class="col-item">ITEM</th>
            <th class="col-qty">QTY</th>
            <th class="col-price">PRICE/UNIT</th>
            <th class="col-tax d-none" id="expenseTaxHead">TAX</th>
            <th class="col-amount">AMOUNT</th>
          </tr>
        </thead>
        <tbody id="formItemsBody"></tbody>
      </table>
    </div>

    <div class="items-footer-bar">
      <button class="btn-add-row" onclick="addItemRow()">ADD ROW</button>
      <div class="items-total-label">
        TOTAL &nbsp;&nbsp; <span id="formQtyTotal">0</span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="formAmtTotal">0</span>
      </div>
    </div>

    <div id="expenseAdditionalChargesSection" style="display:none; margin-top:14px;"></div>
    <div id="expenseTransportationSection" style="display:none; margin-top:14px;"></div>

    <div class="payment-section">
      <div id="paymentCard"></div>
      <div class="total-block">
        <div class="round-off-wrap">
          <input type="checkbox" id="roundOffChk" onchange="calcTotals()">
          <label for="roundOffChk" style="font-size:13px;cursor:pointer;">Round Off</label>
          <input type="text" class="round-val" id="roundOffVal" value="0" readonly>
        </div>
        <div class="total-field-wrap">
          <span class="total-field-label">Total</span>
          <div class="total-box" id="formTotalBox"></div>
        </div>
      </div>
    </div>

    <div class="form-extra-btns" style="display:block;">
      <button class="form-extra-btn" type="button" onclick="toggleExpenseDescription()">
        <i class="bi bi-file-earmark-text"></i> ADD DESCRIPTION
      </button>
      <div id="expenseDescriptionWrap" style="display:none; margin-top:10px;">
        <div class="floating-input-wrapper expense-floating-wrapper">
          <textarea id="expenseDescriptionInput" class="meta-control expense-description-input" rows="3" placeholder=" "></textarea>
          <label>Description</label>
        </div>
        <div class="expense-attachment-actions">
          <button type="button" class="btn-action-light action-btn add-image">
            <i class="fa-solid fa-camera"></i>
            ADD IMAGE
          </button>
          <button type="button" class="btn-action-light action-btn add-document">
            <i class="fa-solid fa-file-lines"></i>
            ADD DOCUMENT
          </button>
        </div>
        <div class="expense-attachment-preview-wrap">
          <div class="image-files-list d-flex flex-wrap gap-2 mt-2"></div>
          <div class="document-files-list list-group mt-2"></div>
        </div>
        <input type="file" class="d-none expense-image-input" accept="image/*" multiple>
        <input type="file" class="d-none expense-document-input" accept=".pdf,.doc,.docx" multiple>
      </div>
    </div>
  </div>

  <div class="form-footer">
    <div class="share-btn-group">
      <button class="btn-share-main" onclick="toggleShareDropdown()">Share</button>
      <button class="btn-share-caret" onclick="toggleShareDropdown()"><i class="bi bi-chevron-down"></i></button>
    </div>
    <button class="btn-save" id="btnSaveExpense" onclick="saveExpense()">Save</button>
    <div class="share-dropdown" id="shareDropdown">
      <div class="share-dd-item"><i class="bi bi-share"></i> Share</div>
      <div class="share-dd-item"><i class="bi bi-printer"></i> Print</div>
      <div class="share-dd-item"><i class="bi bi-plus-square"></i> Save &amp; New</div>
    </div>
  </div>
</div>
