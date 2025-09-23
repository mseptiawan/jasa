<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Bank Accounts
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($accounts->count() == 0)
                <a href="{{ route('bank-accounts.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">Add Bank Account</a>
            @endif


            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (optional($accounts)->count() > 0)
                <div class="bg-white shadow rounded overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bank Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Account Number</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Account Holder</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($accounts as $account)
                                <tr>
                                    <td class="px-6 py-4">{{ $account->bank_name }}</td>
                                    <td class="px-6 py-4">{{ $account->account_number }}</td>
                                    <td class="px-6 py-4">{{ $account->account_holder }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('bank-accounts.edit', $account->id) }}"
                                           class="px-3 py-1 bg-blue-600 text-white rounded">Edit</a>
                                        <form action="{{ route('bank-accounts.destroy', $account->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">No bank accounts yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
