<?php

declare(strict_types=1);

namespace App\Livewire\Banking\Transactions;

use App\Models\BankTransaction;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorize('banking.view');
    }

    public function render()
    {
        $transactions = BankTransaction::with(['account', 'createdBy'])
            ->latest()
            ->paginate(20);

        return view('livewire.banking.transactions.index', [
            'transactions' => $transactions,
        ]);
    }
}
