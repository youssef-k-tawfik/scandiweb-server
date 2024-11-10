<?php

/**
 * This class is responsible for resolving the products.
 * It resolves the products based on the category.
 *  
 * @package App\Controller
 */

namespace App\Controller;

use App\Model\Products\ProductFactory;
use App\Model\Products\ProductType;
use App\Model\Database;

use Exception;

class ProductsResolver
{

    public static function getAllProducts(string $category = null): array
    {
        try {
            error_log("Getting all products from ProductResolver!");
            $db = Database::getInstance();
            if ($category) {
                $db->setFilter('category', $category);
            }

            $results = $db->query();
            error_log("Got the results!");
            $productsData = self::prepareProductsList($results);
            $factory = new ProductFactory();
            $allProducts = array_map(
                function ($product) use ($factory) {
                    return $factory->createProduct($product);
                },
                $productsData
            );

            error_log("All products retrieved successfully!");
            return $allProducts;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function getProductById(string $id)
    {
        try {
            $db = Database::getInstance();
            $results = $db->setFilter('id', $id)->query();

            if (empty($results)) {
                error_log("Product not found");
                throw new Exception("Product not found");
            }

            $productData = self::prepareProductsList($results);
            $factory = new ProductFactory();
            $product = $factory->createProduct($productData[$id]);
            // error_log("desc " . $product->description);
            return $product;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepares a list of products from the database query results.
     *
     * @param array $results The database query results.
     * @return array The prepared list of products.
     */
    public static function prepareProductsList(array $results): array
    {
        $productsData = [];
        foreach ($results as $row) {
            if (!isset($productsData[$row['id']])) {
                $productsData[$row['id']] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'description' => htmlspecialchars($row['description']),
                    'category' => $row['category'],
                    'brand' => $row['brand'],
                    'inStock' => $row['inStock'],
                    'gallery' => [],
                    'prices' => [],
                    'attributes' => []
                ];
            }

            if ($row['gallery_image'] && !in_array($row['gallery_image'], $productsData[$row['id']]['gallery'])) {
                $productsData[$row['id']]['gallery'][] = $row['gallery_image'];
            }

            if ($row['price'] && $row['currency_label'] && $row['currency_symbol']) {
                $priceExists = false;
                foreach ($productsData[$row['id']]['prices'] as $price) {
                    if ($price['price'] == $row['price'] && $price['currency']['label'] == $row['currency_label']) {
                        $priceExists = true;
                        break;
                    }
                }

                if (!$priceExists) {
                    $productsData[$row['id']]['prices'][] = [
                        'price' => $row['price'],
                        'currency' => [
                            'label' => $row['currency_label'],
                            'symbol' => $row['currency_symbol']
                        ]
                    ];
                }
            }

            if ($row['attribute_id']) {
                $attributeItem = [
                    'displayValue' => $row['attribute_display_value'],
                    'value' => $row['attribute_value'],
                    'id' => $row['attribute_id'],
                ];

                $attributeSetId = $row['attribute_set_id'];

                if (!isset($productsData[$row['id']]['attributes'][$attributeSetId])) {
                    $productsData[$row['id']]['attributes'][$attributeSetId] = [
                        'id' => $row['attribute_set_name'],
                        'items' => [],
                        'name' => $row['attribute_set_name'],
                        'type' => $row['attribute_set_type']
                    ];
                }

                $attributeExists = false;
                foreach ($productsData[$row['id']]['attributes'][$attributeSetId]['items'] as $item) {
                    if ($item['id'] == $row['attribute_id']) {
                        $attributeExists = true;
                        break;
                    }
                }

                if (!$attributeExists) {
                    $productsData[$row['id']]['attributes'][$attributeSetId]['items'][] = $attributeItem;
                }
            }
        }

        return $productsData;
    }
}
