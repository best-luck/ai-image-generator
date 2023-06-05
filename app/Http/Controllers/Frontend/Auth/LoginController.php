<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Methods\ReCaptchaValidation;
use App\Models\SocialProvider;
use App\Models\User;
use App\Models\UserLog;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::USER;
    protected $providers = ['facebook'];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Create a log or update an exists one
     *
     * @return void
     */
    protected function setLog($user)
    {
        $ip = ipInfo()->ip;
        $loginLog = UserLog::where([['user_id', $user->id], ['ip', $ip]])->first();
        $location = ipInfo()->location->city . ', ' . ipInfo()->location->country;
        if ($loginLog != null) {
            $loginLog->country = ipInfo()->location->country;
            $loginLog->country_code = ipInfo()->location->country_code;
            $loginLog->timezone = ipInfo()->location->timezone;
            $loginLog->location = $location;
            $loginLog->latitude = ipInfo()->location->latitude;
            $loginLog->longitude = ipInfo()->location->longitude;
            $loginLog->browser = ipInfo()->system->browser;
            $loginLog->os = ipInfo()->system->os;
            $loginLog->update();
        } else {
            $newloginLog = new UserLog();
            $newloginLog->user_id = $user->id;
            $newloginLog->ip = ipInfo()->ip;
            $newloginLog->country = ipInfo()->location->country;
            $newloginLog->country_code = ipInfo()->location->country_code;
            $newloginLog->timezone = ipInfo()->location->timezone;
            $newloginLog->location = $location;
            $newloginLog->latitude = ipInfo()->location->latitude;
            $newloginLog->longitude = ipInfo()->location->longitude;
            $newloginLog->browser = ipInfo()->system->browser;
            $newloginLog->os = ipInfo()->system->os;
            $newloginLog->save();
        }
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ] + ReCaptchaValidation::validate());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (userAuthInfo()->status == 0) {
            Auth::logout();
            toastr()->error(lang('Your account has been blocked', 'auth'));
            return redirect()->route('login');
        }
        $this->setLog($user);
    }

    /**
     * Login using socialite redirect to provider
     *
     * @return // Redirect to provider
     */
    public function redirectToProvider($provider)
    {
        if (demoMode()) {
            toastr()->error(admin_lang('Some features are disabled in the demo version'));
            return redirect()->route('login');
        }
        abort_if(!in_array($provider, $this->providers), 404);
        abort_if(!env('FACEBOOK_CLIENT_ID') || !env('FACEBOOK_CLIENT_SECRET'), 404);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Login using socialite redirect to provider
     *
     * @var // $provider
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            abort_if(!in_array($provider, $this->providers), 404);
            $socialUser = Socialite::driver($provider)->user();
            if ($provider == "facebook") {
                $exist = SocialProvider::where('facebook', $socialUser->id)->first();
                try {
                    if ($exist) {
                        $user = User::find($exist->user_id);
                        $this->setLog($user);
                        Auth::login($user);
                        return redirect()->route('user.gallery.index');
                    } else {
                        if (!settings('actions')->email_verification_status) {
                            toastr()->error(lang('Registration is currently disabled.', 'auth'));
                            return redirect()->route('login');
                        }
                        $name = explode(' ', $socialUser->name);
                        $sessionDetails = [
                            'provider' => $provider,
                            'id' => $socialUser->id,
                            'firstname' => $name[0] ?? null,
                            'lastname' => $name[1] ?? null,
                            'email' => $socialUser->email ?? null,
                        ];
                        $token = encrypt($sessionDetails);
                        Session::put('provider_data', $token);
                        return redirect()->route('complete.registration', $token);
                    }
                } catch (\Exception$e) {
                    toastr()->error(lang('Connection error please try again', 'auth'));
                    return redirect()->route('login');
                }
            }
        } catch (\Throwable$e) {
            return redirect()->route('login');
        }
    }
}
