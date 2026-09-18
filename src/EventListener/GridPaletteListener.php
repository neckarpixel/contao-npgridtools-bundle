<?php

declare(strict_types=1);

namespace Neckarpixel\NpgridtoolsBundle\EventListener;

use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;

/**
 * Registered as tl_content's "config.onpalette" callback (see dca/tl_content.php).
 *
 * Same mechanism Contao core uses to inject the accordion "section headline"
 * field into every child of an accordion element: whenever a content element's
 * palette is being built, we check whether its parent (pid/ptable) is an
 * "element_group" element with "Spalten verwenden" (useGridColumns) enabled.
 * If so, we dynamically prepend the gridMode selector (Standard/Experte) and
 * its corresponding fields to the CHILD element's palette - without touching
 * that content element type's own static palette definition at all.
 *
 * IMPORTANT: Contao's PaletteBuilder resolves the "[selector],fields,[EOF]"
 * subpalette syntax BEFORE it runs any config.onpalette_callback (see
 * core-bundle/src/DataContainer/PaletteBuilder.php::getPalette()). Since
 * "gridMode" is only added here, i.e. AFTER that resolution already ran, the
 * core auto-expansion never sees it - we have to build the "[gridMode],...,
 * [EOF]" subpalette markers ourselves, based on the field's current value,
 * exactly like the core would have done for a statically declared selector.
 */
class GridPaletteListener
{
    public function onPalette(string $palette, DataContainer $dc): string
    {
        if (!$dc->id) {
            return $palette;
        }

        // Bereits injiziert (z.B. erneuter Aufruf durch submitOnChange) -> nicht doppelt einfügen
        if (str_contains($palette, 'gridMode')) {
            return $palette;
        }

        $current = ContentModel::findById($dc->id);

        if (null === $current || 'tl_content' !== $current->ptable || !$current->pid) {
            return $palette;
        }

        $parent = ContentModel::findById($current->pid);

        if (null === $parent || 'element_group' !== $parent->type || !$parent->useGridColumns) {
            return $palette;
        }

        $currentValue = Input::post('gridMode') ?: ($current->gridMode ?: 'standard');
        $subpaletteKey = 'gridMode_'.$currentValue;
        $subpaletteFields = $GLOBALS['TL_DCA']['tl_content']['subpalettes'][$subpaletteKey] ?? '';

        $fieldTokens = ['gridMode'];

        if ('' !== $subpaletteFields) {
            $fieldTokens[] = '[gridMode]';
            array_push($fieldTokens, ...StringUtil::trimsplit(',', $subpaletteFields));
            $fieldTokens[] = '[EOF]';
        }

        return PaletteManipulator::create()
            ->addLegend('gridcolumns_legend', 'type_legend', PaletteManipulator::POSITION_AFTER)
            ->addField($fieldTokens, 'gridcolumns_legend', PaletteManipulator::POSITION_APPEND)
            ->applyToString($palette);
    }
}
