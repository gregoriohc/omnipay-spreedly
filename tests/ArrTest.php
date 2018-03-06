<?php

namespace Omnipay\Spreedly\Tests;

use Omnipay\Spreedly\Arr;
use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testAdd()
    {
        $array = Arr::add(array('name' => 'Desk'), 'price', 100);
        $this->assertEquals(array('name' => 'Desk', 'price' => 100), $array);
    }

    public function testDivide()
    {
        list($keys, $values) = Arr::divide(array('name' => 'Desk'));
        $this->assertEquals(array('name'), $keys);
        $this->assertEquals(array('Desk'), $values);
    }

    public function testDot()
    {
        $array = Arr::dot(array('foo' => array('bar' => 'baz')));
        $this->assertEquals(array('foo.bar' => 'baz'), $array);
    }

    public function testExcept()
    {
        $array = array('name' => 'Desk', 'price' => 100);
        $array = Arr::except($array, array('price'));
        $this->assertEquals(array('name' => 'Desk'), $array);
    }

    public function testFirst()
    {
        $array = array(100, 200, 300);

        $value = Arr::first($array, function ($key, $value) {
            return $value >= 150;
        });

        $this->assertEquals(200, $value);

        $array = array(100, 200, 300);

        $value = Arr::first($array, function ($key, $value) {
            return $value == 150;
        }, 0);

        $this->assertEquals(0, $value);
    }

    public function testLast()
    {
        $array = array(100, 200, 300);
        $last = Arr::last($array, function () {
            return true;
        });
        $this->assertEquals(300, $last);
    }

    public function testFlatten()
    {
        $array = array('name' => 'Joe', 'languages' => array('PHP', 'Ruby'));
        $array = Arr::flatten($array);
        $this->assertEquals(array('Joe', 'PHP', 'Ruby'), $array);
    }

    public function testGet()
    {
        $array = array('products' => array('desk' => array('price' => 100)));
        $value = Arr::get($array, 'products.desk');
        $this->assertEquals(array('price' => 100), $value);
    }

    public function testHas()
    {
        $array = array('products' => array('desk' => array('price' => 100)));
        $this->assertTrue(Arr::has($array, 'products.desk'));
        $this->assertTrue(Arr::has($array, 'products.desk.price'));
        $this->assertFalse(Arr::has($array, 'products.foo'));
        $this->assertFalse(Arr::has($array, 'products.desk.foo'));
    }

    public function testOnly()
    {
        $array = array('name' => 'Desk', 'price' => 100, 'orders' => 10);
        $array = Arr::only($array, array('name', 'price'));
        $this->assertEquals(array('name' => 'Desk', 'price' => 100), $array);
    }

    public function testPull()
    {
        $array = array('name' => 'Desk', 'price' => 100);
        $name = Arr::pull($array, 'name');
        $this->assertEquals('Desk', $name);
        $this->assertEquals(array('price' => 100), $array);
    }

    public function testSet()
    {
        $array = array('products' => array('desk' => array('price' => 100)));
        Arr::set($array, 'products.desk.price', 200);
        Arr::set($array, 'products.desk.name', 'Magnum');
        Arr::set($array, 'foo.bar', 'test');
        $this->assertEquals(array('products' => array('desk' => array('price' => 200, 'name' => 'Magnum')), 'foo' => array('bar' => 'test')), $array);
    }

    public function testWhere()
    {
        $array = array(100, '200', 300, '400', 500);

        $array = Arr::where($array, function ($key, $value) {
            return is_string($value);
        });

        $this->assertEquals(array(1 => 200, 3 => 400), $array);
    }

    public function testForget()
    {
        $array = array('products' => array('desk' => array('price' => 100)));
        Arr::forget($array, null);
        $this->assertEquals(array('products' => array('desk' => array('price' => 100))), $array);

        $array = array('products' => array('desk' => array('price' => 100)));
        Arr::forget($array, array());
        $this->assertEquals(array('products' => array('desk' => array('price' => 100))), $array);

        $array = array('products' => array('desk' => array('price' => 100)));
        Arr::forget($array, 'products.desk');
        $this->assertEquals(array('products' => array()), $array);

        $array = array('products' => array('desk' => array('price' => 100)));
        Arr::forget($array, 'products.desk.price');
        $this->assertEquals(array('products' => array('desk' => array())), $array);

        $array = array('products' => array('desk' => array('price' => 100)));
        Arr::forget($array, 'products.final.price');
        $this->assertEquals(array('products' => array('desk' => array('price' => 100))), $array);

        $array = array('shop' => array('cart' => array(150 => 0)));
        Arr::forget($array, 'shop.final.cart');
        $this->assertEquals(array('shop' => array('cart' => array(150 => 0))), $array);

        $array = array('products' => array('desk' => array('price' => array('original' => 50, 'taxes' => 60))));
        Arr::forget($array, 'products.desk.price.taxes');
        $this->assertEquals(array('products' => array('desk' => array('price' => array('original' => 50)))), $array);

        $array = array('products' => array('desk' => array('price' => array('original' => 50, 'taxes' => 60))));
        Arr::forget($array, 'products.desk.final.taxes');
        $this->assertEquals(array('products' => array('desk' => array('price' => array('original' => 50, 'taxes' => 60)))), $array);
    }
}