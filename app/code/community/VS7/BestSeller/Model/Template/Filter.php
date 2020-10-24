<?php

class VS7_BestSeller_Model_Template_Filter extends Mage_Widget_Model_Template_Filter
{
    public function bestsellerDirective($construction)
    {
        $params = $this->_getIncludeParameters($construction[2]);

        if (
            !isset($params['category_id'])
            ||!isset($params['products_count'])
            ||!isset($params['template'])
            ||empty($params['category_id'])
            ||empty($params['products_count'])
            ||empty($params['template'])
        ) {
            return;
        }
        $blockHtml = Mage::app()
            ->getLayout()
            ->createBlock('vs7_bestseller/bestsellers')
            ->setCategoryId($params['category_id'])
            ->setProductCount($params['products_count'])
            ->setTemplate($params['template'])
            ->toHtml();

        return $blockHtml;
    }
}