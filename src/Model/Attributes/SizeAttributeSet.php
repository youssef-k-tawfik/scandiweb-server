<?php

/**
 * This class represents the size attribute set.
 * 
 * @package App\Model\Attributes
 */

namespace App\Model\Attributes;



class SizeAttributeSet extends AttributeSetType
{

    const TYPE = "Size";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
