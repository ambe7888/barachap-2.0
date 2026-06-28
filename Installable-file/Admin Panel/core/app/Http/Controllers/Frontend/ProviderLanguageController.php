<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Language;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FlashMsg;
use App\Models\User;

class ProviderLanguageController extends Controller
{

    public function makeDefault(Request $request)
    {

        if($request->isMethod('post')){
            $request->validate([
                'selected_lang'=>'required'

            ]);
            $provider_id=Auth::user()->id;
            $provider=User::where("id",$provider_id)->first();
            $selected_lang=$request->selected_lang;
            $provider->selected_lang=$selected_lang;
            $provider->save();
            return back()->with(FlashMsg::item_new(__('Language Change Successfully')));

        }    
        $all_lang = Language::all();
        $provider_id=Auth::user()->id;
        $provider=User::where("id",$provider_id)->first();
        return view('frontend.frontend.provider.pages.languages.change',compact('all_lang','provider'));
    }
}
