var platform = require('platform');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.onerror = function (msg, url, lineNo, columnNo, error) {
  axios.post('/log', {'error': `UNCAUGHT ERROR:\nPLATFORM: ${ platform.description }   URL: ${lineNo} of ${ url } @ ${ window.location.href }\nerror: ${ error }    msg: ${ msg }`});
}
