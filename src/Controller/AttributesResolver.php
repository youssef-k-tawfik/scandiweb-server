<?php

/**
 * This class is responsible for resolving the attributes of a product.
 * It resolves the attributes of a product based on the attribute set type.
 * 
 * @package App\Controller
 */

namespace App\Controller;

use App\Model\Attributes\AttributeFactory;
use Exception;

class AttributesResolver
{

    public static function getAttributes($product): array
    {
        $attributes = $product->attributes ?? [];

        if (empty($attributes)) {
            return [];
        }

        $attributeFactory = new AttributeFactory();

        $finalAttributeSet = array_map(function ($attributeSet) use ($attributeFactory) {
            $type = $attributeSet['id'];
            return $attributeFactory->createAttributeSet($type, $attributeSet);
        }, $attributes);

        return $finalAttributeSet;
    }
}
