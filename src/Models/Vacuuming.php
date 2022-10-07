<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Model;

class Vacuuming extends Model
{
    const DURATION = 1260;

    const NAME = 'Vacuuming';

    protected function __construct(protected string $name = self::NAME, protected int $duration = self::DURATION)
    {
        parent::__construct($name,$duration);
    }
}