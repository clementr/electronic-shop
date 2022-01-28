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
     * @return array Return the sorted items of $type. Return all items if $type is null
     */
    public function getSortedItems(string $type = null): array
    {
        $sorted = $this->getItemsByType($type);
        
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
    public function getItemsByType(string $type = null): array
    {
        if($type === null){
            return $this->items;
        }
        
        $callback = function ($item) use ($type) {
            return $item->getType() === $type;
        };

        return array_filter($this->items, $callback);
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
     * Return the number of ElectronicItem
     * 
     * @return int
     */
    public function getQty(): int
    {
        return count($this->items);
    }
    
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