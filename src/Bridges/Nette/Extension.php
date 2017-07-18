<?php

namespace Prtpe\Bridges\Nette;

use Nette\DI\CompilerExtension;
use Prtpe\Prtpe;


/**
 * Class Extension
 *
 * @author  geniv
 * @package Prtpe\Bridges\Nette
 */
class Extension extends CompilerExtension
{
    /** @var array default values */
    private $defaults = [
        'userId'   => null,
        'password' => null,
        'entityId' => null,
    ];


    /**
     * Load configuration.
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        $builder->addDefinition($this->prefix('default'))
            ->setClass(Prtpe::class, [$config]);
    }
}
