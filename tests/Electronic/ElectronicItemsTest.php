<?php

use PHPUnit\Framework\TestCase;
use Shop\Electronic\ElectronicItem;
use Shop\Electronic\ElectronicItems;
use Shop\Electronic\Items\EIConsole;
use Shop\Electronic\Items\EIController;
use Shop\Electronic\Items\EIMicrowave;
use Shop\Electronic\Items\EITelevision;

final class ElectronicItemsTest extends TestCase
{      
    public function testInit(): void
    {
        $electronicItems = new ElectronicItems(
            [
                new EIConsole(123), 
                new EITelevision(456), 
                new EIController(789), 
                new EIMicrowave(101112)
            ]
        );
        
        $this->assertCount(4, $electronicItems->getItemsByType());
    }
    
    public function testAddItem(): void
    {
        $electronicItems = new ElectronicItems();
        $this->assertEquals(0, $electronicItems->getQty());
        $this->assertCount(0, $electronicItems->getItemsByType());
        
        $electronicItems->addItem(new EIController(789));
        $this->assertEquals(1, $electronicItems->getQty());
    }
    
    public function testGetSortedItems(): void
    {
        $electronicItems = new ElectronicItems();
        $electronicItems->addItem(new EIController(380));
        $electronicItems->addItem(new EIController(100));
        $electronicItems->addItem(new EIController(250));
        $electronicItems->addItem(new EIController(25));
        
        $electronicItemsSorted = $electronicItems->getSortedItems();
        
        $this->assertEquals(25, $electronicItemsSorted[0]->getPrice());
        $this->assertEquals(100, $electronicItemsSorted[1]->getPrice());
        $this->assertEquals(250, $electronicItemsSorted[2]->getPrice());
        $this->assertEquals(380, $electronicItemsSorted[3]->getPrice());
        
        //Check with $type filter
        $electronicItems->addItem(new EIMicrowave(70));
        $this->assertCount(5, $electronicItems->getSortedItems());
        
        //Test no items "console"
        $electronicItemsSorted = $electronicItems->getSortedItems(ElectronicItem::ELECTRONIC_ITEM_CONSOLE);
        $this->assertEmpty($electronicItemsSorted);
        
        $electronicItemsSorted = $electronicItems->getSortedItems(ElectronicItem::ELECTRONIC_ITEM_CONTROLLER);
        
        $this->assertEquals(25, $electronicItemsSorted[0]->getPrice());
        $this->assertEquals(100, $electronicItemsSorted[1]->getPrice());
        $this->assertEquals(250, $electronicItemsSorted[2]->getPrice());
        $this->assertEquals(380, $electronicItemsSorted[3]->getPrice());
        $this->assertCount(4, $electronicItemsSorted);
        
    }
    
}