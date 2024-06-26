<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TallyController;
use App\Http\Controllers\TestController;
use App\Models\Batch;
use App\Models\Due;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Receipt;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});
Route::get('/payment-sync', [HomeController::class, 'payment_sync']);
Route::get('/create-receipt', [HomeController::class, 'create_receipt']);
Route::get('/tax-invoice', [HomeController::class, 'tax_invoice']);

Auth::routes(['register' => false]);

Route::get('/user-payment/{id}', [HomeController::class, 'user_payment'])->name('user.payment');
Route::get('/thank-you/{id}', [HomeController::class, 'thank_you'])->name('thank.you');
Route::get('/thank-you-direct-payment/{id}', [HomeController::class, 'direct_thank_you'])->name('direct.thank.you');

Route::get('/pay-now', [HomeController::class, 'pay_now'])->name('pay.now');

Route::prefix('admin')->as('admin.')->middleware('auth')->group(function () {
    Route::get('/test', [TestController::class, 'test']);
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/export', [HomeController::class, 'exportData'])->name('export');
    Route::get('/export-payment', [HomeController::class, 'exportPayment'])->name('export-payment');
    Route::get('/sales', [HomeController::class, 'index'])->name('sales');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [HomeController::class, 'profile_update'])->name('profile-update');
    Route::get('/payment-history', [HomeController::class, 'paid'])->name('paid');
    Route::get('/online-payment', [HomeController::class, 'online_payment'])->name('online-payment');
    Route::get('/sales/add', [HomeController::class, 'sales_add'])->name('sales.add');
    Route::get('/enrolled', [HomeController::class, 'sales_enrolled'])->name('sales.enrolled');
    Route::get('/sales/{id}/edit', [HomeController::class, 'sales_edit'])->name('sales.edit');
    Route::get('/programs', [HomeController::class, 'program'])->name('programs');
    Route::get('/batches', [HomeController::class, 'batch'])->name('batches');
    Route::get('/fee-structure', [HomeController::class, 'fee_structure'])->name('fee-structure');
    Route::get('/generate-offer', [HomeController::class, 'generate_offer'])->name('generate-offer');
    // Route::get('/sales',[App\Http\Controllers\HomeController::class, 'sales'])->name('sales');
    Route::get('/payment/{id}', [HomeController::class, 'finance_payment'])->name('finance.payment');

    Route::get('/dues', [HomeController::class, 'dues'])->name('dues');

    Route::get('/complemantries', [HomeController::class, 'complemantries'])->name('complemantries');

    Route::get('/offer-letter/{id}', [HomeController::class, 'offer_letter'])->name('offer.letter');
    Route::get('/pdf-export/{id}', [HomeController::class, 'pdf_export'])->name('pdf.export');
    Route::get('/reports', [HomeController::class, 'reports'])->name('reports');

    Route::get('/users', [HomeController::class, 'users'])->name('users');

    Route::get('/datastudio', [HomeController::class, 'datastudio'])->name('datastudio');
    Route::post('/sales/import', [HomeController::class, 'sale_import'])->name('sales.import');

    Route::get('payment-check', [HomeController::class, 'payment_check'])->name('payment.check');

    Route::get('custom-form', [HomeController::class, 'custom_form'])->name('custom.form');

    Route::prefix('tally')->as('tally.')->group(function () {
        Route::get('batches', [TallyController::class, 'tally_batches'])->name('batches');
        Route::get('courses', [TallyController::class, 'tally_courses'])->name('courses');
        Route::get('dues', [TallyController::class, 'tally_dues'])->name('dues');
        Route::get('receipts', [TallyController::class, 'tally_receipts'])->name('receipts');
        Route::get('student', [TallyController::class, 'tally_student'])->name('student');
    });
});
