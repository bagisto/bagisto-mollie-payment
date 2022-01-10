<?php
namespace Webkul\Mollie\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Mollie\Helpers\Webhook;
use Mollie\Laravel\Facades\Mollie;
use Webkul\Sales\Repositories\InvoiceRepository;

/**
 * Mollie controller
 *
 * @author    shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class MollieController extends Controller
{    
    /**
     * Order object
     *
     * @var object
     */
    protected $order;

    /**
     * OrderRepository object
     *
     * @var array
     */

    protected $orderRepository;

    /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;

    /**
     * Mollie object
     *
     * @var array
     */

    protected $webhookHelper;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  Webkul\Sales\Repositories\InvoiceRepository $invoiceRepository
     * 
     * @return void
     */
    
    public function __construct(
        OrderRepository $orderRepository,
        Webhook $webhookHelper,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->webhookHelper = $webhookHelper;

        $this->invoiceRepository = $invoiceRepository;

        $APIkey = core()->getConfigData('sales.paymentmethods.molliePayment.apikey');
        
        Mollie::api()->setApiKey($APIkey);
    }

    /**
     * Redirects to the Mollie.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {   
        return view('mollie::standard-redirect');
    }

    /**
     * Success payment
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {   
        $id = session()->get('mollieId');
        
        $payment = Mollie::api()->payments()->get($id);
        
        if($payment->isPaid()){   
            $order = $this->orderRepository->create(Cart::prepareDataForOrder());

            $this->order = $this->orderRepository->findOneWhere([
                'cart_id' => Cart::getCart()->id
            ]);
    
            $this->orderRepository->update(['status' => 'processing'], $this->order->id);
    
            $this->invoiceRepository = app('Webkul\Sales\Repositories\InvoiceRepository');
    
            if ($this->order->canInvoice()) {
                $this->invoiceRepository->create($this->prepareInvoiceData());
            }
       
            Cart::deActivateCart();
            
            session()->flash('order', $order);

            return redirect()->route('shop.checkout.success');
        } else {
            session()->flash('error', 'Mollie payment has been canceled.');

            return redirect()->route('shop.checkout.cart.index');
        }
       
    }

    /**
     * Mollie listener
     *
     * @return \Illuminate\Http\Response
     */
    public function webhook()
    {  
        $id = request()->id;

        $payment = Mollie::api()->payments()->get($id);
   
        if($payment->isPaid()){  

            return 'true';
            
        } else {
            return 'false';
        }

    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
    */
    public function prepareInvoiceData()
    {
        $invoiceData = [
            "order_id" => $this->order->id
        ];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

}