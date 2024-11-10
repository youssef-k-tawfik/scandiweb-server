<?php

/**
 * This class represents the attribute model.
 *
 * @package App\Model\Attributes
 */

namespace App\Model\Attributes;

class Attribute
{
    public string $displayValue;
    public string $value;
    public string $id;

    public function __construct(
        string $displayValue,
        string $value,
        string $id
    ) {
        $this->displayValue = $displayValue;
        $this->value = $value;
        $this->id = $id;
    }
}
