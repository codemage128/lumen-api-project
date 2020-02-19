<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\ProductMaster;

$router->get('/', function () {
    return view('home');
});

$router->get('/export', 'ExportController@exportCSV');
$router->get('/data/dropbox', 'DropboxController@getImageFile');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->get('keyword/list', 'KeywordController@list');
    $router->get('keyword/all', 'KeywordController@all');
    $router->post('keyword/create', 'KeywordController@create');
    $router->post('keyword/update/{id}', 'KeywordController@update');
    $router->post('keyword/delete/{id}', 'KeywordController@delete');

    $router->get('category/list', 'CategoryController@list');
    $router->get('category/all', 'CategoryController@all');
    $router->post('category/create', 'CategoryController@create');
    $router->post('category/update/{id}', 'CategoryController@update');
    $router->post('category/delete/{id}', 'CategoryController@delete');

    $router->get('artist/list', 'ArtistController@list');
    $router->get('artist/all', 'ArtistController@all');
    $router->post('artist/create', 'ArtistController@create');
    $router->post('artist/update/{id}', 'ArtistController@update');
    $router->post('artist/delete/{id}', 'ArtistController@delete');

    $router->post('variant/colors', "VariantController@colorList");
    $router->post('variant/types', "VariantController@type  List");
    $router->post('variant/sizes', "VariantController@sizeList");
    $router->post('variant/types', "VariantController@typeList");
    $router->post('variant/create', "VariantController@create");
    $router->post('variant/update/{id}', "VariantController@update");
    $router->post('variant/delete/{id}', "VariantController@delete");
    $router->post('variant/images/update', 'VariantController@updateImages');
    $router->get('variant/images', "VariantController@imageList");
    $router->post('variant/merchant-prices/update', 'VariantController@updateMerchantPrices');
    $router->get('variant/merchant-prices', "VariantController@merchantPrices");
    $router->get('variant/prices', "VariantController@prices");
    $router->post('variant/addPrice', "VariantController@addPrice");
    $router->post('variant/deletePrice/{id}', "VariantController@deletePrice");
    $router->post('variant/defaultPrice/{id}', "VariantController@defaultPrice");

    $router->get('tshirtvariant/list', 'VariantController@list_tshirtsList');
    $router->get('mugvariant/list', 'VariantController@list_mugsList');
    $router->get('hoodyvariant/list', 'VariantController@list_hoodiesList');
    $router->get('toteBagvariant/list', 'VariantController@list_totebagsList');
    $router->get('kidvariant/list', 'VariantController@list_kidsList');
    $router->get('cushioncoversVariant/list', 'VariantController@list_cushioncoversList');

    $router->post('setting/update', 'SettingController@update');
    $router->post('setting/get', 'SettingController@get');

    $router->get('product/list', 'ProductController@list');
    $router->get('product/detail', 'ProductController@detail');
    $router->post('product/create', 'ProductController@create');
    $router->post('product/deleteProduct', 'ProductController@delete');
    $router->post('product/allProductInfo', 'ProductController@allProductInfo');
    $router->post('product/price', 'ProductController@priceList');
    $router->post('product/artist', 'ProductController@artistList');
    $router->post('product/stickertype', 'ProductController@stickertypeList');
    $router->post('product/printmode', 'ProductController@printmodeList');
    $router->get('product/imagesets', "ProductController@imageSetList");
    $router->post('product/imagesets/update', "ProductController@updateImageSet");

});
