<?php
declare(strict_types=1);

namespace SmirnovO\Mapper\Example;

use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * class DtoErrors
 */
final class DtoErrors extends Dto
{
    /**
     * @var string
     */
    #[ElementName('errors')]
    public string $errors;

    /**
     * @var string
     */
    #[ElementName('errors1')]
    public string $errors1;

    /**
     * @var int
     */
    #[ElementName('init')]
    public int $init;
}
