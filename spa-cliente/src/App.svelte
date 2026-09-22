<script>
  import { session, signOut } from './lib/session.svelte.js';
  import Login from './routes/Login.svelte';
  import Saldo from './routes/Saldo.svelte';
  import Extrato from './routes/Extrato.svelte';
  import Pix from './routes/Pix.svelte';
  import Investimentos from './routes/Investimentos.svelte';

  let tab = $state('saldo');

  const tabs = [
    ['saldo', 'Saldo'],
    ['extrato', 'Extrato'],
    ['pix', 'Pix'],
    ['investimentos', 'Investimentos'],
  ];
</script>

<div class="app">
  {#if !session.token}
    <Login />
  {:else}
    <header class="topbar">
      <p class="brand">IF<span>Bank</span></p>
      <button class="ghost" onclick={signOut}>Sair</button>
    </header>

    <nav class="tabs">
      {#each tabs as [key, label]}
        <button aria-current={tab === key ? 'page' : undefined} onclick={() => (tab = key)}>{label}</button>
      {/each}
    </nav>

    {#if tab === 'saldo'}<Saldo />
    {:else if tab === 'extrato'}<Extrato />
    {:else if tab === 'pix'}<Pix />
    {:else}<Investimentos />{/if}
  {/if}
</div>
