<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Market\Marketplace;
use App\Models\Other\ConvertNumberToEnglish;
use App\Models\Translate;
use App\Rules\Marketplace\MarketplaceIsBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShowMarketplaceController extends Controller
{
    public function index(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'page' => ['required', 'string', 'min:6', 'regex:/^([a-zA-Z0-9_]+)(\s[a-zA-Z]+)*$/', 'exists:marketplaces,slug', new MarketplaceIsBlock],
        ]);
        if ($validate->fails()) {
            return Response()->json([
                'status' => 400,
                'error' => $validate->errors()
            ], 400);
        }

        $page = ConvertNumberToEnglish::Convert($req['page']);
        // id اجباری است
        $dataMarket = Marketplace::whereSlug($page)->first(['id', 'name', 'slug', 'slogan', 'img_brand', 'img_abl', 'img_bg']);
        $translateMarket = Translate::sortTranslate($dataMarket, ['name', 'slogan']);

        $response = [
            'name' => Translate::checkInTranslate('name', $translateMarket),
            'slug' => $dataMarket['slug'],
            'slogan' => Translate::checkInTranslate('slogan', $translateMarket),
            'img_brand' => $dataMarket['img_brand'],
            'img_abl' => $dataMarket['img_abl'],
            'img_bg' => $dataMarket['img_bg']
        ];
        $response['translate'] = $translateMarket;

        return Response()->json([
            'status' => 200,
            'data' => $response
        ], 200);
    }
}