<?php declare(strict_types=1); 

namespace App;

use Symfony\Component\Console\Application;
use Traversable;

class App extends Application
{
    /**
     * @author Aleksandar Atanasov
     * @param iterable $commands
     * @param string $version
     */
    public function __construct(iterable $commands, string $version)
    {

        $commands = $commands instanceof Traversable ? \iterator_to_array($commands) : $commands;

        foreach ($commands as $command) {
            $this->add($command);
        }

        parent::__construct('Cleaning Schedule Generator', $version);

    }
}