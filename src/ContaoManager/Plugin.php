<?php

namespace Neckarpixel\NpgridtoolsBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Neckarpixel\NpgridtoolsBundle\NeckarpixelNpgridtoolsBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        $loadAfter = [ContaoCoreBundle::class];

        // Bewusst kein Composer-"require" auf npelements – nur lose Kopplung:
        // Ist npelements installiert, soll unsere DCA-Datei NACH der von npelements
        // laden, damit wir prüfen können, ob "layout_legend" schon existiert, statt
        // eine zweite "Layout-Einstellungen"-Sektion zu erzeugen.
        if (class_exists('Neckarpixel\\NpelementsBundle\\NeckarpixelNpelementsBundle')) {
            $loadAfter[] = 'Neckarpixel\\NpelementsBundle\\NeckarpixelNpelementsBundle';
        }

        return [
            BundleConfig::create(NeckarpixelNpgridtoolsBundle::class)
                ->setLoadAfter($loadAfter),
        ];
    }
}
