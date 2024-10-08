<?php

/**
 * This class represents the size attribute set.
 * 
 * @package App\Model
 */

namespace App\Model;



class SizeAttributeSet extends AttributeSetType
{

    const TYPE = "Size";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
