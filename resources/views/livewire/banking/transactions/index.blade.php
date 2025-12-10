<div class="container mx-auto px-4 py-6">
    <x-ui.page-header 
        title="{{ __('Bank Transactions') }}"
        subtitle="{{ __('View all bank transactions') }}"
    />

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6 p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Date') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Account') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Reference') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->transaction_date->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->account->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->reference_number ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->transaction_type }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                {{ number_format($transaction->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No transactions found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
