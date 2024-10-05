<?php

/**
 * This class represents the attribute model.
 *
 * @package App\Model
 */

namespace App\Model;

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
