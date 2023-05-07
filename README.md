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
            'foo' => 'myKey',
            'abc' => 'myArray',
            'abc.foo' => 'foo',
            'abc.bar' => 'bar'
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
    'myKey' => 1,
    'myArray' => ['foo' => 3,'bar' => 4],
    'foo' => 3,
    'bar' => 4
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
            'foo' => 'myKey',
            'abc' => 'myArray',
            'abc.foo' => 'foo',
            'abc.bar' => 'bar'
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

$class->myKey === 1;
$class->myArray === ['foo' => 3,'bar' => 4];
$class->foo === 3;

$class->getData() === [
    'myKey' => 1,
    'myArray' => ['foo' => 3,'bar' => 4],
    'foo' => 3,
    'bar' => 4
];
```





