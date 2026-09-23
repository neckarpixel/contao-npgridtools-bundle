<?php

declare(strict_types=1);

namespace Neckarpixel\NpgridtoolsBundle\Grid;

use Contao\Config;

/**
 * Zentrale Stelle, die weiss, wie eine Grid-/Offset-/Container-Klasse für
 * das aktuell in den System-Einstellungen gewählte CSS-Framework
 * ("npGridFramework": bootstrap|tailwind) aussieht.
 *
 * Wird sowohl vom GridClassListener (baut die tatsächlichen Frontend-
 * Klassen) als auch von den Backend-Sprachdateien (bauen die Feld-Labels/
 * Options-Beschriftungen, damit sie im Backend genau die Klasse zeigen, die
 * am Ende auch gerendert wird) verwendet - so gibt es nur EINE Stelle, die
 * das Klassen-Namensschema je Framework kennt.
 */
final class GridFramework
{
    /**
     * Feldnamen-Suffix => Bootstrap-Infix (VOR der Zahl, "col-{infix}{n}").
     */
    private const INFIX_BOOTSTRAP = [
        'Xs'   => '',
        'Sm'   => 'sm-',
        'Md'   => 'md-',
        'Lg'   => 'lg-',
        'Xl'   => 'xl-',
        'Xxl'  => 'xxl-',
        'Xxxl' => 'xxxl-',
    ];

    /**
     * Feldnamen-Suffix => Tailwind-Prefix (VOR der ganzen Klasse,
     * "{prefix}col-span-{n}"). "Xxl"/"Xxxl" gibt es in Tailwind
     * standardmäßig nur bis "2xl" - "3xl" muss ggf. im eigenen
     * tailwind.config.js als zusätzlicher Breakpoint ergänzt werden.
     */
    private const PREFIX_TAILWIND = [
        'Xs'   => '',
        'Sm'   => 'sm:',
        'Md'   => 'md:',
        'Lg'   => 'lg:',
        'Xl'   => 'xl:',
        'Xxl'  => '2xl:',
        'Xxxl' => '3xl:',
    ];

    private const PRESETS_BOOTSTRAP = [
        1 => 'col-12 col-sm-6 col-md-3',
        2 => 'col-12 col-sm-6 col-md-4',
        3 => 'col-12 col-sm-6 col-md-6',
        4 => 'col-12 col-sm-6 col-md-8',
        5 => 'col-12 col-md-9',
        6 => 'col-12',
    ];

    private const PRESETS_TAILWIND = [
        1 => 'col-span-12 sm:col-span-6 md:col-span-3',
        2 => 'col-span-12 sm:col-span-6 md:col-span-4',
        3 => 'col-span-12 sm:col-span-6 md:col-span-6',
        4 => 'col-span-12 sm:col-span-6 md:col-span-8',
        5 => 'col-span-12 md:col-span-9',
        6 => 'col-span-12',
    ];

    /**
     * @return string[] Die Breakpoint-Suffixe in mobile-first Reihenfolge
     *                   (Xs zuerst) - identisch für beide Frameworks.
     */
    public static function breakpointSuffixes(): array
    {
        return array_keys(self::INFIX_BOOTSTRAP);
    }

    public static function current(): string
    {
        $framework = (string) (Config::get('npGridFramework') ?: 'bootstrap');

        return 'tailwind' === $framework ? 'tailwind' : 'bootstrap';
    }

    public static function isTailwind(?string $framework = null): bool
    {
        return 'tailwind' === ($framework ?? self::current());
    }

    /**
     * Klasse für die Spaltenbreite eines Breakpoints, z. B. "col-lg-6" bzw.
     * "lg:col-span-6".
     */
    public static function colClass(string $breakpointSuffix, int $columns, ?string $framework = null): string
    {
        if (self::isTailwind($framework)) {
            return (self::PREFIX_TAILWIND[$breakpointSuffix] ?? '').'col-span-'.$columns;
        }

        return 'col-'.(self::INFIX_BOOTSTRAP[$breakpointSuffix] ?? '').$columns;
    }

    /**
     * Klasse für den Offset eines Breakpoints, z. B. "offset-lg-3" bzw.
     * "lg:col-start-4" (Tailwind: absolute Grid-Linie = Offset + 1).
     */
    public static function offsetClass(string $breakpointSuffix, int $offsetColumns, ?string $framework = null): string
    {
        if (self::isTailwind($framework)) {
            return (self::PREFIX_TAILWIND[$breakpointSuffix] ?? '').'col-start-'.($offsetColumns + 1);
        }

        return 'offset-'.(self::INFIX_BOOTSTRAP[$breakpointSuffix] ?? '').$offsetColumns;
    }

    /**
     * Klasse(n) für den Grid-Container selbst ("row" bzw. "grid grid-cols-12").
     */
    public static function containerClass(?string $framework = null): string
    {
        return self::isTailwind($framework) ? 'grid grid-cols-12' : 'row';
    }

    /**
     * Klassen-String für einen Standard-Modus-Preset (1-6), oder '' wenn es
     * den Preset-Wert nicht gibt (z. B. 0 = "Aus").
     */
    public static function presetClasses(int $preset, ?string $framework = null): string
    {
        $presets = self::isTailwind($framework) ? self::PRESETS_TAILWIND : self::PRESETS_BOOTSTRAP;

        return $presets[$preset] ?? '';
    }
}
