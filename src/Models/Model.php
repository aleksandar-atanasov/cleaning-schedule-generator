<?php declare(strict_types=1);

namespace App\Models;

abstract class Model
{

    protected function __construct(protected string $name, protected int $duration){}

    /**
    * @author Aleksandar Atanasov
    * @return static
    */
    public static function make()
    {
        return new static;
    }

    /**
    * @author Aleksandar Atanasov
    * @return string
    */
    public function getName() : string
    {
        return $this->name;
    }

    /**
    * @author Aleksandar Atanasov
    * @return int
    */
    public function getDuration() : int
    {
        return $this->duration;
    }
}