<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Service\Cart\CartService;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    protected $cartService;

    public function __construct(CartService $cartService){
        $this->cartService = $cartService;
    }


    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getTotalQty',[$this,'getTotalQty'])
        ];
    }

    public function getTotalQty() : int
    {
        return $this->cartService->getCartQty();
    }
}
