<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Neckarpixel\NpgridtoolsBundle\EventListener\GridPaletteListener;

$strTableName = 'tl_content';

/**
 * "gridMode" und "useGridColumns" müssen global als Selector registriert sein,
 * damit ihre Subpaletten (Standard/Experte) greifen - auch wenn "gridMode" nur
 * dynamisch über den onpalette_callback in fremde Paletten injiziert wird.
 */
$GLOBALS['TL_DCA'][$strTableName]['palettes']['__selector__'][] = 'gridMode';

/**
 * 1) "Spalten verwenden" auf dem Element-Group-Element selbst.
 *
 * Läuft dank Plugin::getBundles()->setLoadAfter() erst NACH npelements (falls
 * installiert), das per foreach-Loop bereits "layout_legend" + "topMargin" in
 * ALLE Paletten einfügt - auch in "element_group". Ist die Legende schon da,
 * hängen wir nur unser Feld vorne dran; ist npelements nicht installiert,
 * legen wir die Legende selbst neu an.
 */
if (isset($GLOBALS['TL_DCA'][$strTableName]['palettes']['element_group'])) {
    $manipulator = PaletteManipulator::create();

    if (!str_contains($GLOBALS['TL_DCA'][$strTableName]['palettes']['element_group'], 'layout_legend')) {
        $manipulator->addLegend('layout_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE);
    }

    $manipulator
        ->addField('useGridColumns', 'layout_legend', PaletteManipulator::POSITION_PREPEND)
        ->applyToPalette('element_group', $strTableName);
}

/**
 * 2) Dynamisches Injizieren des Grid-Modus-Feldes bei jedem Kind-Element
 *    einer Element-Group mit aktivem "useGridColumns" - siehe GridPaletteListener.
 */
$GLOBALS['TL_DCA'][$strTableName]['config']['onpalette_callback'][] = [GridPaletteListener::class, 'onPalette'];

/**
 * Subpaletten
 */
$GLOBALS['TL_DCA'][$strTableName]['subpalettes']['gridMode_standard'] = 'grid';
$GLOBALS['TL_DCA'][$strTableName]['subpalettes']['gridMode_expert'] = 'gridColXxxl,gridOffsetXxxl,gridColXxl,gridOffsetXxl,gridColXl,gridOffsetXl,gridColLg,gridOffsetLg,gridColMd,gridOffsetMd,gridColSm,gridOffsetSm,gridColXs,gridOffsetXs';

/**
 * Felder
 */

// SPALTEN VERWENDEN (Element Group)
$GLOBALS['TL_DCA'][$strTableName]['fields']['useGridColumns'] = [
    'label'     => &$GLOBALS['TL_LANG'][$strTableName]['useGridColumns'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50 m12', 'submitOnChange' => true],
    'sql'       => "char(1) NOT NULL default ''",
];

// GRID-MODUS (wird dynamisch bei Kind-Elementen injiziert)
$GLOBALS['TL_DCA'][$strTableName]['fields']['gridMode'] = [
    'label'     => &$GLOBALS['TL_LANG'][$strTableName]['gridMode'],
    'exclude'   => true,
    'inputType' => 'radio',
    'default'   => 'standard',
    'options'   => ['standard', 'expert'],
    'reference' => &$GLOBALS['TL_LANG'][$strTableName]['gridModeOptions'],
    'eval'      => ['tl_class' => 'clr', 'submitOnChange' => true],
    'sql'       => ['type' => 'string', 'length' => 8, 'default' => 'standard'],
];

// STANDARD-MODUS: vordefinierte Breiten (0-6), identisch zum alten npgridtools "grid"-Feld
// 0 = nicht gesetzt (siehe GridClassListener::buildClassNames - GRID_PRESETS[0] existiert nicht -> keine Klasse)
$GLOBALS['TL_DCA'][$strTableName]['fields']['grid'] = [
    'label'     => &$GLOBALS['TL_LANG'][$strTableName]['grid'],
    'exclude'   => true,
    'inputType' => 'rangeSlider',
    'eval'      => [
        'tl_class' => 'w50 clr',
        'min'      => 0,
        'max'      => 6,
        'step'     => 1,
        'marks'    => [
            ['value' => 0, 'label' => 'Aus'],
            ['value' => 1, 'label' => '1/4'],
            ['value' => 2, 'label' => '1/3'],
            ['value' => 3, 'label' => '1/2'],
            ['value' => 4, 'label' => '2/3'],
            ['value' => 5, 'label' => '3/4'],
            ['value' => 6, 'label' => 'Voll'],
        ],
    ],
    'sql' => "int(10) NOT NULL default '0'",
];

// EXPERT-MODUS: pro Breakpoint Größe + Offset (0 = nicht gesetzt, siehe GridClassListener)
$arrRangeMarks = [
    ['value' => 0, 'label' => '0'],
    ['value' => 3, 'label' => '3'],
    ['value' => 6, 'label' => '6'],
    ['value' => 9, 'label' => '9'],
    ['value' => 12, 'label' => '12'],
];

foreach (['Xxxl', 'Xxl', 'Xl', 'Lg', 'Md', 'Sm', 'Xs'] as $breakpoint) {
    $GLOBALS['TL_DCA'][$strTableName]['fields']['gridCol'.$breakpoint] = [
        'label'     => &$GLOBALS['TL_LANG'][$strTableName]['gridCol'.$breakpoint],
        'exclude'   => true,
        'inputType' => 'rangeSlider',
        'eval'      => ['tl_class' => 'w50 clr', 'min' => 0, 'max' => 12, 'step' => 1, 'marks' => $arrRangeMarks],
        'sql'       => "int(10) NOT NULL default '0'",
    ];

    $GLOBALS['TL_DCA'][$strTableName]['fields']['gridOffset'.$breakpoint] = [
        'label'     => &$GLOBALS['TL_LANG'][$strTableName]['gridOffset'.$breakpoint],
        'exclude'   => true,
        'inputType' => 'rangeSlider',
        'eval'      => ['tl_class' => 'w50', 'min' => 0, 'max' => 12, 'step' => 1, 'marks' => $arrRangeMarks],
        'sql'       => "int(10) NOT NULL default '0'",
    ];
}
