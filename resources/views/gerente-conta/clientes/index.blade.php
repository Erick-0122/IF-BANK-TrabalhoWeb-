@extends('layouts.app')
@section('title', 'Contas de clientes')
@section('heading', 'Contas de clientes')
@section('actions')
    <a href="{{ route('contas.create') }}" class="rounded bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Abrir conta</a>
@endsection

@section('content')
<form method="GET" class="mb-4 flex gap-2 text-sm">
    <input name="busca" value="{{ $search }}" placeholder="Buscar por nome ou e-mail" class="w-72 rounded border-stone-300">
    <button class="rounded bg-stone-800 px-4 py-2 text-white">Buscar</button>
</form>

<div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-stone-200">
    <table class="w-full text-left text-sm">
        <thead class="bg-stone-50 text-stone-600">
            <tr><th class="px-4 py-3">Conta</th><th class="px-4 py-3">Cliente</th><th class="px-4 py-3">Saldo</th><th class="px-4 py-3">Limite</th><th class="px-4 py-3">Situação</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-stone-200">
        @forelse($accounts as $account)
            <tr>
                <td class="px-4 py-3 font-mono">{{ $account->number }}</td>
                <td class="px-4 py-3">
                    <span class="font-medium">{{ $account->user->name }}</span>
                    <span class="block text-stone-500">{{ $account->user->email }}</span>
                </td>
                <td class="px-4 py-3">R$ {{ number_format($account->balance, 2, ',', '.') }}</td>
                <td class="px-4 py-3">R$ {{ number_format($account->limit, 2, ',', '.') }}</td>
                <td class="px-4 py-3">
                    @if($account->blocked)
                        <span class="rounded bg-rose-100 px-2 py-1 text-rose-800">Bloqueada</span>
                    @else
                        <span class="rounded bg-emerald-100 px-2 py-1 text-emerald-800">Ativa</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right whitespace-nowrap">
                    <a href="{{ route('contas.show', $account) }}" class="text-emerald-700 hover:underline">Detalhes</a>
                    <a href="{{ route('contas.extrato', $account) }}" class="ml-3 text-emerald-700 hover:underline">Extrato</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-stone-500">Nenhuma conta encontrada.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $accounts->links() }}</div>
@endsection
