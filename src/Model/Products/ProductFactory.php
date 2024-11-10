<?php

/**
 * This class is responsible for creating the products.
 * It creates the products based on the product type.
 * 
 * @package App\Model\Products
 */

namespace App\Model\Products;

use Exception;

class ProductFactory
{
    private $productMap = [
        'clothes' => ClothesProduct::class,
        'tech' => TechProduct::class,
    ];

    /**
     * Creates a product object from the provided product data.
     *
     * @param array $product The product data.
     * @return ProductType The created product object.
     * @throws Exception If the product category is unknown.
     */
    public function createProduct(array $product): ProductType
    {
        $category = $product['category'];
        if (!isset($this->productMap[$category])) {
            throw new Exception("Unknown product category: " . $product['category']);
        }

        try {
            $productClass = $this->productMap[$category];
            $product['description'] = htmlspecialchars_decode($product['description']);

            return new $productClass(
                $product['id'],
                $product['name'],
                $product['description'],
                $product['brand'],
                $product['inStock'],
                $product['gallery'],
                $product['prices'],
                $product['attributes']
            );
        } catch (Exception $e) {
            error_log("Error instantiating $productClass: " . $e->getMessage());
            throw $e;
        }
    }
}
