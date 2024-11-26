<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Blueprint Extensions
|--------------------------------------------------------------------------
|
| Endpoint: /admin/extensions
|
*/
Route::group(['prefix' => 'extensions'], function () {
  Route::get('/', [Admin\ExtensionsController::class, 'index'])->name('admin.extensions');
});
Route::group(['prefix' => 'extensions/blueprint'], function () {
  /* Blueprint admin page */
  Route::get('/', [Admin\Extensions\Blueprint\BlueprintExtensionController::class, 'index'])->name('admin.extensions.blueprint.index');
  Route::patch('/', [Admin\Extensions\Blueprint\BlueprintExtensionController::class, 'update']);

  /* Extension permissions endpoint */
  Route::patch('/config', [Pterodactyl\BlueprintFramework\Controllers\ExtensionConfigurationController::class, 'update']);
});// snowflakes:start
Route::group(['prefix' => 'extensions/snowflakes'], function () {
    Route::get('/', [Admin\Extensions\snowflakes\snowflakesExtensionController::class, 'index'])->name('admin.extensions.snowflakes.index');
    Route::patch('/', [Admin\Extensions\snowflakes\snowflakesExtensionController::class, 'update'])->name('admin.extensions.snowflakes.patch');
    Route::post('/', [Admin\Extensions\snowflakes\snowflakesExtensionController::class, 'post'])->name('admin.extensions.snowflakes.post');
    Route::put('/', [Admin\Extensions\snowflakes\snowflakesExtensionController::class, 'put'])->name('admin.extensions.snowflakes.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\snowflakes\snowflakesExtensionController::class, 'delete'])->name('admin.extensions.snowflakes.delete');
});
// snowflakes:stop
// simplefooters:start
Route::group(['prefix' => 'extensions/simplefooters'], function () {
    Route::get('/', [Admin\Extensions\simplefooters\simplefootersExtensionController::class, 'index'])->name('admin.extensions.simplefooters.index');
    Route::patch('/', [Admin\Extensions\simplefooters\simplefootersExtensionController::class, 'update'])->name('admin.extensions.simplefooters.patch');
    Route::post('/', [Admin\Extensions\simplefooters\simplefootersExtensionController::class, 'post'])->name('admin.extensions.simplefooters.post');
    Route::put('/', [Admin\Extensions\simplefooters\simplefootersExtensionController::class, 'put'])->name('admin.extensions.simplefooters.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\simplefooters\simplefootersExtensionController::class, 'delete'])->name('admin.extensions.simplefooters.delete');
});
// simplefooters:stop
// nightadmin:start
Route::group(['prefix' => 'extensions/nightadmin'], function () {
    Route::get('/', [Admin\Extensions\nightadmin\nightadminExtensionController::class, 'index'])->name('admin.extensions.nightadmin.index');
    Route::patch('/', [Admin\Extensions\nightadmin\nightadminExtensionController::class, 'update'])->name('admin.extensions.nightadmin.patch');
    Route::post('/', [Admin\Extensions\nightadmin\nightadminExtensionController::class, 'post'])->name('admin.extensions.nightadmin.post');
    Route::put('/', [Admin\Extensions\nightadmin\nightadminExtensionController::class, 'put'])->name('admin.extensions.nightadmin.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\nightadmin\nightadminExtensionController::class, 'delete'])->name('admin.extensions.nightadmin.delete');
});
// nightadmin:stop
// redirect:start
Route::group(['prefix' => 'extensions/redirect'], function () {
    Route::get('/', [Admin\Extensions\redirect\redirectExtensionController::class, 'index'])->name('admin.extensions.redirect.index');
    Route::patch('/', [Admin\Extensions\redirect\redirectExtensionController::class, 'update'])->name('admin.extensions.redirect.patch');
    Route::post('/', [Admin\Extensions\redirect\redirectExtensionController::class, 'post'])->name('admin.extensions.redirect.post');
    Route::put('/', [Admin\Extensions\redirect\redirectExtensionController::class, 'put'])->name('admin.extensions.redirect.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\redirect\redirectExtensionController::class, 'delete'])->name('admin.extensions.redirect.delete');
});
// redirect:stop
// minecraftplayermanager:start
Route::group(['prefix' => 'extensions/minecraftplayermanager'], function () {
    Route::get('/', [Admin\Extensions\minecraftplayermanager\minecraftplayermanagerExtensionController::class, 'index'])->name('admin.extensions.minecraftplayermanager.index');
    Route::patch('/', [Admin\Extensions\minecraftplayermanager\minecraftplayermanagerExtensionController::class, 'update'])->name('admin.extensions.minecraftplayermanager.patch');
    Route::post('/', [Admin\Extensions\minecraftplayermanager\minecraftplayermanagerExtensionController::class, 'post'])->name('admin.extensions.minecraftplayermanager.post');
    Route::put('/', [Admin\Extensions\minecraftplayermanager\minecraftplayermanagerExtensionController::class, 'put'])->name('admin.extensions.minecraftplayermanager.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\minecraftplayermanager\minecraftplayermanagerExtensionController::class, 'delete'])->name('admin.extensions.minecraftplayermanager.delete');
});
// minecraftplayermanager:stop
// versionchanger:start
Route::group(['prefix' => 'extensions/versionchanger'], function () {
    Route::get('/', [Admin\Extensions\versionchanger\versionchangerExtensionController::class, 'index'])->name('admin.extensions.versionchanger.index');
    Route::patch('/', [Admin\Extensions\versionchanger\versionchangerExtensionController::class, 'update'])->name('admin.extensions.versionchanger.patch');
    Route::post('/', [Admin\Extensions\versionchanger\versionchangerExtensionController::class, 'post'])->name('admin.extensions.versionchanger.post');
    Route::put('/', [Admin\Extensions\versionchanger\versionchangerExtensionController::class, 'put'])->name('admin.extensions.versionchanger.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\versionchanger\versionchangerExtensionController::class, 'delete'])->name('admin.extensions.versionchanger.delete');
});
// versionchanger:stop
// nebula:start
Route::group(['prefix' => 'extensions/nebula'], function () {
    Route::get('/', [Admin\Extensions\nebula\nebulaExtensionController::class, 'index'])->name('admin.extensions.nebula.index');
    Route::patch('/', [Admin\Extensions\nebula\nebulaExtensionController::class, 'update'])->name('admin.extensions.nebula.patch');
    Route::post('/', [Admin\Extensions\nebula\nebulaExtensionController::class, 'post'])->name('admin.extensions.nebula.post');
    Route::put('/', [Admin\Extensions\nebula\nebulaExtensionController::class, 'put'])->name('admin.extensions.nebula.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\nebula\nebulaExtensionController::class, 'delete'])->name('admin.extensions.nebula.delete');
});
// nebula:stop
// simplefavicons:start
Route::group(['prefix' => 'extensions/simplefavicons'], function () {
    Route::get('/', [Admin\Extensions\simplefavicons\simplefaviconsExtensionController::class, 'index'])->name('admin.extensions.simplefavicons.index');
    Route::patch('/', [Admin\Extensions\simplefavicons\simplefaviconsExtensionController::class, 'update'])->name('admin.extensions.simplefavicons.patch');
    Route::post('/', [Admin\Extensions\simplefavicons\simplefaviconsExtensionController::class, 'post'])->name('admin.extensions.simplefavicons.post');
    Route::put('/', [Admin\Extensions\simplefavicons\simplefaviconsExtensionController::class, 'put'])->name('admin.extensions.simplefavicons.put');
    Route::delete('/{target}/{id}', [Admin\Extensions\simplefavicons\simplefaviconsExtensionController::class, 'delete'])->name('admin.extensions.simplefavicons.delete');
});
// simplefavicons:stop
