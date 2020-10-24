<?php

class VS7_BestSeller_Block_Tabs extends Mage_Core_Block_Template
{
    private
        $_uniqid,
        $_hashId,
        $_categoriesArray = array();

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('vs7_bestseller/tabs.phtml');
    }

    private function _getProductsCount()
    {
        $count = (int)$this->getData('count');
        if (empty($count)) {
            return 10;
        } else {
            return $count;
        }
    }

    public function getTabs()
    {
        $categories = $this->getCategoriesArray();
        $result = array();
        foreach ($categories as $categoryId => $category) {
            $tabBlock = Mage::app()
                ->getLayout()
                ->createBlock('vs7_bestseller/bestsellers')
                ->setCategoryId($categoryId)
                ->setProductCount($this->_getProductsCount())
                ->setHashId($this->getHashId());
            $tabBlockName = $tabBlock->getNameInLayout();
            $this->setChild($tabBlockName, $tabBlock);
            $tabBlockHtml = $tabBlock->toHtml();
            $result[$categoryId] = $tabBlockHtml;
        }
        return $result;
    }

    public function getUniqid()
    {
        if (!empty($this->_uniqid)) {
            return $this->_uniqid;
        }
        $this->_uniqid = uniqid();
        return $this->_uniqid;
    }

    public function getHashId()
    {
        if (!empty($this->_hashId)) {
            return $this->_hashId;
        }
        $this->_hashId = md5(serialize($this->getData()));
        return $this->_hashId;
    }

    public function getCategoriesArray() {
        if (empty($this->_categoriesArray)) {
            $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();

            $categoriesIds = explode(',', $this->getData('categories'));
            if (empty($categoriesIds)) {
                $categoriesIds = array($rootCategoryId);
            }
            if (!in_array($rootCategoryId, $categoriesIds)) {
                $categoriesIds[] = $rootCategoryId;
            }

            $_categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('name')
                ->addUrlRewriteToResult()
                ->addAttributeToFilter('entity_id', array('in' => $categoriesIds));
            foreach ($_categories as $_category) {
                if ($_category->getId() == $rootCategoryId) {
                    $_category->setName('All');
                }
                $this->_categoriesArray[$_category->getId()] = $_category;
            }
        }

        return $this->_categoriesArray;
    }

    public function getRootCategoryId()
    {
        return Mage::app()->getStore()->getRootCategoryId();
    }
}