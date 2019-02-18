<?php

namespace ControlAltDelete\DuskForMagento2\Actions;

use Laravel\Dusk\Browser;
use ControlAltDelete\DuskForMagento2\DataObjects\Address;
use Tests\Browser\Pages\Page;

class FillShippingAddress extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/checkout/';
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function fillShippingAddress(Browser $browser, Address $address)
    {
        $browser->waitFor('#customer-email');

        $browser->type('#customer-email', $address->getEmail());
        $browser->type('firstname', $address->getFirstname());
        $browser->type('lastname', $address->getLastname());

        foreach ($address->getStreet() as $index => $value) {
            $browser->type('street[' . $index . ']', $value);
        }

        $browser->type('city', $address->getCity());
        $browser->type('postcode', $address->getPostcode());
        $browser->select('country_id', $address->getCountryId());
        $browser->type('telephone', $address->getTelephone());

        $browser->pause(5000);
    }
}
