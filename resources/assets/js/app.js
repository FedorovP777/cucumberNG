/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
// const Vuex = require('vuex');
import VueRouter from 'vue-router';
import Vuex from 'vuex';


Vue.component('CodeMirror', require('./components/CodeEditPage/CodeMirror.vue'));
Vue.component('ConfigFilesList', require('./components/CodeEditPage/ConfigFilesList.vue'));
Vue.component('NavigationMenu', require('./components/NavigationMenu.vue'));
Vue.component('CreateNewConfigFile', require('./components/CodeEditPage/CreateNewConfigFile.vue'));
Vue.component('ChangeNameConfigFile', require('./components/CodeEditPage/ChangeNameConfigFile.vue'));
Vue.component('DeleteFileConfig', require('./components/CodeEditPage/DeleteFileConfig.vue'));

Vue.component('TemplateBlock', require('./components/CodeEditPage/TemplateBlock.vue'));
Vue.use(Vuex);
Vue.use(VueRouter);

import store from './store'

const router = new VueRouter({


    routes: [

        {

            path: '/hosts/',
            component: require('./components/CodeEditPage/CodeEditPage.vue'),

        },
        {

            path: '/mainconf/',
            component: require('./components/CodeEditPage/CodeEditPage.vue')
        },
        {

            path: '', redirect: '/hosts/'

        },
        {

            path: '/setting/',
            component: require('./components/SettingPage/SettingPage.vue'),
            children: [

                {
                    path: '', redirect: 'options',

                },
                {

                    path: 'options',
                    component: require('./components/SettingPage/Options.vue'),

                },
                {

                    path: 'templates/:id',
                    component: require('./components/SettingPage/Templates.vue'),

                },
                {

                    path: 'documentation/:id',
                    component: require('./components/SettingPage/Documentation.vue'),

                }
            ]

        },
    ]
});

const template = `
   <div class="container-fluid main-container">
        <NavigationMenu></NavigationMenu>
        <router-view
        @createNewConfig="createNewConfig"
        ></router-view>
    </div>
`;

const app = new Vue({
    store,
    el: '#app',
    router: router,
    template: template,
    methods: {

        createNewConfig: function (fileName) {

            console.log(fileName);
        },
        downloadOptions: function () {

            return this.$http.get('/setting/options');
        },
        downloadTemplates: function () {

            return this.$http.get('/setting/templates');
        },
        downloadDocumentation: function () {

            return this.$http.get('/setting/documentation');
        },
        downloadHosts: function () {

            return this.$http.get('/config');
        }
    },
    created: function () {

        Promise.all([

            this.downloadOptions(),
            this.downloadTemplates(),
            this.downloadDocumentation(),
            this.downloadHosts()

        ]).then((response) => {

                this.$store.dispatch('changeOptions', response[0].body);
                this.$store.dispatch('changeTemplates', response[1].body);
                this.$store.dispatch('documentation', response[2].body);
                this.$store.dispatch('changeHosts', response[3].body);

            },
            error => {

            });


    },


    beforeRouteEnter: function () {
        console.log('beforeEnter');
    }
});

