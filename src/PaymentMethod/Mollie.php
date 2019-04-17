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

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Mollie extends Page
{
    private $paymentPageScreenshotName = null;

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/checkout';
    }

    public function setPaymentPageScreenshotName(Browser $browser, $name)
    {
        $this->paymentPageScreenshotName = $name;

        return $browser;
    }

    public function placeOrder(\Laravel\Dusk\Browser $browser, $finalState = 'paid')
    {
        $browser->radio('payment[method]', 'mollie_methods_ideal');

        $browser->radio('issuer', 'ideal_INGBNL2A');

        $browser->waitUntilMissing('.loading-mask');

        $browser->press('Place Order');

        $browser->waitUntilMissing('.loading-mask');
        $browser->waitForText('iDEAL - Test mode');

        if ($this->paymentPageScreenshotName) {
            $browser->screenshot($this->paymentPageScreenshotName);
        }

        $browser->radio('final_state', $finalState);
        $browser->press('Continue');
    }
}
