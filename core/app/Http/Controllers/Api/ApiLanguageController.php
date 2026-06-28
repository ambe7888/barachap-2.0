<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backend\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class ApiLanguageController extends Controller
{
    public function languageInfo()
    {

        $languages = Language::select('id', 'name', 'slug', 'direction', 'status', 'default')
            ->where('status', 'publish')
            ->get();

        if (!is_null($languages)) {
            return response()->json([
                'language' => $languages,
            ]);
        }

        return response()->json([
            'language' => [
                "slug" => "en_GB",
                "direction" => "ltr"
            ],
        ]);
    }

    public function translateString(Request $request){

        $translateable_array = json_decode($request->get('strings'),true);
        $translated_array = [];

        if($request->has('strings')){
            foreach($translateable_array as $key => $string){
                $translated_array[$key] = __($key);
            }
        }

        return response()->json([
            'strings'=> $translated_array
        ]);
    }

    public function multiTranslateString(Request $request){

        $request->validate([
            'strings' => 'required',
        ]);

        // Get the translatable strings from the request
        $translateable_array = json_decode($request->get('strings'), true);
        $translated_array = [];

        $slug = $request->get('slug');
        $current_lang = Language::where('slug', $slug)->first();

        if (!$current_lang){
            return response()->json([
                'strings' => __('Language not found.'),
            ]);
        }

        if (session()->has('lang')) {
            if (!empty($current_lang)){
                Carbon::setLocale($current_lang->slug);
                app()->setLocale($current_lang->slug);
            }else {
                session()->forget('lang');
            }

        }else{
            Carbon::setLocale($current_lang->slug);
            app()->setLocale($current_lang->slug);
        }

        // Translate each string in the array
        if ($request->has('strings')) {
            foreach ($translateable_array as $key => $string) {
                // Translate using the translation key or string
                $translated_array[$key] = __($string);
            }
        }


        return response()->json([
            'strings' => $translated_array
        ]);

    }

}
