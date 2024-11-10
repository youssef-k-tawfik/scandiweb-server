<?php

/**
 * This class represents the Touch ID in keyboard attribute set.
 * 
 * @package App\Model\Attributes
 */

namespace App\Model\Attributes;

class TouchIDInKeyboardAttributeSet extends AttributeSetType
{
    const TYPE = "Touch ID in keyboard";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
