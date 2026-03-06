<?php

namespace App\Providers;

use App\Repositories\Master\Material\MaterialRepository;
use App\Repositories\Master\Material\MaterialRepositoryInterface;
use App\Repositories\Master\ProductAce\ProductRepositoryInterface;
use App\Repositories\Master\ProductAce\ProductRepository;
use App\Repositories\Master\ProductJsh\ModelRepository;
use App\Repositories\Master\ProductJsh\ModelRepositoryInterface;
use App\Repositories\MaterialUseAce\MaterialUseAceReposiroty;
use App\Repositories\MaterialUseAce\MaterialUseAceReposirotyInterface;
use App\Repositories\MaterialUseJsh\MaterialUseJshRepository;
use App\Repositories\MaterialUseJsh\MaterialUseJshRepositoryInterface;
use App\Repositories\PlanProductionAce\PlanProdRepositoryAce;
use App\Repositories\PlanProductionAce\PlanProdRepositoryAceInterface;
use App\Repositories\PlanProductionJsh\PlanProdRepository;
use App\Repositories\PlanProductionJsh\PlanProdRepositoryInterface;
use App\Repositories\Report\Ace\AceReportRepository;
use App\Repositories\Report\Ace\AceReportRepositoryInterface;
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
            AceReportRepositoryInterface::class => AceReportRepository::class,
            ModelRepositoryInterface::class => ModelRepository::class,
            PlanProdRepositoryAceInterface::class => PlanProdRepositoryAce::class,
            ProductRepositoryInterface::class => ProductRepository::class,
            MaterialUseAceReposirotyInterface::class => MaterialUseAceReposiroty::class,
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
