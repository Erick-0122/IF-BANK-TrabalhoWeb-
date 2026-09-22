<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IFBank — @yield('title', 'Agência')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
<header class="bg-emerald-950 text-emerald-50">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight">IFBank <span class="font-normal text-emerald-300">agência</span></a>

        <nav class="flex items-center gap-6 text-sm">
            @auth
                @if(auth()->user()->isGerenteGeral())
                    <a href="{{ route('gerentes.index') }}" class="hover:text-emerald-300">Gerentes de conta</a>
                    <a href="{{ route('solicitacoes.index') }}" class="hover:text-emerald-300">Solicitações de limite</a>
                    <a href="{{ route('auditoria.index') }}" class="hover:text-emerald-300">Logs de auditoria</a>
                @elseif(auth()->user()->isGerenteConta())
                    <a href="{{ route('contas.index') }}" class="hover:text-emerald-300">Contas de clientes</a>
                    <a href="{{ route('limites.index') }}" class="hover:text-emerald-300">Meus pedidos de limite</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="rounded bg-emerald-800 px-3 py-1.5 hover:bg-emerald-700">Sair</button>
                </form>
            @endauth
        </nav>
    </div>
</header>

<main class="mx-auto max-w-6xl px-6 py-8">
    <div class="mb-6 flex items-end justify-between gap-4">
        <h1 class="text-2xl font-semibold tracking-tight">@yield('heading')</h1>
        @yield('actions')
    </div>

    @include('partials.flash')
    @yield('content')
</main>
</body>
</html>
