@extends('layouts.app')
@section('title', 'Solicitações de limite')
@section('heading', 'Solicitações de aumento de limite')

@section('content')
<form method="GET" class="mb-4 flex gap-2 text-sm">
    <select name="status" class="rounded border-stone-300">
        <option value="">Todas</option>
        @foreach(['pendente' => 'Pendentes', 'aprovada' => 'Aprovadas', 'reprovada' => 'Reprovadas'] as $value => $label)
            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <button class="rounded bg-stone-800 px-4 py-2 text-white">Filtrar</button>
</form>

<div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-stone-200">
    <table class="w-full text-left text-sm">
        <thead class="bg-stone-50 text-stone-600">
            <tr>
                <th class="px-4 py-3">Conta</th><th class="px-4 py-3">Cliente</th><th class="px-4 py-3">Solicitado por</th>
                <th class="px-4 py-3">Limite</th><th class="px-4 py-3">Situação</th><th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-200">
        @forelse($requests as $req)
            <tr>
                <td class="px-4 py-3 font-mono">{{ $req->account->number }}</td>
                <td class="px-4 py-3">{{ $req->account->user->name }}</td>
                <td class="px-4 py-3 text-stone-600">{{ $req->requester->name }}</td>
                <td class="px-4 py-3">R$ {{ number_format($req->current_limit, 2, ',', '.') }} → <strong>R$ {{ number_format($req->requested_limit, 2, ',', '.') }}</strong></td>
                <td class="px-4 py-3">{{ ucfirst($req->status) }}</td>
                <td class="px-4 py-3 text-right">
                    @if($req->isPending())
                        <form method="POST" action="{{ route('solicitacoes.aprovar', $req) }}" class="inline">@csrf @method('PATCH')
                            <button class="rounded bg-emerald-700 px-3 py-1.5 text-white hover:bg-emerald-800">Aprovar</button>
                        </form>
                        <form method="POST" action="{{ route('solicitacoes.reprovar', $req) }}" class="inline">@csrf @method('PATCH')
                            <button class="ml-2 rounded bg-stone-200 px-3 py-1.5 hover:bg-stone-300">Reprovar</button>
                        </form>
                    @else
                        <span class="text-stone-500">{{ $req->reviewed_at?->format('d/m/Y H:i') }}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-stone-500">Nenhuma solicitação neste filtro.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $requests->links() }}</div>
@endsection
