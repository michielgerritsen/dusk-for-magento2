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

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class NavigateToConfigurationGroup extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/this-is-variable';
    }

    public function open(Browser $browser, $tab, $name)
    {
        // Not sure why the first is capitalized and the other not, but it fails if we don't
        $browser->waitForText('STORES');
        $browser->clickLink('Stores');

        $browser->with('[aria-labelledby="menu-magento-backend-stores"]', function ($browser) {
            $browser->clickLink('Configuration');
        });

        $result = $browser->resolver->all('.config-nav-block');

        $found = false;
        /** @var \Facebook\WebDriver\Remote\RemoteWebElement $element */
        foreach ($result as $element) {
            if ($element->getText() !== strtoupper($tab)) {
                continue;
            }

            $found = true;
            if (stripos($element->getAttribute('class'), 'hide')) {
                $element->click();
            }

            break;
        }

        if (!$found) {
            throw new \Exception('No tab named "' . $tab . '" found');
        }

        $browser->with('.admin__page-nav-items', function ($browser) use ($name) {
            $browser->clickLink($name);
        });
    }
}
