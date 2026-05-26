@php
    $transactionGuardSettings = json_decode((string) \App\Models\AppSetting::getValue('sale_form_settings', '{}'), true) ?: [];
    $transactionGuardEnabled = !empty(data_get($transactionGuardSettings, 'more_transaction_features.passcode_enabled'))
        && filled(data_get($transactionGuardSettings, 'more_transaction_features.transaction_passcode_hash'));
@endphp

<div id="transactionPasscodeConfig"
     data-enabled="{{ $transactionGuardEnabled ? 1 : 0 }}"
     data-verify-url="{{ route('sale.verify-passcode') }}"
     data-csrf-token="{{ csrf_token() }}"
     hidden></div>

<div class="modal fade" id="globalTransactionPasscodeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transaction Passcode</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="small text-muted mb-3">Enter the 4-digit transaction passcode to continue.</p>
        <form autocomplete="off" onsubmit="return false;">
          <input type="text" autocomplete="username" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;opacity:0;">
          <input type="password" autocomplete="current-password" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;opacity:0;">
          <input type="password"
                 inputmode="numeric"
                 maxlength="4"
                 class="form-control"
                 id="globalTransactionPasscodeInput"
                 name="transaction_passcode_guard"
                 placeholder="••••"
                 autocomplete="one-time-code"
                 autocapitalize="off"
                 autocorrect="off"
                 spellcheck="false">
        </form>
        <div class="text-danger small mt-2 d-none" id="globalTransactionPasscodeError"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="globalTransactionPasscodeConfirm">Continue</button>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('js/transaction-passcode.js') }}"></script>
