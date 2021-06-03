<?php

namespace Ydm\Datatables;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DatatableServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        $this->configureComponents();
    }

    public function configurePackage(Package $package): void
    {
        $package->name('livewire-datatables')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations();
    }

    protected function configureComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('table');
            $this->registerComponent('table-td');
            $this->registerComponent('table-th');
            $this->registerComponent('table-tr');
        });
    }

    protected function registerComponent(string $component)
    {
        Blade::component('livewire-datatables::components.' . $component, 'ydm-' . $component);
    }
}
