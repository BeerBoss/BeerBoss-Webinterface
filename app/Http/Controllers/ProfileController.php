<?php

namespace App\Http\Controllers;

use App\BeerProfile;
use App\BeerProfileData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function save(Request $request)
    {
        $beerprofile = Auth::user()->BeerProfiles()->firstOrNew(['name' => $request->name]);
        $beerprofile->name = $request->name;
        Auth::user()->BeerProfiles()->save($beerprofile);
        $beerprofile->BeerProfileData()->delete();
        foreach ($request->profiles as $profile) {
            $profileData = new BeerProfileData();
            $profileData->fill($profile);
            $beerprofile->BeerProfileData()->save($profileData);
        }
    }

    public function get()
    {
        return Auth::user()->BeerProfiles()->with('BeerProfileData')->get();
    }

    public function getActive()
    {
        return Auth::user()->BeerProfiles()->with('BeerProfileData')->whereNotNull('dateStarted')->first();
    }
    public function getActiveTemperature(){
        return Auth::user()->BeerProfiles()->whereNotNull('dateStarted')->first()->getActivePart();
    }

    public function delete($id)
    {
        Auth::user()->BeerProfiles()->where('id', $id)->delete();
    }

    public function toggle($id)
    {
        foreach (Auth::user()->BeerProfiles()->get() as $profile) {
            if ($profile->id == $id)
                if ($profile->dateStarted == null)
                    $profile->toggle(true);
                else
                    $profile->toggle(false);
            else $profile->toggle(false);
        }
    }
}
