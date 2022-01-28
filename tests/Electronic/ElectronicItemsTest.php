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
    
    public function testQuestion1Flat(): void
    {
        //Setup the list of electro items for Question #1
        $electronicItems = $this->getQuestion1ElectronicItems();
        
        $electronicItemsSorted = $electronicItems->getSortedItems(null, true);
        
        $this->assertEquals(17, $electronicItemsSorted[0]->getPrice());
        $this->assertEquals(17, $electronicItemsSorted[1]->getPrice());
        $this->assertEquals(17, $electronicItemsSorted[2]->getPrice());
        $this->assertEquals(25, $electronicItemsSorted[3]->getPrice());
        $this->assertEquals(25, $electronicItemsSorted[4]->getPrice());
        $this->assertEquals(39.99, $electronicItemsSorted[5]->getPrice());
        $this->assertEquals(39.99, $electronicItemsSorted[6]->getPrice());
        $this->assertEquals(145.85, $electronicItemsSorted[7]->getPrice());
        $this->assertEquals(379, $electronicItemsSorted[8]->getPrice());
        $this->assertEquals(399.55, $electronicItemsSorted[9]->getPrice());
        $this->assertEquals(549.99, $electronicItemsSorted[10]->getPrice());
        
    }
    
    public function testQuestion1Nested(): void
    {
        //Setup the list of electro items for Question #1
        $electronicItems = $this->getQuestion1ElectronicItems();
        
        $electronicItemsSorted = $electronicItems->getSortedItems(null, false);
        
        $this->assertEquals(145.85, $electronicItemsSorted[0]->getPrice());
        $this->assertEquals(379, $electronicItemsSorted[1]->getPrice());
        $this->assertEquals(399.55, $electronicItemsSorted[2]->getPrice());
        $this->assertEquals(549.99, $electronicItemsSorted[3]->getPrice());
    }
    
    private function getQuestion1ElectronicItems(): ElectronicItems
    {
        $wiredControllerConsole = new EIController(25);
        $wiredControllerConsole2 = new EIController(25);
        $wireless = new EIController(39.99, false);
        $wireless2 = new EIController(39.99, false);
        
        $console = new EIConsole(
            549.99, 
            true, 
            [
                $wiredControllerConsole, 
                $wiredControllerConsole2, 
                $wireless, 
                $wireless2
            ]
        );
        
        //TV #1 (2 remote controllers)
        $television1 = new EITelevision(379);
        $television1->addExtra(new EIController(17, false));
        $television1->addExtra(new EIController(17, false));
        
        //TV #2 (1 remote controller)
        $television2 = new EITelevision(399.55);
        $television2->addExtra(new EIController(17, false));
        
        //MICROWAVE #1
        $microwave = new EIMicrowave(145.85);
        
        //Setup the list of electro items for Question #1
        return new ElectronicItems(
            [
                $console, 
                $television1, 
                $television2, 
                $microwave
            ]
        );
    }
}