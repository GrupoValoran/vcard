<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailSubscriptionController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\FrontTestimonialController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaypalPayoutController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\ScheduleAppointmentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\VcardBlogController;
use App\Http\Controllers\VcardController;
use App\Http\Controllers\VcardServiceController;
use App\Http\Middleware\XSS;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

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


//Route::get('/', function () {
//    return (!Auth::check()) ? \redirect(route('login')) : Redirect::to(getDashboardURL());
//});

Route::get('/', function () {
    return (!Auth::check()) ? \redirect(route('login')) : Redirect::to('/');
});

//social logins
Route::get('/login/{provider}', [SocialAuthController::class, 'redirectToSocial'])->name('social.login');
Route::get('/login/{provider}/callback', [SocialAuthController::class, 'handleSocialCallback']);



Route::group(['middleware' => ['setLanguage']], function () {
    Route::post('/change-language', [HomeController::class, 'changeLanguage']);
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('terms-conditions', [HomeController::class, 'termCondition'])->name('terms.conditions');
    Route::get('privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
    
});
Route::group(['middleware' => ['auth', 'valid.user', 'xss']], function () {
    // Update profile
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.setting');
    Route::get('/mode', [UserController::class, 'changeMode'])->name('mode.theme');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('update.profile.setting');
    Route::put('/change-user-password', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::put('/change-user-language', [UserController::class, 'changeLanguage'])->name('user.changeLanguage');
    //impersonate leave
    Route::get('/impersonate-leave', [UserController::class, 'impersonateLeave'])->name('impersonate.leave');

    Route::get('payment-success', [SubscriptionController::class, 'paymentSuccess'])->name('payment-success');
    Route::get('failed-payment', [SubscriptionController::class, 'handleFailedPayment'])->name('failed-payment');

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin', 'multi_tenant']], function () {
        
        //dashboard chart
        Route::get('/dashboard-chart', [VcardController::class, 'dashboardChartData'])->name('dashboard.vcard.chart');
        
        //manage-subscription
        Route::get('manage-subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
        Route::get('choose-payment-type/{planId}/{context?}/{fromScreen?}',
            [SubscriptionController::class, 'choosePaymentType'])->name('choose.payment.type');
        Route::post('purchase-subscription',
            [SubscriptionController::class, 'purchaseSubscription'])->name('purchase-subscription');

        Route::get('manage-subscription/upgrade',
            [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
        Route::post('subscription-purchase/{plan}/plan-zero',
            [SubscriptionController::class, 'setPlanZero'])->name('subscription.plan-zero');
        Route::post('subscription-purchase/{plan}/manual',
            [SubscriptionController::class, 'manualPay'])->name('subscription.manual');
        Route::post('stripe/subscription-purchase', [StripeController::class, 'purchase'])->name('stripe.purchase');

        //paypal routes
        Route::get('paypal-onboard', [PaypalController::class, 'onBoard'])->name('paypal.init');
        Route::get('paypal-payment-success', [PaypalController::class, 'success'])->name('paypal.success');
        Route::get('paypal-payment-failed', [PaypalController::class, 'failed'])->name('paypal.failed');

        //razorpay routes
        Route::get('razorpay-onboard', [RazorpayController::class, 'onBoard'])->name('razorpay.init');
        Route::post('razorpay-payment-success', [RazorpayController::class, 'paymentSuccess'])
            ->name('razorpay.success');
        Route::post('razorpay-payment-failed', [RazorpayController::class, 'paymentFailed'])
            ->name('razorpay.failed');

        Route::group(['middleware' => ['subscription']], function () {
            //admin dashboard route
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

            Route::get('/vcard/{vcard}/analytics', [VcardController::class,'analytics'])->name('vcard.analytics');
            Route::get('/enquiries', [EnquiryController::class,'enquiryList'])->name('enquiries.index');
            Route::get('/appointments',
                [ScheduleAppointmentController::class, 'appointmentsList'])->name('appointments.index');
            Route::post('/appointments/status/{appointment}',
                       [ScheduleAppointmentController::class, 'appointmentsUpdate'])->name('appointments.update');
            Route::get('/appointments-calendar',
                [ScheduleAppointmentController::class, 'appointmentCalendar'])->name('appointments.calendar');

            Route::get('/vcard/status/{vcard}', [VcardController::class, 'updateStatus'])->name('vcard.status');
            Route::group(['prefix' => 'vcard'], function () {
                //VCard services
                Route::get('{vcard}/services', [VcardServiceController::class, 'index'])->name('vcard.service.index');
                Route::post('services', [VcardServiceController::class, 'store'])->name('vcard.service.store');
                Route::get('services/{vcardService}',
                    [VcardServiceController::class, 'edit'])->name('vcard.service.edit');
                Route::post('services/{vcardService}/update',
                    [VcardServiceController::class, 'update'])->name('vcard.service.update');
                Route::delete('services/{vcardService}',
                    [VcardServiceController::class, 'destroy'])->name('vcard.service.destroy');

                //VCard blogs
                Route::get('{vcard}/blogs', [VcardBlogController::class, 'index'])->name('vcard.blogs.index');
                Route::post('blogs', [VcardBlogController::class, 'store'])->name('vcard.blog.store');
                Route::get('blogs/{vcardBlog}',
                    [VcardBlogController::class, 'edit'])->name('vcard.blog.edit');
                Route::post('blogs/{vcardBlog}/update',
                    [VcardBlogController::class, 'update'])->name('vcard.blog.update');
                Route::delete('blogs/{vcardBlog}',
                    [VcardBlogController::class, 'destroy'])->name('vcard.blog.destroy');
                
                //gallery
                Route::get('{vcard}/galleries',[GalleryController::class,'index'])->name('gallery.index');
                Route::post('galleries',[GalleryController::class,'store'])->name('gallery.store');
                Route::get('galleries/{gallery}',
                    [GalleryController::class,'edit'])->name('gallery.edit');
                Route::post('galleries/{gallery}/update',
                    [GalleryController::class,'update'])->name('gallery.update');
                Route::delete('galleries/{gallery}',
                    [GalleryController::class,'destroy'])->name('gallery.destroy');
                //vcard products
                Route::get('{vcard}/products', [ProductController::class, 'index'])->name('vcard.products.index');
                Route::post('products', [ProductController::class, 'store'])->name('vcard.products.store');
                Route::get('products/{products}',
                    [ProductController::class, 'edit'])->name('vcard.products.edit');
                Route::post('products/{products}/update',
                    [ProductController::class, 'update'])->name('vcard.products.update');
                Route::delete('products/{products}',
                    [ProductController::class, 'destroy'])->name('vcard.products.destroy');
                //VCard testimonial
                Route::get('{vcard}/testimonials', [TestimonialController::class, 'index'])->name('testimonial.index');
                Route::post('testimonials', [TestimonialController::class, 'store'])->name('testimonial.store');
                Route::get('testimonials/{testimonial}',
                    [TestimonialController::class, 'edit'])->name('testimonial.edit');
                Route::post('testimonials/{testimonial}/update',
                    [TestimonialController::class, 'update'])->name('testimonial.update');
                Route::delete('testimonials/{testimonial}',
                    [TestimonialController::class, 'destroy'])->name('testimonial.destroy');
            });

            Route::get('/vcards/{vcard}/enquiry', [EnquiryController::class, 'index'])->name('enquiry.index');
            Route::get('/getSlot', [VcardController::class, 'getSlot'])->name('get.slot');
            Route::get('/user-settings', [UserSettingController::class, 'index'])->name('user.setting.index');
            Route::post('/user-setting', [UserSettingController::class, 'update'])->name('user.setting.update');
        });
    });

    Route::group(['prefix' => 'sadmin', 'middleware' => ['role:super_admin']], function () {
      
        //dashboard chart
        Route::post('/dashboard-plan-chart', [DashboardController::class, 'planChartData'])->name('dashboard.plan-chart');
        Route::post('/dashboard-income-chart', [DashboardController::class, 'incomeChartData'])->name('dashboard.income-chart');
        Route::get('/dashboard-income-chart', [DashboardController::class, 'incomeChartData'])->name('get.dashboard.income-chart');
        
        Route::get('/planSubscriptions', [SubscriptionController::class,'cashPlan'])->name('subscription.cash');
        Route::get('/planSubscription/{id}',[SubscriptionController::class,'planStatus'])->name('subscription.status');
        Route::get('/subscribedPlans',[SubscriptionController::class,'userSubscribedPlan'])->name('subscription.user.plan');
        Route::get('/subscribedPlan/{id}/edit',[SubscriptionController::class,'userSubscribedPlanEdit'])->name('subscription.user.plan.edit');
        Route::get('/subscribedPlan/{id}/update',[SubscriptionController::class,'userSubscribedPlanUpdate'])->name('subscription.user.plan.update');
        Route::get('logs', [LogViewerController::class, 'index']);
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('sadmin.dashboard');
        //user
        Route::resource('/users', UserController::class);
        //testimonials
        Route::resource('/frontTestimonials', FrontTestimonialController::class);

        Route::post('frontTestimonials/{id}/update',
            [FrontTestimonialController::class, 'update'])->name('frontTestimonial.updateData');

        Route::get('users/email-verified/{user}',
            [UserController::class, 'emailVerified'])->name('users.email-verified');
        Route::get('/users/update-status/{user}', [UserController::class, 'updateStatus'])->name('users.status');
        //impersonate
        Route::get('/impersonate/{user}', [UserController::class, 'impersonate'])->name('impersonate');
        //vcard
        Route::get('/vcards', [VcardController::class, 'vcards'])->name('sadmin.vcards.index');
        //vcards templates
        Route::get('/templates', [VcardController::class, 'template'])->name('sadmin.templates.index');
        //analytics
        Route::get('/vcard/{vcard}/analytics', [VcardController::class, 'analytics'])->name('sadmin.vcard.analytics');
        //country
        Route::resource('/countries', CountryController::class);
        //state
        Route::resource('/states', StateController::class);
        //city
        Route::resource('/cities', CityController::class);
        //plan
        Route::resource('/plans', PlanController::class);
        Route::get('/plans/status/{plan}', [PlanController::class, 'updateStatus'])->name('plan.status');
        Route::post('subscription-plans/{user}/make-plan-as-default',
            [PlanController::class, 'makePlanDefault'])->name('make.plan.default');
        //currency
        Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index');
        // Role route
        // Feature route
        Route::resource('/features', FeatureController::class);
        //AboutUs route
        Route::get('/about-us', [AboutUsController::class, 'index'])->name('aboutUs.index');
        Route::post('/about-us', [AboutUsController::class, 'store'])->name('aboutUs.store');
        // Setting routes
//        contact us
        Route::get('contactUs', [HomeController::class, 'showContactUs'])->name('contact.contactus');
        //contact list
        Route::get('/dashboard-users', [DashboardController::class, 'getUsersList'])->name('usersData.dashboard');
        
        Route::get('/front-cms', [SettingController::class, 'frontCmsIndex'])->name('setting.front.cms');
        Route::post('/front-cms', [SettingController::class, 'frontCmsUpdate'])->name('setting.front.cms.update');
        Route::get('/email-subscriptions', [EmailSubscriptionController::class, 'index'])->name('email.sub.index');
        Route::delete('/email-sub/{emailSubscription}',
            [EmailSubscriptionController::class, 'destroy'])->name('email.sub.destroy');
        
        Route::group(['middleware' => ['permission:manage_language']], function () {
            Route::resource('languages', LanguageController::class);
            Route::get('languages/translation/{language}',
                [LanguageController::class, 'showTranslation'])->name('languages.translation');
            Route::post('languages/translation/{language}/update',
                [LanguageController::class, 'updateTranslation'])->name('languages.translation.update');
            Route::put('/change-password/{user}', [UserController::class, 'changeUserPassword'])->name('changePassword');
        });
        Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('setting.update');
        Route::post('/setting-credential', [SettingController::class, 'settingTermsConditions'])->name('setting.TermsConditions.update')->withoutMiddleware([XSS::class]);
    });
});

Route::group(['prefix' => 'admin','middleware' => ['subscription','auth', 'valid.user','role:admin', 'multi_tenant']], function () {
    Route::resource('/vcards', VcardController::class);
});

Route::post('/email-sub', [EmailSubscriptionController::class, 'store'])->name('email.sub')->middleware('setLanguage');

Route::get('/v')->name('vcard.defaultIndex');
Route::get('/v/{alias}', [VcardController::class, 'show'])->name('vcard.show')->middleware(['analytics','language','vcardSubscription']);
Route::get('/v/{alias}/blog/{id}', [VcardController::class, 'showBlog'])->name('vcard.show-blog')->middleware(['vcardSubscription']);
Route::get('/v/{alias}/privacy-policy/{id}', [VcardController::class, 'showPrivacyPolicy'])->name('vcard.show-privacy-policy')->middleware(['vcardSubscription']);
Route::get('/vcard/{alias}/chart', [VcardController::class, 'chartData'])->name('vcard.chart');
Route::post('/vcard/{vcard}/check-password', [VcardController::class, 'checkPassword'])->name('vcard.password');
Route::post('/vcard/{vcard}/enquiry/store', [EnquiryController::class, 'store'])->name('enquiry.store');
Route::post('/vcard/{vcard}/appointment/store', [ScheduleAppointmentController::class, 'store'])->name('appointment.store');
Route::get('enquiry/{enquiry}', [EnquiryController::class, 'show'])->name('enquiry.show');

Route::get('language/{languageName}/{alias}',[VcardController::class,'language'])->name('LanguageChange');

Route::get('language/',[LanguageController::class,'getAllLanguage'])->name('get.all.language');


// user stripe routes
Route::post('user-stripe-payment', [StripeController::class, 'userCreateSession'])->name('user.stripe-payment');
Route::get('user-payment-success', [StripeController::class, 'userPaymentSuccess'])->name('user.payment-success');
Route::get('user-failed-payment', [StripeController::class, 'userHandleFailedPayment'])->name('user.failed-payment');

// user paypal routes
Route::get('user-paypal-onboard', [PaypalController::class, 'userOnBoard'])->name('user.paypal.init');
Route::get('user-paypal-payment-success', [PaypalController::class, 'userSuccess'])->name('user.paypal.success');
Route::get('user-paypal-payment-failed', [PaypalController::class, 'userFailed'])->name('user.paypal.failed');

Route::get('paypal-payout', [PaypalPayoutController::class, 'userPayout'])->name('paypal.payout');
    
Route::group(['middleware' => ['auth', 'valid.user', 'role:super_admin','xss']], function () {
    Route::get('vcard1', function () {
        return view('vcards.vcard1');
    });
    Route::get('vcard2', function () {
        return view('vcards.vcard2');
    });

    Route::get('vcard3', function () {
        return view('vcards.vcard3');
    });

    Route::get('vcard4', function () {
        return view('vcards.vcard4');
    });


    Route::get('vcard5', function () {
        return view('vcards.vcard5');
    });

    Route::get('vcard6', function () {
        return view('vcards.vcard6');
    });

    Route::get('vcard7', function () {
        return view('vcards.vcard7');
    });

    Route::get('vcard8', function () {
        return view('vcards.vcard8');
    });

    Route::get('vcard9', function () {
        return view('vcards.vcard9');
    });

    Route::get('vcard10', function () {
        return view('vcards.vcard10');
    });
});
require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/upgrade.php';

