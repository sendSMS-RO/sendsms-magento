<?php
namespace AnyPlaceMedia\SendSMS\Controller\Adminhtml\Campaign;

class Index extends \Magento\Backend\App\Action
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
        $postData = $this->getRequest()->getParam('campaign_form');
        if (is_array($postData)) {
            if (!empty($postData['start_date'])) {
                $postData['start_date'] = urlencode($postData['start_date']);
            }
            if (!empty($postData['start_date'])) {
                $postData['start_date'] = urlencode($postData['start_date']);
            }
            if (!empty($postData['min_sum'])) {
                $postData['start_date'] = urlencode($postData['min_sum']);
            }
            if (!empty($postData['product'])) {
                $postData['product'] = urlencode($postData['product']);
            }
            if (!empty($postData['county'])) {
                $postData['county'] = urlencode($postData['county']);
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/filtered', $postData);
        }
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
