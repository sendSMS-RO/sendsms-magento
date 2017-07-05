<?php

namespace AnyPlaceMedia\SendSMS\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class OrderSave
 *
 * Watch for order status change
 *
 * @package AnyPlaceMedia\SendSMS\Observer
 */
class OrderSave implements ObserverInterface
{
    protected $scopeConfig;
    protected $storeDate;
    protected $history;
    protected $helper;
    protected $pricingHelper;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \AnyPlaceMedia\SendSMS\Model\HistoryFactory $history,
        \AnyPlaceMedia\SendSMS\Helper\SendSMS $helper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeDate = $date;
        $this->history = $history;
        $this->helper = $helper;
        $this->pricingHelper = $pricingHelper;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $status = $order->getStatus();
        $text = $this->scopeConfig->getValue(
            'sendsms/sendsms/sendsms_settings_status_'.$status,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!empty($text)) {
            $text = $this->replaceVariables($text, $order);
            $this->helper->sendSMS($order->getBillingAddress()->getTelephone(), $text, 'order');
        }
    }

    /**
     * @param $message
     * @param $order
     * @return string
     */
    private function replaceVariables($message, $order)
    {
        $billingAddress = $order->getBillingAddress()->getData();
        $shippingAddress = $order->getShippingAddress()->getData();
        $formattedPrice = $this->pricingHelper->currency($order->getGrandTotal(), true, false);

        $replace = array(
            '{billing_first_name}' => $this->helper->cleanDiacritice($billingAddress['firstname']),
            '{billing_last_name}' => $this->helper->cleanDiacritice($billingAddress['lastname']),
            '{shipping_first_name}' => $this->helper->cleanDiacritice($shippingAddress['firstname']),
            '{shipping_last_name}' => $this->helper->cleanDiacritice($shippingAddress['lastname']),
            '{order_number}' => $order->getRealOrderId(),
            '{order_date}' => date('d.m.Y', strtotime($order->getCreatedAt())),
            '{order_total}' => $formattedPrice
        );
        foreach ($replace as $key => $value) {
            $message = str_replace($key, $value, $message);
        }

        return $message;
    }
}
