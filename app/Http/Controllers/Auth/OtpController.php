<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Other\ConvertNumberToEnglish;
use App\Models\Other\ConvertPhoneNumberStandard;
use App\Models\User;
use App\Rules\Auth\lengthOtp;
use App\Rules\isNumber;
use App\Rules\PhoneNumber;
use App\Rules\PhoneNumberExist;
use App\Rules\PhoneNumberIsBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function index(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'phone' => ['required', new PhoneNumber, new PhoneNumberExist, new PhoneNumberIsBlock],
            'code' => ['required', new isNumber, new lengthOtp],
        ]);
        if ($validate->fails()) {
            return Response()->json([
                'status' => 400,
                'error' => $validate->errors()
            ], 400);
        }

        $data = ConvertNumberToEnglish::ConvertAll($req->all());
        $phone = ConvertPhoneNumberStandard::Convert($data['phone']);
        $code = $data['code'];



        // این قسمت دقیقا داخل پسور وجود دارهه.موقع تغییر اونجا رو هم تغییر بده تا زمانی که مرتبط سازی بشه

        $user = User::wherePhone($phone)->with('EndOTP')->first();
        if ($user) {
            $otp = $user->EndOTP()->first();
            if (!$otp->active) {

                if ($otp->try && $otp->try % 10 === 0 && strtotime($otp->updated_at) - strtotime("-1 day")) {
                    return Response()->json([
                        'status' => 400,
                        'remaining_time' => $otp->updated_at,
                        'error' => 'محدود شده'
                    ], 400);
                }

                if ($otp->code === $code) {
                    return Response()->json([
                        'status' => 200,
                        'redirect' => 'password'
                    ], 200);
                } else {

                    DB::beginTransaction();
                    try {

                        $baghimande = 10 - $otp->try % 10;
                        $otp->try += 1;
                        $otp->save();

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        // ذخیره در تیبل ارور ها

                        return Response()->json([
                            'status' => 400,
                            'error' => ['not_find' => 'ارور ناشناخته']
                        ], 400);
                    }
                    
                    return Response()->json([
                        'status' => 400,
                        'error' => "زمر اشتباه است. {$baghimande} بار دیگر مانده."
                    ], 400);
                }
            }
        }
    }
}