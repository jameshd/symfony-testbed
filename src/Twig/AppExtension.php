<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter']),
        ];
    }

    public function getGlobals()
    {
        return ['locale' => $this->locale];
    }

    public function priceFilter($value, $dp = 2)
    {
        return '$' . number_format($value, $dp);
    }
}