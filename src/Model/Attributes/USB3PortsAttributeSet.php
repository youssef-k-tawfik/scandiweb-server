<?php

/**
 * This class represents the USB 3 ports attribute set.
 * 
 * @package App\Model\Attributes
 */

namespace App\Model\Attributes;

class USB3PortsAttributeSet extends AttributeSetType
{

    const TYPE = "USB 3 ports";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
