<?php
namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\TokenNumber;
use Illuminate\Http\Request;
use Response;

class ApiCallController extends Controller
{
    /**
     * Summary of apiTokenNumber
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiTokenNumber(Request $request)
    {
        $data = TokenNumber::with('department', 'branch');
        if($request->id_branch != '')       {
            $data = $data->where('id_branch', $request->id_branch);
        }
        $data = $data->where('status', null)
            ->where('date', date('Y-m-d'))
            ->get();
        return response()->json($data);
    }

    /**
     * Summary of apiGetHaveCalled
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiGetHaveCalled(Request $request)
    {
        $data = TokenNumber::with('department', 'counter', 'branch');
        if($request->id_branch != '')       {
            $data = $data->where('id_branch', $request->id_branch);
        }
        $data = $data->where('status', 'done')
            ->where('date', date('Y-m-d'))
            ->get();
        return response()->json($data);
    }
}
