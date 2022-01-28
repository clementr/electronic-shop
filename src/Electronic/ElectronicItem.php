<?php

namespace Shop\Electronic;

use Exception;

abstract class ElectronicItem
{
    public int $price;
    protected string $type;
    protected bool $wired;
    protected ElectronicItems $extras;
    
    const ELECTRONIC_ITEM_CONTROLLER = 'controller';
    const ELECTRONIC_ITEM_TELEVISION = 'television';
    const ELECTRONIC_ITEM_CONSOLE = 'console';
    const ELECTRONIC_ITEM_MICROWAVE = 'microwave';

    public static $types = [
        self::ELECTRONIC_ITEM_CONTROLLER, 
        self::ELECTRONIC_ITEM_CONSOLE, 
        self::ELECTRONIC_ITEM_MICROWAVE, 
        self::ELECTRONIC_ITEM_TELEVISION
    ];
    
    public function __construct(float $price, bool $wired = true, array $extras = [])
    {
        $this->setPrice($price);
        $this->setWired($wired);
        $this->setExtras($extras);
    }
    
    /**
     * Return the price of the ElectroItem
     * 
     * @return float Price of the item
     */
    
    /**
     * Return the price of the ElectroItem
     * 
     * @param bool $includeExtras If true, return the price including all the associated extras (including extras of extras if applicable)
     * @return float Price of the item
     */
    public function getPrice(bool $includeExtras = false): float
    {
        $price = $this->price;
        
        if(!$includeExtras){
            return $price / 100;
        }
        
        if($this->hasExtras()){
            foreach($this->getExtras()->getItemsByType() as $extra){
                if($extra->hasExtras()){
                    $price += $extra->getPrice() * 100;
                }
                $price += $extra->price;
            }
        }
        
        return $price / 100;
    }
    
    /**
     * Return the type of the item
     * 
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * 
     * @return bool
     */
    public function getWired(): bool
    {
        return $this->wired;
    }
    
    /**
     * Set the price (concerted in cents)
     * 
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price * 100;
    }
    
    /**
     * Set the type of the ElectronicItem
     * 
     * @param string $type Should be controller|television|console|microwave
     * @return void
     */
    public function setType(string $type): void
    {
        if(!in_array($type, self::$types, true)){
            throw new Exception(sprintf('The ElectronicItem «%s» is not a valid type', $type));
        }
        
        $this->type = $type;
    }
    
    /**
     * 
     * @param bool $wired
     * @return void
     */
    public function setWired(bool $wired): void
    {
        $this->wired = $wired;
    }
    
    /**
     * Add an extra item to the ElectronicItem
     * 
     * @param ElectronicItem $electronicItem
     * @return void
     */
    public function addExtra(ElectronicItem $electronicItem): void
    {
        if($this->maxExtras()){
            throw new Exception(sprintf('The ElectronicItem «%s» has reached its max capacity of extras', $electronicItem->getType()));
        }
        
        $this->extras->addItem($electronicItem);
    }
    
    /**
     * Add a set of extra ElectronicItem to an ElectornicItem
     * 
     * @param array $extras
     * @return void
     */
    public function setExtras(array $extras = []): void
    {
        $this->extras = new ElectronicItems();
        
        if(!empty($extras)){
            foreach($extras as $electronicItem){
                $this->addExtra($electronicItem);
            }
        }
    }
    
    /**
     * Check if the electronic item as extra items attached to it
     * 
     * @return bool
     */
    public function hasExtras(): bool
    {
        return $this->extras->getQty() !== 0;
    }
    
    /**
     * Return the extras (ElectronicItems) of the ElectronicItem
     * 
     * @return ElectronicItems
     */
    public function getExtras(): ElectronicItems
    {
        return $this->extras;
    }
    
    /**
     * Specific to each electronic item. 
     * Each sub class of ElectronicItem should implement this method
     */
    abstract protected function maxExtras(): bool;
}
