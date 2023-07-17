# PHP Mapper

#### RU

Маппинг массива любой вложенности в новый массив или свойства класса.
В процессе обработки массива можно изменять данные своими методами.

#### EN

Mapping an array of any nesting into a new array or class properties.
In the process of processing the array, you can change the data with your own methods.

#### Methods

RU: Если необходимо, чтобы конструктор класс был пустой, то нужно использовать этот метод.<br>
EN: If it is necessary for the class constructor to be empty, then you need to use this method.

```php
public function init(array $data): static
```

RU: Возвращает маппинг полей. Обязательно.<br>
RN: Returns mapping of fields. Necessarily.

```php
public function getMap(): array;
```

RU: Возвращает массив готовых данных. В случае со свойствами класса, будет пустой.<br> 
EN: Returns an array of ready data. In the case of class properties, it will be empty.

```php
public function getData(): array;
```

RU: Возвращает список методов применяемых для изменения поля.<br>
EN: Returns a list of methods used to change the field.

```php
public function getCast(): array;
```

RU: В процессе маппинга не было совпадений не найдено.<br>
EN: No matches were found during the mapping process.

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

RU: Возможность выбора данных из нескольких полей.
Поля перечисляются через `||`. После нахождения первого, поиск прекращается.

EN: Ability to select data from multiple fields.
Fields are listed with `||`. After finding the first one, the search stops.

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