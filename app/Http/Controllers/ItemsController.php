<?php

namespace App\Http\Controllers;

use App\Models\{Itemlogs, Logs, Items, Types};
use App\Http\Requests\StoreItemsRequest;
use App\Http\Requests\UpdateItemsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('items.items', [
            'items' => Items::where('isTrash', '0')->orderBy('id', 'desc')->paginate(10)
        ]);
    }

    public function addItemQuantity($itemId)
    {
        return view('items.add-item-quantity', [
            'item' => Items::where('id', $itemId)->first()
        ]);
    }

    public function addItemQuantityProcess(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required',
            'reason' => 'required',
            'serial_numbers' => '',
        ]);

        // if items has serial numbers

        if ($request->serial_numbers != "") {
            $currentSerialNumbers = Items::where('id', $itemId)->value('serial_numbers');
            Items::where('id', $itemId)->update([
                'serial_numbers' => $currentSerialNumbers.', '.$request->serial_numbers
            ]);
        }

        // increase or descrease quantity

        $currentQuantity = Items::where('id', $itemId)->value('quantity');

        Items::where('id', $itemId)->update([
            'quantity' => $currentQuantity + $request->quantity
        ]);

        // input to logs

        Itemlogs::create([
            'items_id' => $itemId,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Item Quantity Added Successfully!');
    }

    public function viewAddItemQuantityLogs($itemId)
    {   
        $itemLogs = Itemlogs::where('items_id', $itemId)->paginate(10);
        return view('items.view-add-item-quantity-logs', [
            'itemLogs' => $itemLogs,
            'singleItem' => Items::find($itemId)
        ]);
    }

    public function trash()
    {
        return view('items.trash-items', [
            'items' => Items::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($itemsId)
    {
        /* Log ************************************************** */
        // $oldName = Items::where('id', $itemsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored an item "'.$oldName.'".']);
        /******************************************************** */

        Items::where('id', $itemsId)->update(['isTrash' => '0']);

        return redirect('/items');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Types::all();
        return view('items.create-items', [
            'types' => $types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemsRequest $request)
    {
        // Get the last Item ID from the Items table
        $lastItemId = Items::latest('created_at')->value('itemId'); // Get the last created item ID

        // Check if there is an existing Item ID and generate the new one
        if ($lastItemId) {
            // Extract the numeric part of the last Item ID and increment it
            $lastNumber = (int) substr($lastItemId, 4); // Get the number part after "ITEM"
            $newNumber = $lastNumber + 1; // Increment by 1
        } else {
            // If no items exist, start from ITEM0001
            $newNumber = 1;
        }

        // Generate the new Item ID with leading zeros (e.g., ITEM0001, ITEM0002, etc.)
        $newItemId = 'ITEM' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Create the new item with the generated Item ID
        Items::create([
            'itemId' => $newItemId,
            'name' => $request->name,
            'model' => $request->model,
            'brand' => $request->brand,
            'types_id' => $request->types_id,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'serial_numbers' => $request->serial_numbers,
            'unit' => $request->unit,
        ]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') created a new item "' . $request->name . '"']);
        /******************************************************** */

        return back()->with('success', 'Item Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $items, $itemsId)
    {
        return view('items.show-items', [
            'item' => Items::where('id', $itemsId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Items $items, $itemsId)
    {
        return view('items.edit-items', [
            'item' => Items::where('id', $itemsId)->first(),
            'types' => Types::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemsRequest $request, Items $items, $itemsId)
    {
        /* Log ************************************************** */
        // $oldName = Items::where('id', $itemsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') updated an items from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        Items::where('id', $itemsId)->update([
            'name' => $request->name,
            'model' => $request->model,
            'brand' => $request->brand,
            'types_id' => $request->types_id,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'serial_numbers' => $request->serial_numbers,
            'unit' => $request->unit,
        ]);

        return back()->with('success', 'Items Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Items $items, $itemsId)
    {
        return view('items.delete-items', [
            'item' => Items::where('id', $itemsId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Items $items, $itemsId)
    {

        /* Log ************************************************** */
        // $oldName = Items::where('id', $itemsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted an item "'.$oldName.'".']);
        /******************************************************** */

        Items::where('id', $itemsId)->update(['isTrash' => '1']);

        return redirect('/items');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Items::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Items "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Items::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Items::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted an items "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Items::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Items::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a item "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Items::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}