@extends('layouts.app')
@php($editing = $manager->exists)
@section('title', $editing ? 'Editar gerente' : 'Novo gerente')
@section('heading', $editing ? 'Editar gerente de conta' : 'Cadastrar gerente de conta')

@section('content')
<form method="POST" action="{{ $editing ? route('gerentes.update', $manager) : route('gerentes.store') }}"
      class="max-w-xl space-y-5 rounded-lg bg-white p-6 shadow-sm ring-1 ring-stone-200">
    @csrf
    @if($editing) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium" for="name">Nome</label>
        <input id="name" name="name" value="{{ old('name', $manager->name) }}" required
               class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
    </div>

    <div>
        <label class="block text-sm font-medium" for="email">E-mail (login)</label>
        <input id="email" name="email" type="email" value="{{ old('email', $manager->email) }}" required
               class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium" for="password">Senha</label>
            <input id="password" name="password" type="password" {{ $editing ? '' : 'required' }}
                   class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
            @if($editing)<p class="mt-1 text-xs text-stone-500">Deixe em branco para manter a senha atual.</p>@endif
        </div>
        <div>
            <label class="block text-sm font-medium" for="password_confirmation">Confirmar senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password" {{ $editing ? '' : 'required' }}
                   class="mt-1 w-full rounded border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>
    </div>

    @if($editing)
        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" @checked(old('active', $manager->active)) class="rounded border-stone-300 text-emerald-700">
            Gerente ativo
        </label>
    @else
        <p class="text-sm text-stone-600">As credenciais serão enviadas por e-mail (Mailtrap) assim que o cadastro for salvo.</p>
    @endif

    <div class="flex gap-3">
        <button class="rounded bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Salvar gerente</button>
        <a href="{{ route('gerentes.index') }}" class="rounded px-4 py-2 text-sm text-stone-600 hover:bg-stone-200">Cancelar</a>
    </div>
</form>
@endsection
