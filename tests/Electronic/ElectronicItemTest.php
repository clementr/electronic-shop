<?php

use PHPUnit\Framework\TestCase;
use Shop\Electronic\ElectronicItem;
use Shop\Electronic\ElectronicItems;
use Shop\Electronic\Items\EIConsole;
use Shop\Electronic\Items\EIController;

final class ElectronicItemTest extends TestCase
{
           
    public function testElectronicItemSupportTypes(): void
    {
        $this->assertEmpty(
            array_diff(
                [
                    ElectronicItem::ELECTRONIC_ITEM_CONTROLLER, 
                    ElectronicItem::ELECTRONIC_ITEM_CONSOLE, 
                    ElectronicItem::ELECTRONIC_ITEM_MICROWAVE, 
                    ElectronicItem::ELECTRONIC_ITEM_TELEVISION
                ], 
                ElectronicItem::$types
            )
        );
    }
    
    public function testEIConsoleDefault(): void
    {
        $price = 123.25;
        $electronicItem = new EIConsole($price);
        
        $this->assertEquals($electronicItem::ELECTRONIC_ITEM_CONSOLE, $electronicItem->getType());
        $this->assertEquals(true, $electronicItem->getWired());
        $this->assertEquals($price, $electronicItem->getPrice());
        $this->assertEquals($price * 100, $electronicItem->price);
        $this->assertInstanceOf(ElectronicItems::class, $electronicItem->getExtras());
        
        $this->assertIsArray($electronicItem->getExtras()->getItemsByType());
        $this->assertEmpty($electronicItem->getExtras()->getItemsByType());
    }
    
    public function testEIConsoleWithExtras(): void
    {
        $price = 456;
        $priceController = 18.21;
        $priceController2 = 9.99;
        
        $electronicItem = new EIConsole($price, true, [
            new EIController($priceController, false), 
            new EIController($priceController2, true), 
        ]);
        
        $this->assertEquals(true, $electronicItem->getWired());
        $this->assertEquals($price, $electronicItem->getPrice());
        $this->assertEquals($price * 100, $electronicItem->price);
        
        //Price console + controllers
        $this->assertEquals(($price + $priceController + $priceController2), $electronicItem->getPrice(true));
        
        //Extras are type of ElectronicItems
        $this->assertInstanceOf(ElectronicItems::class, $electronicItem->getExtras());
        
        $this->assertIsArray($electronicItem->getExtras()->getItemsByType());
        $this->assertCount(2, $electronicItem->getExtras()->getItemsByType());
        
        //Extras contain ElectronicItem
        foreach($electronicItem->getExtras()->getItemsByType() as $extra){
            $this->assertInstanceOf(ElectronicItem::class, $extra);
        }
    }
    
    public function testEIConsoleMaxExtras(): void
    {
        $price = 456;
        $priceController = 12;
        
        //The console only accept 4 controllers
        $electronicItem = new EIConsole($price, true, [
            new EIController($priceController, false), 
            new EIController($priceController, true), 
            new EIController($priceController, true), 
            new EIController($priceController, true), 
        ]);
        
        $this->assertEquals(true, $electronicItem->getWired());
        $this->assertEquals($price, $electronicItem->getPrice());
        $this->assertEquals($price * 100, $electronicItem->price);
        
        //Price console + controllers
        $this->assertEquals(($price + ($priceController * 4)), $electronicItem->getPrice(true));
        
        //Extras contain ElectronicItem
        foreach($electronicItem->getExtras()->getItemsByType() as $extra){
            $this->assertInstanceOf(ElectronicItem::class, $extra);
        }
        
        $this->expectException(\Exception::class);
        $electronicItem->addExtra(new EIController($priceController, false));
    }
    
}