<?php
namespace App\Http\Controllers\Frontend\Token;

use App\Http\Controllers\Controller;
use App\Mail\HTMLMail;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Setting;
use App\Models\TokenNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Config;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class TokenController extends Controller
{

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $department = [];
        $branch = Branch::orderBy('id')->get()->pluck('full_name', 'id');
        $getSettings = Setting::find(1);

        return view('frontend.token.index', [
            'department' => $department,
            'branch' => $branch,
            'settings' => $getSettings
        ]);
    }

    /**
     * Summary of getDataFromDB
     * @param mixed $id
     * @return mixed
     */
    public function getDataFromDB($id){
        return TokenNumber::where('id_department', $id)
            ->where('date', date('Y-m-d'));
    }

    /**
     * Summary of getToken
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken(Request $request){

        $idDept = $request->post('id_department');
        $emailClient = $request->post('email');
        $phoneClient = $request->post('phone');
        // Get info of department
        $getDepartment = Department::find($idDept);

        // Get last number
        $getLastNumber = $this->getDataFromDB($idDept)
            ->orderby('number', 'desc')
            ->take(1)
            ->first()
        ;

        if($getLastNumber){
            $getNextNumber = $getLastNumber->number + 1;
        }else{
            $getNextNumber = 1;
        }

        $randNumber = random_int(10000,99999);
        $encryptedToken = Crypt::encryptString($randNumber.'-'.$getDepartment->letter.'-'.$getNextNumber.'-'.$getDepartment->name.'-'.$getDepartment->branch->name);

        // Save the token number
        $saveToken = new TokenNumber();
        $saveToken->id_department = $idDept;
        $saveToken->id_branch = $request->post('id_branch');
        $saveToken->date = date('Y-m-d');
        $saveToken->number = $getNextNumber;
        $saveToken->secret_number = $randNumber;
        $saveToken->crypt = $encryptedToken;
        $saveToken->email_client = $emailClient;
        $saveToken->phone_client = $phoneClient;
        $saveToken->save();

        return response()->json(['token' => $encryptedToken]);
    }

    /**
     * Summary of tokenNumber
     * @param mixed $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tokenNumber($token){
        // Decrypted the token
        $decrypted = Crypt::decryptString($token);

        // Explode the data
        $decryptedExplode = explode('-', $decrypted);
        
        // If array token less then 5
        // Will show fail token
        if(count($decryptedExplode) < 5){
            return view('frontend.token.token_queue', [
                'branch' => '',
                'token' => 0,
                'department' => '',
                'current_token' => 0,
            ]);
        }
        $getRand = $decryptedExplode[0];
        $getLetter = $decryptedExplode[1];
        $getTokenNumber = $decryptedExplode[2];
        $getDepartment = $decryptedExplode[3];
        $getBranch = $decryptedExplode[4];

        // Check the token
        $checkTokenData = TokenNumber::with('department')
            ->whereHas('department', function($query) use ($getDepartment) {
                $query->where('name', $getDepartment);
            })
            ->where('date', date('Y-m-d'))
            ->where('secret_number', $getRand)
            ->where('number', $getTokenNumber)
            ->first()
        ;

        if($checkTokenData) {
            // Get current token
            $currentData = TokenNumber::with('department')
                ->whereHas('department', function ($query) use ($getDepartment) {
                    $query->where('name', $getDepartment);
                })
                ->where('status', 'active')
                ->where('date', date('Y-m-d'))
                ->orderby('number', 'desc')
                ->first()
            ;

            // Get current token in department but status is done
            $currentDataDone = TokenNumber::with('department')
                ->whereHas('department', function ($query) use ($getDepartment) {
                    $query->where('name', $getDepartment);
                })
                ->where('status', 'done')
                ->where('date', date('Y-m-d'))
                ->orderby('number', 'desc')
                ->first()
            ;

            if ($currentData) {
                if($currentData->number > $getTokenNumber){
                    $theToken = -1; // The token is expire
                    $currentNumberToken = -1;
                }else{
                    $theToken = $getLetter . str_pad($getTokenNumber, 4, '0', STR_PAD_LEFT);
                    $currentNumberToken = $getLetter . str_pad($currentData->number, 4, '0', STR_PAD_LEFT);
                }
            } else {
                // Is any status done, if yes will get current number with status done
                if($currentDataDone){
                    $theToken = $getLetter . str_pad($getTokenNumber, 4, '0', STR_PAD_LEFT);
                    $currentNumberToken = $getLetter . str_pad($currentDataDone->number, 4, '0', STR_PAD_LEFT);
                }else{
                    $theToken = $getLetter . str_pad($getTokenNumber, 4, '0', STR_PAD_LEFT);
                    $currentNumberToken = $getLetter . str_pad(0, 4, '0', STR_PAD_LEFT);
                }

            }
        }else{
            $theToken = 0; // The token not recorded
            $currentNumberToken = 0;
        }

        return view('frontend.token.token_queue', [
            'branch' => $getBranch,
            'token' => $theToken,
            'department' => $getDepartment,
            'current_token' => $currentNumberToken,
        ]);
    }

    /**
     * Summary of getCurrentToken
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentToken(Request $request){
        // Get department
        $dept = $request->get('department');

        // Get current token in department
        $currentData = TokenNumber::with('department')
            ->whereHas('department', function($query) use ($dept) {
                $query->where('name', $dept);
            })
            ->where('status', 'active')
            ->where('date', date('Y-m-d'))
            ->orderby('number', 'desc')
            ->first()
        ;

        // Get current token in department but status is done
        $currentDataDone = TokenNumber::with('department')
            ->whereHas('department', function($query) use ($dept) {
                $query->where('name', $dept);
            })
            ->where('status', 'done')
            ->where('date', date('Y-m-d'))
            ->orderby('number', 'desc')
            ->first()
        ;

        if($currentData){
            $getLetter = Department::getInfoByName($dept)->letter;
            $currentNumberToken = $getLetter.str_pad($currentData->number, 4, '0', STR_PAD_LEFT);
        }else{
            // Is any status done, if yes will get current number with status done
            if($currentDataDone){
                $getLetter = Department::getInfoByName($dept)->letter;
                $currentNumberToken = $getLetter.str_pad($currentDataDone->number, 4, '0', STR_PAD_LEFT);
            }else{
                $getLetter = Department::getInfoByName($dept)->letter;
                $currentNumberToken = $getLetter.str_pad(0, 4, '0', STR_PAD_LEFT);
            }
        }

        return response()->json($currentNumberToken);
    }

    /**
     * Summary of sentMail
     * @param Request $request
     * @return string
     */
    public function sentMail(Request $request){
        $requestParam = $request->all();
        $toMail = $requestParam['email'];
        $url = $requestParam['url'];

        $data = [
            'url'       => $url,
            'subject'   => 'Your token queue',
            'from'      => env('MAIL_FROM_ADDRESS', ''),
            'from_name' => env('MAIL_FROM_NAME', ''),
        ];

        Mail::to($toMail)->send(new HTMLMail($data));

        return 'success';
    }

    /**
     * Summary of sendMessage
     * @param Request $request
     * @return string
     */
    public function sendMessage(Request $request)
    {
        $requestParam = $request->all();
        $recipients = $requestParam['phone'];
        $url = $requestParam['url'];

        $shortener = url()->shortener();
        $shortURL = $shortener->shorten($url);

        // This is the message on twilio
        $message = __("sms_template.register_token_the_token") . $shortURL .  __("sms_template.register_thank_you");

        // Get twilio sid, token, phone number
        $getSettings = Setting::find(1);

        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = $getSettings->twilio_sid;
        $auth_token = $getSettings->twilio_token;
        $twilio_number = $getSettings->twilio_number;
        $client = new Client($account_sid, $auth_token);

        try {
            $client->messages->create($recipients,
                [
                    'from' => $twilio_number,
                    'body' => $message
                ]);

            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
