<?php
    use App\Http\Controllers\CardController;
    use Illuminate\Support\Facades\Route;

    Route::prefix('v1')->group(function(){
        Route::apiResource('cards',CardController::class);
    }
    );


?>