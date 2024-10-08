<?php

/**
 * This abstract class represents the attribute set model.
 * It uses Attribute class to represent the attributes of the attribute set.
 * 
 * @package App\Model
 */

namespace App\Model;

abstract class AttributeSetType
{
    public string $id;
    public array  $items;
    public string $type;

    public function __construct($attributeSet)
    {
        // error_log("creating attribute set");
        // error_log(print_r($attributeSet, true));
        $this->id = $this->getAttributeSetType();
        $this->items = $this->getAttributesList($attributeSet["items"]);
        $this->type = $attributeSet["type"];
    }

    /**
     * Get the type of the attribute set.
     * 
     * @return string The type of the attribute set.
     */
    abstract public function getAttributeSetType();

    /**
     * Get the list of attributes for the attribute set.
     *  
     * @param array $setItems The items of the attribute set.
     * @return array The list of attributes.
     */
    public function getAttributesList($items)
    {
        return array_map(
            fn($item) =>  $this->getAttributeDetails($item),
            $items
        );
    }

    /**
     * Get the details of the attribute.
     * 
     * @param array $item The attribute item.
     * @return Attribute The attribute details.
     */
    public function getAttributeDetails($item)
    {
        // error_log("creating attribute". print_r($item, true));
        return new Attribute(
            $item["displayValue"],
            $item["value"],
            $item["id"]
        );
    }
}
