<?php

namespace OC\BookingBundle\Twig;

use Symfony\Component\Intl\Intl;

class CountryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('country', [$this, 'country']),
        ];
    }

    public function country($countryCode)
    {
        \Locale::setDefault('fr');

        return Intl::getRegionBundle()->getCountryName($countryCode);
    }

    public function getName()
    {
        return 'country_extension';
    }
}
