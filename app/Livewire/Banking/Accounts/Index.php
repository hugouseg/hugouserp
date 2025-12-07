<?php

declare(strict_types=1);

namespace App\Livewire\Banking\Accounts;

use App\Models\BankAccount;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $currency = '';

    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function mount(): void
    {
        $this->authorize('banking.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getStatistics(): array
    {
        $branchId = auth()->user()->branch_id;

        $accounts = BankAccount::where('branch_id', $branchId)->get();

        return [
            'total_accounts' => $accounts->count(),
            'active_accounts' => $accounts->where('status', 'active')->count(),
            'total_balance' => $accounts->where('status', 'active')->sum('current_balance'),
            'currencies' => $accounts->pluck('currency')->unique()->count(),
        ];
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $branchId = auth()->user()->branch_id;

        $query = BankAccount::where('branch_id', $branchId);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('account_name', 'like', "%{$this->search}%")
                    ->orWhere('account_number', 'like', "%{$this->search}%")
                    ->orWhere('bank_name', 'like', "%{$this->search}%");
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->currency) {
            $query->where('currency', $this->currency);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $accounts = $query->paginate(15);
        $statistics = $this->getStatistics();

        $currencies = BankAccount::where('branch_id', $branchId)
            ->select('currency')
            ->distinct()
            ->pluck('currency');

        return view('livewire.banking.accounts.index', [
            'accounts' => $accounts,
            'statistics' => $statistics,
            'currencies' => $currencies,
        ]);
    }
}
