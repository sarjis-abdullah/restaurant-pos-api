<?php

namespace App\Providers;


use App\Models\Addon;
use App\Models\AddonVariant;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Discount;
use App\Models\Floor;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Payment;
use App\Models\Table;
use App\Models\Tax;
use App\Models\User;
use App\Models\Variant;
use App\Repositories\AddonRepository;
use App\Repositories\AddonVariantRepository;
use App\Repositories\BranchRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\Contracts\AddonInterface;
use App\Repositories\Contracts\AddonVariantInterface;
use App\Repositories\Contracts\BranchInterface;
use App\Repositories\Contracts\CategoryInterface;
use App\Repositories\Contracts\CompanyInterface;
use App\Repositories\Contracts\DiscountInterface;
use App\Repositories\Contracts\FloorInterface;
use App\Repositories\Contracts\MenuInterface;
use App\Repositories\Contracts\MenuItemInterface;
use App\Repositories\Contracts\OrderInterface;
use App\Repositories\Contracts\OrderItemAddonInterface;
use App\Repositories\Contracts\OrderItemInterface;
use App\Repositories\Contracts\PaymentInterface;
use App\Repositories\Contracts\TableInterface;
use App\Repositories\Contracts\TaxInterface;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\Contracts\VariantInterface;
use App\Repositories\DiscountRepository;
use App\Repositories\FloorRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\MenuRepository;
use App\Repositories\OrderItemAddonRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\TableRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UserRepository;
use App\Repositories\VariantRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, fn() => new UserRepository(new User()));
        $this->app->bind(BranchInterface::class, fn() => new BranchRepository(new Branch()));
        $this->app->bind(CategoryInterface::class, fn() => new CategoryRepository(new Category()));
        $this->app->bind(CompanyInterface::class, fn() => new CompanyRepository(new Company()));
        $this->app->bind(FloorInterface::class, fn() => new FloorRepository(new Floor()));
        $this->app->bind(MenuItemInterface::class, fn() => new MenuItemRepository(new MenuItem()));
        $this->app->bind(MenuInterface::class, fn() => new MenuRepository(new Menu()));
        $this->app->bind(OrderInterface::class, fn() => new OrderRepository(new Order()));
        $this->app->bind(OrderItemInterface::class, fn() => new OrderItemRepository(new OrderItem()));
        $this->app->bind(TableInterface::class, fn() => new TableRepository(new Table()));
        $this->app->bind(TaxInterface::class, fn() => new TaxRepository(new Tax()));
        $this->app->bind(DiscountInterface::class, fn() => new DiscountRepository(new Discount()));
        $this->app->bind(AddonInterface::class, fn() => new AddonRepository(new Addon()));
        $this->app->bind(OrderItemAddonInterface::class, fn() => new OrderItemAddonRepository(new OrderItemAddon()));
        $this->app->bind(VariantInterface::class, fn() => new VariantRepository(new Variant()));
        $this->app->bind(PaymentInterface::class, fn() => new PaymentRepository(new Payment()));
        $this->app->bind(AddonVariantInterface::class, fn() => new AddonVariantRepository(new AddonVariant()));


    }
}
