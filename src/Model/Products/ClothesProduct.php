<?php

/**
 * This class represents the clothes product type.
 * 
 * @package App\Model\Products
 */

namespace App\Model\Products;

use App\Controller\ProductsResolver;

class ClothesProduct extends ProductType
{
    private const CATEGORY = 'clothes';

    public static function getCategory(): string
    {
        return self::CATEGORY;
    }

    public static function getAllProducts(): array
    {
        return ProductsResolver::getAllProducts(self::getCategory());
    }
}
