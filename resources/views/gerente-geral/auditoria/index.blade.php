@extends('layouts.app')
@section('title', 'Logs de auditoria')
@section('heading', 'Logs de auditoria dos gerentes de conta')

@section('content')
<form method="GET" class="mb-4 flex flex-wrap items-end gap-3 text-sm">
    <div>
        <label class="block text-stone-600" for="manager_id">Gerente</label>
        <select id="manager_id" name="manager_id" class="mt-1 rounded border-stone-300">
            <option value="">Todos</option>
            @foreach($managers as $manager)
                <option value="{{ $manager->id }}" @selected(($filters['manager_id'] ?? null) == $manager->id)>{{ $manager->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-stone-600" for="event">Evento</label>
        <select id="event" name="event" class="mt-1 rounded border-stone-300">
            <option value="">Todos</option>
            @foreach(['created' => 'Criação', 'updated' => 'Alteração', 'deleted' => 'Exclusão'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['event'] ?? null) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-stone-600" for="from">De</label>
        <input id="from" type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="mt-1 rounded border-stone-300">
    </div>
    <div>
        <label class="block text-stone-600" for="to">Até</label>
        <input id="to" type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="mt-1 rounded border-stone-300">
    </div>
    <button class="rounded bg-stone-800 px-4 py-2 text-white">Filtrar</button>
</form>

<div class="space-y-3">
@forelse($audits as $audit)
    <article class="rounded-lg bg-white p-4 shadow-sm ring-1 ring-stone-200">
        <div class="flex flex-wrap items-baseline justify-between gap-2">
            <p class="font-medium">{{ $audit->user?->name ?? 'Usuário removido' }}
                <span class="font-normal text-stone-600">— {{ $audit->event }} em {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</span>
            </p>
            <time class="text-sm text-stone-500">{{ $audit->created_at->format('d/m/Y H:i:s') }}</time>
        </div>
        @if($audit->old_values || $audit->new_values)
            <dl class="mt-3 grid gap-2 text-sm sm:grid-cols-2">
                @foreach($audit->new_values as $field => $value)
                    <div class="rounded bg-stone-50 px-3 py-2">
                        <dt class="text-stone-500">{{ $field }}</dt>
                        <dd>
                            @isset($audit->old_values[$field])
                                <span class="text-rose-700 line-through">{{ $audit->old_values[$field] }}</span>
                            @endisset
                            <span class="text-emerald-800">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        @endif
        <p class="mt-2 text-xs text-stone-400">{{ $audit->ip_address }} · {{ $audit->url }}</p>
    </article>
@empty
    <p class="rounded-lg bg-white p-8 text-center text-stone-500 ring-1 ring-stone-200">Nenhum registro encontrado para este filtro.</p>
@endforelse
</div>
<div class="mt-4">{{ $audits->links() }}</div>
@endsection
