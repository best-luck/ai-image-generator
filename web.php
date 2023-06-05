<?php

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
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
 */
Route::group(['middleware' => 'notInstalled', 'prefix' => adminPath(), 'namespace' => 'Backend'], function () {
    Route::name('admin.')->namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@redirectToLogin')->name('index');
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.store');
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.reset.change');
        Route::post('logout', 'LoginController@logout')->name('logout');
    });
    Route::group(['middleware' => 'admin'], function () {
        Route::name('admin.')->middleware('demo')->group(function () {
            Route::group(['prefix' => 'dashboard'], function () {
                Route::get('/', 'DashboardController@index')->name('dashboard');
                Route::get('charts/earnings', 'DashboardController@earningsChartData')->middleware('ajax.only');
                Route::get('charts/users', 'DashboardController@usersChartData')->middleware('ajax.only');
                Route::get('charts/logs', 'DashboardController@logsChartData')->middleware('ajax.only');
            });
            Route::name('notifications.')->prefix('notifications')->group(function () {
                Route::get('/', 'NotificationController@index')->name('index');
                Route::get('view/{id}', 'NotificationController@view')->name('view');
                Route::get('readall', 'NotificationController@readAll')->name('readall');
                Route::delete('deleteallread', 'NotificationController@deleteAllRead')->name('deleteallread');
            });
            Route::name('users.')->prefix('users')->group(function () {
                Route::post('{user}/edit/change/avatar', 'UserController@changeAvatar');
                Route::delete('{user}/edit/delete/avatar', 'UserController@deleteAvatar')->name('deleteAvatar');
                Route::post('{user}/edit/sentmail', 'UserController@sendMail')->name('sendmail');
                Route::get('{user}/edit/logs', 'UserController@logs')->name('logs');
                Route::get('{user}/edit/logs/get/{userLog}', 'UserController@getLogs')->middleware('ajax.only');
                Route::get('logs/{ip}', 'UserController@logsByIp')->name('logsbyip');
            });
            Route::resource('users', 'UserController');
            Route::name('images.')->prefix('images')->group(function () {
                Route::get('/', 'GeneratedImageController@index')->name('index');
                Route::get('{image}/download', 'GeneratedImageController@download')->name('download');
                Route::get('{image}/edit', 'GeneratedImageController@edit')->name('edit');
                Route::post('{image}/update', 'GeneratedImageController@update')->name('update');
                Route::delete('{image}', 'GeneratedImageController@destroy')->name('destroy');
                Route::post('nultiDelete', 'GeneratedImageController@nultiDelete')->name('nultiDelete');
            });
            Route::resource('subscriptions', 'SubscriptionController');
            Route::resource('transactions', 'TransactionController');
            Route::resource('plans', 'PlanController');
            Route::resource('coupons', 'CouponController');
            Route::name('advertisements.')->prefix('advertisements')->group(function () {
                Route::get('/', 'AdvertisementController@index')->name('index');
                Route::get('{advertisement}/edit', 'AdvertisementController@edit')->name('edit');
                Route::post('{advertisement}', 'AdvertisementController@update')->name('update');
            });
        });
        Route::namespace ('Navigation')->prefix('navigation')->name('admin.')->middleware('demo')->group(function () {
            Route::post('navbarMenu/nestable', 'NavbarMenuController@nestable')->name('navbarMenu.nestable');
            Route::resource('navbarMenu', 'NavbarMenuController');
            Route::post('footerMenu/nestable', 'FooterMenuController@nestable')->name('footerMenu.nestable');
            Route::resource('footerMenu', 'FooterMenuController');
        });
        Route::group(['prefix' => 'blog', 'namespace' => 'Blog', 'middleware' => ['demo', 'disable.blog']], function () {
            Route::get('categories/slug', 'CategoryController@slug')->name('categories.slug');
            Route::resource('categories', 'CategoryController');
            Route::get('articles/slug', 'ArticleController@slug')->name('articles.slug');
            Route::get('articles/categories/{lang}', 'ArticleController@getCategories')->middleware('ajax.only');
            Route::resource('articles', 'ArticleController');
            Route::get('comments', 'CommentController@index')->name('comments.index');
            Route::get('comments/{id}/view', 'CommentController@viewComment')->middleware('ajax.only');
            Route::post('comments/{id}/update', 'CommentController@updateComment')->name('comments.update');
            Route::delete('comments/{id}', 'CommentController@destroy')->name('comments.destroy');
        });
        Route::group(['prefix' => 'settings', 'namespace' => 'Settings', 'middleware' => 'demo', 'as' => 'admin.settings.'], function () {
            Route::get('general', 'GeneralController@index')->name('general');
            Route::post('general/update', 'GeneralController@update')->name('general.update');
            Route::name('storage.')->prefix('storage')->group(function () {
                Route::get('/', 'StorageController@index')->name('index');
                Route::get('edit/{storageProvider}', 'StorageController@edit')->name('edit');
                Route::post('edit/{storageProvider}', 'StorageController@update')->name('update');
                Route::post('connect/{storageProvider}', 'StorageController@storageTest')->name('test');
                Route::post('default/{storageProvider}', 'StorageController@setDefault')->name('default');
            });
            Route::name('smtp.')->prefix('smtp')->group(function () {
                Route::get('/', 'SmtpController@index')->name('index');
                Route::post('update', 'SmtpController@update')->name('update');
                Route::post('test', 'SmtpController@test')->name('test');
            });
            Route::name('storage.')->prefix('storage')->group(function () {
                Route::get('/', 'StorageController@index')->name('index');
                Route::get('edit/{storageProvider}', 'StorageController@edit')->name('edit');
                Route::post('edit/{storageProvider}', 'StorageController@update')->name('update');
                Route::post('connect/{storageProvider}', 'StorageController@storageTest')->name('test');
                Route::post('default/{storageProvider}', 'StorageController@setDefault')->name('default');
            });
            Route::name('extensions.')->prefix('extensions')->group(function () {
                Route::get('/', 'ExtensionController@index')->name('index');
                Route::get('{extension}/edit', 'ExtensionController@edit')->name('edit');
                Route::post('{extension}', 'ExtensionController@update')->name('update');
            });
            Route::name('gateways.')->prefix('gateways')->group(function () {
                Route::get('/', 'GatewayController@index')->name('index');
                Route::get('{gateway}/edit', 'GatewayController@edit')->name('edit');
                Route::post('{gateway}', 'GatewayController@update')->name('update');
            });
            Route::name('mailtemplates.')->prefix('mailtemplates')->group(function () {
                Route::get('/', 'MailTemplateController@index')->name('index');
                Route::post('settings/update', 'MailTemplateController@updateSettings')->name('updateSettings');
                Route::get('{mailTemplate}/edit', 'MailTemplateController@edit')->name('edit');
                Route::post('{mailTemplate}', 'MailTemplateController@update')->name('update');
            });
            Route::resource('taxes', 'TaxController');
            Route::get('pages/slug', 'PageController@slug')->name('pages.slug');
            Route::resource('pages', 'PageController');
            Route::resource('admins', 'AdminController');
            Route::name('languages.')->prefix('languages')->group(function () {
                Route::post('sort', 'LanguageController@sort')->name('sort');
                Route::get('translate/{code}', 'LanguageController@translate')->name('translates');
                Route::post('translate/{code}/export', 'LanguageController@export')->name('translates.export');
                Route::post('translate/{code}/import', 'LanguageController@import')->name('translates.import');
                Route::post('{id}/update', 'LanguageController@translateUpdate')->name('translates.update');
                Route::get('translate/{code}/{group}', 'LanguageController@translate')->name('translates.group');
            });
            Route::resource('languages', 'LanguageController');
            Route::resource('seo', 'SeoController');
        });

        Route::name('admin.')->middleware('demo')->group(function () {
            Route::name('extra.')->prefix('extra')->namespace('Extra')->group(function () {
                Route::get('custom-css', 'CustomCssController@index')->name('css');
                Route::post('custom-css/update', 'CustomCssController@update')->name('css.update');
                Route::get('popup-notice', 'PopupNoticeController@index')->name('notice');
                Route::post('popup-notice/update', 'PopupNoticeController@update')->name('notice.update');
            });
            Route::namespace ('Others')->prefix('others')->group(function () {
                Route::resource('features', 'FeatureController');
                Route::resource('faqs', 'FaqController');
            });
            Route::post('ckeditor/upload', 'CKEditorController@upload');
            Route::name('system.')->namespace('System')->prefix('system')->group(function () {
                Route::get('info', 'InfoController@index')->name('info.index');
                Route::post('info/cache', 'InfoController@cache')->name('info.cache');
                Route::resource('plugins', 'PluginController')->except(['create', 'show']);
                Route::get('editor-files', 'EditorFileController@index')->name('editor-files.index');
                Route::delete('editor-files/{editorFile}', 'EditorFileController@destroy')->name('editor-files.destroy');
            });
            Route::namespace ('Account')->prefix('account')->group(function () {
                Route::get('details', 'SettingsController@detailsForm')->name('account.details');
                Route::get('security', 'SettingsController@securityForm')->name('account.security');
                Route::post('details/update', 'SettingsController@detailsUpdate')->name('account.details.update');
                Route::post('security/update', 'SettingsController@securityUpdate')->name('account.security.update');
            });
        });
    });
});
/*
|--------------------------------------------------------------------------
| Frontend Routs With Laravel Localization
|--------------------------------------------------------------------------
 */
