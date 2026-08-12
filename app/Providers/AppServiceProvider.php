<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Pelatihan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewLogViewer', function ($user) {
            return $user->hasRole('super_admin');
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Share variables to all views (for badges in sidebar)
        View::composer('*', function ($view) {
            try {
                $jumlah_pelatihan_tersedia = \App\Models\Pelatihan::where('status', 'published')
                    ->where('tanggal_selesai', '>=', now())
                    ->count();
                
                $pendingCount = \App\Models\UMKM::where('status_verifikasi', 'terkirim')->count();
                $pendingAkunCount = \App\Models\Pemilik::where('status_verifikasi_ktp', 'pending')->count();
            } catch (\Exception $e) {
                $jumlah_pelatihan_tersedia = 0;
                $pendingCount = 0;
                $pendingAkunCount = 0;
            }
            $view->with('jumlah_pelatihan_tersedia', $jumlah_pelatihan_tersedia);
            $view->with('pendingCount', $pendingCount);
            $view->with('pendingAkunCount', $pendingAkunCount);
        });
    }
}