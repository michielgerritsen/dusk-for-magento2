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

namespace ControlAltDelete\DuskForMagento2\PaymentMethod;

use Tests\Browser\Pages\Page;

class MoneyOrder extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/checkout';
    }

    public function placeOrder(\Laravel\Dusk\Browser $browser)
    {
        $browser->radio('payment[method]', 'checkmo');

        $browser->press('Place Order');

        $browser->pause(10000);

        $browser->waitForLocation('/checkout/onepage/success/');
    }
}
