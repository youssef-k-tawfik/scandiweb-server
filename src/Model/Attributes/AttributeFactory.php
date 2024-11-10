<?php

/**
 * This class is responsible for creating the attributes of a product.
 * It creates the attributes of a product based on the attribute set type.
 * 
 * @package App\Model\Attributes
 */

namespace App\Model\Attributes;

use Exception;

class AttributeFactory
{
    private $attributeSetMap = [
        'Size' => SizeAttributeSet::class,
        'Color' => ColorAttributeSet::class,
        'Capacity' => CapacityAttributeSet::class,
        'USB 3 ports' => USB3PortsAttributeSet::class,
        'Touch ID in keyboard' => TouchIDInKeyboardAttributeSet::class,
    ];

    public function createAttributeSet($type, $attributeSet)
    {
        if (!isset($this->attributeSetMap[$type])) {
            throw new Exception("Unknown Attribute Set type: " . $type);
        }

        $attributeSetClass = $this->attributeSetMap[$type];

        try {
            return new $attributeSetClass($attributeSet);
        } catch (Exception $e) {
            error_log("Error instantiating $attributeSetClass: " . $e->getMessage());
            throw $e;
        }
    }
}
