<?php
namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TokenNumber;
use Response;

class ApiDisplayController extends Controller
{
    /**
     * Summary of apiGetDataDisplay
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiGetDataDisplay($id)
    {
        $data = TokenNumber::with('department', 'counter')
            ->where('id_department', $id)
            ->where('date', date('Y-m-d'))
            ->where('status','active')
            ->orderBy('number', 'desc')
            ->get()
        ;

        $getNewData = TokenNumber::with('department', 'counter')
            ->where('id_department', $id)
            ->where('date', date('Y-m-d'))
            ->where('status','active')
            ->where('is_new', true)
            ->orderBy('number', 'asc')
            ->first()
        ;

        if($getNewData){
            $data['getNew'] = [
                'department' => $getNewData->department->name,
                'counter' => $getNewData->counter->name,
                'token' => $getNewData->department->letter.str_pad($getNewData->number, 4, '0', STR_PAD_LEFT)
            ];

        }else{
            $data['getNew'] = [
                'letter' => 0,
                'token' => 0
            ];
        }

        $changeStatus =TokenNumber::with('department')
            ->where('id_department', $id)
            ->where('date', date('Y-m-d'))
            ->where('status','active')
            ->where('is_new', true)
            ->orderBy('number', 'asc')
            ->take(1)
        ;
        $changeStatus->update(array(
            'is_new' => false,
        ));

        return response()->json($data);
    }
}
