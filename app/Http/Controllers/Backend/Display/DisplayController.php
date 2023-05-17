<?php

namespace App\Http\Controllers\Backend\Display;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DisplayController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        if (Auth::user()->hasRole('administrator')) {
            $department = Department::orderBy('id_branch')->get()->pluck('full_name', 'id');
        }else{
            $userBranch = Auth::User()->id_branch;
            $department = Department::where('id_branch', $userBranch)->orderBy('id')->pluck('name', 'id');
        }
        return view('backend.display.index', [
            'department' => $department,
        ]);
    }
}
