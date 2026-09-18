<?php

declare(strict_types=1);

namespace Neckarpixel\NpgridtoolsBundle\EventListener;

use Contao\ContentModel;

/**
 * Registered on the "getContentElement" hook (see contao/config/config.php).
 *
 * Runs for every content element the frontend renders and handles two
 * INDEPENDENT cases - both can apply to the very same element at once,
 * which is exactly what happens with a nested "Elementgruppe":
 *
 * 1. The element is a CHILD of an "element_group" with "Spalten verwenden"
 *    (useGridColumns) active -> gets the Bootstrap grid classes (col-*,
 *    offset-*) built from the fields the GridPaletteListener injects into
 *    that child's own palette. This also applies when the child is itself
 *    an "element_group" (nested groups).
 * 2. The element itself IS an "element_group" with its own "Spalten
 *    verwenden" active -> gets the Bootstrap "row" wrapper class for ITS
 *    children.
 *
 * Both are appended directly onto the element's own wrapping
 * <div class="...">, exactly like npelements' ElementListener does for its
 * own classes.
 */
class GridClassListener
{
    /**
     * 1-6 preset widths for "Standard" mode, identical to the old
     * npgridtools GridBoxStart element.
     */
    private const GRID_PRESETS = [
        1 => 'col-12 col-sm-6 col-md-3',
        2 => 'col-12 col-sm-6 col-md-4',
        3 => 'col-12 col-sm-6 col-md-6',
        4 => 'col-12 col-sm-6 col-md-8',
        5 => 'col-12 col-md-9',
        6 => 'col-12',
    ];

    /**
     * Field-name suffix => Bootstrap breakpoint infix, ordered mobile-first.
     */
    private const BREAKPOINTS = [
        'Xs'   => '',
        'Sm'   => 'sm-',
        'Md'   => 'md-',
        'Lg'   => 'lg-',
        'Xl'   => 'xl-',
        'Xxl'  => 'xxl-',
        'Xxxl' => 'xxxl-',
    ];

    public function npGetGridClasses(ContentModel $contentModel, string $buffer, $element): string
    {
        // Fall 1: das Element ist Kind einer Elementgruppe mit aktivem Grid
        // (gilt auch, wenn das Kind selbst wieder eine Elementgruppe ist -
        // verschachtelte Elementgruppen).
        if ('tl_content' === $contentModel->ptable && $contentModel->pid) {
            $parent = ContentModel::findById($contentModel->pid);

            if (null !== $parent && 'element_group' === $parent->type && $parent->useGridColumns) {
                $classNames = $this->buildClassNames($contentModel);

                if ('' !== $classNames) {
                    $buffer = $this->appendClass($buffer, $classNames);
                }
            }
        }

        // Fall 2: das Element ist selbst eine Elementgruppe mit aktivem
        // eigenem Grid -> bekommt "row" für ihre eigenen Kinder.
        if ('element_group' === $contentModel->type && $contentModel->useGridColumns) {
            $buffer = $this->appendClass($buffer, 'row');
        }

        return $buffer;
    }

    private function appendClass(string $buffer, string $classNames): string
    {
        $strBufferNew = preg_replace('/class="([^"]+)"/', 'class="\\1 '.$classNames.'"', $buffer, 1);

        return $strBufferNew ?? $buffer;
    }

    private function buildClassNames(ContentModel $contentModel): string
    {
        if ('expert' === $contentModel->gridMode) {
            $classes = [];

            foreach (self::BREAKPOINTS as $suffix => $infix) {
                $col = $contentModel->{'gridCol'.$suffix};
                $offset = $contentModel->{'gridOffset'.$suffix};

                if ($col) {
                    $classes[] = 'col-'.$infix.(int) $col;
                }

                if ($offset) {
                    $classes[] = 'offset-'.$infix.(int) $offset;
                }
            }

            return implode(' ', $classes);
        }

        return self::GRID_PRESETS[(int) $contentModel->grid] ?? '';
    }
}
