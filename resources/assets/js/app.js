require('./bootstrap');
// window.Vue = require('vue');
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource';

Vue.use(VueRouter);
Vue.use(VueResource);
const mainComponent = require('./components/MainComponent.vue');
const editConfigPage = require('./components/EditConfigPage.vue');
const newFile=require('./components/NewFile.vue');

Vue.component('example', require('./components/Example.vue'));
Vue.component('main-component', mainComponent);
Vue.component('head-panel', require('./components/HeadPanel.vue'));
Vue.component('edit-config-page', editConfigPage);
Vue.component('code-mirror', require('./components/CodeMirror.vue'));
Vue.component('templates', require('./components/Templates.vue'));
Vue.component('new-file', newFile);

const routes = [
    {path: '/', component: mainComponent},
    {path: '/edit-file/:id' ,component: editConfigPage},
    {path: '/new-file/' ,component: newFile}
];
const router = new VueRouter({
    routes
});

Vue.http.options.root = '/';
Vue.http.headers.common['Authorization'] = 'Basic YXBpOnBhc3N3b3Jk';

const app = new Vue({
    data(){

       let  test ="test";
       let documentation = [];
       let configTemplates =[];
       return {test,documentation,configTemplates}
    },
    router,
    template: '<div><router-view :documentation="documentation" :configTemplates="configTemplates"></router-view></div>',
    el: '#app',

    methods: {

        loadDocumentation() {

            this.$http.get('/documentation/en.json').then((response) => {

                this.documentation.splice(0, this.documentation.length - 1);

                for (let item of response.body) {

                    this.documentation.push(item);
                }
            })
        },
        loadConfigTemplates(){
            this.$http.get('/templates.json').then((response) => {

                this.configTemplates.splice(0, this.configTemplates.length - 1);

                for (let item of response.body) {

                    this.configTemplates.push(item);
                }
            })
        }
    },
    created() {
this.loadConfigTemplates();
        this.loadDocumentation();

    }
});
