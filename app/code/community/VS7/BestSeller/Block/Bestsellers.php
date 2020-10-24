<?php

class VS7_BestSeller_Block_Bestsellers extends Mage_Catalog_Block_Product_Abstract
{
    private $_productCollection;
    private $_productCount = 7;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('vs7_bestseller/tab.phtml');
    }

    public function setProductCount($productCount)
    {
        $this->_productCount = $productCount;
        return $this;
    }

    public function getProductCount()
    {
        return $this->_productCount;
    }

    public function getProductCollection()
    {
        if (isset($this->_productsCollection)) {
            return $this->_productsCollection;
        }

        $categoryId = $this->getCategoryId();
        if (empty($categoryId)) return;

        $category = Mage::getModel('catalog/category')->load($categoryId);
        $this->_productCollection = $category->getProductCollection();
        $this->_productCollection
            ->addStoreFilter()
            ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addFinalPrice()
            ->addUrlRewrite()
            ->addAttributeToSort('vs7_bestseller_ordered_qty', 'DESC')
            ->setCurPage(1)
            ->setPageSize($this->getProductCount());
        $data = array();
        if ($this->getParentBlock()) {
            $data = $this->getParentBlock()->getData();
        }
        if (
            isset($data['filter'])
            && !empty($data['filter'])
            && isset($data['filter-value'])
            && !empty($data['filter-value'])
        ) {
            if ($data['filter'] == 'sale') {
                $select = $this->_productCollection->getSelect();
                if (
                    $data['filter-value'] == true
                    || $data['filter-value'] == '1'
                ) {
                    $select->where('price_index.final_price < price_index.price');
                } else {
                    $select->where('price_index.final_price = price_index.price');
                }
            } else {
                $this->_productCollection->addAttributeToFilter($data['filter'], array('eq' => $data['filter-value']));
            }
        }
        return $this->_productCollection;
    }
}