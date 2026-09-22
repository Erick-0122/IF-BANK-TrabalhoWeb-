<script>
  import { api, brl } from '../lib/api.js';

  let data = $state(null);
  let error = $state('');

  async function load() {
    try {
      data = await api('/saldo');
    } catch (e) {
      error = e.message;
    }
  }

  load();
</script>

{#if error}
  <p class="alert error">{error}</p>
{:else if !data}
  <p class="muted">Carregando seu saldo…</p>
{:else}
  {#if data.blocked}
    <p class="alert blocked">Conta bloqueada por suspeita de fraude: {data.block_reason}. Fale com seu gerente. Enquanto isso você só consegue ver o saldo.</p>
  {/if}

  <section class="card">
    <p class="muted">Conta {data.account_number} · saldo disponível</p>
    <p class="balance">{brl(data.balance)}</p>
    <p class="muted">Limite de {brl(data.limit)} · total com limite {brl(data.available)}</p>
  </section>

  <section class="card">
    <h2 style="margin-top:0;font-size:1rem">Investimentos</h2>
    <ul class="list">
      {#each data.investments as investment}
        <li><span>{investment.type}</span><span>{brl(investment.balance)}</span></li>
      {/each}
    </ul>
    <p class="muted" style="margin-top:.75rem">Total aplicado: {brl(data.invested_total)}</p>
  </section>
{/if}
