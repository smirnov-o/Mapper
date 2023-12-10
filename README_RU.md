# PHP Mapper

![Packagist Version (custom server)](https://img.shields.io/packagist/v/smirnov-o/Mapper)
![Packagist License (custom server)](https://img.shields.io/packagist/l/smirnov-o/Mapper)

### Методы маппера
Если необходимо, чтобы конструктор класс был пустой, то нужно использовать этот метод.<br>
```php
public function init(array $data): static
```
Возвращает маппинг полей. Обязательно.<br>
```php
public function getMap(): array;
```
Возвращает массив готовых данных. В случае со свойствами класса, будет пустой.<br>
```php
public function getData(): array;
```
Возвращает список методов применяемых для изменения поля.<br>
```php
public function getCast(): array;
```
В процессе маппинга не было совпадений не найдено.<br>
```php
public function isNotEmpty(): bool;
```
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
````
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
#### Select data
Возможность выбора данных из нескольких полей.
Поля перечисляются через `||`. После нахождения первого, поиск прекращается.

```php
    public function getMap()
    {
        return [
            'myKey'   => 'foo',
            'myArray' => 'abc',
            'foo'     => 'abc.foo||abc.foo||abc.foo',
            'bar'     => 'abc.bar||abc.bar||abc.bar'
        ];
    }
```
### Dto
```php
final class DtoExample extends Dto {
    /**
     * @var string
     */
    #[ElementName('test')]
    public int $test;
    
    /**
     * @var string
     */
    #[ElementName('bar||a.b')]
    public int $foo;
    
    /**
     * @var int
     */
    #[ElementName('int'), CastMethod('cast')]
    public int $cast;
    
     /**
     * @var int
     */
    #[ElementName('hello'), CastDefault(100)]
    public int $castDefInt;
    
    /**
     * @param int $val
     * @return int
     */
    public function cast(int $val): int
    {
        return $val + 100;
    }
}

$dto = new DtoExample(['int' => 100, 'test' => 'test','a' => ['b' => 'foo']]);
or
$dto = new DtoExample();
$dto->init(['int' => 100, 'test' => 'test','a' => ['b' => 'foo']])

$dto->test = 200;
$dto->cast = 200;
$dto->castDefInt = 100;
$dto->foo = 'foo';
$dto->taArrya() = [
            'test' => test
            'int' => 100
            'str' => 100
            'cast' => 200
            'cast1' => string
            'castDefStr' => string
            'castDefInt' => 100
            'castDefArray' => [1,2,3]
            'castDefArray1' => [1,2,3]
            'foo' => foo
        ];
$dto->has('dto') === false;
```