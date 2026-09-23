<?php

declare(strict_types=1);

namespace Neckarpixel\NpgridtoolsBundle\EventListener;

use Contao\ContentModel;
use Neckarpixel\NpgridtoolsBundle\Grid\GridFramework;

/**
 * Registered on the "getContentElement" hook (see contao/config/config.php).
 *
 * Runs for every content element the frontend renders and handles two
 * INDEPENDENT cases - both can apply to the very same element at once,
 * which is exactly what happens with a nested "Elementgruppe":
 *
 * 1. The element is a CHILD of an "element_group" with "Spalten verwenden"
 *    (useGridColumns) active -> gets the grid classes (Bootstrap col- /
 *    offset- classes OR Tailwind col-span- / col-start- classes, depending
 *    on the global "npGridFramework" setting) built from the fields the
 *    GridPaletteListener injects into that child's own palette. This also
 *    applies when the child is itself an "element_group" (nested groups).
 * 2. The element itself IS an "element_group" with its own "Spalten
 *    verwenden" active -> gets the container class ("row" for Bootstrap,
 *    "grid grid-cols-12" for Tailwind) for ITS children.
 *
 * Both are appended directly onto the element's own wrapping
 * <div class="...">, exactly like npelements' ElementListener does for its
 * own classes.
 *
 * Das eigentliche "welches Framework, welche Klassennamen"-Wissen steckt
 * zentral in Grid\GridFramework - siehe dort für den Bootstrap/Tailwind-
 * Unterschied beim Offset (relativ per margin vs. absolute Grid-Linie).
 */
class GridClassListener
{
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
        // eigenem Grid -> bekommt den Container für ihre eigenen Kinder.
        if ('element_group' === $contentModel->type && $contentModel->useGridColumns) {
            $buffer = $this->appendClass($buffer, GridFramework::containerClass());
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

            foreach (GridFramework::breakpointSuffixes() as $suffix) {
                $col = (int) $contentModel->{'gridCol'.$suffix};
                $offset = (int) $contentModel->{'gridOffset'.$suffix};

                if ($col) {
                    $classes[] = GridFramework::colClass($suffix, $col);
                }

                if ($offset) {
                    $classes[] = GridFramework::offsetClass($suffix, $offset);
                }
            }

            return implode(' ', $classes);
        }

        return GridFramework::presetClasses((int) $contentModel->grid);
    }
}
