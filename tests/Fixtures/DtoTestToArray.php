<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Tests\Fixtures;

use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * class DtoTestToArray
 *
 * @property int $hello
 */
final class DtoTestToArray extends Dto
{
    #[ElementName('str1')]
    public string $str1;

    #[ElementName('str2')]
    public string $str2;

    #[ElementName('errors')]
    public string $errors;

    #[ElementName('init')]
    public int $init;
}
