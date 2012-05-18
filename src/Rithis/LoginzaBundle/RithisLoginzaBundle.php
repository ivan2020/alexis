<?php

namespace Rithis\LoginzaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rithis\LoginzaBundle\DependencyInjection\Security\Factory\LoginzaFactory;

class RithisLoginzaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new LoginzaFactory());
    }
}
