<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Model;

class RefrigeratorCleaning extends Model
{
    const DURATION = 3000;

    const NAME = 'Refrigerator Cleaning';

    protected function __construct(protected string $name = self::NAME, protected int $duration = self::DURATION)
    {
        parent::__construct($name,$duration);
    }
}