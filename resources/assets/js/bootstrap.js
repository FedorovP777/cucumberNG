window._ = require('lodash');
window.CodeMirror = require("codemirror");
require('material-design-icons');

window.axios = require('axios');
require('codemirror/mode/nginx/nginx.js');
require('codemirror/addon/selection/mark-selection.js');
require('codemirror/addon/hint/show-hint.js');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = window.Laravel.csrfToken;

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
