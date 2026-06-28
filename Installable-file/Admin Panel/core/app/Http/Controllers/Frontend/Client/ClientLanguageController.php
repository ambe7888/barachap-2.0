<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Language;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FlashMsg;
use App\Models\User;

class ClientLanguageController extends Controller
{
    public function makeDefault(Request $request)
    {

        if($request->isMethod('post')){
            $request->validate([
                'selected_lang'=>'required'

            ]);
            $client_id=Auth::user()->id;
            $client=User::where("id",$client_id)->first();
            $selected_lang=$request->selected_lang;
            $client->selected_lang=$selected_lang;
            $client->save();
            return back()->with(FlashMsg::item_new(__('Language Change Successfully')));

        }
        $all_lang = Language::all();
        $client_id=Auth::user()->id;
        $client=User::where("id",$client_id)->first();
        return view('frontend.frontend.client.pages.Languages.change',compact('all_lang','client'));
    }
}
