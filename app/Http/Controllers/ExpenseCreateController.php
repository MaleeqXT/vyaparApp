<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\BankAccount;
use App\Models\ExpenseCategory;
use App\Models\ExpenseItem;
use App\Models\Expense;
use App\Models\Party;
use App\Models\TaxRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ExpenseCreateController extends Controller
{
    // ─── MAIN VIEW ───────────────────────────────────────────────
    public function createExpense(Request $request)
    {
        return $this->expense($request, true);
    }

    public function expense(Request $request, bool $startInCreate = false)
    {
        $userId = Auth::id();
        $settings = json_decode((string) AppSetting::getValue('sale_form_settings', '{}'), true) ?: [];

        $categoriesRaw = ExpenseCategory::where('user_id', $userId)
            ->with(['expenses' => function($q) {
                $q->orderBy('expense_date', 'desc');
            }])
            ->orderBy('name')
            ->get();

        $categories = $categoriesRaw->map(function($cat) {
            return [
                'id'      => $cat->id,
                'name'    => $cat->name,
                'type'    => $cat->type,
                'amount'  => $cat->expenses->sum('total_amount'),
                'entries' => $cat->expenses->map(function($e) {
                    $items = $e->items_json;
                    return [
                        'id'          => $e->id,
                        'date'        => $e->expense_date,
                        'expNo'       => $e->expense_no ?? '',
                        'reference_no'=> $e->reference_no ?? '',
                        'party_id'    => $e->party_id,
                        'party'       => $e->party ?? '',
                        'taxEnabled'  => (bool) ($e->tax_enabled ?? false),
                        'taxRateId'   => $e->tax_rate_id,
                        'taxRateName' => $e->tax_rate_name ?? '',
                        'taxRateValue'=> $e->tax_rate_value ?? 0,
                        'taxAmount'   => $e->tax_amount ?? 0,
                        'items'       => is_array($items) ? $items : [],
                        'additionalCharges' => $e->additional_charges ?? [],
                        'transportationDetails' => $e->transportation_details ?? [],
                        'description' => $e->description ?? '',
                        'bankAccountId' => $e->bank_account_id,
                        'paymentType' => $e->payment_type,
                        'amount'      => $e->total_amount,
                        'balance'     => $e->balance,
                    ];
                })->values(),
            ];
            })->values();

        $expenseItems = ExpenseItem::where('user_id', $userId)
            ->orderBy('name')
            ->get()
            ->map(fn($it) => ['id' => $it->id, 'name' => $it->name, 'price' => $it->price])
            ->values();

        $parties = Party::orderBy('name')
            ->get()
            ->map(fn($party) => [
                'id' => $party->id,
                'name' => $party->name,
                'phone' => $party->phone,
                'phone_number_2' => $party->phone_number_2,
                'ptcl_number' => $party->ptcl_number,
                'email' => $party->email,
                'city' => $party->city,
                'address' => $party->address,
                'billing_address' => $party->billing_address,
                'shipping_address' => $party->shipping_address,
                'opening_balance' => $party->opening_balance,
                'current_balance' => $party->current_balance,
                'transaction_type' => $party->transaction_type,
            ])
            ->values();

        $bankAccounts = BankAccount::active()
            ->orderBy('display_name')
            ->get()
            ->map(fn($bank) => [
                'id' => $bank->id,
                'display_name' => $bank->display_name,
                'display_with_account' => $bank->display_with_account,
                'type' => $bank->type,
                'bank_name' => $bank->bank_name,
                'account_number' => $bank->account_number,
            ])
            ->values();

        $taxRates = TaxRate::where('user_id', $userId)
            ->orderBy('name')
            ->get()
            ->map(fn($rate) => [
                'id' => $rate->id,
                'name' => $rate->name,
                'rate' => $rate->rate,
            ])
            ->values();

        $transactionSettings = [
            'tax_enabled' => (bool) data_get($settings, 'transaction_totals.tax_enabled', true),
            'additional_charges_enabled' => (bool) data_get($settings, 'additional_charges.enabled', false),
            'transportation_details_enabled' => (bool) data_get($settings, 'transportation_details.enabled', false),
            'additional_charges_items' => data_get($settings, 'additional_charges.items', []),
            'transportation_details_fields' => data_get($settings, 'transportation_details.fields', []),
        ];

        return view('dashboard.expense.expense', compact(
            'categories',
            'expenseItems',
            'parties',
            'bankAccounts',
            'taxRates',
            'transactionSettings',
            'startInCreate'
        ));
    }

    // ─── EXPENSE CATEGORY CRUD ───────────────────────────────────
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'type' => 'required|string']);
        $cat = ExpenseCategory::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'type'    => $request->type,
        ]);
        return response()->json(['success' => true, 'category' => [
            'id' => $cat->id, 'name' => $cat->name, 'type' => $cat->type, 'amount' => 0, 'entries' => []
        ]]);
    }

   public function updateCategory(Request $request, $id)
{
    $category = ExpenseCategory::where('user_id', auth()->id())
                    ->findOrFail($id);

    $category->update([
        'name' => $request->name,
        'type' => $request->type,
    ]);

    return response()->json(['success' => true, 'category' => $category]);
}

    public function destroyCategory($id)
    {
        $cat = ExpenseCategory::where('user_id', Auth::id())->findOrFail($id);
        if ($cat->expenses()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete: category has transactions.'], 422);
        }
        $cat->delete();
        return response()->json(['success' => true]);
    }

    // ─── EXPENSE ITEM CRUD ───────────────────────────────────────
    public function storeItem(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $itemData = [
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'price'   => $request->price ?? 0,
        ];

        if (Schema::hasColumn('expense_items', 'tax_included')) {
            $itemData['tax_included'] = $request->tax_included ?? null;
        }
        if (Schema::hasColumn('expense_items', 'tax_rate_id')) {
            $itemData['tax_rate_id'] = $request->tax_rate_id ?? null;
        }
        if (Schema::hasColumn('expense_items', 'tax_rate_name')) {
            $itemData['tax_rate_name'] = $request->tax_rate_name ?? null;
        }
        if (Schema::hasColumn('expense_items', 'tax_rate_value')) {
            $itemData['tax_rate_value'] = $request->tax_rate_value ?? null;
        }

        $item = ExpenseItem::create($itemData);
        return response()->json(['success' => true, 'item' => [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'tax_included' => $item->tax_included ?? null,
            'tax_rate_id' => $item->tax_rate_id ?? null,
            'tax_rate_name' => $item->tax_rate_name ?? null,
            'tax_rate_value' => $item->tax_rate_value ?? null,
        ]]);
    }

    public function updateItem(Request $request, $id)
    {
        $item = ExpenseItem::where('user_id', Auth::id())->findOrFail($id);
        $updateData = [
            'name'  => $request->name ?? $item->name,
            'price' => $request->price ?? $item->price,
        ];

        if (Schema::hasColumn('expense_items', 'tax_included')) {
            $updateData['tax_included'] = $request->tax_included ?? $item->tax_included ?? null;
        }
        if (Schema::hasColumn('expense_items', 'tax_rate_id')) {
            $updateData['tax_rate_id'] = $request->tax_rate_id ?? $item->tax_rate_id ?? null;
        }
        if (Schema::hasColumn('expense_items', 'tax_rate_name')) {
            $updateData['tax_rate_name'] = $request->tax_rate_name ?? $item->tax_rate_name ?? null;
        }
        if (Schema::hasColumn('expense_items', 'tax_rate_value')) {
            $updateData['tax_rate_value'] = $request->tax_rate_value ?? $item->tax_rate_value ?? null;
        }

        $item->update($updateData);
        return response()->json(['success' => true, 'item' => [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'tax_included' => $item->tax_included ?? null,
            'tax_rate_id' => $item->tax_rate_id ?? null,
            'tax_rate_name' => $item->tax_rate_name ?? null,
            'tax_rate_value' => $item->tax_rate_value ?? null,
        ]]);
    }

    public function destroyItem($id)
    {
        $item = ExpenseItem::where('user_id', Auth::id())->findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    // ─── EXPENSE CRUD ─────────────────────────────────────────────
    public function storeExpense(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_date'        => 'required|date',
            'total_amount'        => 'required|numeric|min:0',
            'payment_type'        => 'nullable|string',
            'party_id'            => 'nullable|exists:parties,id',
            'bank_account_id'     => 'nullable|exists:bank_accounts,id',
            'tax_rate_id'         => 'nullable|exists:tax_rates,id',
            'tax_amount'          => 'nullable|numeric|min:0',
            'items_json'          => 'nullable|array',
            'additional_charges'  => 'nullable|array',
            'transportation_details' => 'nullable|array',
            'description'         => 'nullable|string',
        ]);

        $partyName = $request->input('party', '');
        if (!$partyName && $request->filled('party_id')) {
            $partyName = Party::whereKey($request->party_id)->value('name') ?? '';
        }

        $paymentType = $request->input('payment_type');
        if ($request->filled('bank_account_id') && (!$paymentType || strtolower((string) $paymentType) === 'bank')) {
            $paymentType = 'Bank';
        } elseif (!$paymentType) {
            $paymentType = 'Cash';
        }

        $expense = Expense::create([
            'user_id'             => Auth::id(),
            'expense_category_id' => $request->expense_category_id,
            'expense_no'          => $request->expense_no,
            'expense_date'        => $request->expense_date,
            'party_id'            => $request->party_id,
            'party'               => $partyName,
            'tax_enabled'         => (bool) $request->boolean('tax_enabled'),
            'tax_rate_id'         => $request->tax_rate_id,
            'tax_rate_name'       => $request->tax_rate_name,
            'tax_rate_value'      => $request->tax_rate_value,
            'tax_amount'          => $request->tax_amount ?? 0,
            'items_json'          => $request->items_json ?? [],
            'additional_charges'  => $request->additional_charges ?? [],
            'transportation_details' => $request->transportation_details ?? [],
            'description'         => $request->description,
            'bank_account_id'     => $request->bank_account_id,
            'total_amount'        => $request->total_amount,
            'payment_type'        => $paymentType,
            'reference_no'        => $request->reference_no,
            'balance'             => 0,
        ]);

        return response()->json(['success' => true, 'expense' => [
            'id'          => $expense->id,
            'date'        => $expense->expense_date,
              'expNo'       => $expense->expense_no ?? '',
              'reference_no'=> $expense->reference_no ?? '',
              'party'       => $expense->party ?? '',
            'party_id'    => $expense->party_id,
            'taxEnabled'  => (bool) $expense->tax_enabled,
            'taxRateId'   => $expense->tax_rate_id,
            'taxRateName' => $expense->tax_rate_name,
            'taxRateValue'=> $expense->tax_rate_value,
            'taxAmount'   => $expense->tax_amount,
            'items'       => $expense->items_json ?? [],
            'additionalCharges' => $expense->additional_charges ?? [],
            'transportationDetails' => $expense->transportation_details ?? [],
            'description' => $expense->description ?? '',
            'bankAccountId' => $expense->bank_account_id,
            'paymentType' => $expense->payment_type,
            'amount'      => $expense->total_amount,
            'balance'     => $expense->balance,
        ]]);
    }

    public function destroyExpense($id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        $expense->delete();
        return response()->json(['success' => true]);
    }
}
