<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Model;

class WindowCleaning extends Model
{
    const DURATION = 2100;

    const NAME = 'Window Cleaning';

    protected function __construct(protected string $name = self::NAME, protected int $duration = self::DURATION)
    {
        parent::__construct($name,$duration);
    }
}