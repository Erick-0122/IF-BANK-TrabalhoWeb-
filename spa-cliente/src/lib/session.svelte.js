import { getToken, setToken } from './api.js';

/** Estado global da sessao do cliente (runes do Svelte 5). */
export const session = $state({
  token: getToken(),
  user: null,
});

export function signIn(token, user) {
  setToken(token);
  session.token = token;
  session.user = user;
}

export function signOut() {
  setToken(null);
  session.token = null;
  session.user = null;
}
