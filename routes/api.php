<?php 
    use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\RoomController;

    // test
    Route::get('/test', function() {
        return response()->json(['message' => 'API Working']);
    });

    // Auth
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout',[AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('/me',[AuthController::class, 'me']);
    Route::middleware('auth:sanctum')->group(function() {
        Route::apiResource('room', RoomController::class);
    });
?>