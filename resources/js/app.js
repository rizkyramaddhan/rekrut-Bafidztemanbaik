// Bootstrap + Popper (bundle)
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';
window.bootstrap = bootstrap;

import $ from 'jquery';
import Swal from 'sweetalert2';
window.Swal = Swal;

import '@fortawesome/fontawesome-free/js/all.min.js';
import './sidebar';

import initPelamarTable from './pages/dashboard';

document.addEventListener('DOMContentLoaded', () => {
  const root = document.querySelector('[data-module="pelamarTable"]');
  const root2 = document.querySelector('[data-module="pelamarTable"]');
  if (root) initPelamarTable(root);
});
