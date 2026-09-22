@if(session('status'))
    <div class="mb-6 rounded border-l-4 border-emerald-600 bg-emerald-50 px-4 py-3 text-emerald-900">
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded border-l-4 border-rose-600 bg-rose-50 px-4 py-3 text-rose-900">
        <ul class="list-inside list-disc space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
