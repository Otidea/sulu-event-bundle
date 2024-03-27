<?php

declare(strict_types=1);

namespace Otd\SuluEventBundle;

use Otd\SuluEventBundle\DependencyInjection\OtdSuluEventExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OtdSuluEventBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new OtdSuluEventExtension();
    }
}
