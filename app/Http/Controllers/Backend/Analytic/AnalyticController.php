<?php

namespace App\Http\Controllers\Backend\Analytic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenNumber;
use Yajra\Datatables\Datatables;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AnalyticController extends Controller
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
     * Summary of index
     * @param Datatables $datatables
     * @param Request $request
     * @return mixed
     */
    public function index(Datatables $datatables, Request $request)
    {
        $from = isset($request->from) ? Carbon::parse($request->from)->startOfDay() : '';
        $to = isset($request->to) ? Carbon::parse($request->to)->endOfDay() : '';
        $param['from'] = $from != '' ? Carbon::parse($from)->format('Y-m-d') : '';
        $param['to'] = $to != '' ? Carbon::parse($to)->format('Y-m-d') : '';

        $param['type'] =  1;
        // Run query for analytic
        $getDataAnalytics = $this->getDataAnalyticDb($param);

        if ($param['from'] && $param['to']) {
            $dateArrFrom =  Carbon::parse($param['from'])->startOfDay();
            $dateArrTo =  Carbon::parse($param['to'])->endOfDay();
        } else {
            $dateArrFrom =  Carbon::parse(Carbon::now()->firstOfMonth())->startOfDay();
            $dateArrTo =  Carbon::parse(Carbon::now()->lastOfMonth())->endOfDay();
        }

        // Generate date with CarbonPeriod
        $daysOfMonth = collect(
            CarbonPeriod::create(
                $dateArrFrom,
                $dateArrTo
            )
        )
            ->map(function ($getDataAnalytics){
                return [
                    'label' => $getDataAnalytics->format('F d, Y'),
                    'countActiveTime' => 0,
                    'countDoneTime' => 0,
                    'countPendingTime' => 0,
                ];
            })
            ->keyBy('label')
            ->merge(
                $getDataAnalytics->keyBy('label')
            )
            ->values();

        $returnData['label'] = [];
        $returnData['dataSum'] = [];

        foreach ($daysOfMonth as $value) {
            $returnData['label'][] = $value['label'];
            $returnData['dataActive'][] = (int)$value['countActiveTime'];
            $returnData['dataDone'][] = (int)$value['countDoneTime'];
            $returnData['dataPending'][] = (int)$value['countPendingTime'];
        }

        $analytic = $this->chartAnalytics('analyticHistories', "Analytic", $returnData);

        // DataTables
        $columns = [
            'department' => ['name' => 'department.name'],
            'counter' => ['name' => 'counter.name'],
            'date',
            'status',
            'user' => ['name' => 'user.name'],
        ];

        $param['type'] =  2;
        // Run query for dataTables
        $getDataAnalytics = $this->getDataAnalyticDb($param);

        if ($datatables->getRequest()->ajax()) {
            return $datatables->of($getDataAnalytics)
                ->addColumn('department', function ($model) {
                    return $model->department->name;
                })
                ->addColumn('status', function ($model) {
                    return $model->status != null ? ucfirst($model->status) : "Pending";
                })
                ->addColumn('counter', function ($model) {
                return $model->id_counter != null ? $model->counter->name : "No Data";
                })
                ->addColumn('user', function ($model) {
                    return $model->id_user  != null ? $model->user->name : "No Data";
                })
                ->rawColumns(['name', 'user', 'counter'])
                ->toJson();
        }

        $columnsArrExPr = [0,1,2,3,4,5,6,7,8,9];
        $html = $datatables->getHtmlBuilder()
            ->columns($columns)
            ->parameters([
                'order' => [[1,'desc'], [2,'desc']],
                'responsive' => true,
                'autoWidth' => false,
                'searching' => false,
                'lengthMenu' => [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                'dom' => 'Bfrtip',
                'buttons' => $this->buttonDatatables($columnsArrExPr),
            ]);

        return view('backend.analytic.index', compact('analytic', 'param', 'html'));
    }

    /**
     * Summary of getDataAnalyticDb
     * @param mixed $param
     * @return mixed
     */
    public function getDataAnalyticDb($param)
    {
        $getDataAnalytics = TokenNumber::with('user', 'counter', 'department')
            ->select('token_numbers.*');

        if ($param['type'] == 1) {
            $getDataAnalytics = $getDataAnalytics->select(
                DB::raw("DATE_FORMAT(date, '%M %d, %Y') as label"),
                DB::raw('COUNT(case when `status` = "active" then 0 END) as `countActiveTime`'),
                DB::raw('COUNT(case when `status` = "done" then 0 END) as `countDoneTime`'),
                DB::raw('COUNT(case when `status` IS NULL then 0 END) as `countPendingTime`')
            );
        }

        if ($param['from'] && $param['to']) {
            $dateArrFrom =  Carbon::parse($param['from'])->startOfDay();
            $dateArrTo =  Carbon::parse($param['to'])->endOfDay();
            $getDataAnalytics = $getDataAnalytics->whereBetween('date', [$param['from'], $param['to']]);
        } else {
            $dateArrFrom =  Carbon::parse(Carbon::now()->firstOfMonth())->startOfDay();
            $dateArrTo =  Carbon::parse(Carbon::now()->lastOfMonth())->endOfDay();
            $getDataAnalytics = $getDataAnalytics->whereBetween('date', [$dateArrFrom, $dateArrTo]);
        }

        if ($param['type'] == 1) {
            $getDataAnalytics = $getDataAnalytics->groupBy('date');
            $getDataAnalytics = $getDataAnalytics->get();
        }

        return $getDataAnalytics;
    }

    /**
     * Summary of chartAnalytics
     * @param mixed $name
     * @param mixed $title
     * @param mixed $data
     * @return mixed
     */
    public function chartAnalytics($name, $title, $data)
    {
        $chartjs = app()->chartjs
            ->name($name)
            ->type('line')
            ->size(['width' => 800, 'height' => 500])
            ->labels($data['label'])
            ->datasets([
                [
                    "label" => "Active",
                    'borderDash' => [5, 5],
                    'pointRadius' => true,
                    'backgroundColor' => "rgba(255, 34, 21, 0.31)",
                    'borderColor' => "rgba(255, 34, 21, 0.7)",
                    "pointColor" => "rgba(255, 34, 21, 0.7)",
                    "pointStrokeColor" => "rgba(255, 34, 21, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHighlightStroke" => "rgba(220,220,220,1)",
                    'data' => $data['dataActive']
                ],
                [
                    "label" => "Done",
                    'backgroundColor' => 'rgba(210, 214, 222, 1)',
                    'borderColor' => 'rgba(210, 214, 222, 1)',
                    'pointRadius' => true,
                    "pointColor" => 'rgba(210, 214, 222, 1)',
                    "pointStrokeColor" => '#c1c7d1',
                    "pointHighlightFill" => "#fff",
                    "pointHighlightStroke" => 'rgba(220,220,220,1)',
                    'data' => $data['dataDone']
                ],
                [
                    "label" => "Pending",
                    'backgroundColor' => 'rgba(60,141,188,0.9)',
                    'borderColor' => 'rgba(60,141,188,0.8)',
                    'pointRadius' => true,
                    "pointColor" => '#3b8bba',
                    "pointStrokeColor" => 'rgba(60,141,188,1)',
                    "pointHighlightFill" => "#fff",
                    "pointHighlightStroke" => 'rgba(60,141,188,1)',
                    'data' => $data['dataPending']
                ],
            ])
            ->options([]);

        $chartjs->optionsRaw([
            'title' => [
                'text' => $title,
                'display' => true,
                'position' => "top",
                'fontSize' => 18,
                'fontColor' => "#000"
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'legend' => [
                'position' => 'top',
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'gridLines' => [
                            'display' => false
                        ]
                    ]
                ],
                'yAxes' => [
                    [
                        'gridLines' => [
                            'display' => false
                        ]
                    ]
                ],
            ]
        ]);

        return $chartjs;
    }

    /**
     * Summary of buttonDatatables
     * @param mixed $columnsArrExPr
     * @return array<array>
     */
    public function buttonDatatables($columnsArrExPr)
    {
        return [
            [
                'pageLength'
            ],
            [
                'extend' => 'csvHtml5',
                'exportOptions' => [
                    'columns' => $columnsArrExPr
                ]
            ],
            [
                'extend' => 'pdfHtml5',
                'exportOptions' => [
                    'columns' => $columnsArrExPr
                ]
            ],
            [
                'extend' => 'excelHtml5',
                'exportOptions' => [
                    'columns' => $columnsArrExPr
                ]
            ],
            [
                'extend' => 'print',
                'exportOptions' => [
                    'columns' => $columnsArrExPr
                ]
            ],
        ];
    }
}
