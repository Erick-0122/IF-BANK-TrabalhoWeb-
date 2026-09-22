import { defineConfig } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
  plugins: [svelte()],
  server: {
    port: 5174,
    proxy: {
      // O SPA chama /api/... e o Vite encaminha para o Laravel
      '/api': { target: 'http://localhost:8000', changeOrigin: true },
    },
  },
});
