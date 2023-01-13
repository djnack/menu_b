<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Market\Marketplace;
use App\Models\Other\ConvertNumberToEnglish;
use App\Models\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateMarketplaceController extends Controller
{
    public function index(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'name' => 'required | json',
            'slug' => ['required', 'string', 'min:6', 'regex:/^([a-zA-Z0-9_]+)(\s[a-zA-Z0-9]+)*$/', 'unique:marketplaces,slug'],
            'slogan' => 'json',
            'img_brand' => 'image | max:250 | mimes:jpeg,jpg,png',
            'img_abl' => 'image | max:250 | mimes:jpeg,jpg,png',
            'img_bg' => 'image | max:250 | mimes:jpeg,jpg,png',
        ]);
        if ($validate->fails()) {
            return Response()->json([
                'status' => 400,
                'error' => $validate->errors(),
            ], 400);
        }

        $data = ConvertNumberToEnglish::ConvertAll($req->all());
        $userTokenId = $req->user()->currentAccessToken()->id;

        // DB::beginTransaction();
        // try {

        $market = new Marketplace;
        $market->created_by_token_id = $userTokenId;
        $market->slug = $data['slug'];
        $market->save();

        // برای اشغال نشدن فضا فقط داخل ویرایش برای اولین برای نوشته سازنده و خود سازنده ذخیره میشه.و تغییرات هم ذخیره میشه
        // $history = new History;
        // $history->created_by_token_id = $userTokenId;
        // $history->key = 'marketplace_slug';
        // $history->value = $data['slug'];
        // $history->save();

        if ($req->file('img_brand')) {
            $file = $req->file('img_brand');
            $filename = date('YmdHi') . '_brand' . '_' . ConvertNumberToEnglish::Convert($file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $image = new Image;
            $image->path = $filename;
            $image->created_by_token_id = $userTokenId;
            $image->alt = $market->slug;
            $market->image()->save($image, ['detail' => 'brand', 'created_by_token_id' => $userTokenId]);
        }

        if ($req->file('img_abl')) {
            $file = $req->file('img_abl');
            $filename = date('YmdHi') . '_abl' . '_' . ConvertNumberToEnglish::Convert($file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $image = new Image;
            $image->path = $filename;
            $image->created_by_token_id = $userTokenId;
            $image->alt = $market->slug;
            $market->image()->save($image, ['detail' => 'abl', 'created_by_token_id' => $userTokenId]);
        }

        if ($req->file('img_bg')) {
            $file = $req->file('img_bg');
            $filename = date('YmdHi') . '_bg' . '_' . ConvertNumberToEnglish::Convert($file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $image = new Image;
            $image->path = $filename;
            $image->created_by_token_id = $userTokenId;
            $image->alt = $market->slug;
            $market->image()->save($image, ['detail' => 'bg', 'created_by_token_id' => $userTokenId]);
        }
        if ($req['name']) {
            foreach (json_decode($data['name'], true) as $key => $value) {
                $translate = new Translate;
                $translate->created_by_token_id = $userTokenId;
                $translate->name = 'marketplace_name';
                $translate->lang = $key;
                $translate->text = $value;
                $market->translate()->save($translate);
            }
        }

        if ($req['slogan']) {
            foreach (json_decode($data['slogan'], true) as $key => $value) {
                $slogan = new Translate;
                $slogan->created_by_token_id = $userTokenId;
                $slogan->name = 'marketplace_slogan';
                $slogan->lang = $key;
                $slogan->text = $value;
                $market->translate()->save($slogan);
            }
        }

        //     DB::commit();
        // } catch (\Exception$e) {
        //     DB::rollBack();
        //     // ذخیره در تیبل ارور ها

        //     return Response()->json([
        //         'status' => 400,
        //         'error' => ['not_find' => 'ارور ناشناخته'],
        //     ], 400);
        // }

        return Response()->json([
            'status' => 200,
            'data' => ['success' => 'ok'],
        ], 200);

    }
}

// باید بررسی بشه که اگ طول اسم عکس کمتر بود تایم یا رندوم ی نوشته به عنوان اسن فایل در نظر گرفته بشه
