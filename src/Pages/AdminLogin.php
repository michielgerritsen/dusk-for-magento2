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

namespace ControlAltDelete\DuskForMagento2\Pages;

use Laravel\Dusk\Browser;

class AdminLogin extends \Tests\Browser\Pages\Page
{
    /**
     * @var string
     */
    private $frontName;

    public function __construct(
        $frontName
    ) {
        $this->frontName = $frontName;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/' . $this->frontName;
    }

    public function fillForm(Browser $browser, $username, $password)
    {
        $browser->type('login[username]', $username);
        $browser->type('login[password]', $password);

        $browser->press('Sign in');
    }
}
