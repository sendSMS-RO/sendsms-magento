<?php
namespace AnyPlaceMedia\SendSMS\Block;

class Regions extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function getRegions()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('customer_address_entity');
        $sql = "SELECT DISTINCT region FROM " . $tableName;
        $result = $connection->fetchAll($sql);
        return $result;
    }
}
