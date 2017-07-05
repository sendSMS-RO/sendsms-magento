<?php
namespace AnyPlaceMedia\SendSMS\Model\Config\Config\Structure\Element;
 
use \Magento\Config\Model\Config\Structure\Element\Group as OriginalGroup;
use \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as OrderStatuses;
 
class Group
{
    const DIRECTORY_REQUIRED_GROUP_ID = 'sendsms';
    protected $statusCollectionFactory;

    public function __construct(OrderStatuses $statusCollectionFactory)
    {
        $this->statusCollectionFactory = $statusCollectionFactory;
    }
 
    /**
     * Add dynamic fields
     *
     * @param OriginalGroup $subject
     * @param callable $proceed
     * @param array $data
     * @param $scope
     * @return mixed
     */
    public function aroundSetData(OriginalGroup $subject, callable $proceed, array $data, $scope)
    {
        if ($data['id'] == self::DIRECTORY_REQUIRED_GROUP_ID) {
            $dynamicFields = $this->getDynamicConfigFields();
            $data['children'] += $dynamicFields;
        }
 
        return $proceed($data, $scope);
    }

    /**
     * Get dynamic config fields (if any)
     *
     * @return array
     */
    protected function getDynamicConfigFields()
    {
        $statuses = $this->statusCollectionFactory->create()->toOptionArray();
        $dynamicConfigFields = [];
        foreach ($statuses as $status) {
            $configId = 'sendsms_settings_status_'.$status['value'];
            $dynamicConfigFields[$configId] = [
                'id' => $configId,
                'type' => 'textarea',
                'sortOrder' => 10,
                'showInDefault' => '1',
                'showInWebsite' => '0',
                'showInStore' => '0',
                'label' => 'Mesaj: '.$status['label'],
                'comment' => 'Variabile disponibile: {billing_first_name}, {billing_last_name}, {shipping_first_name}, {shipping_last_name}, {order_number}, {order_date}, {order_total}<p class="sendsms-char-count">160 caractere ramase</p>',
                '_elementType' => 'field',
                'path' => 'sendsms/sendsms'
            ];
        }
        return $dynamicConfigFields;
    }
}
