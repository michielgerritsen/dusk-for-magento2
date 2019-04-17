<?php
/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */

namespace ControlAltDelete\DuskForMagento2\Actions;

use ControlAltDelete\DuskForMagento2\DataObjects\BundleOption;
use ControlAltDelete\DuskForMagento2\DataObjects\BundleOptionList;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class AddBundleProductToCart extends Page
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        $url,
        $name
    ) {
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    public function addToCart(Browser $browser, BundleOptionList $optionList, $quantity = null)
    {
        $browser->pause(2000);

        $browser->click('#bundle-slide');

        $browser->pause(2000);

        /** @var BundleOption $option */
        foreach ($optionList->getOptions() as $option) {
            $this->selectOption($browser, $option);
        }

        if ($quantity) {
            $browser->type('qty', $quantity);
        }

        $browser->click('#product-addtocart-button');
        $browser->pause(20000);

        $browser->waitForText('You added ' . $this->name . ' to your', 15);
        $browser->pause(2000);

        $browser->visit('/checkout/cart');

        $browser->assertSee($this->name);
    }

    /**
     * @param Browser $browser
     * @param BundleOption $option
     */
    private function selectOption(Browser $browser, BundleOption $option): void
    {
        $browser->select('bundle_option[' . $option->getOptionId() . ']', $option->getValueId());

        if ($quantity = $option->getQuantity()) {
            $browser->type('bundle_option_qty[' . $option->getOptionId() . ']', $quantity);
        }
    }
}
