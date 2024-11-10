<?php

/**
 * This class represents the color attribute set.
 * 
 * @package App\Model\Attributes
 */

namespace App\Model\Attributes;

class ColorAttributeSet extends AttributeSetType
{
    const TYPE = "Color";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
