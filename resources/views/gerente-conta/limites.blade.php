@extends('layouts.app')
@section('title', 'Solicitações de limite')
@section('heading', 'Solicitações de aumento de limite')

@section('content')
<div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-stone-200">
    <table class="w-full text-left text-sm">
        <thead class="bg-stone-50 text-stone-600">
            <tr><th class="px-4 py-3">Conta</th><th class="px-4 py-3">Cliente</th><th class="px-4 py-3">Limite pedido</th><th class="px-4 py-3">Situação</th><th class="px-4 py-3">Analisado por</th></tr>
        </thead>
        <tbody class="divide-y divide-stone-200">
        @forelse($requests as $req)
            <tr>
                <td class="px-4 py-3 font-mono">{{ $req->account->number }}</td>
                <td class="px-4 py-3">{{ $req->account->user->name }}</td>
                <td class="px-4 py-3">R$ {{ number_format($req->requested_limit, 2, ',', '.') }}</td>
                <td class="px-4 py-3">{{ ucfirst($req->status) }}</td>
                <td class="px-4 py-3 text-stone-600">{{ $req->reviewer?->name ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">Você ainda não fez solicitações. Abra uma conta e peça o aumento pela tela de detalhes.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $requests->links() }}</div>
@endsection
