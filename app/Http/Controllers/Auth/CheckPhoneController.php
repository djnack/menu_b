<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\OTP;
use App\Models\Other\ConvertNumberToEnglish;
use App\Models\Other\ConvertPhoneNumberStandard;
use App\Rules\PhoneNumberIsBlock;
use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckPhoneController extends Controller
{
    public function index(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'phone' => ['required', new PhoneNumber, new PhoneNumberIsBlock],
        ]);
        if ($validate->fails()) {
            return Response()->json([
                'status' => 400,
                'error' => $validate->errors()
            ], 400);
        }

        $data = ConvertNumberToEnglish::ConvertAll($req->all());
        $phone = ConvertPhoneNumberStandard::Convert($data['phone']);

        // dd($req->Ip());


        // dd(LoginIp::whereIp($req->Ip())->get());



        $user = User::wherePhone($phone)->with('EndOTP')->first();

        // اگر یوزری وجود نداشت
        if ($user === Null) {
            DB::beginTransaction();
            try {

                $user = new User;
                $user->phone = $phone;
                $user->save();

                $otp = new OTP;
                $otp->code = OTP::getRandomCode();
                $user->OTPs()->save($otp);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // ذخیره در تیبل ارور ها

                return Response()->json([
                    'status' => 400,
                    'error' => ['not_find' => 'ارور ناشناخته']
                ], 400);
            }



            // ارسال پیامک و ثبت آن در دیتا بیس

            return Response()->json([
                'status' => 200,
                'redirect' => 'otp'
            ], 200);
        }

        if (!$user->active_otp) {

            if (strtotime($user->EndOTP->first()->created_at) < strtotime("-5 minutes")) {

                DB::beginTransaction();
                try {

                    $otp = new OTP;
                    $otp->code = OTP::getRandomCode();
                    $user->OTPs()->save($otp);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    // ذخیره در تیبل ارور ها

                    return Response()->json([
                        'status' => 400,
                        'error' => ['not_find' => 'ارور ناشناخته']
                    ], 400);
                }

                // ارسال پیامک و ثبت آن در دیتا بیس

            }

            return Response()->json([
                'status' => 200,
                'redirect' => 'otp'
            ], 200);
        }

        // dd(strtotime($user->EndOTP->first()->created_at) < strtotime("-5 minutes"));

        // dd($user->EndOTP->first()->created_at);

        return Response()->json([
            'status' => 200,
            'redirect' => 'login'
        ], 200);
    }
}