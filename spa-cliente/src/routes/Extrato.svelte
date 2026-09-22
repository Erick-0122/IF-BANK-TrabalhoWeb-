<script>
  import { api, brl } from '../lib/api.js';

  let from = $state('');
  let to = $state('');
  let statement = $state(null);
  let error = $state('');

  async function load(event) {
    event?.preventDefault();
    error = '';

    const query = new URLSearchParams();
    if (from) query.set('from', from);
    if (to) query.set('to', to);

    try {
      statement = await api(`/extrato?${query}`);
    } catch (e) {
      error = e.message;
      statement = null;
    }
  }

  load();
</script>

<form class="card" onsubmit={load}>
  <div class="row">
    <div class="field"><label for="from">De</label><input id="from" type="date" bind:value={from} /></div>
    <div class="field"><label for="to">Até</label><input id="to" type="date" bind:value={to} /></div>
  </div>
  <button class="primary">Gerar extrato</button>
</form>

{#if error}<p class="alert error">{error}</p>{/if}

{#if statement}
  <section class="card">
    <p class="muted">Entradas {brl(statement.credits)} · saídas {brl(statement.debits)}</p>
    <ul class="list">
      {#each statement.transactions as t}
        <li>
          <span>
            {t.label}
            <br /><small class="muted">{t.date}{t.counterpart ? ` · ${t.counterpart}` : ''}</small>
          </span>
          <span class="amount {t.direction === 'credito' ? 'credit' : 'debit'}">
            {t.direction === 'credito' ? '+' : '−'} {brl(Math.abs(t.amount))}
          </span>
        </li>
      {:else}
        <li><span class="muted">Nenhuma movimentação no período escolhido.</span></li>
      {/each}
    </ul>
  </section>
{/if}
