import { defineConfig } from 'vite';
import path from 'path';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
  build: {
    outDir: 'dist',
    emptyOutDir: true,

    manifest: true,

    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/index.js'),
      },

      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',

        assetFileNames: (assetInfo) => {
          if (assetInfo.name?.endsWith('.css')) {
            return 'css/[name][extname]';
          }

          return 'assets/[name][extname]';
        },
      },
    },
  },

  server: {
    host: 'localhost',
    port: 5173,
    strictPort: true,
    cors: true,
    fs: {
        strict: false
    }
  },
});