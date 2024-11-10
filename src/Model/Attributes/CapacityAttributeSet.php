<?php

/**
 * This class represents the capacity attribute set.
 * 
 * @package App\Model\Attributes
 */

namespace App\Model\Attributes;

class CapacityAttributeSet extends AttributeSetType
{
    const TYPE = "Capacity";
    public function getAttributeSetType()
    {

        return self::TYPE;
    }
}
