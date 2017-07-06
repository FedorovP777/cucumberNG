/**
 * Created by pavel on 04.06.17.
 */


import Vue from 'vue'
import Vuex from 'vuex'

import textEn from './language/en';
import textRu from './language/ru';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {

        options: {
            language_current:'en'
        },
        templates: [],
        documentation: [],
        hosts: [],
        caption:{
            'en':textEn,
            'ru':textRu
        }

    },
    getters:{

        captions:function (state, getters) {

            return state.caption[state.options.language_current];
        }
    },
    mutations: {

        options: function (state, value) {

            state.options = value;
        },
        templates: function (state, value) {

            state.templates.splice(0, state.templates.length);

            for (let item of value) {

                state.templates.push(item);
            }
        },
        documentation: function (state, value) {

            state.documentation.splice(0, state.documentation.length);

            for (let item of value) {

                state.documentation.push(item);
            }
        },
        hosts: function (state, value) {

            state.hosts.splice(0, state.hosts.length);

            for (let item of value) {

                state.hosts.push(item);
            }

        },
        addHost: function (state, value) {

            state.hosts.push(value);
        },
        currentLanguage: function (state, value) {

            state.options.language_current = value;
        },
        templateName: function (state, value) {

            state.templates[value.index].name = value.value;
        },
        templateContent: function (state, value) {

            state.templates[value.index].content = value.value;
        },
        addTemplateItem: function (state, value) {

            state.templates.push({name: '', content: ''});
        },
        documentationUpdate: function (state, value) {


            state.documentation[value.index][value.prop] = value.value;

            if (value.prop === "currentText") {

                state.documentation[value.index].description[state.documentation[value.index].currentLanguage] = value.value;

            }

        },
        addDocumentation: function (state, value) {

            state.documentation.push(value);
        }
    },
    actions: {

        //======================Templates=======================

        changeTemplatesName: function (context, data) {

            context.commit('templateName', data);
            context.dispatch('saveTemplatesOnServer');
        },

        changeTemplatesContent: function (context, data) {

            context.commit('templateContent', data);
            context.dispatch('saveTemplatesOnServer');
        },

        addTemplateItem: function (context, data) {

            context.commit('addTemplateItem');
            // context.dispatch('saveTemplatesOnServer');
        },
        changeTemplates: function (context, data) {

            context.commit('templates', data);

        },

        saveTemplatesOnServer: function (context) {

            Vue.http.put('/setting/templates', {data: context.state.templates}, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}});
        },


        //==============Options==================

        changeOptions: function (context, data) {

            context.commit('options', data);
        },
        changeCurrentLanguage: function (context, value) {

            context.commit('currentLanguage', value);
            context.dispatch('saveOptionOnServer');

        },

        downloadOption: function (context) {

            Vue.http.get('/setting/options', {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}});
        },
        saveOptionOnServer: function (context) {

            Vue.http.put('/setting/options', {data: context.state.options}, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}});
        },
        //==================Documentation========

        changeDocumentation: function (context, data) {

            context.commit('documentationUpdate', data);
            // context.dispatch('saveDocumentationOnServer');
        },
        documentation: function (context, data) {

            context.commit('documentation', data);

        },

        addDocumentationItem: function (context, data) {

            context.commit('addDocumentation', data);
            context.dispatch('saveDocumentationOnServer');
        },
        saveDocumentationOnServer: function (context, data) {

            Vue.http.put('/setting/documentation', {data: context.state.documentation}, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}});
        },
        //==============confFiles==============

        changeHosts: function (context, data) {

            context.commit('hosts', data);
        },
        downloadHosts: function (context) {

            Vue.http.get('/config').then(response => {

                context.commit('hosts', response.body);
            });
        },

        addHost: function (context, fileName) {


            Vue.http.post('/config', {'filename': fileName}, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}}).then(response => {

                context.dispatch('downloadHosts');

            }).bind(this);

        },

        deleteConfigFile: function (context, fileName) {

            Vue.http.delete('/config/' + fileName, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}}).then(response => {

                context.dispatch('downloadHosts');

            }).bind(this);

        },
        renameConfigFile: function (context, fileNameObj) {


            Vue.http.post('/config', {
                'filename': fileNameObj.name,
                'newName': fileNameObj.newName
            }, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}}).then(response => {

                context.dispatch('downloadHosts');
            });

        },


    }
})