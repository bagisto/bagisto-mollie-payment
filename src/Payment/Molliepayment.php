<?php
namespace Webkul\Mollie\Payment;

use Webkul\Checkout\Facades\Cart;
use Webkul\Mollie\Payment\Payment;
use Mollie\Laravel\Facades\Mollie;

/**
 * Mollie Molliepayment method class
 *
 * @author     Shaiv Roy <shaiv.roy361@webkul.com>
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Molliepayment extends Payment
{
    /**
     * Mollie Payment method code
     *
     * @var string
     */
    protected $code  = 'molliePayment';

    public function __construct()
    {
      $this->payment = Mollie::api()->payments();
    }

    /**
     * preparePayment method code
     *
     * @return string
     */

    public function preparePayment()
    {
        try {

            $APIkey = core()->getConfigData('sales.paymentmethods.molliePayment.apikey');

            Mollie::api()->setApiKey($APIkey);

            $cart = Cart::getCart();

            $price = $cart->base_grand_total;

            $price = str_replace('$', '', $price);

            //remove last two zero from the price
            $price = number_format($price, 2);

            // dd($price);
            /*
            * Payment parameters:
            *   amount        Amount accordiing to the cart.
            *   description   Description of the payment.
            *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
            *   webhookUrl    Webhook location, used to report when the payment changes state.
            *   metadata      Custom metadata that is stored with the payment.
            */

            $payment = $this->payment->create([
            'amount' => [
                'currency' => core()->getBaseCurrencyCode(),
                'value' => $price,
            ],
            'description' => core()->getConfigData('sales.paymentmethods.molliePayment.description'),
            'webhookUrl' => route('mollie.payment.webhook'),
            'redirectUrl' => route('mollie.payment.success'),
            'metadata' => array(
                            'order_id' => $cart->id,
                            ),
            ]);

            session()->put('mollieId',$payment->id);

            $payment = $this->payment->get($payment->id);

            return $payment->_links->checkout->href;

        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            // dd($e->getMessage());
            $data = [
                'error' => $e->getMessage(),
                'false' => 'false',
            ];
            return $data;
        }


    }

    /**
     * Return mollie redirect url
     *
     * @var string
     */

    public function getRedirectUrl()
    {
        return route('mollie.payment.redirect');
    }

}