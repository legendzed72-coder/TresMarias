<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;
use Laravel\Fortify\Http\Responses\SimpleViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);

        $this->app->singleton(RegisterViewResponse::class, function () {
            return new SimpleViewResponse('auth.register');
        });

        $this->app->singleton(LoginViewResponse::class, function () {
            return new SimpleViewResponse('auth.login');
        });

        $this->app->singleton(RequestPasswordResetLinkViewResponse::class, function () {
            return new SimpleViewResponse('auth.forgot-password');
        });

        $this->app->singleton(ResetPasswordViewResponse::class, function () {
            return new SimpleViewResponse('auth.reset-password');
        });

        $this->app->singleton(VerifyEmailViewResponse::class, function () {
            return new SimpleViewResponse('auth.verify-email');
        });

        $this->app->singleton(ConfirmPasswordViewResponse::class, function () {
            return new SimpleViewResponse('auth.confirm-password');
        });

        $this->app->singleton(TwoFactorChallengeViewResponse::class, function () {
            return new SimpleViewResponse('auth.two-factor-challenge');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
