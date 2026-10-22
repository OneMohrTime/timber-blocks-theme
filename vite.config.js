import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'wp-content/themes/timber-blocks-theme/assets',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: 'wp-content/themes/timber-blocks-theme/src/scripts/app.js',
        'app-style': 'wp-content/themes/timber-blocks-theme/src/styles/app.scss',
        'hero-script': 'wp-content/themes/timber-blocks-theme/blocks/hero/index.js',
        'hero-style': 'wp-content/themes/timber-blocks-theme/blocks/hero/style.scss',
        'split-script': 'wp-content/themes/timber-blocks-theme/blocks/split-content/index.js',
        'split-style': 'wp-content/themes/timber-blocks-theme/blocks/split-content/style.scss',
        'feature-script': 'wp-content/themes/timber-blocks-theme/blocks/feature-grid/index.js',
        'feature-style': 'wp-content/themes/timber-blocks-theme/blocks/feature-grid/style.scss',
        'cta-script': 'wp-content/themes/timber-blocks-theme/blocks/cta-banner/index.js',
        'cta-style': 'wp-content/themes/timber-blocks-theme/blocks/cta-banner/style.scss',
      },
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]';
          }

          return 'assets/[name][extname]';
        },
      },
    },
  },
});
