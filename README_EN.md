# PHP Mapper

![Packagist Version (custom server)](https://img.shields.io/packagist/v/smirnov-o/Mapper)
![Packagist License (custom server)](https://img.shields.io/packagist/l/smirnov-o/Mapper)

### Mapper methods
If it is necessary for the class constructor to be empty, then you need to use this method.
```php
public function init(array $data): static
```
Returns mapping of fields. Necessarily.
```php
public function getMap(): array;
```
Returns an array of ready data. In the case of class properties, it will be empty.
```php
public function getData(): array;
```
Returns a list of methods used to change the field.
```php
public function getCast(): array;
```
Returns `true` if at least one field was set during mapping (value is not `null`). Otherwise `false`.
```php
public function isNotEmpty(): bool;
```
**Key behavior in getMap():** you can specify multiple keys with `||` (e.g. `'foo' => 'a||b.c||d'`). The **first non-empty** value is used: first `a`, then `b.c`, then `d`.

**MapperStatic / anonymous class:** if a class implementing `MapperObject` returns an empty array from `getMap()`, the map passed to the constructor (`$map`) is used instead.

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
#### Select data
EAbility to select data from multiple fields.<br>
Fields are listed with `||`. After finding the first one, the search stops.

```php
    public function getMap()
    {
        return [
            'myKey'   => 'foo',
            'myArray' => 'abc',
            'foo'     => 'abc.foo||abc.foo||abc.foo',
            'bar'     => 'abc.bar||abc.bar||abc.bar',
        ];
    }
```
### Dto
Attributes

1. Element Name - finding the value by name
2. Cast Default - if there is no value, then the Default specified in the Case is set.
3. CastMethodDefault - if there is no value, then the value is initialized using the CastMethodDefault method.
4. CastMethod - changes the value of the method from CastMethod
5. CanBeNull - if an array element has arrived with a NULL value, it can be initialized with NULL (! it is important to specify
   the null option in the property)
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
     * @var string
     */
    #[ElementName('castMethod'), CastMethodDefault('castMethod')]
    public string $castMethod;
    
    /**
     * @var string|null
     */
    #[ElementName('canNull'), CanBeNull()]
    public ?string $canNull;
    
    /**
     * @param int $val
     * @return int
     */
    public function cast(int $val): int
    {
        return $val + 100;
    }
    
    /**
     * @return string
     */
    public function castMethod(): string
    {
        return 'vasa';
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
### Dto Methods
#### Method getErrors
Returns parsing errors.
Example:
```php
[
    errors => 'Cannot assign array to property ...\DtoErrors::$errors of type string',
    errors1 => 'Cannot assign array to property ...\DtoErrors::$errors1 of type string'
]
```