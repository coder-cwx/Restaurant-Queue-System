<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\TokenNumber;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $departmentCount = Department::count();
        $queueHaventCalledCount = TokenNumber::where('status', null)->where('date', date('Y-m-d'))->count();
        $queueCalledCount = TokenNumber::where('status', 'done')->where('date', date('Y-m-d'))->count();

        // Get branch current user
        $branch = Auth::User()->id_branch;
        // Get all information of user like online or offline also the branch
        $users = User::with('branch');
        if($branch != null){
            $users = $users->where('id_branch', $branch);
        }
        $users = $users->orderBy('id_branch', 'asc')
            ->get();

        return view('backend/home', compact('queueHaventCalledCount', 'queueCalledCount', 'departmentCount', 'users'));
    }

    /**
     * Show online or offline users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserOfflineOnline()
    {
        // Get branch current user
        $branch = Auth::User()->id_branch;
        // Get all information of user like online or offline also the branch
        $users = User::with('branch');
        if($branch != null){
            $users = $users->where('id_branch', $branch);
        }
        $users = $users->orderBy('id_branch', 'asc')
            ->get();

        return response()->json($users);
    }
}
