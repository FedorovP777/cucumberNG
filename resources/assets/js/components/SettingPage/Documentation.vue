<template>
    <div class="full-height">
        <div class="scroll-content-documentations">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-8 col-md-offset-1">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ captions['DIRECTIVE'] }}</label>
                                <div class="col-sm-9">
                                    <input type="text" v-bind:value="directive"
                                           @change="changeObject(key, 'directive', $event.srcElement.value)"
                                           class="form-control input-sm">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ captions['SYNTAX'] }}</label>
                                <div class="col-sm-9">
                                    <input type="text" v-bind:value="syntax"
                                           @change="changeObject(key, 'syntax', $event.srcElement.value)"
                                           class="form-control input-sm">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ captions['DEFAULT_VALUE'] }}</label>
                                <div class="col-sm-9">
                                    <input type="text" v-bind:value="defaultValue"
                                           @change="changeObject(key, 'defaultValue', $event.srcElement.value)"
                                           class="form-control input-sm">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ captions['CONTEXT'] }}</label>
                                <div class="col-sm-9">
                                    <input type="text" v-bind:value="context"
                                           @change="changeObject(key, 'context', $event.srcElement.value)"
                                           class="form-control input-sm">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ captions['DESCRIPTION'] }}</label>
                                <div class="col-sm-9">
                                    <!--<select class="form-control" v-bind:value="documentation[key].currentLanguage"-->
                                    <!--@change="changeObject(key, 'currentLanguage', $event.srcElement.value)"-->
                                    <!--&gt;-->
                                    <!--<option value="ru">ru</option>-->
                                    <!--<option value="en">en</option>-->
                                    <!--</select>-->
                                    <textarea class="form-control"
                                              v-bind:value="description"
                                              @change="changeObject(key, 'currentText', $event.srcElement.value)"
                                              rows="3"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12 text-right">
            <button class="btn btn-primary" @click="addItem">{{ captions['ADD'] }}</button>
        </div>
    </div>
</template>

<script>

    export default{


        data: function () {


            let directive = "";
            let syntax = "";
            let defaultValue = "";
            let context = "";
            let description = '';
            let currentText = "";

            return {
                directive,
                syntax,
                defaultValue,
                context,
                description,
                currentText
            };
        },
        mounted: function () {

            this.directive = this.$store.state.documentation[this.$route.param.id].directive;
            this.syntax = this.$store.state.documentation[this.$route.param.id].syntax;
            this.defaultValue = this.$store.state.documentation[this.$route.param.id].defaultValue;
            this.context = this.$store.state.documentation[this.$route.param.id].context;
            this.description = this.$store.state.documentation[this.$route.param.id].description;
            this.currentText = this.$store.state.documentation[this.$route.param.id].currentText;

        },
        beforeRouteUpdate:function (to, from, next) {
            this.directive = this.$store.state.documentation[to.params.id].directive;
            this.syntax = this.$store.state.documentation[to.params.id].syntax;
            this.defaultValue = this.$store.state.documentation[to.params.id].defaultValue;
            this.context = this.$store.state.documentation[to.params.id].context;
            this.description = this.$store.state.documentation[to.params.id].description;
            this.currentText = this.$store.state.documentation[to.params.id].currentText;
console.log(to);
        },
        computed: {

//            currentLanguage: function () {
//
//                return this.$store.state.options.language_current;
//            },
            captions: function () {

                return this.$store.getters.captions;
            }
        },
        methods: {


            changeObject: function (index, prop, value) {

                this.$store.dispatch('changeDocumentation', {index, prop, value});

            },

            saveToServer: function (data) {

                this.$http.put('/setting/documentation', {data: data}, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}});
            },

            changeCurrentText: function (index, value) {

//                for (let key in this.documentation[index].description) {
//
//                    if (this.documentation[index].description[key].language === this.documentation[index].currentLanguage) {
//
//                        this.documentation[index].description[key].text = value;
//                    }
//                }

            },

//            changeCurrentLanguage: function (index, value) {
//
//
//                for (let key in this.documentation[index].description) {
//
//                    if (this.documentation[index].description[key].language === value) {
//                        this.documentation[index].currentText = this.documentation[index].description[key].text;
//                    }
//                }
//
//            },

//            addItem: function () {
//
//                let defaultObject = {
//                    "directive": "",
//                    "syntax": "",
//                    "defaultValue": "",
//                    "context": "",
//                    "description": {
//
//                        "en": "test1",
//                        "ru": "test2",
//                    }
//                    ,
//                    "currentText": "",
//                    "currentLanguage": this.currentLanguage
//                };
//
//
//                this.$store.dispatch('addDocumentationItem', defaultObject);
//            }
        },


    }

</script>
<style>
    .scroll-content-documentations {
        height: calc(100% - 150px);
        overflow: auto;
    }
</style>