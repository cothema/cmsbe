<?php

namespace Cothema\DAO\Entities;

use Kdyby\Doctrine\Entities;

/**
 * Description of StandardEntity
 *
 * @author Milos Havlicek <miloshavlicek@gmail.com>
 */
abstract class StandardEntity extends Entities\BaseEntity
{

    protected function emptyToNull($in)
    {
        return trim($in) === '' ? null : $in;
    }

    protected function fixDecimalValue($in)
    {
        return $in === null ? null : str_replace(' ', '', str_replace(',', '.', $in));
    }
}
