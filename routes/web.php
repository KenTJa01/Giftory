<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpendingController;
use App\Http\Controllers\ExpendingDetailController;
use App\Http\Controllers\ExpendingHeaderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceivingDetailController;
use App\Http\Controllers\ReceivingHeaderController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\ReceivingHeader;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class,'index'])->name("login");
Route::post('/', [LoginController::class,'authenticate']);

Route::group(['middleware' => ['auth']], function() {

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // ===== DATA MASTER ROUTE ===============================

    Route::get('/master-profile',[ProfileController::class, 'index'])->name('master-profile');
    Route::post('/get-profile-list-datatable',[ProfileController::class, 'getProfileListDatatable'])->name('get-profile-list-datatable');
    Route::get('/get-old-data-profile-edit',[ProfileController::class, 'getOldDataProfileEdit'])->name('get-old-data-profile-edit');

    Route::get('/get-profile-location',[ProfileController::class, 'getProfileLocation'])->name('get-profile-location');
    Route::get('/get-location-by-id',[ProfileController::class, 'getLocationById'])->name('get-location-by-id');
    Route::post('/post-profile-req-submit',[ProfileController::class, 'postProfileReqSubmit'])->name('post-profile-req-submit');
    Route::post('/post-profile-update-submit',[ProfileController::class, 'postProfileUpdateSubmit'])->name('post-profile-update-submit');

    Route::get('/master-site',[SiteController::class, 'index'])->name('master-site');

    Route::get('/master-location',[LocationController::class, 'index'])->name('master-location');
    Route::post('/get-location-list-datatable',[LocationController::class, 'getLocationListDatatable'])->name('get-location-list-datatable');
    Route::post('/post-location-req-submit',[LocationController::class, 'postLocationReqSubmit'])->name('post-location-req-submit');
    Route::get('/get-old-data-location-edit',[LocationController::class, 'getOldDataLocationEdit'])->name('get-old-data-location-edit');
    Route::post('/post-location-update-submit',[LocationController::class, 'postLocationUpdateSubmit'])->name('post-location-update-submit');


    // ===== MASTER PRODUCT CATEGORY ============================================================================================================================
    Route::get('/master-product-category',[ProductCategoryController::class, 'index'])->name('master-product-category');
    Route::post('/get-product-list-datatable', [ProductCategoryController::class, 'getProductListDatatable'])->name('get-product-list-datatable');
    Route::get('/get-old-data-product-edit',[ProductCategoryController::class, 'getOldDataProduct'])->name('get-old-data-product-edit');
    Route::post('/post-product-req-submit',[ProductCategoryController::class, 'postProductReqSubmit'])->name('post-product-req-submit');
    Route::post('/post-product-req-submit-edit',[ProductCategoryController::class, 'postProductReqEdit'])->name('post-product-req-submit-edit');


    // ===== MASTER UNIT ========================================================================================================================================
    Route::get('/master-unit',[UnitController::class, 'index'])->name('master-unit');
    Route::post('/create-unit',[UnitController::class, 'store']);
    Route::post('/edit-unit',[UnitController::class, 'update']);
    Route::post('/delete-unit',[UnitController::class, 'destroy']);

    Route::get('/master-supplier',[SupplierController::class, 'index'])->name('master-supplier');
    Route::post('/get-supplier-list-datatable', [SupplierController::class, 'getSupplierListDatatable'])->name('get-supplier-list-datatable');
    Route::post('/post-supplier-req-submit',[SupplierController::class, 'postSupplierReqSubmit'])->name('post-supplier-req-submit');
    Route::get('/get-old-data-supplier-edit',[SupplierController::class, 'getOldDataSupplier'])->name('get-old-data-supplier-edit');
    Route::post('/post-supplier-req-submit-edit',[SupplierController::class, 'postSupplierReqEdit'])->name('post-supplier-req-submit-edit');

    Route::get('/master-user',[UserController::class, 'index'])->name('master-user');

    Route::get('/change-password', function () {
        return view('change-password');
    })->name('change-password');

    Route::post('/post-change-pass-submit',[UserController::class, 'postChangePw'])->name('post-change-pass-submit');
    Route::get('/get-sidebar-menu',[UserController::class, 'getMenu'])->name('get-sidebar-menu');

    // Route::post('/create-user',[UserController::class, 'store']);
    // Route::post('/edit-user',[UserController::class, 'update']);
    // Route::post('/delete-user',[UserController::class, 'destroy'])

    Route::get('/get-all-site-master-user', [UserController::class, 'getAllSite'])->name('get-all-site-master-user');
    Route::get('/get-all-site-master-user-edit', [UserController::class, 'getAllSiteEdit'])->name('get-all-site-master-user-edit');
    Route::get('/get-all-profile-master-user', [UserController::class, 'getAllProfile'])->name('get-all-profile-master-user');
    Route::get('/get-old-data-user-edit', [UserController::class, 'getOldDataUser'])->name('get-old-data-user-edit');
    Route::post('/post-user-req-submit', [UserController::class, 'postUserSubmit'])->name('post-user-req-submit');
    Route::post('/post-user-req-submit-edit', [UserController::class, 'postUserSubmitEdit'])->name('post-user-req-submit-edit');
    Route::post('/post-user-req-reset-pw', [UserController::class, 'postUserResetPw'])->name('post-user-req-reset-pw');
    Route::post('/get-user-list-datatable', [UserController::class, 'getUserListDatatable'])->name('get-user-list-datatable');

    // Route::resource('/master-user-ajax', UserController::class);

    /** Route for master data */
    Route::get('/get-all-user-site-permission', [MasterDataController::class, 'getAllUserSitePermission'])->name('get-all-user-site-permission');
    Route::get('/get-list-sites-datatable', [MasterDataController::class, 'getListSitesDatatable'])->name('get-list-sites-datatable');
    Route::get('/get-list-status-for-rec', [MasterDataController::class, 'getListStatusForRec'])->name('get-list-status-for-rec');


    Route::get('/get-list-status-for-trf', [MasterDataController::class, 'getListStatusForTrf'])->name('get-list-status-for-trf');
    Route::get('/get-list-locations', [MasterDataController::class, 'getListLocations'])->name('get-list-locations');
    Route::get('/get-list-mov-type', [MasterDataController::class, 'getListMovType'])->name('get-list-mov-type');
    Route::get('/get-list-status-for-stock-opname', [MasterDataController::class, 'getListStatusForStockOpname'])->name('get-list-status-for-stock-opname');

    // ===== RECEIVING ROUTE =================================

    Route::get('/form-receiving', [ReceivingHeaderController::class, 'showdata'])->name('form-receiving');

    Route::get('/get-rcv-transfer-list', [ReceivingHeaderController::class, 'getTransferData'])->name('get-rcv-transfer-list');
    Route::get('/get-rcv-product-list', [ReceivingHeaderController::class, 'getProductData'])->name('get-rcv-product-list');
    Route::get('/get-rcv-supplier-list', [ReceivingHeaderController::class, 'getSupplierData'])->name('get-rcv-supplier-list');
    Route::get('/get-rcv-transfer-detail', [ReceivingHeaderController::class, 'getTransferDetail'])->name('get-rcv-transfer-detail');
    Route::get('/get-rcv-location', [ReceivingHeaderController::class, 'getLocation'])->name('get-rcv-location');

    Route::post('/post-rec-req-submit-trans', [ReceivingHeaderController::class, 'postReceivingReqSubmitTrans'])->name('post-rec-req-submit-trans');
    Route::post('/post-rec-req-submit-supp', [ReceivingHeaderController::class, 'postReceivingReqSubmitSupp'])->name('post-rec-req-submit-supp');

    Route::post('/get-rec-list-datatable', [ReceivingHeaderController::class, 'getRecListDatatable'])->name('get-rec-list-datatable');

    // Export Excel List
    Route::get('/export-excel-list-receiving', [ReceivingHeaderController::class, 'exportExcelListReceiving'])->name('export-excel-list-receiving');
    Route::get('/export-excel-list-expending', [ExpendingController::class, 'exportExcelListExpending'])->name('export-excel-list-expending');
    Route::get('/export-excel-list-transfer', [TransferController::class, 'exportExcelListTransfer'])->name('export-excel-list-transfer');
    Route::get('/export-excel-list-stock-opname', [StockOpnameController::class, 'exportExcelListStockOpname'])->name('export-excel-list-stock-opname');
    Route::get('/export-excel-list-adjustment', [AdjustmentController::class, 'exportExcelListAdjustment'])->name('export-excel-list-adjustment');
    Route::get('/export-excel-list-return', [ReturnController::class, 'exportExcelListReturn'])->name('export-excel-list-return');
    Route::get('/export-excel-list-stock', [StockController::class, 'exportExcelListStock'])->name('export-excel-list-stock');

    // SEMENTARA BUAT YANG RECEIVING LIST
    Route::resource('/receiving-ajax', ReceivingHeaderController::class);

    Route::get('/view-receiving/{id}', [ReceivingHeaderController::class, 'viewReceivingPage'])->name('view-receiving');

    // Route::middleware(['trf_req_check'])->group(function () {
    //     Route::get('/view-receiving/{id}', [ReceivingHead\er::class, 'viewReceivingPage'])->name('view-receiving');
    // });

    Route::get('/list-receiving', [ReceivingHeaderController::class, 'listReceivingPage'])->name('list-receiving');

    // Route::get('/list-receiving', function () {
    //     return view('list-receiving');
    // })->name('list-receiving');

    // Route::get('/edit-receiving', function () {      // REMOVE
    //     return view('edit-receiving');
    // });

    Route::get('/view-receiving', function () {
        return view('view-receiving');
    });

    // ===== EXPENDINGS ROUTE =================================

    // Route::get('/form-expending',[ExpendingHeaderController::class, 'show_data']);
    Route::resource('/expending-detail-ajax', ExpendingDetailController::class);

    Route::get('/get-all-user-site-expending', [ExpendingHeaderController::class, 'index']);

    // Route::get('/edit-receiving', function () {      // REMOVE
    //     return view('edit-receiving');
    // });

    // Route::get('/view-expending', function () {
    //     return view('view-expending');
    // });

    // Route::get('/approve-expending', function () {
    //     return view('approve-expending');
    // });


    // Route for expending request
    Route::get('/form-expending', [ExpendingController::class, 'formExpendingPage'])->name('form-expending');
    // Route::get('/get-all-exp-list', [ExpendingController::class, 'getAllExpList'])->name('get-all-exp-list');
    Route::get('/get-exp-product-list', [ExpendingController::class, 'getExpProductList'])->name('get-exp-product-list');
    Route::get('/get-exp-product-location-list', [ExpendingController::class, 'getExpProductLocationList'])->name('get-exp-product-location-list');
    Route::get('/get-exp-stock-qty', [ExpendingController::class, 'getExpStockQty'])->name('get-exp-stock-qty');
    Route::post('/post-exp-req-submit', [ExpendingController::class, 'getExpReqSubmit'])->name('post-exp-req-submit');

    // Route for expending list
    Route::get('/list-expending', [ExpendingController::class, 'listExpendingPage'])->name('list-expending');
    Route::post('/get-exp-list-datatable', [ExpendingController::class, 'getExpListDatatable'])->name('get-exp-list-datatable');

    Route::middleware(['exp_req_check'])->group(function () {
        Route::get('/view-expending/{id}', [ExpendingController::class, 'viewExpendingPage'])->name('view-expending');
    });

    Route::middleware(['exp_req_approve_check'])->group(function () {
        Route::get('/approve-expending/{id}', [ExpendingController::class, 'approveExpendingPage'])->name('approve-expending');
    });


    Route::post('/post-exp-req-approve', [ExpendingController::class, 'postExpReqApprove'])->name('post-exp-req-approve');
    Route::post('/post-exp-req-reject', [ExpendingController::class, 'postExpReqReject'])->name('post-exp-req-reject');

    // Route for stock list and Stock movement
    Route::get('/list-stock', [StockController::class, 'listStock'])->name('list-stock');
    Route::get('/get-product-list-filter', [StockController::class, 'getProductListFilter'])->name('get-product-list-filter');
    Route::post('/get-stock-list-datatable', [StockController::class, 'getStockListDatatable'])->name('get-stock-list-datatable');

    Route::get('/movement-stock', [StockController::class, 'listStockMovement'])->name('movement-stock');
    Route::get('/get-product-list-filter-stock-mov', [StockController::class, 'getProductListFilterStockMov'])->name('get-product-list-filter-stock-mov');
    Route::post('/get-stock-mov-list-datatable', [StockController::class, 'getStockMovementListDatatable'])->name('get-stock-mov-list-datatable');

    // ===== ADJUSTMENTS ROUTE =================================

    Route::get('/form-adjustments', [AdjustmentController::class, 'formAdjustmentPage'])->name('form-adjustments');
    Route::get('/list-adjustments', [AdjustmentController::class, 'listAdjustmentPage'])->name('list-adjustments');

    Route::get('/view-adjustments', function () {
        return view('view-adjustments');
    });


    Route::get('/get-adj-product-list', [AdjustmentController::class, 'getProductData'])->name('get-adj-product-list');
    Route::get('/get-adj-location', [AdjustmentController::class, 'getLocation'])->name('get-adj-location');
    Route::get('/get-adj-reason', [AdjustmentController::class, 'getReason'])->name('get-adj-reason');
    Route::get('/get-adj-qty-unit-list', [AdjustmentController::class, 'getRecQtyUnit'])->name('get-adj-qty-unit-list');
    Route::get('/get-adj-update-qty-list', [AdjustmentController::class, 'getRecUpdQty'])->name('get-adj-update-qty-list');
    Route::post('/post-adj-req-submit', [AdjustmentController::class, 'postAdjReqSubmit'])->name('post-adj-req-submit');

    Route::post('/get-adj-list-datatable', [AdjustmentController::class, 'getAdjListTable'])->name('get-adj-list-datatable');
    Route::get('/view-adjustment/{id}', [AdjustmentController::class, 'viewAdjustmentPage'])->name('view-adjustment');

    // ===== TRANSFER ROUTE ==================================

    Route::get('/form-transfer', function () {
        return view('form-transfer');
    });

    /** Route for transfer request */
    Route::get('/form-transfer', [TransferController::class, 'formTransferPage'])->name('form-transfer');
    Route::get('/get-trf-site-to-list', [TransferController::class, 'getTrfSiteToList'])->name('get-trf-site-to-list');
    Route::get('/get-trf-product-list', [TransferController::class, 'getTrfProductList'])->name('get-trf-product-list');
    Route::get('/get-trf-product-location-list', [TransferController::class, 'getTrfProductLocationList'])->name('get-trf-product-location-list');
    Route::get('/get-trf-stock-qty', [TransferController::class, 'getTrfStockQty'])->name('get-trf-stock-qty');
    Route::post('/post-trf-req-submit', [TransferController::class, 'postTrfReqSubmit'])->name('post-trf-req-submit');

    /** Route for transfer list */
    Route::get('/list-transfer', [TransferController::class, 'listTransferPage'])->name('list-transfer');
    Route::post('/get-trf-list-datatable', [TransferController::class, 'getTrfListDatatable'])->name('get-trf-list-datatable');

    Route::middleware(['trf_req_check'])->group(function () {
        Route::get('/view-transfer/{id}', [TransferController::class, 'viewTransferPage'])->name('view-transfer');
    });

    Route::middleware(['trf_req_approve_check'])->group(function () {
        Route::get('/approve-transfer/{id}', [TransferController::class, 'approveTransferPage'])->name('approve-transfer');
    });

    Route::post('/post-trf-req-approve', [TransferController::class, 'postTrfReqApprove'])->name('post-trf-req-approve');
    Route::post('/post-trf-req-reject', [TransferController::class, 'postTrfReqReject'])->name('post-trf-req-reject');

    Route::middleware(['trf_doc_check'])->group(function () {
        Route::get('/document-trf/{id}', [TransferController::class, 'documentTrfPage'])->name('document-trf');
    });


    Route::post('/logout', [LoginController::class,'logout']);

    // ================== STOCK OPNAME ROUTE ==================
    /** Route for stock opname */
    Route::get('/list-stock-opname', [StockOpnameController::class, 'listStockOpnamePage'])->name('list-stock-opname');
    Route::post('/get-stock-opname-list-datatable', [StockOpnameController::class, 'getStockOpnameListDatatable'])->name('get-stock-opname-list-datatable');

    Route::middleware(['stock_opname_freeze_check'])->group(function () {
        Route::get('/freeze-stock-opname/{id}', [StockOpnameController::class, 'freezeStockOpnamePage'])->name('freeze-stock-opname');
    });

    Route::middleware(['stock_opname_input_check'])->group(function () {
        Route::get('/input-stock-opname/{id}', [StockOpnameController::class, 'inputStockOpnamePage'])->name('input-stock-opname');
    });

    Route::middleware(['stock_opname_check'])->group(function () {
        Route::get('/view-stock-opname/{id}', [StockOpnameController::class, 'viewStockOpnamePage'])->name('view-stock-opname');
        Route::get('/document-stock-opname/{id}', [StockOpnameController::class, 'documentStockOpnamePage'])->name('document-stock-opname');
    });

    Route::get('/form-stock-opname', [StockOpnameController::class, 'formStockOpnamePage'])->name('form-stock-opname');
    Route::get('/get-stock-opname-product-location-list', [StockOpnameController::class, 'getStockOpnameProductLocationList'])->name('get-stock-opname-product-location-list');
    Route::get('/get-stock-opname-product-list', [StockOpnameController::class, 'getStockOpnameProductList'])->name('get-stock-opname-product-list');
    Route::post('/post-stock-opname-partial-submit', [StockOpnameController::class, 'postStockOpnamePartialSubmit'])->name('post-stock-opname-partial-submit');
    Route::post('/post-stock-opname-full-submit', [StockOpnameController::class, 'postStockOpnameFullSubmit'])->name('post-stock-opname-full-submit');
    Route::post('/post-stock-opname-freeze-stock', [StockOpnameController::class, 'postStockOpnameFreezeStock'])->name('post-stock-opname-freeze-stock');
    Route::post('/post-stock-opname-cancel', [StockOpnameController::class, 'postStockOpnameCancel'])->name('post-stock-opname-cancel');
    Route::post('/post-stock-opname-input-stock', [StockOpnameController::class, 'postStockOpnameInputStock'])->name('post-stock-opname-input-stock');
    Route::post('/post-stock-opname-process-data', [StockOpnameController::class, 'postStockOpnameProcessData'])->name('post-stock-opname-process-data');

    // ================== DASHBOARD ROUTE ==================
    Route::get('/get-exp-pending-approve-list', [DashboardController::class, 'getExpPendingApproveList'])->name('get-exp-pending-approve-list');
    Route::get('/get-trf-pending-approve-list', [DashboardController::class, 'getTrfPendingApproveList'])->name('get-trf-pending-approve-list');
    Route::get('/get-trf-approve-list', [DashboardController::class, 'getTrfApproveList'])->name('get-trf-approve-list');
    Route::get('/get-ret-pending-approve-list', [DashboardController::class, 'getRetPendingApproveList'])->name('get-ret-pending-approve-list');


    // RETURN ROUTE

    // LIST RETURN
    Route::get('/list-return', [ReturnController::class, 'listReturnPage'])->name('list-return');
    Route::get('/get-ret-list-datatable', [ReturnController::class, 'getRetListDatatable'])->name('get-ret-list-datatable');
    Route::get('/form-return', [ReturnController::class, 'formReturn'])->name('form-return');

    // FORM RETURN
    Route::get('/get-ret-all-location', [ReturnController::class, 'getRetAllLocation'])->name('get-ret-all-location');
    Route::get('/get-ret-product-list', [ReturnController::class, 'getRetProductList'])->name('get-ret-product-list');
    Route::get('/get-ret-product-location-list', [ReturnController::class, 'getRetProductLocationList'])->name('get-ret-product-location-list');
    Route::get('/get-ret-location', [ReturnController::class, 'getRetLocation'])->name('get-ret-location');

    Route::post('/post-ret-req-submit-supp', [ReturnController::class, 'postRetReqSubmitSupp'])->name('post-ret-req-submit-supp');
    Route::post('/post-ret-req-submit-internal', [ReturnController::class, 'postRetReqSubmitinternal'])->name('post-ret-req-submit-internal');

    // APPROVE RETURN
    Route::middleware(['ret_req_approve_check'])->group(function () {
        Route::get('/approve-return/{id}', [ReturnController::class, 'approveReturnPage'])->name('approve-return');
    });
    Route::post('/post-ret-req-approve', [ReturnController::class, 'postRetReqApprove'])->name('post-ret-req-approve');
    Route::post('/post-ret-req-reject', [ReturnController::class, 'postRetReqReject'])->name('post-ret-req-reject');

    // VIEW RETURN
    // Route::get('/view-return', function () {
    //     return view('view-return');
    // });
    Route::middleware(['ret_req_check'])->group(function () {
        Route::get('/view-return/{id}', [ReturnController::class, 'viewReturnPage'])->name('view-return');
    });

});


