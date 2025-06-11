<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Sites};
use App\Http\Requests\StoreSitesRequest;
use App\Http\Requests\UpdateSitesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SitesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sites.sites', [
            'sites' => Sites::where('isTrash', '0')->orderBy('id', 'desc')->paginate(12)
        ]);
    }

    public function trash()
    {
        return view('sites.trash-sites', [
            'sites' => Sites::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($sitesId)
    {
        /* Log ************************************************** */
        // $oldName = Sites::where('id', $sitesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a sites "'.$oldName.'".']);
        /******************************************************** */

        Sites::where('id', $sitesId)->update(['isTrash' => '0']);

        return redirect('/sites');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sites.create-sites');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSitesRequest $request)
    {
        Sites::create([
            'name' => $request->name,
            'phonenumber' => $request->phonenumber,
            'google_map_link' => $request->google_map_link,
            'users_id' => Auth::user()->id,
        ]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new sites '.'"'.$request->name.'"']);
        /******************************************************** */

        return back()->with('success', 'Sites Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sites $sites, $sitesId)
    {
        return view('sites.show-sites', [
            'item' => Sites::where('id', $sitesId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sites $sites, $sitesId)
    {
        return view('sites.edit-sites', [
            'item' => Sites::where('id', $sitesId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSitesRequest $request, Sites $sites, $sitesId)
    {
        /* Log ************************************************** */
        // $oldName = Sites::where('id', $sitesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a sites from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        $site = Sites::findOrFail($sitesId);

        $site->name = $request->name;
        $site->phonenumber = $request->phonenumber;
        $site->google_map_link = $request->google_map_link;

        $site->save();

        return back()->with('success', 'Sites Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Sites $sites, $sitesId)
    {
        return view('sites.delete-sites', [
            'item' => Sites::where('id', $sitesId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sites $sites, $sitesId)
    {

        /* Log ************************************************** */
        // $oldName = Sites::where('id', $sitesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a site "'.$oldName.'".']);
        /******************************************************** */

        Sites::where('id', $sitesId)->update(['isTrash' => '1']);

        return redirect('/sites');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Sites::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a sites "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Sites::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Sites::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a sites "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Sites::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Sites::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Sites "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Sites::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}
