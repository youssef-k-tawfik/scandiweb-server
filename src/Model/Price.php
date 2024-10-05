<?php

/**
 * This class represents the price model.
 * 
 * @package App\Model
 */

namespace App\Model;

class Price
{
    public $amount;
    public $currency;

    public function __construct(array $price)
    {
        $this->amount = $price['price'];
        $this->currency = new Currency($price['currency']);
    }
}
