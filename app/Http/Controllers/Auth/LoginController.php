<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Other\ConvertNumberToEnglish;
use App\Models\Other\ConvertPhoneNumberStandard;
use App\Models\User;
use App\Rules\Auth\lengthOtp;
use App\Rules\isNumber;
use App\Rules\PhoneNumberExist;
use App\Rules\PhoneNumberIsBlock;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'phone' => ['required', new PhoneNumber, new PhoneNumberExist, new PhoneNumberIsBlock],
            'password' => 'required | string',
        ]);

        if ($validate->fails()) {
            return Response()->json([
                'status' => 400,
                'error' => $validate->errors()
            ], 400);
        }

        $data = ConvertNumberToEnglish::ConvertAll($req->all());
        $phone = ConvertPhoneNumberStandard::Convert($data['phone']);
        $password = $data['password'];

        $user = User::wherePhone($phone)->first();

        if (!$user || !Hash::check($password, $user->password)) {

            // ذخیره تعداد تلاش و محدود سازی شماره تماس کاربر

            return Response()->json([
                'status' => 400,
                'error' => ['password' => 'پسورد اشتباه است']
            ], 400);
        }
        $token = $user->createToken($user->password)->plainTextToken;

        return Response()->json([
            'status' => 200,
            'redirect' => 'home',
            'data' => ['token' => $token]
        ], 200);
    }
}