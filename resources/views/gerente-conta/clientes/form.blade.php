@extends('layouts.app')
@php($editing = $account->exists)
@section('title', $editing ? 'Editar conta' : 'Abrir conta')
@section('heading', $editing ? 'Editar conta '.$account->number : 'Abrir conta de cliente')

@section('content')
<form method="POST" action="{{ $editing ? route('contas.update', $account) : route('contas.store') }}"
      class="max-w-xl space-y-5 rounded-lg bg-white p-6 shadow-sm ring-1 ring-stone-200">
    @csrf
    @if($editing) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium" for="name">Nome do cliente</label>
        <input id="name" name="name" value="{{ old('name', $account->user?->name) }}" required class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
    </div>

    <div>
        <label class="block text-sm font-medium" for="email">E-mail (login e chave pix)</label>
        <input id="email" name="email" type="email" value="{{ old('email', $account->user?->email) }}" required class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium" for="password">Senha</label>
            <input id="password" name="password" type="password" {{ $editing ? '' : 'required' }} class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>
        <div>
            <label class="block text-sm font-medium" for="password_confirmation">Confirmar senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password" {{ $editing ? '' : 'required' }} class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium" for="balance">Saldo inicial (R$)</label>
            <input id="balance" name="balance" type="number" step="0.01" min="0" value="{{ old('balance', $account->balance ?? 0) }}" required class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>
        <div>
            <label class="block text-sm font-medium" for="limit">Limite (R$)</label>
            <input id="limit" name="limit" type="number" step="0.01" min="0" value="{{ old('limit', $account->limit ?? 0) }}" required class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>
    </div>

    @unless($editing)
        <p class="text-sm text-stone-600">O número da conta é gerado automaticamente e as credenciais seguem por e-mail (Mailtrap).</p>
    @endunless

    <div class="flex gap-3">
        <button class="rounded bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Salvar conta</button>
        <a href="{{ route('contas.index') }}" class="rounded px-4 py-2 text-sm text-stone-600 hover:bg-stone-200">Cancelar</a>
    </div>
</form>
@endsection
