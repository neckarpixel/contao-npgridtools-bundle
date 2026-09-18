<?php

declare(strict_types=1);

/**
 * Hooks
 */

use Neckarpixel\NpgridtoolsBundle\EventListener\GridClassListener;

$GLOBALS['TL_HOOKS']['getContentElement'][] = [GridClassListener::class, 'npGetGridClasses'];
