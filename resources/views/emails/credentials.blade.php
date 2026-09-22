<x-mail::message>
# Bem-vindo(a) ao IFBank

Olá, {{ $user->name }}. Sua conta de **{{ $profile }}** foi criada.

@if($accountNumber)
**Número da conta:** {{ $accountNumber }}
@endif

**Login:** {{ $user->email }}
**Senha:** {{ $plainPassword }}

Altere sua senha no primeiro acesso.

<x-mail::button :url="config('app.url')">
Acessar o IFBank
</x-mail::button>

Agência IFBank
</x-mail::message>
