<?php

    use App\Http\Controllers\Api\Auth\AuthController;
    use App\Http\Controllers\Api\Classroom\ClassroomController;
    use App\Http\Controllers\Api\Configuration\ConfigurationController;
    use App\Http\Controllers\Api\Contact\ContactController;
    use App\Http\Controllers\Api\Dashboard\DashboardController;
    use App\Http\Controllers\Api\Level\LevelController;
    use App\Http\Controllers\Api\Payment\PaymentController;
    use App\Http\Controllers\Api\Receipt\ReceiptController;
    use App\Http\Controllers\Api\Registration\RegistrationController;
    use App\Http\Controllers\Api\Reminder\ReminderController;
    use App\Http\Controllers\Api\ScholarshipFeed\ScholarshipFeedController;
    use App\Http\Controllers\Api\Student\StudentController;
    use App\Http\Controllers\Api\StudentContact\StudentContactController;
    use App\Http\Controllers\Api\Year\YearController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');


    Route::post('/authenticate', AuthController::class);
    Route::get('/test', function () {
        return response()->json(['message' => 'success']);
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/verify-token', function (Request $request) {
            return response()->json(['message' => 'success']);
        });

        Route::prefix('student')->controller(StudentController::class)->group(function () {
            Route::get('all', 'all');
            Route::get('compact', 'compact');
            Route::get('not-registered', 'notRegistered');
            Route::put('{id}/update', 'update')->where(['id' => '[0-9]+']);
            Route::post('store', 'store');
        });

        Route::prefix('contact')->controller(ContactController::class)->group(function () {
            Route::get('get-all', 'getAll');
            Route::post('store', 'store');

            Route::prefix('{id}')->controller(ContactController::class)->group(function () {
                Route::put('update', 'update');
                Route::delete('delete', 'delete');
            });

            Route::prefix('{studentId}')->group(function () {
                Route::get('get-all-by-student', 'getAllByStudent');
                Route::get('get-all-without-student', 'getAllWithoutStudent');
            })->where(['studentId' => '[0-9]+']);
        });

        Route::prefix('student-contact')->controller(StudentContactController::class)->group(function () {
            Route::post('store', 'store');
            Route::post('delete', 'delete');
        });

        Route::prefix('year')->controller(YearController::class)->group(function () {
            Route::get('all', 'all');
            Route::get('first', 'first');
            Route::post('store', 'store');

            Route::prefix('{id}')->group(function () {
                Route::get('one', 'one');
                Route::put('update', 'update');
                Route::delete('delete', 'delete');
            });
        });

        Route::prefix('classroom')->controller(ClassroomController::class)->group(function () {
            Route::get('all', 'all');
            Route::get('{levelId}/all', 'allByLevel')->where(['levelId' => '[0-9]+']);
            Route::post('store', 'store');
            Route::get('not-in-scholarship-feed', 'notInScholarshipFeed');

            Route::prefix('{id}')->group(function () {

                Route::get('get', 'get');
                Route::put('update', 'update');

            })->where(['id' => '[0-9]+']);

        });

        Route::prefix('level')->controller(LevelController::class)->group(function () {
            Route::get('all', 'all');
            Route::post('store', 'store');

            Route::prefix('{id}')->group(function () {

                Route::get('get', 'get');
                Route::put('update', 'update');
                Route::put('delete', 'delete');

            })->where(['id' => '[0-9]+']);
        });

        Route::prefix('scholarship-feed')->controller(ScholarshipFeedController::class)->group(function () {
            Route::get('all', 'all');
            Route::post('store', 'store');

            Route::prefix('{id}')->group(function () {
                Route::put('update', 'update');
                Route::delete('delete', 'delete');
                Route::get('show', 'show');
            })->where(['id' => '[0-9]+']);
        });

        Route::prefix('configuration')->controller(ConfigurationController::class)->group(function () {
            Route::get('/one', 'one');
            Route::post('/store', 'store');

            Route::prefix('{id}')->group(function () {
                Route::put('update', 'update');
                Route::delete('delete', 'delete');
            })->where(['id' => '[0-9]+']);
        });

        Route::prefix('registration')->controller(RegistrationController::class)->group(function () {
            Route::get('all', 'all');
            Route::get('configuration', 'configuration');
            Route::get('verify', 'verify');
            Route::post('store', 'store');

            Route::prefix('{id}')->group(function () {
                Route::get('get-information', 'getInformation');
                Route::put('update', 'update');
                Route::delete('delete', 'delete');
            })->where(['id' => '[0-9]+']);
        });

        Route::prefix('payment')->controller(PaymentController::class)->group(function () {
            Route::get('all', 'getAll');

            Route::prefix('{id}')->group(function () {
                Route::delete('reset', 'reset');
            })->where(['id' => '[0-9]+']);
        });

        Route::prefix('receipt')->controller(ReceiptController::class)->group(function () {
            Route::post('store', 'store');
            Route::get('all', 'all');
            Route::delete('{id}/delete', 'delete');
        });

        Route::prefix('reminder')->controller(ReminderController::class)->group(function () {
            Route::get('{value}/part', 'part')->where(['value' => '[0-9]+']);
        });

        Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
            Route::get('registration-summary', 'registrationSummary');
            Route::get('billing-summary', 'billingSummary');
        });
    });
