import ModuJS from 'modujs';

const app = new ModuJS({
  selector: '[data-module]',
  onInit: (module) => {
    module.classList.add('is-initialized');
  },
});

app.init();

window.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('has-js');
});
