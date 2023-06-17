# PHP Mapper

#### RU
Маппинг массива любой вложенности в новый массив или свойства класса. 
В процессе обработки массива можно изменять данные своими методами.

#### EN
Mapping an array of any nesting into a new array or class properties.
In the process of processing the array, you can change the data with your own methods.

#### Install
```
composer require smirnov-o/mapper
```
#### Base mapping

```php
class Map extends Mapper
{
    public function getMap()
    {
        return [
            'myKey'   => 'foo',
            'myArray' => 'abc',
            'foo'     => 'abc.foo',
            'bar'     => 'abc.bar'
        ];
    }
}

$array = [
            'foo' => 1,
            'abc' => [
                    'foo' => 3,
                    'bar' => 4
                ]       
        ];

$class = new Map($array);

$class->getData() === [
    'myKey'   => 1,
    'myArray' => [
            'foo' => 3,
            'bar' => 4
                ],
    'foo'     => 3,
    'bar'     => 4
];
```
#### Set property class

```php
class Map extends Mapper implements MapperObject
{
    public ?string $myKey;
    public ?mixed $myArray;
    public ?int $foo;

    public function getMap()
    {
        return [
            'myKey'   => 'foo',
            'myArray' => 'abc',
            'foo'     => 'abc.foo',
            'bar'     => 'abc.bar'
        ];
    }
}

$array = [
            'foo' => 1,
            'abc' => [
                    'foo' => 3,
                    'bar' => 4
                ]       
        ];

$class = new Map($array);

$class->myKey   === 1;
$class->myArray === [
            'foo' => 3,
            'bar' => 4
            ];
$class->foo     === 3;

$class->getData() === [];
```
#### Use cast

```php
class Map extends Mapper implements MapperObject
{
    public ?string $myKey;
    public ?mixed $myArray;
    public ?int $foo;

    public function getMap()
    {
        return [
            'myKey'   => 'foo',
            'myArray' => 'abc',
            'foo'     => 'abc.foo',
            'bar'     => 'abc.bar'
        ];
    }
    
    public function getCast()
    {
        return [
            'myKey'   => 'myCast',
            'myArray' => 'mySomeCast'
        ];
    }
    
    public function myCast(mixed $data)
    {
        return $data + 100;
    }
    
    public function mySomeCast(mixed $data)
    {
        return 'changeValue';
    }
}

$array = [
            'foo' => 1,
            'abc' => [
                    'foo' => 3,
                    'bar' => 4
                ]       
        ];

$class = new Map($array);

$class->myKey     === 101;
$class->myArray   === 'changeValue';
$class->foo       === 3;
$class->getData() === [];
```
#### Static Call

```php
$array = [
    'a' => '1', 
    'b' => 100
    ];
    
$maps = [
    'a' => 'a', 
    'b' => 'b'
    ];

$array = MapperStatic::getArray($array, $maps)->getData();
$array['a'] === 1;
$array['b'] === 100;

$object = MapperStatic::getObject($array, $maps);
$object->a === 1;
$object->b === 1000;
```

