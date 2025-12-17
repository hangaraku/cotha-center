<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SessionCompletedHint extends Widget
{
    protected static string $view = 'filament.widgets.session-completed-hint';

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): string
    {
        return 'Session Completed';
    }
} 