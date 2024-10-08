<?php

/**
 * This class represents the color attribute set.
 * 
 * @package App\Model
 */

namespace App\Model;

class ColorAttributeSet extends AttributeSetType
{
    const TYPE = "Color";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
