<?php

namespace App\Providers;

use App\Models\Community;
use App\Models\FootballMatch;
use App\Models\Prediction;
use App\Observers\MatchObserver;
use App\Policies\CommunityPolicy;
use App\Policies\PredictionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Policies
        Gate::policy(Community::class, CommunityPolicy::class);
        Gate::policy(Prediction::class, PredictionPolicy::class);

        // Observer del recálculo de puntos
        FootballMatch::observe(MatchObserver::class);
    }
}
