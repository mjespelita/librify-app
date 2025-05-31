<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Itemlogs};
use App\Http\Requests\StoreItemlogsRequest;
use App\Http\Requests\UpdateItemlogsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemlogsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('itemlogs.itemlogs', [
            'itemlogs' => Itemlogs::where('isTrash', '0')->orderBy('id', 'desc')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('itemlogs.trash-itemlogs', [
            'itemlogs' => Itemlogs::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($itemlogsId)
    {
        /* Log ************************************************** */
        // $oldName = Itemlogs::where('id', $itemlogsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Itemlogs "'.$oldName.'".']);
        /******************************************************** */

        Itemlogs::where('id', $itemlogsId)->update(['isTrash' => '0']);

        return redirect('/itemlogs');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('itemlogs.create-itemlogs');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemlogsRequest $request)
    {
        Itemlogs::create(['items_id' => $request->items_id,'reason' => $request->reason,'quantity' => $request->quantity]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new Itemlogs '.'"'.$request->name.'"']);
        /******************************************************** */

        return back()->with('success', 'Itemlogs Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Itemlogs $itemlogs, $itemlogsId)
    {
        return view('itemlogs.show-itemlogs', [
            'item' => Itemlogs::where('id', $itemlogsId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Itemlogs $itemlogs, $itemlogsId)
    {
        return view('itemlogs.edit-itemlogs', [
            'item' => Itemlogs::where('id', $itemlogsId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemlogsRequest $request, Itemlogs $itemlogs, $itemlogsId)
    {
        /* Log ************************************************** */
        // $oldName = Itemlogs::where('id', $itemlogsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Itemlogs from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        Itemlogs::where('id', $itemlogsId)->update(['items_id' => $request->items_id,'reason' => $request->reason,'quantity' => $request->quantity]);

        return back()->with('success', 'Itemlogs Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Itemlogs $itemlogs, $itemlogsId)
    {
        return view('itemlogs.delete-itemlogs', [
            'item' => Itemlogs::where('id', $itemlogsId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Itemlogs $itemlogs, $itemlogsId)
    {

        /* Log ************************************************** */
        // $oldName = Itemlogs::where('id', $itemlogsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Itemlogs "'.$oldName.'".']);
        /******************************************************** */

        Itemlogs::where('id', $itemlogsId)->update(['isTrash' => '1']);

        return redirect('/itemlogs');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Itemlogs::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Itemlogs "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Itemlogs::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Itemlogs::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Itemlogs "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Itemlogs::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Itemlogs::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Itemlogs "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Itemlogs::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}