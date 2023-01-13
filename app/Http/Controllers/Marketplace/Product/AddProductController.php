<?php

namespace App\Http\Controllers\Marketplace\Product;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Image;
use App\Models\Market\Marketplace;
use App\Models\Market\Product;
use App\Models\Other\ConvertNumberToEnglish;
use App\Models\Tags;
use App\Models\Translate;
use App\Rules\Marketplace\MarketplaceIsBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddProductController extends Controller
{
    public function index(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'page' => ['required', 'string', 'min:6', 'regex:/^([a-zA-Z0-9_]+)(\s[a-zA-Z0-9]+)*$/', 'exists:marketplaces,slug', new MarketplaceIsBlock],
            'name' => 'required | json',
            'slug' => ['required', 'string', 'min:6', 'regex:/^([a-zA-Z0-9_]+)(\s[a-zA-Z0-9]+)*$/', 'unique:products,slug'],
            'description' => 'json',
            'img_1' => 'image | max:250 | mimes:jpeg,jpg,png',
            'img_2' => 'image | max:250 | mimes:jpeg,jpg,png',
            'img_3' => 'image | max:250 | mimes:jpeg,jpg,png',
            'img_4' => 'image | max:250 | mimes:jpeg,jpg,png',
            'publish' => 'integer | between:0,2',
            "categories" => "array",
            "categories.*" => "string|distinct",
            "tags" => "array",
            "tags.*" => "string|distinct",
        ]);

        // publish =
        // 0 or empty = published
        // 1 = not published
        // 2 = Published later

        // $table->boolean('price')->nullable(); //کمکی
        // $table->boolean('discount')->nullable(); //کمکی
        // $table->integer('count')->nullable();
        // $table->timestamp('publish_start')->nullable();
        // $table->timestamp('publish_stop')->nullable();

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

        $Marke = Marketplace::whereSlug($data['page'])->first();

        $product = new Product;
        $product->slug = $data['slug'];
        if ($req['publish'] && $product->publish !== 0) {
            $product->publish = $data['publish'];
        }
        $product->created_by_token_id = $userTokenId;

        $Marke->product()->save($product);

        if ($req['categories']) {
            foreach ($data['categories'] as $value) {
                $category = Categories::whereName($value)->first();
                if (!$category) {
                    $category = new Categories;
                    $category->name = $value;
                }
                $product->categories()->save($category, ['created_by_token_id' => $userTokenId]);
            }
        }

        if ($req['tags']) {
            foreach ($data['tags'] as $value) {
                $tag = Tags::whereName($value)->first();
                if (!$tag) {
                    $tag = new Tags;
                    $tag->name = $value;
                }
                $product->tags()->save($tag, ['created_by_token_id' => $userTokenId]);
            }
        }

        if ($req['name']) {
            foreach (json_decode($data['name'], true) as $key => $value) {
                $translate = new Translate;
                $translate->created_by_token_id = $userTokenId;
                $translate->name = 'product_name';
                $translate->lang = $key;
                $translate->text = $value;
                $product->translate()->save($translate);
            }
        }

        if ($req['description']) {
            foreach (json_decode($data['description'], true) as $key => $value) {
                $translate = new Translate;
                $translate->created_by_token_id = $userTokenId;
                $translate->name = 'product_description';
                $translate->lang = $key;
                $translate->text = $value;
                $product->translate()->save($translate);
            }
        }

        if ($req->file('img_1')) {
            $file = $req->file('img_1');
            $filename = date('YmdHi') . '_1' . '_' . ConvertNumberToEnglish::Convert($file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $image = new Image;
            $image->path = $filename;
            $image->created_by_token_id = $userTokenId;
            $image->alt = $product->slug;
            $product->image()->save($image, ['created_by_token_id' => $userTokenId]);
        }

        if ($req->file('img_2')) {
            $file = $req->file('img_2');
            $filename = date('YmdHi') . '_2' . '_' . ConvertNumberToEnglish::Convert($file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $image = new Image;
            $image->path = $filename;
            $image->created_by_token_id = $userTokenId;
            $image->alt = $product->slug;
            $product->image()->save($image, ['created_by_token_id' => $userTokenId]);
        }

        if ($req->file('img_3')) {
            $file = $req->file('img_3');
            $filename = date('YmdHi') . '_3' . '_' . ConvertNumberToEnglish::Convert($file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $image = new Image;
            $image->path = $filename;
            $image->created_by_token_id = $userTokenId;
            $image->alt = $product->slug;
            $product->image()->save($image, ['created_by_token_id' => $userTokenId]);
        }

        if ($req->file('img_4')) {
            $file = $req->file('img_4');
            $filename = date('YmdHi') . '_4' . '_' . ConvertNumberToEnglish::Convert($file->getClientOriginalName());
            $file->move(public_path('images'), $filename);
            $image = new Image;
            $image->path = $filename;
            $image->created_by_token_id = $userTokenId;
            $image->alt = $product->slug;
            $product->image()->save($image, ['created_by_token_id' => $userTokenId]);
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
