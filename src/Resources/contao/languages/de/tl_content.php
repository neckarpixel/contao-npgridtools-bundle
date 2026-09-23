<?php

declare(strict_types=1);

use Neckarpixel\NpgridtoolsBundle\Grid\GridFramework;

/**
 * Alle Labels/Hinweise/Options-Beschriftungen unten werden anhand der
 * GLOBAL in den System-Einstellungen gewählten Einstellung
 * ("npGridFramework": Bootstrap/Tailwind) gebaut - über GridFramework,
 * dieselbe Klasse, die auch die tatsächlichen Frontend-Klassen erzeugt
 * (GridClassListener). Das Backend zeigt also immer exakt die Klasse, die
 * am Ende auch gerendert wird.
 */
$npGridFrameworkLabel = GridFramework::isTailwind() ? 'Tailwind CSS' : 'Bootstrap';

/**
 * Element Group - "Spalten verwenden"
 */
$GLOBALS['TL_LANG']['tl_content']['useGridColumns'] = [
    'Spalten verwenden',
    \sprintf('Die Elementgruppe als Grid nutzen (aktuelles CSS-Framework: %s, umstellbar unter System-Einstellungen).', $npGridFrameworkLabel),
];

/**
 * Grid-Modus (wird bei jedem Kind-Element einer Grid-Elementgruppe eingeblendet)
 */
$GLOBALS['TL_LANG']['tl_content']['gridMode'] = ['Spalten-Einstellungen', 'Standard: vordefinierte Breiten. Experte: Größe und Offset pro Breakpoint frei einstellen.'];
$GLOBALS['TL_LANG']['tl_content']['gridModeOptions']['standard'] = 'Standard (vordefinierte Breiten)';
$GLOBALS['TL_LANG']['tl_content']['gridModeOptions']['expert'] = 'Experte (pro Breakpoint einstellen)';

$GLOBALS['TL_LANG']['tl_content']['grid'] = ['Größe', 'Legen Sie hier die Größe des Elements fest.'];

/**
 * Standard-Modus Options (1-6) - Prozentangabe + die tatsächlich erzeugte
 * Klassen-Kombination für das aktuell gewählte Framework.
 */
$arrGridPercent = [
    1 => '1/4 Breite (25%)',
    2 => '1/3 Breite (33.3%)',
    3 => '1/2 Breite (50%)',
    4 => '2/3 Breite (66.6%)',
    5 => '3/4 Breite (75%)',
    6 => 'Volle Breite (100%)',
];

foreach ($arrGridPercent as $intPreset => $strPercentLabel) {
    $GLOBALS['TL_LANG']['tl_content']['gridOptions'][(string) $intPreset] = \sprintf(
        '%s - %s',
        $strPercentLabel,
        GridFramework::presetClasses($intPreset)
    );
}

/**
 * Expert-Modus Feldlabels - der Hinweistext zeigt direkt die für das
 * aktuell gewählte Framework tatsächlich erzeugte Beispiel-Klasse.
 */
$arrBreakpointLabels = [
    'Xxxl' => 'Ultra Wide Desktop',
    'Xxl'  => 'Wide Desktop',
    'Xl'   => 'Big Desktop',
    'Lg'   => 'Desktop',
    'Md'   => 'Tablet Landscape',
    'Sm'   => 'Tablet Portrait',
    'Xs'   => 'Mobile',
];

foreach ($arrBreakpointLabels as $strSuffix => $strDeviceLabel) {
    $GLOBALS['TL_LANG']['tl_content']['gridCol'.$strSuffix] = [
        \sprintf('Spalten (%s)', $strDeviceLabel),
        \sprintf('Anzahl Spalten, z. B. %s.', GridFramework::colClass($strSuffix, 6)),
    ];

    $GLOBALS['TL_LANG']['tl_content']['gridOffset'.$strSuffix] = [
        \sprintf('Offset (%s)', $strDeviceLabel),
        \sprintf('Abstand nach links (Offset), z. B. %s.', GridFramework::offsetClass($strSuffix, 3)),
    ];
}

/**
 * Expert-Modus Select-Optionen (1-12) je Breakpoint, mit der tatsächlich
 * erzeugten Klasse für das aktuell gewählte Framework in der Beschriftung.
 */
foreach (array_keys($arrBreakpointLabels) as $strSuffix) {
    for ($i = 1; $i <= 12; ++$i) {
        $GLOBALS['TL_LANG']['tl_content']['gridCol'.$strSuffix.'Options'][$i] = \sprintf('%d (%s)', $i, GridFramework::colClass($strSuffix, $i));
        $GLOBALS['TL_LANG']['tl_content']['gridOffset'.$strSuffix.'Options'][$i] = \sprintf('%d (%s)', $i, GridFramework::offsetClass($strSuffix, $i));
    }
}

/**
 * Legenden
 */
$GLOBALS['TL_LANG']['tl_content']['layout_legend'] = 'Layout-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['gridcolumns_legend'] = \sprintf('Spalten-Einstellungen (%s)', $npGridFrameworkLabel);
