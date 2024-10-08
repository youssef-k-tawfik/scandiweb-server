<?php

/**
 * This class represents the capacity attribute set.
 * 
 * @package App\Model
 */

namespace App\Model;

class CapacityAttributeSet extends AttributeSetType
{
    const TYPE = "Capacity";
    public function getAttributeSetType()
    {

        return self::TYPE;
    }
}
