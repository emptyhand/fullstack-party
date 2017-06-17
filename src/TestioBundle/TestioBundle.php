<?php

namespace TestioBundle;

use TestioBundle\DependencyInjection\TestioBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class TestioBundle
 * @package TestioBundle
 */
class TestioBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestioBundleExtension();
    }
}
