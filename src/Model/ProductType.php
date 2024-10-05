<?php

/**
 * This abstract class represents a product model.
 * It uses the Price class to represent the prices of the product.
 * It implements the Product interface to enforce the implementation of the getAllProducts method.
 * 
 * @package App\Model
 */

namespace App\Model;

use App\Controller\ProductsResolver;

require 'AttributeSetType.php';

interface Product
{
    public static function getAllProducts();
}

abstract class ProductType implements Product
{
    public string $id;
    public string $name;
    public string $description;
    public string $category;
    public string $brand;
    public bool   $inStock;
    public array  $gallery;
    public array  $prices;
    public array  $attributes;

    public function __construct(
        string $id,
        string $name,
        string $description,
        string $brand,
        bool   $inStock,
        array  $gallery,
        array  $prices,
        array  $attributes
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->category = $this->getCategory();
        $this->brand = $brand;
        $this->inStock = $inStock;
        $this->gallery = $gallery;
        $this->prices = $this->getPrices($prices);
        $this->attributes = $attributes;
    }

    abstract static public function getCategory(): string;

    public function getPrices($prices): array
    {
        if (empty($prices)) {
            error_log("No prices found!");
            return ["No prices found!"];
        }

        $finalPrices = array_map(function ($price) {
            return new Price($price);
        }, $prices);

        return $finalPrices;
    }
}

class ClothesProduct extends ProductType
{
    public static function getCategory(): string
    {
        return "clothes";
    }

    public static function getAllProducts(): array
    {
        return ProductsResolver::getAllProducts(self::getCategory());
    }
}

class TechProduct extends ProductType
{
    public static function getCategory(): string
    {
        return "tech";
    }

    public static function getAllProducts(): array
    {
        return ProductsResolver::getAllProducts(self::getCategory());
    }
}
