const mix = require('laravel-mix');

const PATHS = {
  src: './wp-content/themes/timber-blocks-theme/src',
  dist: './wp-content/themes/timber-blocks-theme/assets',
};

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.jsx'],
  },
});

mix
  .js(`${PATHS.src}/scripts/app.js`, `${PATHS.dist}/js`)
  .sass(`${PATHS.src}/styles/app.scss`, `${PATHS.dist}/css`)
  .options({
    processCssUrls: false,
  })
  .setPublicPath(PATHS.dist);
