<?php
namespace Webkul\Mollie\Helpers;

use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
/**
 * Mollie Webhook listener helper
 *
 * @author    shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Webhook
{
    /**
     * Webhook post data
     *
     * @var array
     */
    protected $post;
    /**
     * Order object
     *
     * @var object
     */
    protected $order;
    /**
     * OrderRepository object
     *
     * @var object
     */
    protected $orderRepository;
    /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;
    /**
     * Create a new helper instance.
     *
     * @param  Webkul\Sales\Repositories\OrderRepository   $orderRepository
     * @param  Webkul\Sales\Repositories\InvoiceRepository $invoiceRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * This function process the webhook sent from mollie
     *
     * @param array $post
     * @return void
     */

    public function processWebhook($post)
    { 
        $this->post = $post;
      
        try {

            $this->getOrder();
            $this->processOrder();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Load order via webhook order id
     *
     *
     * @return void
     */
    protected function getOrder()
    {
        if (empty($this->order)) {
            $this->order = $this->orderRepository->findOneByField(['cart_id' => $this->post->metadata->order_id]);
        }
    }

    /**
     * Process order and create invoice
     *
     *
     * @return void
     */
    protected function processOrder()
    {
      
        if ($this->post->amount->value != $this->order->base_grand_total) {
            
        } else {
            $this->orderRepository->update(['status' => 'processing'], $this->order->id);
            
            if ($this->order->canInvoice()) {
                
                $this->invoiceRepository->create($this->prepareInvoiceData());
            }
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     *
     * @return array
     */
    protected function prepareInvoiceData()
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