<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxRate;
use App\Models\TaxGroup;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    public function storeRate(Request $request)
    {
        $request->validate([ 'name' => 'required|string|max:255', 'rate' => 'required|numeric' ]);
        $rate = TaxRate::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'rate' => $request->rate,
        ]);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'rate' => $rate]);
        }
        return redirect()->route('settings.taxes')->with('success', 'Tax rate added.');
    }

    public function storeGroup(Request $request)
    {
        $request->validate([ 'name' => 'required|string|max:255' ]);
        $group = TaxGroup::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        // attach rates if provided
        if ($request->has('rate_ids') && is_array($request->rate_ids)) {
            $group->rates()->sync($request->rate_ids);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'group' => $group->load('rates')]);
        }
        return redirect()->route('settings.taxes')->with('success', 'Tax group added.');
    }

    public function updateRate(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255', 'rate' => 'required|numeric']);
        $rate = TaxRate::where('user_id', Auth::id())->findOrFail($id);
        $rate->update(['name' => $request->name, 'rate' => $request->rate]);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'rate' => $rate]);
        }
        return redirect()->route('settings.taxes')->with('success', 'Tax rate updated.');
    }

    public function updateGroup(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $group = TaxGroup::where('user_id', Auth::id())->findOrFail($id);
        $group->update(['name' => $request->name]);
        $group->rates()->sync($request->rate_ids ?? []);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'group' => $group->load('rates')]);
        }
        return redirect()->route('settings.taxes')->with('success', 'Tax group updated.');
    }

    // destroy endpoints (support form requests)
    public function destroyRate($id)
    {
        $rate = TaxRate::where('user_id', Auth::id())->findOrFail($id);
        $rate->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('settings.taxes')->with('success', 'Tax rate deleted.');
    }

    public function destroyGroup($id)
    {
        $group = TaxGroup::where('user_id', Auth::id())->findOrFail($id);
        $group->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('settings.taxes')->with('success', 'Tax group deleted.');
    }
}
