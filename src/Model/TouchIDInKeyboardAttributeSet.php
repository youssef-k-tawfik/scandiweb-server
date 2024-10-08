<?php

/**
 * This class represents the Touch ID in keyboard attribute set.
 * 
 * @package App\Model
 */

namespace App\Model;

class TouchIDInKeyboardAttributeSet extends AttributeSetType
{
    const TYPE = "Touch ID in keyboard";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
