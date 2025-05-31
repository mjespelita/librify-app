<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Damages, Deployedtechnicians, Items, Sites, User};
use App\Http\Requests\StoreDamagesRequest;
use App\Http\Requests\UpdateDamagesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DamagesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('damages.damages', [
            'damages' => Damages::where('isTrash', '0')->orderBy('id', 'desc')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('damages.trash-damages', [
            'damages' => Damages::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function viewTechnicianDamageItems($userId)
    {
        return view('damages.view-technician-damage-items', [
            'damages' => Damages::where('isTrash', '0')
                ->where('technicians_id', $userId)->paginate(10),
            'technician' => User::find($userId)
        ]);
    }

    public function restore($damagesId)
    {
        /* Log ************************************************** */
        // $oldName = Damages::where('id', $damagesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Damages "'.$oldName.'".']);
        /******************************************************** */

        Damages::where('id', $damagesId)->update(['isTrash' => '0']);

        return redirect('/damages');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('damages.create-damages', [
            'items' => Items::orderBy('name', 'asc')->get(),
            'technicians' => User::whereNot('role', 'admin')->get(),
            'sites' => Sites::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDamagesRequest $request)
    {
        // Damages::create(['items_id' => $request->items_id,'items_types_id' => $request->items_types_id,'technicians_id' => $request->technicians_id,'sites_id' => $request->sites_id,'quantity' => $request->quantity]);

        
        // get the total quantty of item

        $itemTotalQuantity = Items::where('id', $request->items_id)->value('quantity');
        $damageItemQuantity = Damages::where('items_id', $request->items_id)->sum('quantity');
        $remainingQuantity = $itemTotalQuantity - $damageItemQuantity;

        if ($request->quantity > $remainingQuantity) {
            return back()->with('error', 'Quantity must not exceed from warehouse quantity!');
        }

        $damageItem = Damages::create([
            'items_id' => $request->items_id,
            'items_types_id' => Items::where('id', $request->items_id)->value('types_id'),
            'technicians_id' => $request->technicians_id,
            'sites_id' => $request->sites_id,
            'quantity' => $request->quantity,
            'serial_numbers' => $request->serial_numbers,
            'updated_by' => Auth::user()->id
        ]);

        // check if already deployed technicians

        Deployedtechnicians::firstOrCreate(
            [
                'sites_id' => $request->sites_id,
                'technicians_id' => $request->technicians_id,
            ]
        );

        // increase the count

        /* Log ************************************************** */
        Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') created a new damage item: '
        .$damageItem->items->name.', '
        .$damageItem->types->name.', '
        .$damageItem->technicians->name.' to '
        .$damageItem->sites->name.', '
        .$damageItem->quantity.' '
        .$damageItem->items->unit.'(s). '
        ]);
        /******************************************************** */

        return back()->with('success', 'Damages Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Damages $damages, $damagesId)
    {
        return view('damages.show-damages', [
            'item' => Damages::where('id', $damagesId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Damages $damages, $damagesId)
    {
        return view('damages.edit-damages', [
            'item' => Damages::where('id', $damagesId)->first(),
            'items' => Items::orderBy('name', 'asc')->get(),
            'technicians' => User::whereNot('role', 'admin')->get(),
            'sites' => Sites::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDamagesRequest $request, Damages $damages, $damagesId)
    {
        /* Log ************************************************** */
        // $oldName = Damages::where('id', $damagesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated an on site item from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        // First, update the damage record
        $damage = Damages::where('id', $damagesId)->update([
            'items_id' => $request->items_id,
            'items_types_id' => Items::where('id', $request->items_id)->value('types_id'),
            'technicians_id' => $request->technicians_id,
            'quantity' => $request->quantity,
            'sites_id' => $request->sites_id,
            'serial_numbers' => $request->serial_numbers,
            'updated_by' => Auth::user()->id
        ]);

        // check if already deployed technicians

        Deployedtechnicians::firstOrCreate(
            [
                'sites_id' => $request->sites_id,
                'technicians_id' => $request->technicians_id,
            ]
        );

        return back()->with('success', 'Damages Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Damages $damages, $damagesId)
    {
        return view('damages.delete-damages', [
            'item' => Damages::where('id', $damagesId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Damages $damages, $damagesId)
    {

        /* Log ************************************************** */
        // $oldName = Damages::where('id', $damagesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Damages "'.$oldName.'".']);
        /******************************************************** */

        Damages::where('id', $damagesId)->update(['isTrash' => '1']);

        if (Auth::user()->role === 'technician') {
            return redirect('/my-damaged-items/'.Auth::user()->id);
        } else {
            return redirect('/damages');
        }
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Damages::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Damages "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Damages::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Damages::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Damages "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Damages::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Damages::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Damages "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Damages::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}