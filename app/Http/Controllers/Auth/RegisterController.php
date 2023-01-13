<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Other\ConvertNumberToEnglish;
use App\Models\Other\ConvertPhoneNumberStandard;
use App\Models\User;
use App\Rules\Auth\lengthOtp;
use App\Rules\isNumber;
use App\Rules\PhoneNumberExist;
use Illuminate\Http\Request;
use App\Rules\PhoneNumber;
use App\Rules\PhoneNumberIsBlock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'phone' => ['required', new PhoneNumber, new PhoneNumberExist, new PhoneNumberIsBlock],
            'code' => ['required', new isNumber, new lengthOtp],
            'password' => 'required | string |min:8',
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
        $password = $data['password'];

        $user = User::wherePhone($phone)->with('EndOTP')->first();

        // این قسمت دقیقا داخل او تی پی وجود دارهه.موقع تغییر اونجا رو هم تغییر بده تا زمانی که مرتبط سازی بشه


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

                    DB::beginTransaction();
                    try {

                        $user->password = $password;
                        $user->active_otp = 1;
                        $user->save();

                        $otp->active = 1;
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

                    $token = $user->createToken($req->password + $phone)->plainTextToken;

                    return Response()->json([
                        'status' => 200,
                        'data' => ['token' => $token],
                        'redirect' => 'home'
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