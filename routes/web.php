<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompostController;
use App\Http\Controllers\CompostEntryController;
use App\Http\Controllers\CompostProducerController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\PriceListCompostController;
use App\Http\Controllers\PriceListCropController;
use App\Http\Controllers\PriceListWasteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WasteLogController;
use App\Models\PriceListCrop;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::prefix('restaurant-owner')->group(function () {
    Route::get('/', [RestaurantController::class, 'index'])->name('restaurant.index');
    Route::get('/waste-report/restaurant-ownerID={restaurantOwnerID}', [WasteLogController::class, 'list'])->name('waste_log.list');
    Route::get('/create-waste-log', [WasteLogController::class, 'create'])->name('waste_log.create');
    Route::post('/create-waste-log', [WasteLogController::class, 'store'])->name('waste_log.store');
    Route::post('/prices/{id}', [PriceListWasteController::class, 'store'])->name('waste-log-price.store');
    Route::put('waste-logs/{id}', [WasteLogController::class, 'update'])->name('waste_log.update');

    Route::post("/sub-manage-resume", [RestaurantController::class, "subsManagementResume"])->name('restaurant.subsManageResume');
    Route::post("/sub-manage-pause", [RestaurantController::class, "subsManagementPause"])->name('restaurant.subsManagePause');
    Route::post('/restaurant/subscription/cancel', [RestaurantController::class, 'subsManageCancel'])->name('restaurant.subsManageCancel');

    Route::prefix('farmers')->name('farmers.')->group(function () {
        Route::get('/', [FarmerController::class, 'indexFarmer'])->name('index');
        Route::get('/farmerId={farmerId}', [FarmerController::class, 'showFarmer'])->name('show');
        Route::get('/farmerId={farmerId}/cropId={cropId}/details', [FarmerController::class, 'detailsFarmer'])->name('show-detail');
    });
    Route::post('/ROsubF', [SubscriptionController::class, 'storeROSubscribeFarmer'])->name('subscription.storeROSubscribeFarmer');


});

Route::prefix('account')->middleware(['auth', 'verified'])->group(function () {
    Route::get("/", [AccountController::class, 'index'])->name('account');
    Route::get("/point", [AccountController::class, 'point']);
    Route::post("/complete", [AccountController::class, 'complete'])->name('complete');
});

Route::get("/", [HomeController::class, 'index'])->name('home');

// Route::get("/market", [HomeController::class, 'market']);
Route::get("/aboutUs", [HomeController::class, 'aboutUS']);

Route::prefix("compost-producer")->middleware(['auth', 'verified'])->group(function () {
    Route::get("/", [CompostController::class, 'index'])->name('compost.home');
    Route::post("/schedule", [CompostController::class, 'schedule'])->name('addSchedule');
    Route::get('/create-compost', [CompostEntryController::class, 'create'])->name('compost.create');
    Route::post('/create-compost', [CompostEntryController::class, 'store'])->name('compost.store');
    Route::post("/sub-manage-resume", [CompostController::class, "subsManagementResume"])->name('compost.subsManageResume');
    Route::post("/sub-manage-pause", [CompostController::class, "subsManagementPause"])->name('compost.subsManagePause');
    Route::post('/subscription/cancel', [CompostController::class, 'subsManageCancel'])->name('compost.subsManageCancel');
    Route::post('/prices/{id}', [PriceListCompostController::class, 'store'])->name('compost-price.store');
    Route::put('composts/{id}', [CompostEntryController::class, 'update'])->name('compost.update');

    Route::get('composts', [CompostEntryController::class, 'index'])->name('compost.index');

    Route::prefix('resto-owners')->name('resto-owners.')->group(function () {
        Route::get('/', [WasteLogController::class, 'indexOwner'])->name('index');
        Route::get('/ownerID={ownerID}', [WasteLogController::class, 'showOwner'])->name('show');
        Route::get('/ownerID={ownerID}/wastelogID={wastelogID}/details', [WasteLogController::class, 'detailOwner'])->name('show-detail');
    });
    Route::post('/CPsubRO', [SubscriptionController::class, 'storeCPSubscribeRO'])->name('subscription.storeCPSubscribeRO');
});


Route::prefix("farmer")->middleware(['auth', 'verified'])->group(function () {
    Route::get("/", [FarmerController::class, 'index']);

    Route::get('/create-crop', [CropController::class, 'create'])->name('crop.create');
    Route::post('/create-corp', [CropController::class, 'store'])->name('crop.store');

    Route::resource('crops', CropController::class);
    Route::get('crops/{crop}/details', [CropController::class, 'show'])->name('crops.show');
    Route::get('crops/{id}/edit', [CropController::class, 'edit'])->name('crops.edit');
    Route::put('crops/{id}', [CropController::class, 'update'])->name('crops.update');

    Route::resource('orders', OrderController::class)->except(['create', 'store']);

    Route::get('orders/{crop}/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

    Route::post('/prices', [PriceListCropController::class, 'store'])->name('crop-prices.store');

    Route::post('/subscribe-to-producers', [FarmerController::class, 'subscribeToProducers'])->name('subscribe.to.producers');

    Route::prefix('composters')->name('composters.')->group(function () {
        Route::get('/', [CompostProducerController::class, 'index'])->name('index');
        Route::get('/composterId={composterId}', [CompostProducerController::class, 'show'])->name('show');
        Route::get('/composterId={composterId}/compostId={compostId}/details', [CompostProducerController::class, 'details'])->name('show-detail');
    });
    Route::post('/FsubCP', [SubscriptionController::class, 'storeFarmerSubscribeCompost'])->name('subscription.storeFarmerSubscribeCompost');
    Route::get('/points', [FarmerController::class, 'showPoints'])->name('farmer.points');

    Route::post("/sub-manage-resume", [FarmerController::class, "subsManagementResume"])->name('farmer.subsManageResume');
    Route::post("/sub-manage-pause", [FarmerController::class, "subsManagementPause"])->name('farmer.subsManagePause');
    Route::post('/subscription/cancel', [FarmerController::class, 'subsManageCancel'])->name('farmer.subsManageCancel');
});

