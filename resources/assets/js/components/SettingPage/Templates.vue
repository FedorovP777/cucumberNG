<template>
    <div class="full-height">
        <div class="scroll-content-templates">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary" @click="newTemplate">{{ captions['ADD_NEW_TEMPLATE'] }}</button>
                </div>
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">
                        <strong>{{ templates[index].name}}</strong>
                    </div>
                    <div class="col-md-8">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ captions['NAME'] }}</label>
                                <div class="col-sm-9">
                                    <input type="text" v-bind:value="templates[index].name"
                                           @change="changeName(index,$event.srcElement.value)"
                                           class="form-control input-sm">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <code-mirror :text-for-edit="templates[index].content"
                                     @changeTextEdit="changeContent"
                                     :uniqname="getNameCodeMirror(index)"
                                     index="90"
                                     watchText="true"
                        ></code-mirror>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12 text-right">
            <button class="btn btn-primary" @click="save">{{ captions['SAVE'] }}</button>
        </div>
    </div>
</template>

<script>

    export default{


        data: function () {

            let text = '';
            let index = 0;
            return {index, text};
        },

        beforeRouteUpdate: function (to, from, next) {

            this.index = parseInt(to.params.id);
            next();

        },

        methods: {
            newTemplate: function () {

                this.$store.dispatch('addTemplateItem');
                this.index =  this.$store.state.templates.length-1;

            },
            changeName: function (index, value) {

                this.$store.dispatch('changeTemplatesName', {index, value});
            },
            changeContent: function (value, index) {

                console.log(value);
                this.text = value;
//                console.log(index, value);

            },
            saveToServer: function (data) {

                this.$http.put('/setting/templates', {data: data}, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}});
            },
            changeTextEdit: function (text, index) {

                this.templates[index].content = text;
            },

            getNameCodeMirror: function (id) {

                return 'codemirror-template-' + id.toString();
            },

            save: function () {

                this.$store.dispatch('changeTemplatesContent', {"index": this.index, "value": this.text});

            }
        },

        computed: {

            captions: function () {

                return this.$store.getters.captions;
            },
            templates: function () {

                return this.$store.state.templates;
            },

        },


    }

</script>
<style>

    .scroll-content-templates {
        height: calc(100% - 150px);
        overflow: auto;
    }
</style>