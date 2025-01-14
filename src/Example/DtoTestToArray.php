<?php
declare(strict_types=1);

namespace SmirnovO\Mapper\Example;

use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * class DtoTestToArray
 *
 * @property int $hello
 */
final class DtoTestToArray extends Dto
{
    /**
     * @var string
     */
    #[ElementName('str1')]
    public string $str1;

    /**
     * @var string
     */
    #[ElementName('str2')]
    public string $str2;

    /**
     * @var string
     */
    #[ElementName('errors')]
    public string $errors;

    /**
     * @var int
     */
    #[ElementName('init')]
    public int $init;
}
