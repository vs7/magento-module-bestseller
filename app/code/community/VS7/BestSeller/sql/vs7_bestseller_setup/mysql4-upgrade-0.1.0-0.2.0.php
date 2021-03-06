<?php
$installer = $this;

$installer->startSetup();

$installer->addAttribute("catalog_product", "vs7_bestseller_ordered_sum", array(
    "type" => "decimal",
    "backend" => "",
    "label" => "Ordered Sum",
    "input" => "text",
    "source" => "",
    "visible" => true,
    "required" => false,
    "default" => "0",
    "frontend" => "",
    "unique" => false,
    'used_in_product_listing' => true,
    'searchable' => true,
    'filterable' => true,
    "note" => "",
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->endSetup();