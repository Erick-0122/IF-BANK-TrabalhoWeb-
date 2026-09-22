<script>
  import { api } from '../lib/api.js';
  import { signIn } from '../lib/session.svelte.js';

  let email = $state('');
  let password = $state('');
  let error = $state('');
  let loading = $state(false);

  async function submit(event) {
    event.preventDefault();
    loading = true;
    error = '';

    try {
      const data = await api('/login', { method: 'POST', body: { email, password } });
      signIn(data.token, data.user);
    } catch (e) {
      error = e.message;
    } finally {
      loading = false;
    }
  }
</script>

<header class="topbar"><p class="brand">IF<span>Bank</span></p></header>

<form class="card" onsubmit={submit}>
  <h1 style="margin-top:0">Entrar na sua conta</h1>

  {#if error}<p class="alert error">{error}</p>{/if}

  <div class="field">
    <label for="email">E-mail</label>
    <input id="email" type="email" bind:value={email} required autocomplete="username" />
  </div>

  <div class="field">
    <label for="password">Senha</label>
    <input id="password" type="password" bind:value={password} required autocomplete="current-password" />
  </div>

  <button class="primary" disabled={loading}>{loading ? 'Entrando…' : 'Entrar'}</button>
</form>
