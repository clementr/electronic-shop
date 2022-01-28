<?php

namespace Shop\Electronic\Items;

trait EITrait 
{
    /**
     * Determine wether or not the limit of extras have been reached for an ElectronicItem
     * 
     * @return bool Return true if the max number of extra have been reached, return false otherwise
     */
    protected function maxExtras(): bool
    {        
        if(self::MAX_EXTRA === null){
            return false;
        }
        
        return $this->extras->getQty() >= self::MAX_EXTRA;
    }
}