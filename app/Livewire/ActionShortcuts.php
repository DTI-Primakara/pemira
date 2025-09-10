<?php

namespace App\Livewire;

use Filament\Actions\View\ActionsIconAlias;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class ActionShortcuts extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function logList(): Action{
        return Action::make('logList')
            ->color('success')
            ->keyBindings(['command+m', 'ctrl+m'])
            ->extraAttributes(['class' => 'py-24'])
            ->url('https://pemira.userjot.com/?cursor=1&order=top&limit=10')
            ->openUrlInNewTab()
            ->icon('heroicon-m-arrow-top-right-on-square');
    }

    public function render()
    {
        return <<<'HTML'
            <div class="w-full">
                {{ $this->logList }}
            </div>
        HTML;
    }
}
