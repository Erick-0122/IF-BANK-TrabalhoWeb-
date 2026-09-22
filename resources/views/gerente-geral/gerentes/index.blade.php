@extends('layouts.app')
@section('title', 'Gerentes de conta')
@section('heading', 'Gerentes de conta')
@section('actions')
    <a href="{{ route('gerentes.create') }}" class="rounded bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Cadastrar gerente</a>
@endsection

@section('content')
<div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-stone-200">
    <table class="w-full text-left text-sm">
        <thead class="bg-stone-50 text-stone-600">
            <tr><th class="px-4 py-3">Nome</th><th class="px-4 py-3">E-mail</th><th class="px-4 py-3">Situação</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-stone-200">
        @forelse($managers as $manager)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $manager->name }}</td>
                <td class="px-4 py-3 text-stone-600">{{ $manager->email }}</td>
                <td class="px-4 py-3">{{ $manager->active ? 'Ativo' : 'Inativo' }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('gerentes.edit', $manager) }}" class="text-emerald-700 hover:underline">Editar</a>
                    <form method="POST" action="{{ route('gerentes.destroy', $manager) }}" class="inline" onsubmit="return confirm('Remover este gerente?')">
                        @csrf @method('DELETE')
                        <button class="ml-3 text-rose-700 hover:underline">Remover</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-stone-500">Nenhum gerente cadastrado. Comece cadastrando o primeiro.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $managers->links() }}</div>
@endsection
