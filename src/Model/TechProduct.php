<?php

/**
 * This class represents the tech product type.
 * 
 * @package App\Model
 */

namespace App\Model;

use App\Controller\ProductsResolver;

class TechProduct extends ProductType
{
    private const CATEGORY = 'tech';

    public static function getCategory(): string
    {
        return self::CATEGORY;
    }

    public static function getAllProducts(): array
    {
        return ProductsResolver::getAllProducts(self::getCategory());
    }
}
