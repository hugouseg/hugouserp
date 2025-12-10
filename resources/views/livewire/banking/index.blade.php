<div class="container mx-auto px-4 py-6">
    <x-ui.page-header 
        title="{{ __('Banking') }}"
        subtitle="{{ __('Manage bank accounts, transactions, and reconciliations') }}"
    />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <a href="{{ route('app.banking.accounts.index') }}" 
           class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('Bank Accounts') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('View and manage your bank accounts') }}
            </p>
        </a>

        <a href="{{ route('app.banking.transactions.index') }}" 
           class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('Transactions') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Review bank transactions') }}
            </p>
        </a>

        <a href="{{ route('app.banking.reconciliation') }}" 
           class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('Reconciliation') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Reconcile bank statements') }}
            </p>
        </a>
    </div>
</div>
