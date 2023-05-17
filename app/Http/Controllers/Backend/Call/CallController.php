<?php
namespace App\Http\Controllers\Backend\Call;

use App\Http\Controllers\Controller;
use App\Mail\HTMLMailReminders;
use App\Models\Counter;
use App\Models\Department;
use App\Models\Setting;
use App\Models\TokenNumber;
use App\Models\User;
use App\Notifications\NewStatusUserNotification;
use Auth;
use Config;
use Illuminate\Http\Request;
use Notification;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Crypt;

class CallController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // If User Administrator
        if (Auth::user()->hasRole('administrator')) {
            $department = Department::orderBy('id_branch')->get()->pluck('full_name', 'id');
            $counter = Counter::orderBy('id')->get()->pluck('full_name', 'id');

            $data = TokenNumber::where('status', null)
                ->where('date', date('Y-m-d'))
                ->get();

            $dataHaveCalled = TokenNumber::with('department', 'counter')
                ->where('status', 'done')
                ->where('date', date('Y-m-d'))
                ->get();
        }else{
            $userBranch = Auth::User()->id_branch;

            $department = Department::where('id_branch', $userBranch)->orderBy('id')->pluck('name', 'id');
            $counter = Counter::where('id_branch', $userBranch)->orderBy('id')->pluck('name', 'id');

            $data = TokenNumber::where('status', null)
                ->where('id_branch', $userBranch)
                ->where('date', date('Y-m-d'))
                ->get();

            $dataHaveCalled = TokenNumber::with('department', 'counter')
                ->where('id_branch', $userBranch)
                ->where('status', 'done')
                ->where('date', date('Y-m-d'))
                ->get();
        }

        $data->form_action = $this->getRoute() . '.update';
        $data->button_text = 'Call';

        return view('backend.calls.index', [
            'data' => $data,
            'department' => $department,
            'counter' => $counter,
            'have_called' => $dataHaveCalled,
        ]);
    }

    /**
     * Summary of getRoute
     * @return string
     */
    private function getRoute()
    {
        return 'calls';
    }

    /**
     * Summary of update
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $getCounterId = $request->get('id_counter');
        $getDeptId = $request->get('id_department');
        try {

            // Check is there any active status
            $currentActive = $this->getDataFromDB($getDeptId)
                ->where('id_user', Auth::user()->id)
                ->where('status', 'active')
                ->get();

            // If yes
            // Will search number of active
            if ($currentActive->count() > 0)
            {
                // Get current token
                $getCurrentNumber = $this->getDataFromDB($getDeptId)
                    ->where('id_user', Auth::user()->id)
                    ->where('status', 'active')
                    ->orderby('number', 'asc')
                    ->first()
                ;

                // Get next token
                $getNextNumber = $this->getDataFromDB($getDeptId)
                    ->where('status', null)
                    ->where('number', '>', $getCurrentNumber->number)
                    ->orderby('number', 'asc')
                    ->take(1)
                    ->first()
                ;

                if($getNextNumber) {
                    $this->getDataFromDB($getDeptId)
                        ->where('status', null)
                        ->where('number', $getNextNumber->number)
                        ->update(array(
                            'id_user' => Auth::user()->id,
                            'id_counter' => $getCounterId,
                            'status' => 'active',
                            'is_new' => true,
                        ));

                    $type = 'success';
                    $message = Config::get('const.SUCCESS_CALL');
                    $idToken = $getNextNumber->id;
                }else{
                    $type = 'error';
                    $message = Config::get('const.ERROR_CALL');
                    $idToken = 00; // Means error
                }

                $this->getDataFromDB($getDeptId)
                    ->where('id_user', Auth::user()->id)
                    ->where('status', 'active')
                    ->where('number', $getCurrentNumber->number)
                    ->update(array(
                        'status' => 'done',
                    ));
            }
            // If no
            // Will set status active and the id_counter
            // Start a queue number on a new day
            else
            {
                $getNumber = $this->getDataFromDB($getDeptId)
                    ->where('status', null)
                    ->orderby('number', 'asc')
                    ->first()
                ;

                if($getNumber) {
                    $changeStatus = $this->getDataFromDB($getDeptId)
                        ->where('status', null)
                        ->where('number', $getNumber->number);

                    $changeStatus->update(array(
                        'id_user' => Auth::user()->id,
                        'id_counter' => $getCounterId,
                        'status' => 'active',
                        'is_new' => true,
                    ));

                    // Get current token
                    $getCurrentNumber = $this->getDataFromDB($getDeptId)
                        ->where('id_user', Auth::user()->id)
                        ->where('status', 'active')
                        ->orderby('number', 'asc')
                        ->first()
                    ;

                    // Get next token
                    $getNextNumber = $this->getDataFromDB($getDeptId)
                        ->where('status', null)
                        ->where('number', '>', $getCurrentNumber->number)
                        ->orderby('number', 'asc')
                        ->take(1)
                        ->first()
                    ;

                    $type = 'success';
                    $message = Config::get('const.SUCCESS_CALL');
                    if($getNextNumber) {
                        $idToken = $getNextNumber->id;
                    }else{
                        $idToken = 00; // Means error
                    }
                }else{
                    $type = 'error';
                    $message = Config::get('const.ERROR_CALL');
                    $idToken = 00; // Means error
                }
            }

            // Return the data
            return response()->json(['type' => $type, 'message' => $message, 'id_token' => $idToken]);

        } catch (\Exception $e) {
            // If update is failed
            $type = 'error';
            $message = Config::get('const.FAILED_CALL');
            return response()->json(['type' => $type, 'message' => $message]);
        }
    }

    /**
     * Summary of getDataFromDB
     * @param mixed $id
     * @return mixed
     */
    public function getDataFromDB($id){
        $currentData = TokenNumber::where('id_department', $id)
            ->where('date', date('Y-m-d'));

        return $currentData;
    }

    /**
     * Summary of sendToNextClient
     * @param Request $request
     * @return void
     */
    public function sendToNextClient(Request $request)
    {
        $remindManyQueue = 10;

        $getThreeToken = $this->getDataFromDB($request->deptId)
            ->where('id', '>', $request->idCurrentToken)
            ->take($remindManyQueue)
            ->orderBy('id','asc')
            ->get()
            ->toArray()
        ;

        if($getThreeToken > 0){
            $data = [
                'current_token' => $request->currnetToken,
                'subject'   => 'Reminder of Current Token Number',
                'from'      => env('MAIL_FROM_ADDRESS', ''),
                'from_name' => env('MAIL_FROM_NAME', ''),
            ];

            // Get twilio sid, token, phone number
            $getSettings = Setting::find(1);

            // Your Account SID and Auth Token from twilio.com/console
            $account_sid = $getSettings->twilio_sid;
            $auth_token = $getSettings->twilio_token;
            $twilio_number = $getSettings->twilio_number;

            if($account_sid != null && $account_sid != '' && $auth_token != null && $auth_token != '' && $twilio_number != null && $twilio_number != '' ){
                $client = new Client( $account_sid, $auth_token );

                // This is the message on twilio
                $message = __("sms_template.reminder_token_the_token") . $request->currnetToken . __("sms_template.reminder_thank_you");
            }

            foreach ($getThreeToken as $singleData)
            {
                // Check if phone number not null
                if(($singleData['phone_client'] != null) && $account_sid != null && $account_sid != '' && $auth_token != null && $auth_token != '' && $twilio_number != null && $twilio_number != '') {
                    $client->messages->create(
                        $singleData['phone_client'],
                        [
                            'from' => $twilio_number,
                            'body' => $message,
                        ]
                    );
                }

                // Check if email not null
                if($singleData['email_client'] != null){
                    $getDetailToken = $this->getInfoToken($singleData['crypt']);
                    $data['client_token'] = $getDetailToken['getLetter'] . str_pad($getDetailToken['getTokenNumber'], 4, '0', STR_PAD_LEFT);
                    Mail::to($singleData['email_client'])->send((new HTMLMailReminders($data))->delay(30));
                }
            }
        }
    }

    /**
     * Summary of getInfoToken
     * @param mixed $token
     * @return array<string>
     */
    public function getInfoToken($token){
        // Decrypted the token
        $decrypted = Crypt::decryptString($token);

        // Explode the data
        $decryptedExplode = explode('-', $decrypted);
        return [
            'getRand' => $decryptedExplode[0],
            'getLetter' => $decryptedExplode[1],
            'getTokenNumber' => $decryptedExplode[2],
            'getDepartment' => $decryptedExplode[3],
            'getBranch' => $decryptedExplode[4],
        ];
    }

    /**
     * Summary of updateStatusOnline
     * @param Request $request
     * @return void
     */
    public function updateStatusOnline(Request $request){
        $currentData = User::find($request->id);
        $currentData->is_online = $request->status;
        $currentData->save();

        $details = [
            'name' => $currentData->name,
            'status' => $request->status,
        ];

        $users = User::where('is_online', 1)
            ->where('id', '!=' , $request->id)
            ->where('id_branch' , $request->id_branch)
            ->orWhere('id_branch', null)
            ->get();

        Notification::send($users, new NewStatusUserNotification($details));

        dd('done');
    }

    /**
     * Summary of checkStatusOnline
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatusOnline(Request $request){
        $dataStatus = User::find($request->id);

        return response()->json(['is_online' => $dataStatus->is_online]);

    }
}
