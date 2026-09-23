# Contao npgridtools Bundle

Fügt dem Contao-Kernelement **"Element-Gruppe"** (`element_group`) und
seinen Kind-Elementen ein Bootstrap-artiges Spalten-Raster hinzu (`row`,
`col-*`, `offset-*`, jeweils pro Breakpoint einstellbar).

## Eigenschaften

- "Spalten verwenden"-Schalter direkt am Element-Gruppe-Element
- Pro Kind-Element wahlweise **Standard-Modus** (eine vordefinierte
  Breite: Aus / 1/4 / 1/3 / 1/2 / 2/3 / 3/4 / Voll) oder
  **Experten-Modus** (Breite + Offset einzeln pro Breakpoint: XXL, XL,
  LG, MD, SM, XS)
- Eingabe über das
  [nprangeslider-Widget](https://github.com/neckarpixel/contao-nprangeslider-bundle)
  (Range-Slider mit Skala statt Auswahlfeld)
- Funktioniert verschachtelt (Element-Gruppe in Element-Gruppe)
- **CSS-Framework wählbar**: Unter System-Einstellungen →
  "Grid-Werkzeuge (npgridtools)" stellst du global ein, ob **Bootstrap**
  (`row` / `col-*` / `offset-*`) oder **Tailwind CSS**
  (`grid grid-cols-12` / `col-span-*` / `col-start-*`) in den Code
  geschrieben wird. Standard: Bootstrap.

### Bootstrap vs. Tailwind - ein wichtiger Unterschied beim Offset

Bootstraps `offset-*` verschiebt ein Element per `margin-left` - **relativ**
zum vorherigen Element, unabhängig davon, wie breit dieses war.

Tailwinds `col-start-*` setzt dagegen eine **absolute** Grid-Linie
innerhalb des Containers (`grid grid-cols-12`, wird automatisch mitgesetzt).
Das Ergebnis ist bei einem einzeiligen Grid identisch, aber die Zahl bezieht
sich auf die Spaltenposition im Gesamtraster (1-12), nicht auf den Abstand
zum vorherigen Element.

## Installation

```bash
composer require neckarpixel/contao-npgridtools-bundle
```

Danach in Contao Manager die Datenbank aktualisieren.

## Abhängigkeiten

- [`neckarpixel/contao-nprangeslider-bundle`](https://github.com/neckarpixel/contao-nprangeslider-bundle) `^1.0` (wird automatisch mitinstalliert)
- Contao `^5.0`
- PHP `^8.1`

## Lizenz

LGPL-3.0-or-later — siehe [LICENSE](LICENSE) und [COPYING.LESSER](COPYING.LESSER).
