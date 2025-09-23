<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBankAccount;
use Illuminate\Support\Facades\Auth;

class UserBankAccountController extends Controller
{
    public function index()
    {
        $accounts = Auth::user()->bankAccounts;
        return view('bank_accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('bank_accounts.create');
    }

    public function store(Request $request)
    {
        // Cek apakah user sudah punya akun bank
        if (Auth::user()->bankAccounts()->exists()) {
            return redirect()->route('bank-accounts.index')
                ->with('error', 'You can only have 1 bank account.');
        }

        $request->validate([
            'bank_name' => 'required|string|max:20',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
        ], [
            'bank_name.required' => 'Bank Name wajib diisi.',
            'bank_name.string' => 'Bank Name harus berupa teks.',
            'bank_name.max' => 'Bank Name maksimal 20 karakter.',

            'account_number.required' => 'Account Number wajib diisi.',
            'account_number.string' => 'Account Number harus berupa teks.',
            'account_number.max' => 'Account Number maksimal 50 karakter.',

            'account_holder.required' => 'Account Holder wajib diisi.',
            'account_holder.string' => 'Account Holder harus berupa teks.',
            'account_holder.max' => 'Account Holder maksimal 100 karakter.',
        ]);


        UserBankAccount::create([
            'user_id' => Auth::id(),
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
        ]);

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account added.');
    }
    public function edit($id)
    {
        $account = UserBankAccount::findOrFail($id);
        if ($account->user_id != Auth::id()) {
            abort(403);
        }
        $banks = ['BRI', 'BCA', 'Mandiri', 'BNI', 'Danamon', 'CIMB Niaga', 'Permata'];
        return view('bank_accounts.edit', compact('account', 'banks'));
    }

    public function update(Request $request, $id)
    {
        $account = UserBankAccount::findOrFail($id);
        if ($account->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'bank_name' => 'required|string|max:20',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
        ], [
            'bank_name.required' => 'Bank Name wajib diisi.',
            'bank_name.string' => 'Bank Name harus berupa teks.',
            'bank_name.max' => 'Bank Name maksimal 20 karakter.',

            'account_number.required' => 'Account Number wajib diisi.',
            'account_number.string' => 'Account Number harus berupa teks.',
            'account_number.max' => 'Account Number maksimal 50 karakter.',

            'account_holder.required' => 'Account Holder wajib diisi.',
            'account_holder.string' => 'Account Holder harus berupa teks.',
            'account_holder.max' => 'Account Holder maksimal 100 karakter.',
        ]);


        $account->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
        ]);

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account updated.');
    }
    public function destroy($id)
    {
        $account = UserBankAccount::findOrFail($id);
        if ($account->user_id != Auth::id()) {
            abort(403); // pastikan user hanya bisa hapus miliknya
        }
        $account->delete();
        return redirect()->route('bank-accounts.index')->with('success', 'Bank account deleted.');
    }
}
