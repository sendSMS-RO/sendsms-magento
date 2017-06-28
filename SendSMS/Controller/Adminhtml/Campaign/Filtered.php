<?php
namespace AnyPlaceMedia\SendSMS\Controller\Adminhtml\Campaign;

class Filtered extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $startDate = urldecode($this->getRequest()->getParam('start_date'));
        $endDate = urldecode($this->getRequest()->getParam('end_date'));
        $minSum = urldecode($this->getRequest()->getParam('min_sum'));
        $product = urldecode($this->getRequest()->getParam('product'));
        $county = urldecode($this->getRequest()->getParam('county'));

        # do query to get phone numbers

        return $this->resultPageFactory->create();
    }

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return true;
    }
}
