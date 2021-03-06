<?php

namespace ControlAltDelete\DuskForMagento2\Actions;

use ControlAltDelete\DuskForMagento2\Exceptions\OutOfStockException;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class AddSimpleProductToCart extends Page
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

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        $browser->assertPathIs($path);
    }

    public function addToCart(Browser $browser, $quantity = null)
    {
        if (Str::contains($browser->resolver->findOrFail('')->getText(), 'OUT OF STOCK')) {
            throw new OutOfStockException;
        }

        $browser->waitForText('Add to Cart');

        if ($quantity) {
            $browser->type('qty', $quantity);
        }

        $browser->press('Add to Cart');

        $browser->waitForText('You added ' . $this->name . ' to your');
        $browser->pause(2000);

        $browser->visit('/checkout/cart');

        $browser->assertSee($this->name);
    }
}
