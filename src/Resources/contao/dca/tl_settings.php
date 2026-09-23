<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/**
 * Globale Einstellung (System-Einstellungen -> "npgridtools"), welches
 * CSS-Framework GridClassListener beim Rendern der Grid-Klassen verwendet.
 *
 * tl_settings ist KEINE Datenbanktabelle, sondern wird über Contao\Config in
 * system/config/localconfig.php gespeichert - daher hier bewusst kein 'sql'.
 *
 * addLegend() mit legend=null haengt die neue Legende ohne festen Anker ans
 * Ende der bestehenden Palette an, damit wir hier nicht von einem konkreten,
 * core-internen Legenden-Namen abhaengig sind, der sich zwischen Contao-
 * Versionen aendern koennte.
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = PaletteManipulator::create()
    ->addLegend('npgridtools_legend', null, PaletteManipulator::POSITION_APPEND)
    ->addField('npGridFramework', 'npgridtools_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToString($GLOBALS['TL_DCA']['tl_settings']['palettes']['default'])
;

$GLOBALS['TL_DCA']['tl_settings']['fields']['npGridFramework'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['npGridFramework'],
    'inputType' => 'select',
    'options'   => ['bootstrap', 'tailwind'],
    'reference' => &$GLOBALS['TL_LANG']['tl_settings']['npGridFrameworkOptions'],
    'eval'      => ['tl_class' => 'w50', 'includeBlankOption' => false],
    'default'   => 'bootstrap',
];
