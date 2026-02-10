<?php

namespace App\Providers;

use App\Repositories\Master\Material\MaterialRepository;
use App\Repositories\Master\Material\MaterialRepositoryInterface;
use App\Repositories\Master\ProductJsh\ModelRepository;
use App\Repositories\Master\ProductJsh\ModelRepositoryInterface;
use App\Repositories\MaterialUseJsh\MaterialUseJshRepository;
use App\Repositories\MaterialUseJsh\MaterialUseJshRepositoryInterface;
use App\Repositories\PlanProductionJsh\PlanProdRepository;
use App\Repositories\PlanProductionJsh\PlanProdRepositoryInterface;
use App\Repositories\Report\Jsh\JshReportRepository;
use App\Repositories\Report\Jsh\JshReportRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $bindings = [
            PlanProdRepositoryInterface::class => PlanProdRepository::class,
            MaterialUseJshRepositoryInterface::class => MaterialUseJshRepository::class,
            MaterialRepositoryInterface::class => MaterialRepository::class,
            JshReportRepositoryInterface::class => JshReportRepository::class,
            ModelRepositoryInterface::class => ModelRepository::class,
        ];

        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
