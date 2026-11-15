// =============================================================================
// Modules: Hero
// =============================================================================
// ...

// Import dependencies
// =============================================================================
import { module as Module } from 'modujs';

// Set default function and extend it ontop of our imported 'module'
// =============================================================================
export default class extends Module {

  // Set initial values
  // =========================================================================
  constructor(m) {
    super(m);
  }

  // Init module
  // =========================================================================
  init() {
    console.log('Component mounted:');
    console.log('==================');
    console.log(this.el);

    this.el.classList.add('is-ready');
  }

  // Destroy
  // =========================================================================
  destroy() {}
}
