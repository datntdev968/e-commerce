<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Catalogue;
use App\Policies\Catalogue\CataloguePolicy;
use App\Policies\Role\RolePolicy;
use Spatie\Permission\Models\Role;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Catalogue::class => CataloguePolicy::class,
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
