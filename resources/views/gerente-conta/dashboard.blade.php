@extends('layouts.app')
@section('title', 'Painel do gerente de conta')
@section('heading', 'Painel do gerente de conta')
@section('actions')
    <a href="{{ route('contas.create') }}" class="rounded bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Abrir conta</a>
@endsection

@section('content')
<h2 class="mb-3 text-lg font-medium">Contas recentes</h2>
<ul class="divide-y divide-stone-200 rounded-lg bg-white shadow-sm ring-1 ring-stone-200">
@forelse($accounts as $account)
    <li class="flex items-center justify-between px-4 py-3">
        <div>
            <p class="font-medium">{{ $account->user->name }}</p>
            <p class="text-sm text-stone-500">Conta {{ $account->number }} · saldo R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
        </div>
        <a href="{{ route('contas.show', $account) }}" class="text-sm text-emerald-700 hover:underline">Abrir</a>
    </li>
@empty
    <li class="px-4 py-8 text-center text-stone-500">Nenhuma conta aberta ainda.</li>
@endforelse
</ul>
<div class="mt-4">{{ $accounts->links() }}</div>
@endsection
