<script>
  import { api, brl } from '../lib/api.js';

  let pixKey = $state('');
  let amount = $state('');
  let description = $state('');
  let feedback = $state(null);
  let error = $state('');
  let loading = $state(false);

  async function submit(event) {
    event.preventDefault();
    loading = true;
    error = '';
    feedback = null;

    try {
      const data = await api('/pix', {
        method: 'POST',
        body: { pix_key: pixKey, amount: Number(amount), description },
      });
      feedback = `Pix enviado. Novo saldo: ${brl(data.balance)}`;
      pixKey = amount = description = '';
    } catch (e) {
      error = e.message;
    } finally {
      loading = false;
    }
  }
</script>

<form class="card" onsubmit={submit}>
  <h2 style="margin-top:0;font-size:1rem">Enviar pix</h2>

  {#if error}<p class="alert error">{error}</p>{/if}
  {#if feedback}<p class="alert ok">{feedback}</p>{/if}

  <div class="field">
    <label for="key">Chave pix do destinatário (e-mail)</label>
    <input id="key" bind:value={pixKey} required />
  </div>

  <div class="field">
    <label for="amount">Valor</label>
    <input id="amount" type="number" step="0.01" min="0.01" bind:value={amount} required />
  </div>

  <div class="field">
    <label for="description">Descrição (opcional)</label>
    <input id="description" bind:value={description} />
  </div>

  <button class="primary" disabled={loading}>{loading ? 'Enviando…' : 'Enviar pix'}</button>
</form>
