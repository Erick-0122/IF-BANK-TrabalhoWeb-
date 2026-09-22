@extends('layouts.app')
@section('title', 'Conta '.$account->number)
@section('heading', 'Conta '.$account->number.' — '.$account->user->name)
@section('actions')
    <div class="flex gap-3 text-sm">
        <a href="{{ route('contas.extrato', $account) }}" class="rounded bg-stone-800 px-4 py-2 text-white">Ver extrato</a>
        <a href="{{ route('contas.edit', $account) }}" class="rounded bg-emerald-700 px-4 py-2 text-white">Editar</a>
    </div>
@endsection

@section('content')
<div class="grid gap-4 sm:grid-cols-3">
    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-stone-200">
        <p class="text-sm text-stone-500">Saldo</p>
        <p class="text-2xl font-semibold">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
    </div>
    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-stone-200">
        <p class="text-sm text-stone-500">Limite</p>
        <p class="text-2xl font-semibold">R$ {{ number_format($account->limit, 2, ',', '.') }}</p>
    </div>
    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-stone-200">
        <p class="text-sm text-stone-500">Investido</p>
        <p class="text-2xl font-semibold">R$ {{ number_format($account->investments->sum('balance'), 2, ',', '.') }}</p>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <h2 class="text-lg font-medium">Suspeita de fraude</h2>
        @if($account->blocked)
            <p class="mt-2 text-sm text-rose-800">Conta bloqueada: {{ $account->block_reason }}</p>
            <form method="POST" action="{{ route('contas.desbloquear', $account) }}" class="mt-4">@csrf @method('PATCH')
                <button class="rounded bg-emerald-700 px-4 py-2 text-sm text-white hover:bg-emerald-800">Desbloquear conta</button>
            </form>
        @else
            <form method="POST" action="{{ route('contas.bloquear', $account) }}" class="mt-4 space-y-3">@csrf @method('PATCH')
                <label class="block text-sm font-medium" for="block_reason">Motivo do bloqueio</label>
                <input id="block_reason" name="block_reason" required class="w-full rounded border-stone-300" placeholder="Ex.: pix atípico para conta desconhecida">
                <button class="rounded bg-rose-700 px-4 py-2 text-sm text-white hover:bg-rose-800">Bloquear conta</button>
            </form>
            <p class="mt-2 text-xs text-stone-500">Com a conta bloqueada o cliente só consegue ver o saldo.</p>
        @endif
    </section>

    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <h2 class="text-lg font-medium">Aumento de limite</h2>
        <form method="POST" action="{{ route('contas.limite', $account) }}" class="mt-4 space-y-3">@csrf
            <div>
                <label class="block text-sm font-medium" for="requested_limit">Novo limite (R$)</label>
                <input id="requested_limit" name="requested_limit" type="number" step="0.01" min="0.01" required class="mt-1 w-full rounded border-stone-300">
            </div>
            <div>
                <label class="block text-sm font-medium" for="justification">Justificativa</label>
                <textarea id="justification" name="justification" rows="2" class="mt-1 w-full rounded border-stone-300"></textarea>
            </div>
            <button class="rounded bg-emerald-700 px-4 py-2 text-sm text-white hover:bg-emerald-800">Solicitar ao gerente geral</button>
        </form>

        @if($account->limitRequests->isNotEmpty())
            <ul class="mt-5 space-y-2 text-sm">
                @foreach($account->limitRequests->sortByDesc('created_at')->take(5) as $req)
                    <li class="flex justify-between border-t border-stone-100 pt-2">
                        <span>R$ {{ number_format($req->requested_limit, 2, ',', '.') }}</span>
                        <span class="text-stone-500">{{ ucfirst($req->status) }} · {{ $req->created_at->format('d/m/Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>
@endsection
