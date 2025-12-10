<?php

declare(strict_types=1);

namespace App\Livewire\Banking;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    public function mount(): void
    {
        $this->authorize('banking.view');
    }

    public function render()
    {
        return view('livewire.banking.index');
    }
}
