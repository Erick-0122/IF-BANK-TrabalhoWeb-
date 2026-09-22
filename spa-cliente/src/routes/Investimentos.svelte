<script>
  import { api, brl } from '../lib/api.js';

  let investments = $state([]);
  let type = $state('CDB');
  let amount = $state('');
  let error = $state('');
  let feedback = $state('');
  let loading = $state(false);

  async function load() {
    investments = await api('/investimentos');
  }

  async function act(endpoint, label) {
    loading = true;
    error = feedback = '';

    try {
      const data = await api(`/investimentos/${endpoint}`, {
        method: 'POST',
        body: { type, amount: Number(amount) },
      });
      feedback = `${label} concluído. Saldo em conta: ${brl(data.balance)}`;
      amount = '';
      await load();
    } catch (e) {
      error = e.message;
    } finally {
      loading = false;
    }
  }

  load();
</script>

<section class="card">
  <h2 style="margin-top:0;font-size:1rem">Sua posição</h2>
  <ul class="list">
    {#each investments as investment}
      <li><span>{investment.type}</span><span>{brl(investment.balance)}</span></li>
    {/each}
  </ul>
</section>

<section class="card">
  <h2 style="margin-top:0;font-size:1rem">Aplicar ou resgatar</h2>

  {#if error}<p class="alert error">{error}</p>{/if}
  {#if feedback}<p class="alert ok">{feedback}</p>{/if}

  <div class="row">
    <div class="field">
      <label for="type">Investimento</label>
      <select id="type" bind:value={type}>
        <option value="CDB">CDB</option>
        <option value="CDI">CDI</option>
        <option value="POUPANCA">Poupança</option>
      </select>
    </div>
    <div class="field">
      <label for="value">Valor</label>
      <input id="value" type="number" step="0.01" min="0.01" bind:value={amount} />
    </div>
  </div>

  <div class="row">
    <button class="primary" disabled={loading} onclick={() => act('aplicar', 'Aplicação')}>Aplicar</button>
    <button class="primary" disabled={loading} onclick={() => act('resgatar', 'Resgate')}>Resgatar</button>
  </div>
</section>
