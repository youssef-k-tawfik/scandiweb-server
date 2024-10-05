<?php

/**
 * This class represents the currency model.
 * 
 * @package App\Model
 */

namespace App\Model;

class Currency
{
    public $label;
    public $symbol;

    public function __construct(array $currency)
    {
        $this->label = $currency['label'];
        $this->symbol = $currency['symbol'];
    }
}
