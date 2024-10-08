<?php

/**
 * This class represents the USB 3 ports attribute set.
 * 
 * @package App\Model
 */

namespace App\Model;

class USB3PortsAttributeSet extends AttributeSetType
{

    const TYPE = "USB 3 ports";

    public function getAttributeSetType()
    {
        return self::TYPE;
    }
}
