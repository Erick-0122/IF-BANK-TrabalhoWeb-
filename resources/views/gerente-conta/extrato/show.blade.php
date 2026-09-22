@extends('layouts.app')
@section('title', 'Extrato')
@section('heading', 'Extrato da conta '.$account->number)

@section('content')
<form method="GET" class="mb-4 flex flex-wrap items-end gap-3 text-sm">
    <div>
        <label class="block text-stone-600" for="from">De</label>
        <input id="from" type="date" name="from" value="{{ $from }}" class="mt-1 rounded border-stone-300">
    </div>
    <div>
        <label class="block text-stone-600" for="to">Até</label>
        <input id="to" type="date" name="to" value="{{ $to }}" class="mt-1 rounded border-stone-300">
    </div>
    <button class="rounded bg-stone-800 px-4 py-2 text-white">Gerar extrato</button>
</form>

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <div class="rounded-lg bg-white p-5 ring-1 ring-stone-200"><p class="text-sm text-stone-500">Entradas</p><p class="text-xl font-semibold text-emerald-700">R$ {{ number_format($credits, 2, ',', '.') }}</p></div>
    <div class="rounded-lg bg-white p-5 ring-1 ring-stone-200"><p class="text-sm text-stone-500">Saídas</p><p class="text-xl font-semibold text-rose-700">R$ {{ number_format($debits, 2, ',', '.') }}</p></div>
    <div class="rounded-lg bg-white p-5 ring-1 ring-stone-200"><p class="text-sm text-stone-500">Saldo atual</p><p class="text-xl font-semibold">R$ {{ number_format($balance, 2, ',', '.') }}</p></div>
</div>

<div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-stone-200">
    <table class="w-full text-left text-sm">
        <thead class="bg-stone-50 text-stone-600">
            <tr><th class="px-4 py-3">Data</th><th class="px-4 py-3">Movimentação</th><th class="px-4 py-3">Descrição</th><th class="px-4 py-3 text-right">Valor</th><th class="px-4 py-3 text-right">Saldo</th></tr>
        </thead>
        <tbody class="divide-y divide-stone-200">
        @forelse($transactions as $t)
            <tr>
                <td class="px-4 py-3 text-stone-600">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">{{ $t->label }}</td>
                <td class="px-4 py-3 text-stone-600">{{ $t->description }} {{ $t->counterpart ? '· '.$t->counterpart : '' }}</td>
                <td class="px-4 py-3 text-right font-medium {{ $t->isCredit() ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $t->isCredit() ? '+' : '−' }} R$ {{ number_format(abs($t->amount), 2, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-right text-stone-600">R$ {{ number_format($t->balance_after, 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">Nenhuma movimentação no período escolhido.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
