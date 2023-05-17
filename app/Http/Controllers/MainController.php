<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
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
     * Get notifications data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusOnline(){
        $notifications = auth()->user()->unreadNotifications;
        return response()->json($notifications);
    }

    /**
     * Mark as read notification.
     *
     * @param Request $request
     * @return void
     */
    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->id, function ($query) use ($request) {
                return $query->where('id', $request->id);
            })
            ->markAsRead();
    }

    public function checkProductVerify(){
        $exists = Storage::disk('local')->exists('helpers/helper.json');
        $chk = 0;
        if($exists){
            $path = Storage::disk('local')->get('helpers/helper.json');
            $content = json_decode($path, true);
            $chk = $content['verify'];
        }

        return $chk;
    }
}
