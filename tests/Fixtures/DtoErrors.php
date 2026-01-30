<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Tests\Fixtures;

use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * class DtoErrors
 *
 * @property int $hello
 */
final class DtoErrors extends Dto
{
    #[ElementName('errors')]
    public string $errors;

    #[ElementName('init')]
    public int $init;
}
