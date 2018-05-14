<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;
use App\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);
        return view('user.index', ['users' => $users]);
    }

    public function roleStudent() {
        $roles = Role::find(1);
        $students = $roles->users()->paginate(5);
        return view('user.student', ['students' => $students]);
    }

    public function roleTeacher() {
        $roles = Role::find(2);
        $teachers = $roles->users()->paginate(5);
        return view('user.teacher', ['teachers' => $teachers]);
    }

    public function roleAdmin() {
        $roles = Role::find(3);
        $admins = $roles->users()->paginate(5);
        return view('user.admin', ['admins' => $admins]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('user.show');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit');
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), $this->rules());
        if($validation->passes()) {
            $user = Auth::user();
            $user->name = $request->name;
            $user->save();

            return redirect()->route('user.edit', ['id' => $id]);
        } else {
             return redirect()->route('user.edit')->withErrors($validator)->withInput();
        }
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('user.index');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|digits:10|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
