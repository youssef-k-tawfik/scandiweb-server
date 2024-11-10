<?php

/**
 * This class represents the GraphQL schema.
 * It builds the schema for the GraphQL server.
 * It uses the controllers to resolve the queries and mutations.
 * 
 * @package App\View
 */

namespace App\View;

// * Controllers
use App\Controller\AttributesResolver;
use App\Controller\OrderResolver;
use App\Controller\ProductsResolver;

// * Models
use App\Model\Category;
use App\Model\Products\ClothesProduct;
use App\Model\Products\TechProduct;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;

class GraphQLSchema
{
    static public function buildSchema(): Schema
    {
        $currencyType = new ObjectType([
            'name' => 'Currency',
            'fields' => [
                'label' => ['type' => Type::string()],
                'symbol' => ['type' => Type::string()],
            ],
        ]);

        $pricesType = new ObjectType([
            'name' => 'Price',
            'fields' => [
                'amount' => ['type' => Type::float()],
                'currency' => ['type' => $currencyType],
            ],
        ]);

        $attributeType = new ObjectType([
            'name' => 'Attribute',
            'fields' => [
                'id' => ['type' => Type::string()],
                'displayValue' => ['type' => Type::string()],
                'value' => ['type' => Type::string()],
            ],
        ]);

        $attributeSetsType = new ObjectType([
            'name' => 'AttributeSet',
            'fields' => [
                'id' => ['type' => Type::string()],
                'items' => ['type' => Type::listOf($attributeType)],
                'type' => ['type' => Type::string()],
            ],
        ]);

        $productType = new ObjectType([
            'name' => 'Product',
            'fields' => [
                'id' => ['type' => Type::string()],
                'name' => ['type' => Type::string()],
                'inStock' => ['type' => Type::boolean()],
                'gallery' => ['type' => Type::listOf(Type::string())],
                'description' => ['type' => Type::string()],
                'category' => ['type' => Type::string()],
                'brand' => ['type' => Type::string()],
                'prices' => ['type' => Type::listOf($pricesType)],
                'attributes' => [
                    'type' => Type::listOf($attributeSetsType),
                    'resolve' => static fn($product) =>
                    AttributesResolver::getAttributes($product),
                ],
            ],
        ]);

        $categoryType = new ObjectType([
            'name' => 'Category',
            'fields' => [
                'name' => ['type' => Type::string()],
            ],
        ]);

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf($categoryType),
                    'resolve' => static fn() => Category::getCategories(),
                ],
                'allProducts' => [
                    'type' => Type::listOf($productType),
                    'resolve' => static fn() => ProductsResolver::getAllProducts(),
                ],
                'clothesProducts' => [
                    'type' => Type::listOf($productType),
                    'resolve' => static fn() => ClothesProduct::getAllProducts(),
                ],
                'techProducts' => [
                    'type' => Type::listOf($productType),
                    'resolve' => static fn()  => TechProduct::getAllProducts(),
                ],
                'productById' => [
                    'type' => $productType,
                    'args' => [
                        'id' => ['type' => Type::nonNull(Type::string())],
                    ],
                    'resolve' => static fn($root, $args) => ProductsResolver::getProductById($args['id']),
                ],
            ],
        ]);

        // ~ Mutations ~ //

        $orderResponseType = new ObjectType([
            'name' => 'OrderResponse',
            'fields' => [
                'order_number' => ['type' => Type::nonNull(Type::string())],
            ],
        ]);

        $selectedAttributeInputType = new InputObjectType([
            'name' => 'SelectedAttribute',
            'fields' => [
                'id' => ['type' => Type::string()],
                'value' => ['type' => Type::string()],
            ],
        ]);

        $orderItemInputType = new InputObjectType([
            'name' => 'OrderItemInput',
            'fields' => [
                'product_id' => ['type' => Type::nonNull(Type::string())],
                'quantity' => ['type' => Type::nonNull(Type::int())],
                'selectedAttributes' => ['type' => Type::nonNull(Type::listOf($selectedAttributeInputType))],
            ],
        ]);

        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => $orderResponseType,
                    'args' => [
                        'orderItems' => ['type' => Type::nonNull(Type::listOf($orderItemInputType))],
                    ],
                    'resolve' => [OrderResolver::class, 'placeOrder'],
                ],
            ],
        ]);

        return new Schema(
            (new SchemaConfig())
                ->setQuery($queryType)
                ->setMutation($mutationType)
        );
    }
}
