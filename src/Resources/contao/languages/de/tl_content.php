<?php

declare(strict_types=1);

/**
 * Element Group - "Spalten verwenden"
 */
$GLOBALS['TL_LANG']['tl_content']['useGridColumns'] = ['Spalten verwenden', 'Die Elementgruppe als Grid nutzen.'];

/**
 * Grid-Modus (wird bei jedem Kind-Element einer Grid-Elementgruppe eingeblendet)
 */
$GLOBALS['TL_LANG']['tl_content']['gridMode'] = ['Spalten-Einstellungen', 'Standard: vordefinierte Breiten. Experte: Größe und Offset pro Breakpoint frei einstellen.'];
$GLOBALS['TL_LANG']['tl_content']['gridModeOptions']['standard'] = 'Standard (vordefinierte Breiten)';
$GLOBALS['TL_LANG']['tl_content']['gridModeOptions']['expert'] = 'Experte (pro Breakpoint einstellen)';

$GLOBALS['TL_LANG']['tl_content']['grid'] = ['Größe', 'Legen Sie hier die Größe des Elements fest.'];

$GLOBALS['TL_LANG']['tl_content']['gridOptions']['1'] = '1/4 Breite (25%)';
$GLOBALS['TL_LANG']['tl_content']['gridOptions']['2'] = '1/3 Breite (33.3%)';
$GLOBALS['TL_LANG']['tl_content']['gridOptions']['3'] = '1/2 Breite (50%)';
$GLOBALS['TL_LANG']['tl_content']['gridOptions']['4'] = '2/3 Breite (66.6%)';
$GLOBALS['TL_LANG']['tl_content']['gridOptions']['5'] = '3/4 Breite (75%)';
$GLOBALS['TL_LANG']['tl_content']['gridOptions']['6'] = 'Volle Breite (100%)';

/**
 * Expert-Modus Feldlabels
 */
$GLOBALS['TL_LANG']['tl_content']['gridColXxxl'] = ['Spalten (Ultra Wide Desktop)', 'Anzahl Spalten XXXL (col-xxxl-)'];
$GLOBALS['TL_LANG']['tl_content']['gridColXxl'] = ['Spalten (Wide Desktop)', 'Anzahl Spalten XXL (col-xxl-)'];
$GLOBALS['TL_LANG']['tl_content']['gridColXl'] = ['Spalten (Big Desktop)', 'Anzahl Spalten XL (col-xl-)'];
$GLOBALS['TL_LANG']['tl_content']['gridColLg'] = ['Spalten (Desktop)', 'Anzahl Spalten Large (col-lg-)'];
$GLOBALS['TL_LANG']['tl_content']['gridColMd'] = ['Spalten (Tablet Landscape)', 'Anzahl Spalten Medium (col-md-)'];
$GLOBALS['TL_LANG']['tl_content']['gridColSm'] = ['Spalten (Tablet Portrait)', 'Anzahl Spalten Small (col-sm-)'];
$GLOBALS['TL_LANG']['tl_content']['gridColXs'] = ['Spalten (Mobile)', 'Anzahl Spalten XS (col-)'];

$GLOBALS['TL_LANG']['tl_content']['gridOffsetXxxl'] = ['Offset (Ultra Wide Desktop)', 'Abstand nach links (Offset) XXXL (offset-xxxl-)'];
$GLOBALS['TL_LANG']['tl_content']['gridOffsetXxl'] = ['Offset (Wide Desktop)', 'Abstand nach links (Offset) XXL (offset-xxl-)'];
$GLOBALS['TL_LANG']['tl_content']['gridOffsetXl'] = ['Offset (Big Desktop)', 'Abstand nach links (Offset) XL (offset-xl-)'];
$GLOBALS['TL_LANG']['tl_content']['gridOffsetLg'] = ['Offset (Desktop)', 'Abstand nach links (Offset) Large (offset-lg-)'];
$GLOBALS['TL_LANG']['tl_content']['gridOffsetMd'] = ['Offset (Tablet Landscape)', 'Abstand nach links (Offset) Medium (offset-md-)'];
$GLOBALS['TL_LANG']['tl_content']['gridOffsetSm'] = ['Offset (Tablet Portrait)', 'Abstand nach links (Offset) Small (offset-sm-)'];
$GLOBALS['TL_LANG']['tl_content']['gridOffsetXs'] = ['Offset (Mobile)', 'Abstand nach links (Offset) XS (offset-)'];

/**
 * Expert-Modus Select-Optionen (1-12) je Breakpoint, Spalten
 */
foreach (['Xxxl' => 'xxxl-', 'Xxl' => 'xxl-', 'Xl' => 'xl-', 'Lg' => 'lg-', 'Md' => 'md-', 'Sm' => 'sm-', 'Xs' => ''] as $breakpoint => $infix) {
    for ($i = 1; $i <= 12; ++$i) {
        $GLOBALS['TL_LANG']['tl_content']['gridCol'.$breakpoint.'Options'][$i] = $i.' (col-'.$infix.$i.')';
        $GLOBALS['TL_LANG']['tl_content']['gridOffset'.$breakpoint.'Options'][$i] = $i.' (offset-'.$infix.$i.')';
    }
}

/**
 * Legenden
 */
$GLOBALS['TL_LANG']['tl_content']['layout_legend'] = 'Layout-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['gridcolumns_legend'] = 'Spalten-Einstellungen';
