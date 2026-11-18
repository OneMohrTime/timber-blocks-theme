import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'wp-content/themes/timber-blocks-theme/assets',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: 'wp-content/themes/timber-blocks-theme/src/scripts/app.js',
        'app-style': 'wp-content/themes/timber-blocks-theme/src/styles/app.scss',
        editor: 'wp-content/themes/timber-blocks-theme/src/styles/editor.scss',
        'hero-banner-script': 'wp-content/themes/timber-blocks-theme/blocks/hero-banner/index.js',
        'hero-banner-style': 'wp-content/themes/timber-blocks-theme/blocks/hero-banner/style.scss',
        'hero-slideshow-script': 'wp-content/themes/timber-blocks-theme/blocks/hero-slideshow/index.js',
        'hero-slideshow-style': 'wp-content/themes/timber-blocks-theme/blocks/hero-slideshow/style.scss',
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
