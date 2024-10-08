<?php

/**
 * This class is responsible for resolving the attributes of a product.
 * It resolves the attributes of a product based on the attribute set type.
 * 
 * @package App\Controller
 */

namespace App\Controller;

use App\Model\SizeAttributeSet;
use App\Model\ColorAttributeSet;
use App\Model\CapacityAttributeSet;
use App\Model\USB3PortsAttributeSet;
use App\Model\TouchIDInKeyboardAttributeSet;
use Exception;

class AttributesResolver
{
    // private static $instanceCount = 0;

    public static $attributeSetMap = [
        'Size' => SizeAttributeSet::class,
        'Color' => ColorAttributeSet::class,
        'Capacity' => CapacityAttributeSet::class,
        'USB 3 ports' => USB3PortsAttributeSet::class,
        'Touch ID in keyboard' => TouchIDInKeyboardAttributeSet::class,
    ];

    public static function getAttributes($product): array
    {

        // error_log("Resolving attributes for product: " . print_r($product->attributes, true));

        // self::$instanceCount++;
        $attributes = $product->attributes ?? [];

        if (empty($attributes)) {
            return [];
        }

        $attributeSetMap = self::$attributeSetMap;

        $finalAttributeSet = array_map(function ($attributeSet) use ($attributeSetMap) {
            $type = $attributeSet['id'];
            if (!isset($attributeSetMap[$type])) {
                throw new Exception("Unknown Attribute Set type: " . $type);
            }

            $attributeSetClass = $attributeSetMap[$type];

            try {
                return new $attributeSetClass($attributeSet);
            } catch (Exception $e) {
                error_log("Error instantiating $attributeSetClass: " . $e->getMessage());
                throw $e;
            }
        }, $attributes);

        // error_log("All attributes resolved!");
        // error_log("Attributes retrieved successfully No. " . self::$instanceCount);

        return $finalAttributeSet;
    }
}
