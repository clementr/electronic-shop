<?php

namespace Shop\Electronic;

class ElectronicItems
{
    private array $items;
    
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }
    
    /**
     * Returns the items ordered by price (asc) depending on the sorting type requested
     * 
     * @param string $type Type of electronic: console|television|microwave|controller|null
     * @param bool $recursive If true we will consider all items recursively (and not only the first level)
     * @return array Return the sorted items of $type. Return all items if $type is null
     */
    public function getSortedItems(string $type = null, bool $recursive = false): array
    {
        $sorted = $this->getItemsByType($type, $recursive);
        
        usort($sorted, function($a, $b){
            return ($a->price <= $b->price) ? -1 : 1;
        });
                
        return $sorted;
    }

    /**
     * Return the list of ElectronicItem by type
     * 
     * @param string $type
     * @return array
     */
    
    /**
     * Return the list of ElectronicItem by type
     * 
     * @param string $type
     * @param bool $recursive If true we will consider all items recursively (and not only the first level)
     * @return array
     */
    public function getItemsByType(string $type = null, bool $recursive = false): array
    {
        $items = $this->items;
        
        if($recursive){
            foreach($this->items as $electronicItem){
                if($electronicItem->hasExtras()){
                    $items = array_merge(
                        $items, 
                        $electronicItem->getExtras()->getItemsByType($type, $recursive)
                    );
                }
            }
        }
        
        if($type === null){
            return $items;
        }
        
        $callback = function ($item) use ($type) {
            return $item->getType() === $type;
        };

        return array_filter($items, $callback);
    }
    
    /**
     * Add an electronic item to the list of electronic items
     * 
     * @param ElectronicItem $electronicItem
     * @throws \Exception
     */
    public function addItem(ElectronicItem $electronicItem): void
    {        
        $this->items[] = $electronicItem;
    }
    
    /**
     * Return the number of ElectronicItem within the ElctroItems list
     * 
     * @return int
     */
    public function getQty(): int
    {
        return count($this->items);
    }
    
    /**
     * Return total amount for all the cumulated ElectroItem within the ElectroItems list
     * 
     * @param bool $includingExtras If true we consider the price of extras ElectroItem recursively
     * @return float Total amount
     */
    public function getTotal(bool $includingExtras = true): float
    {
        $total = 0;
        
        if($this->getQty() === 0){
            return $total;
        }
        
        foreach($this->getItemsByType() as $item){
            $total += $item->getPrice($includingExtras);
        }
        
        return $total;
    }
}