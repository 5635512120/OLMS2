<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Section;

class SectionController extends Controller
{
    public function index()
    {
        return view('section.index', ['section' => Section::all()]);
    }

    public function create()
    {
        return view('section.create');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rules());
        if($validation->passes()) {
            $section = new Section;
            $section->section = $request->section;
            $section->subject_id = $request->subject_id;
            $section->save();
            return redirect()->route('section.index');
        } else {
             return redirect()->route('section.create')->withErrors($validation)->withInput();
        }
    }

    public function show($id)
    {
        return view('section.show', ['section' => Section::find($id)]);
    }

    public function edit($id)
    {
        return view('section.edit', ['section' => Section::find($id)]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), $this->rules());
        if($validation->passes()) {
            $section = Section::find($id);
            $section->section = $request->section;
            $section->subject_id = $request->subject_id;
            $section->save();
            return redirect()->route('section.index');
        } else {
             return redirect()->route('section.edit')->withErrors($validation)->withInput();
        }
    }

    public function destroy($id)
    {
        Section::destroy($id);
        return redirect()->route('section.index');
    }

    public function rules()
    {
        return [
            'section' => 'required|digits_between:1,20',
        ];
    }
}
