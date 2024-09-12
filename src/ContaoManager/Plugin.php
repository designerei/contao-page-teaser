<?php

namespace designerei\ContaoPageTeaserBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\CoreBundle\ContaoCoreBundle;
use designerei\ContaoPageTeaserBundle\ContaoPageTeaserBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoPageTeaserBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}