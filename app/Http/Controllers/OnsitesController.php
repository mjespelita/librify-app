<?php

namespace App\Http\Controllers;

use App\Models\{Deployedtechnicians, Items, Logs, Onsites, Sites, Technicians, Types, User};
use App\Http\Requests\StoreOnsitesRequest;
use App\Http\Requests\UpdateOnsitesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OnsitesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('onsites.onsites', [
            'onsites' => Onsites::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('onsites.trash-onsites', [
            'onsites' => Onsites::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function viewTechnicianOnsiteItems($userId)
    {
        return view('onsites.view-technician-onsite-items', [
            'onsites' => Onsites::where('isTrash', '0')
                ->where('technicians_id', $userId)->paginate(10),
            'technician' => User::find($userId)
        ]);
    }

    public function restore($onsitesId)
    {
        /* Log ************************************************** */
        // $oldName = Onsites::where('id', $onsitesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Onsites "'.$oldName.'".']);
        /******************************************************** */

        Onsites::where('id', $onsitesId)->update(['isTrash' => '0']);

        return redirect('/onsites');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('onsites.create-onsites', [
            'items' => Items::orderBy('name', 'asc')->get(),
            'technicians' => User::whereNot('role', 'admin')->get(),
            'sites' => Sites::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOnsitesRequest $request)
    {

        // get the total quantty of item

        $itemTotalQuantity = Items::where('id', $request->items_id)->value('quantity');
        $onSiteQuantity = Onsites::where('items_id', $request->items_id)->sum('quantity');
        $remainingQuantity = $itemTotalQuantity - $onSiteQuantity;

        if ($request->quantity > $remainingQuantity) {
            return back()->with('error', 'Quantity must not exceed from warehouse quantity!');
        } else {
            $onsite = Onsites::create([
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
        }

        // increase the count

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') created a new on site item: '
        // .$onsite->items->name.', '
        // .$onsite->types->name.', '
        // .$onsite->technicians->name.' to '
        // .$onsite->sites->name.', '
        // .$onsite->quantity.' '
        // .$onsite->items->unit.'(s). '
        // ]);
        /******************************************************** */

        return back()->with('success', 'Onsites Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Onsites $onsites, $onsitesId)
    {
        return view('onsites.show-onsites', [
            'item' => Onsites::where('id', $onsitesId)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Onsites $onsites, $onsitesId)
    {
        return view('onsites.edit-onsites', [
            'item' => Onsites::where('id', $onsitesId)->first(),
            'items' => Items::orderBy('name', 'asc')->get(),
            'technicians' => User::whereNot('role', 'admin')->get(),
            'sites' => Sites::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOnsitesRequest $request, Onsites $onsites, $onsitesId)
    {
        /* Log ************************************************** */
        // $oldName = Onsites::where('id', $onsitesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated an on site item from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        // First, update the Onsite record
        $onsite = Onsites::where('id', $onsitesId)->update([
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

        return back()->with('success', 'Onsites Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Onsites $onsites, $onsitesId)
    {
        return view('onsites.delete-onsites', [
            'item' => Onsites::where('id', $onsitesId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Onsites $onsites, $onsitesId)
    {

        /* Log ************************************************** */
        // $oldName = Onsites::where('id', $onsitesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a on site item "'.$oldName.'".']);
        /******************************************************** */

        Onsites::where('id', $onsitesId)->update(['isTrash' => '1']);

        if (Auth::user()->role === 'technician') {
            return redirect('/my-onsite-items/'.Auth::user()->id);
        } else {
            return redirect('/onsites');
        }
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Onsites::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted an on site item "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Onsites::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Onsites::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Onsites "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Onsites::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Onsites::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored an on sites item "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Onsites::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}