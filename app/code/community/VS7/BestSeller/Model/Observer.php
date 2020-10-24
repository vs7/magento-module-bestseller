<?php
class VS7_BestSeller_Model_Observer
{
    private function _getFromDate()
    {
        $months = (int)Mage::getStoreConfig('vs7_bestseller/general/period');
        return date("Ymd",strtotime('-' . $months . ' month'));
    }

    private function _getToDate()
    {
        return date("Ymd");
    }

    public function setOrderedQty() {
        $this->setOrderedQtyInStore();
        $stores = Mage::app()->getStores();
        foreach ($stores as $store) {
            $this->setOrderedQtyInStore($store->getId());
        }
    }

    public function setOrderedQtyInStore($storeId = 0)
    {
        $from_date = $this->_getFromDate();
        $to_date = $this->_getToDate();

        $collection = Mage::getResourceModel('reports/product_sold_collection')
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            ->addAttributeToSelect('price')
            ->addOrderedQty($from_date, $to_date)
            ->setOrder('ordered_qty', 'DESC');

        foreach ($collection as $product) {
            $orderedProducts[$product->getId()] = array(
                'ordered_qty' => $product->getOrderedQty(),
                'ordered_sum' => $product->getOrderedQty() * $product->getPrice()
            );
        }

        $products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('vs7_bestseller_ordered_qty')
            ->addAttributeToSelect('vs7_bestseller_ordered_sum');
        $action = Mage::getModel('catalog/resource_product_action');
        foreach ($products as $product) {
            if (in_array($product->getId(), array_keys($orderedProducts))) {
                $action->updateAttributes(array($product->getId()), array(
                    'vs7_bestseller_ordered_qty' => $orderedProducts[$product->getId()]['ordered_qty'],
                    'vs7_bestseller_ordered_sum' => $orderedProducts[$product->getId()]['ordered_sum']
                ), $storeId);
            } else if(
                $product->getData('vs7_bestseller_ordered_qty') != null
                || $product->getData('vs7_bestseller_ordered_sum') != null
            ) { //null other
                $action->updateAttributes(array($product->getId()), array(
                    'vs7_bestseller_ordered_qty' => null,
                    'vs7_bestseller_ordered_sum' => null
                ), $storeId);
            }
        }
    }
}