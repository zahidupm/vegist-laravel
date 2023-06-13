<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(){
        $sliders = Slider::latest()->paginate(10);

        return view('backend.slider.index', compact('sliders'));
    }

    //create a new slider
    public function create(Request $request){

        if($request->isMethod('POST')){
            $request->validate([
                'title'      => 'required|string|max:255',
                'sub_title'  => 'nullable|string|max:255',
                'btn_text'   => 'nullable|string|max:255',
                'btn_link'   => 'nullable|string|max:255',
                'alignment'  => 'required|in:left,right,center',
                'background' => 'required',
            ]);

            $background = ' ';
            if(!empty($request->file('background'))){
                $background = $request->file('background')->getClientOriginalName();
                $background = str_replace(' ', '--', $background);
                $background = time() . '-' . $background;
                // store the background
                $request->file('background')->storeAs('/', $background);
            }

            Slider::create([
                'title'      => $request->title,
                'sub_title'  => $request->sub_title,
                'btn_link'   => $request->btn_link,
                'btn_text'   => $request->btn_text,
                'alignment'  => $request->alignment,
                'background' => $background,
            ]);

            return redirect()->back()->with('success', 'Slider updated successfully');
        }
        return view('backend.slider.create');
    }


}