# Laravel Dusk components for Magento 2

**Q: What is Laravel Dusk?**

A: Laravel Dusk is created to write end-to-end tests for your application. This means that when running your test, an actual Chrome browser is started which you can give instructions. Click on a link, fill in fields and submit a form.

**Q: Huh, Laravel is a framework, what has it to do with Magento?**

A: Nothing, but Laravel Dusk is capable to visit any site that is accessible from the machine it runs on.  

**Q: So how do i use this?**

A: You need a full Laravel setup. But where do you install this? There are a few options:

- **Install it in a seperate directory next to Magento** 
  
  Your folder structure would look like this:

  Websites
    - `my-magento2-store`
    - `my-laravel-dusk-project`
    
  This is the cleanest way, but it may be a bit hard to maintain the code as it typically requires 2 code bases.
  
- **Install it into the Magento directory** 
  
  Go to your Magento folder and run `laravel new end-to-end-tests`.

- **Install a standalone Laravel Dusk version in Magento 2**
  
  I haven't tried this, but in theory it could work:
  https://duncan3dc.github.io/dusk/
  
**Q: So how does the test looks like?**
 
A: When your Laravel install is ready to go, the next thing to do is install Laravel Dusk in your Laravel project:

`composer require laravel/dusk`

Create the file `test/Browser/OrderTest.php` with this contents:

```
namespace Tests\Browser;

use ControlAltDelete\DuskForMagento2\DataObjects\Address;
use ControlAltDelete\DuskForMagento2\Pages\AddProduct;
use ControlAltDelete\DuskForMagento2\Pages\ShippingAddress;
use ControlAltDelete\DuskForMagento2\PaymentMethod\MoneyOrder;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class OrderProductsTest extends DuskTestCase
{
    protected function baseUrl()
    {
        return 'http://my-super-cool-project.test';
    }

    public function testOrderProducts()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

            $browser->visit(new AddProduct('/fusion-backpack.html', 'Fusion Backpack'))->addToCart(2);
            $browser->visit(new AddProduct('/push-it-messenger-bag.html', 'Push It Messenger Bag'))->addToCart(3);

            $browser->visit('/checkout/');

            $address = new Address(
                'michiel@controlaltdelete.nl', // E-mail
                'Michiel', // Firstname
                'Gerritsen', // Lastname
                ['Simonszand 69'], // Array of streetlines
                'Hoofddorp', // City
                '2134ZX', // Postcode
                'NL', // Country id
                '0031623925470' // Postcode
            );

            $browser->visit(new ShippingAddress())->fillShippingAddress($address);

            $browser->press('Next');

            $browser->waitUntilMissing('.loading-mask');
            $browser->waitFor('.payment-method');

            $browser->on(new MoneyOrder())->placeOrder();

            $browser->assertSee('Thank you for your purchase!');
        });
    }
}
```

And run:

`php artisan dusk`

When there is a successful test, check your orders. There should be a new one.

**Q: My test fails, what to do?**

A: There are a few thing:

- For starters, try it a few times. There are a few functions in there that might have a smaller timeout than the average Magento installation requires to warm up it's caches. So it might well be the case that when you try it a few times all caches get warmed up and your test succeeds.  
- If that doesn't help, check the screenshot. Everytime a test fails, Dusk will create a screenshot in tests/Browser/screenshots. It also tries to give you a hint on wat went wrong, a missing element for example.
- This code is written on a stock Magento 2 installation, so there might be some changes in your installation, elements with a different name for example. Try to tweak it here and there. 


**Note**

This is mainly created as a proof of concept. It works in my environment, but there is a decent change it doesn't work right away in yours. Feel free to open an issue or pull request to improve these components. This code is tested on a stock Magento 2.2.6 and 2.3.0 with sample data.

## Components overview

`Pages\AddProduct`

Add a product to you shopping with the optional given quantity cart. It verifies that the product is added to the cart.

**Usage**

```
$browser->visit(new AddProduct($relativeUrl, $name))->addToCart($quantity = null);
$browser->visit(new AddProduct('/fusion-backpack.html', 'Fusion Backpack'))->addToCart(2);
```

When you enter a quantity it is required to have the quantity field enable on the product page. You can repeat this with different products to create shopping cart with differen items in them.

---

`Pages\ShippingAddress`

Navigate to the checkout and fill the shipping address. It is required to provide an `\ControlAltDelete\DuskForMagento2\DataObjects\Address` object.

**Usage**

```
$browser->visit(new ShippingAddress())->fillShippingAddress($address);
```

---

`PaymentMethod\MoneyOrder`

This selects the moneyorder payment method and clicks the *Place order* button.

**Usage**

```$browser->on(new MoneyOrder())->placeOrder();```

---

`Pages\AdminLogin`

Login on the admin panel.

**Usage**
```
$browser->visit(new AdminLogin($frontName))->fillForm($username, $password);
$browser->visit(new AdminLogin('my-custom-frontname'))->fillForm('my-username', 'my-password');
```

---

`Pages\ConfigurationGroup`

Navigate to a configuration group. Note: The capitalization is important here. *Payment methods* will fail, while *Payment Methods* will succeed.

**Usage**

```
$browser->on(new ConfigurationGroup)->open($tab', $name);
$browser->on(new ConfigurationGroup)->open('Sales', 'Payment Methods');
```
