<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Inventaris;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Lab API Routes
Route::group(['prefix' => 'lab'], function () {
    // Get inventaris by labor_id
    Route::get('/inventaris/{labor_id}', function ($labor_id) {
        $inventaris = Inventaris::where('labor_id', $labor_id)
            ->where('status', 'tersedia')
            ->where('jumlah', '>', 0)
            ->select('id', 'nama_inventaris', 'jenis', 'jumlah', 'kondisi', 'status')
            ->get();
            
        return response()->json($inventaris);
    });
});
