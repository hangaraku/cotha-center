<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Auth::user()->accounts;
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'account_details' => 'required|string',
            'platform_link' => 'nullable|url|max:500',
        ]);

        Auth::user()->accounts()->create([
            'platform_name' => $request->platform_name,
            'account_details' => $request->account_details,
            'platform_link' => $request->platform_link,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        // Ensure user can only view their own accounts
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        // Ensure user can only edit their own accounts
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        // Ensure user can only update their own accounts
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'platform_name' => 'required|string|max:255',
            'account_details' => 'required|string',
            'platform_link' => 'nullable|url|max:500',
        ]);

        $account->update([
            'platform_name' => $request->platform_name,
            'account_details' => $request->account_details,
            'platform_link' => $request->platform_link,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        // Ensure user can only delete their own accounts
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus!');
    }
}
