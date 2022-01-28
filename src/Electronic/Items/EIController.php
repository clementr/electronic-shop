<?php

namespace Shop\Electronic\Items;

use Shop\Electronic\ElectronicItem;

class EIController extends ElectronicItem
{
    use EITrait;
    
    CONST MAX_EXTRA = 0;
    
    public function __construct(float $price, bool $wired = true, array $extras = [])
    {
        parent::__construct($price, $wired, $extras);
        
        $this->setType(self::ELECTRONIC_ITEM_CONTROLLER);
    }
}