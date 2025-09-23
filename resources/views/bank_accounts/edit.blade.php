<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Bank Account
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('bank-accounts.update', $account->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700">Bank Name</label>
                        <select name="bank_name"
                                class="w-full border-gray-300 rounded mt-1">
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}"
                                        @if ($account->bank_name == $bank) selected @endif>{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Account Number</label>
                        <input type="text"
                               name="account_number"
                               class="w-full border-gray-300 rounded mt-1"
                               value="{{ $account->account_number }}">
                        @error('account_number')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Account Holder</label>
                        <input type="text"
                               name="account_holder"
                               class="w-full border-gray-300 rounded mt-1"
                               value="{{ $account->account_holder }}">
                        @error('account_holder')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded">Update</button>
                        <a href="{{ route('bank-accounts.index') }}"
                           class="px-4 py-2 bg-gray-400 text-white rounded">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
