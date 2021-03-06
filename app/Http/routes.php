<?php

$prefixedResourceNames = function($prefix) {
	return [
		'index'   => $prefix . '.index',
		'create'  => $prefix . '.create',
		'store'   => $prefix . '.store',
		'show'    => $prefix . '.show',
		'edit'    => $prefix . '.edit',
		'update'  => $prefix . '.update',
		'destroy' => $prefix . '.destroy'
	];
};

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/**
 * Protected
 */

Route::group(array('middleware' => 'auth'), function() use ($prefixedResourceNames)
{

	Route::group(array('prefix' => 'account'), function() use ($prefixedResourceNames) {

		/**
		 * Subscription Plans
		 */

		Route::get('subscription-plans/activate', [
			'as' =>'subscription-plans.activate',
			'uses' => 'SubscriptionPlansController@activate'
		]);

		Route::resource('subscription-plans', 'SubscriptionPlansController', ['names' => $prefixedResourceNames('subscription-plans'), 'only' => ['index', 'store']]);

		/**
		 * Settings
		 */

		Route::resource('settings', 'AccountSettingsController', ['names' => $prefixedResourceNames('account-settings'), 'only' => ['index', 'store']]);

	});


	/**
	 * Inventory Rules Section
	 */

	Route::group(array('prefix' => 'inventory-rules'), function() use ($prefixedResourceNames)
	{

		/**
		 * Product Rules
		 */

		Route::post('products/search', [
			'as'=>'products.search',
			'uses'=> 'ProductRulesController@search'
		]);

		Route::resource('products', 'ProductRulesController', ['names' => $prefixedResourceNames('products'), 'only' => ['index', 'store']]);

		/**
		 * Variant Rules
		 */

		Route::post('variants/search', [
			'as'=>'variants.search',
			'uses'=> 'VariantRulesController@search'
		]);

		Route::resource('variants', 'VariantRulesController', ['names' => $prefixedResourceNames('variants'), 'only' => ['index', 'store']]);

		Route::get('', [
				'as' =>'showInventoryRules',
				'uses' => 'InventorySettingsController@show'
		]);

		Route::post('global-limit/save', [
				'as'=>'saveGlobalLimit',
				'uses'=> 'InventorySettingsController@store'
		]);

		Route::post('',[
				'as'=>'searchInventoryRules',
				'uses'=> 'InventorySettingsController@search'
		]);

		Route::get('global', [
			'as'=>'global.index',
			'uses'=> 'GlobalRulesController@index'
		]);

		Route::get('variants', [
			'as'=>'variants.index',
			'uses'=> 'VariantRulesController@index'
		]);

		Route::post('variant/save', [
			'as'=>'saveVariantRule',
			'uses'=> 'InventorySettingsController@saveVariantRule'
		]);

		Route::get('variant/delete/{id}', [
			'as'=>'deleteVariantRule',
			'uses'=> 'InventorySettingsController@deleteVariantRule'
		]);

		Route::post('product/save', [
			'as'=>'saveProductRule',
			'uses'=> 'InventorySettingsController@saveProductRule'
		]);

		Route::get('product/delete/{id}', [
			'as'=>'deleteProductRule',
			'uses'=> 'InventorySettingsController@deleteProductRule'
		]);

	});

	/**
	 * Notifications Section
	 */

	Route::get('notifications', 'NotificationsController@show');

	Route::post('notifications/email', 'NotificationsController@addEmail');

	Route::delete('notifications/email/delete/{id}', [
		'as'=>'deleteEmail',
		'uses'=> 'NotificationsController@removeEmail'
	]);

	Route::post('notifications/webhook', [
		'as'=>'addWebhook',
		'uses'=> 'NotificationsController@addWebhook'
	]);

	Route::post('notifications/frequency/save', [
		'as'=>'saveFrequency',
		'uses'=> 'NotificationsController@saveFrequency'
	]);


	Route::delete('notifications/webhook/delete/{id}', [
		'as'=>'deleteWebhook',
		'uses'=> 'NotificationsController@removeWebhook'
	]);

});


/**
 * Shopify
 */

Route::get('settings/check', 'InventorySettingsController@check');

Route::get('shopify/shop', 'shopifyController@shop');

Route::get('shopify/product', 'shopifyController@product');

Route::get('shopify/oauth', 'shopifyController@oauth');

Route::get('shopify/toInstall', 'shopifyController@toInstall');


/**
 * Landing page
 */

Route::get('', 'LandingPageController@index');

/**
 * Other
 */

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::get('test', [
	'as'=>'test',
	'uses'=> 'TestingController@test'
]);

Route::get('home', 'HomeController@index');