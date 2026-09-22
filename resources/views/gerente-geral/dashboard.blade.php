@extends('layouts.app')
@section('title', 'Painel do gerente geral')
@section('heading', 'Painel do gerente geral')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2">
        <a href="{{ route('gerentes.index') }}" class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-stone-200 hover:ring-emerald-500">
            <p class="text-3xl font-semibold">{{ $totalManagers }}</p>
            <p class="mt-1 text-stone-600">gerentes de conta cadastrados</p>
        </a>
        <a href="{{ route('solicitacoes.index') }}?status=pendente" class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-stone-200 hover:ring-emerald-500">
            <p class="text-3xl font-semibold">{{ $pendingRequests }}</p>
            <p class="mt-1 text-stone-600">solicitações de limite aguardando análise</p>
        </a>
    </div>
@endsection
