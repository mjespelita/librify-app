<?php

namespace App\Http\Controllers;

use App\Models\Damages;
use App\Models\Onsites;
use App\Models\Sites;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RandomController extends Controller
{
    public function myOnsiteItems()
    {
        return view('technicians.my-onsite-items', [
            'onsites' => Onsites::where('isTrash', '0')
                ->paginate(10),
                // ->where('technicians_id', Auth::user()->id)->paginate(10),
            'technician' => User::find(Auth::user()->id)
        ]);
    }

    public function viewMyOnsiteItemsOnSite($siteId)
    {
        return view('technicians.view-my-onsite-items-on-site', [
            'onsites' => Onsites::where('isTrash', '0')
                ->where('sites_id', $siteId)
                ->paginate(10),
                // ->where('technicians_id', Auth::user()->id)->paginate(10),
            'site' => Sites::find($siteId)
        ]);
    }

    public function myDamagedItems()
    {
        return view('technicians.my-damaged-items', [
            'damages' => Damages::where('isTrash', '0')
                ->paginate(10),
                // ->where('technicians_id', Auth::user()->id)->paginate(10),
            'technician' => User::find(Auth::user()->id)
        ]);
    }

    public function viewMyDamagedItemsOnSite($siteId)
    {
        return view('technicians.view-my-damaged-items-on-site', [
            'damages' => Damages::where('isTrash', '0')
                ->where('sites_id', $siteId)
                ->paginate(10),
                // ->where('technicians_id', Auth::user()->id)->paginate(10),
            'site' => Sites::find($siteId)
        ]);
    }

    public function mySites()
    {
        return view('technicians.my-sites', [
            'sites' => Sites::where('users_id', Auth::user()->id)->paginate(12)
        ]);
    }
}
