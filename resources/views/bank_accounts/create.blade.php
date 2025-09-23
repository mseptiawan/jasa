<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Bank Account
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('bank-accounts.store') }}"
                      method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Bank Name</label>
                        <select name="bank_name"
                                class="w-full border-gray-300 rounded mt-1">
                            <option value=""
                                    disabled
                                    selected>Select Bank</option>
                            <option value="BRI">BRI</option>
                            <option value="BCA">BCA</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BNI">BNI</option>
                            <option value="Danamon">Danamon</option>
                            <option value="CIMB Niaga">CIMB Niaga</option>
                            <option value="Permata">Permata</option>
                        </select>
                        @error('bank_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Account Number</label>
                        <input type="text"
                               name="account_number"
                               class="w-full text-gray-400 border-gray-300 rounded mt-1"
                               placeholder="Contoh:3342394023232">
                        @error('account_number')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Account Holder</label>
                        <input type="text"
                               name="account_holder"
                               class="w-full border-gray-300 rounded mt-1">
                        @error('account_holder')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
                        <a href="{{ route('bank-accounts.index') }}"
                           class="px-4 py-2 bg-gray-400 text-white rounded">Back</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