Route::get('images/secure/{id}/{filename}', 'Frontend\ImageController@secure')->name('images.secure');
Route::group(localizeOptions(), function () {
    Route::name('ipn.')->prefix('ipn')->namespace('Frontend\Gateways')->group(function () {
        Route::get('paypal_express', 'PaypalExpressController@ipn')->name('paypal_express');
        Route::get('stripe_checkout', 'StripeCheckoutController@ipn')->name('stripe_checkout');
        Route::get('mollie', 'MollieController@ipn')->name('mollie');
        Route::post('razorpay', 'RazorpayController@ipn')->name('razorpay');
    });
    Auth::routes(['verify' => true]);
    Route::group(['namespace' => 'Frontend'], function () {
        Route::get('cookie/accept', 'ExtraController@cookie')->middleware('ajax.only');
        Route::get('popup/close', 'ExtraController@popup')->middleware('ajax.only');
        Route::group(['namespace' => 'Auth'], function () {
            Route::get('login', 'LoginController@showLoginForm')->name('login');
            Route::post('login', 'LoginController@login');
            Route::get('login/{provider}', 'LoginController@redirectToProvider')->name('provider.login');
            Route::get('login/{provider}/callback', 'LoginController@handleProviderCallback')->name('provider.callback');
            Route::post('logout', 'LoginController@logout')->name('logout');
            Route::middleware(['disable.registration'])->group(function () {
                Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
                Route::post('register', 'RegisterController@register')->middleware('check.registration');
                Route::get('register/complete/{token}', 'RegisterController@showCompleteForm')->name('complete.registration');
                Route::post('register/complete/{token}', 'RegisterController@complete')->middleware('check.registration');
            });
            Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
            Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
            Route::post('email/verify/email/change', 'VerificationController@changeEmail')->name('change.email');
            Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
            Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
        });
        Route::group(['namespace' => 'Auth', 'middleware' => ['auth', 'verified']], function () {
            Route::get('checkpoint/2fa/verify', 'CheckpointController@show2FaVerifyForm')->name('2fa.verify');
            Route::post('checkpoint/2fa/verify', 'CheckpointController@verify2fa');
        });
        Route::group(['prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth', 'verified', '2fa.verify']], function () {
            Route::get('/', function () {
                return redirect()->route('user.gallery.index');
            });
            Route::name('checkout.')->prefix('checkout')->group(function () {
                Route::get('{checkout_id}', 'CheckoutController@index')->name('index');
                Route::post('{checkout_id}/coupon/apply', 'CheckoutController@applyCoupon')->name('coupon.apply');
                Route::post('{checkout_id}/coupon/remove', 'CheckoutController@removeCoupon')->name('coupon.remove');
                Route::post('{checkout_id}/proccess', 'CheckoutController@proccess')->name('proccess');
            });
            Route::name('user.')->group(function () {
                Route::name('gallery.')->prefix('gallery')->group(function () {
                    Route::get('/', 'GalleryController@index')->name('index');
                    Route::post('{id}/update', 'GalleryController@update')->name('update');
                    Route::delete('{id}', 'GalleryController@destroy')->name('destroy');
                });
                Route::name('settings.')->prefix('settings')->group(function () {
                    Route::get('/', 'SettingsController@index')->name('index');
                    Route::post('details/update', 'SettingsController@detailsUpdate')->name('details.update');
                    Route::post('details/mobile/update', 'SettingsController@mobileUpdate')->name('details.mobile.update');
                    Route::get('subscription', 'SettingsController@subscription')->name('subscription');
                    Route::get('payment-history', 'SettingsController@paymentHistory')->name('payment-history');
                    Route::get('password', 'SettingsController@password')->name('password');
                    Route::post('password/update', 'SettingsController@passwordUpdate')->name('password.update');
                    Route::get('2fa', 'SettingsController@towFactor')->name('2fa');
                    Route::post('2fa/enable', 'SettingsController@towFactorEnable')->name('2fa.enable');
                    Route::post('2fa/disabled', 'SettingsController@towFactorDisable')->name('2fa.disable');
                });
            });
        });
        Route::middleware(['verified', '2fa.verify'])->group(function () {
            Route::get('/', 'HomeController@index')->name('home');
            Route::name('images.')->prefix('images')->group(function () {
                Route::get('explore', 'ImageController@index')->name('index');
                Route::post('generate', 'ImageController@generator')->name('generator');
                Route::get('{id}/view', 'ImageController@show')->name('show');
                Route::get('download/{id}/{filename}', 'ImageController@download')->name('download');
            });
            Route::get('features', 'GlobalController@features')->name('features')->middleware('disable.features');
            Route::get('pricing', 'GlobalController@pricing')->name('pricing');
            Route::post('pricing/{id}/{type}', 'SubscribeController@subscribe')->name('subscribe');
            Route::name('blog.')->prefix('blog')->middleware('disable.blog')->group(function () {
                Route::get('/', 'BlogController@index')->name('index');
                Route::get('categories', 'BlogController@categories')->name('categories');
                Route::get('categories/{slug}', 'BlogController@category')->name('category');
                Route::get('articles', 'BlogController@articles');
                Route::get('articles/{slug}', 'BlogController@article');
                Route::post('articles/{slug}', 'BlogController@comment')->name('article');
            });
            Route::get('faqs', 'GlobalController@faqs')->name('faqs')->middleware('disable.faqs');
            Route::middleware('disable.contact')->group(function () {
                Route::get('contact-us', 'GlobalController@contact');
                Route::post('contact-us', 'GlobalController@contactSend')->name('contact');
            });
            if (env('VR_SYSTEMSTATUS') && !settings('actions')->language_type) {
                Route::get('{lang}', 'LocalizationController@localize')->where('lang', '^[a-z]{2}$')->name('localize');
            }
            Route::get('{slug}', 'GlobalController@page')->name('page');
        });
    });
});