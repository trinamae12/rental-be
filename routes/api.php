<?php 
    use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\AuthController;

    // test
    Route::get('/test', function() {
        return response()->json(['message' => 'API Working']);
    });

    // Auth
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout',[AuthController::class, 'logout']);
?>