<template>
    <div class="flex-center position-ref full-height">
        <head-panel></head-panel>
        <div class="row m-0">
            <div class="col-sm-4">
                <templates @pasteTemplate="pasteTemplate" @closeTemplatesBlock="closeTemplatesBlock()" v-show="showTemplates"
                           :config-templates="configTemplates"></templates>
                <div v-show="!showTemplates" class="card card-paste-template-button mb-3  mt-3">
                    <div class="card-body">
                        <h5 class="card-title text-muted">{{ fileName }}</h5>
                        <div class="row">
                            <p class="card-text col">
                                Row:{{ cursorLine }}, charset: {{ cursorCh }}
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button @click="showTemplatesBlock()" class="btn btn-danger btn-sm">Paste template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="!showTemplates" class="card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Documentation</h5>
                        <h6>Name directive:{{ documentationHelpText.directive }}</h6>
                        <h6>Context:{{ documentationHelpText.context }}</h6>
                        <h6>Default value: {{ documentationHelpText.defaultValue }}</h6>
                        <h6>Syntax: {{ documentationHelpText.syntax }}</h6>
                        <h6>Description:</h6>
                        <div v-html="documentationHelpText.description"></div>
                        <h6></h6>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-8  mt-3">
                <div class="card">
                    <div class="card-body">
                        <code-mirror
                                :text-for-edit="contentFile"
                                uniqname="config-edit-codemirror"
                                watch-text="true"
                                @changeCursor="changeCursor"
                                @changeTextEdit="changeTextEdit"
                                :autocompleteWords="autocompleteWords"
                        ></code-mirror>
                    </div>
                </div>
                <div class="row float-right m-3">
                    <router-link tag="button" to="/" type="button" class="btn btn-danger mr-2">
                        Close(Ctrl+W)
                    </router-link>
                    <button class="btn btn-primary">Save(Ctrl+S)</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['documentation', 'configTemplates'],
        data() {
            let showTemplates = false;
            let contentFile = "";
            let fileName = "";
            let cursorLine = 1;
            let cursorCh = 1;
            let editContentTextTemp = '';
            return {contentFile, fileName, cursorLine, cursorCh, editContentTextTemp, showTemplates}
        },
        created() {

            this.fetchFileConf();
        },

        methods: {
            pasteTemplate(pasteCode){

                let text = this.contentFile.split('\n');

                let result = '';
                for (let row in text) {


                    if (this.cursorLine == (row)) {

                        result += pasteCode;
                    }
                    result += text[row] + '\n';

                }
                this.contentFile = result;
            },
            showTemplatesBlock() {

                this.showTemplates = true;
            },
            closeTemplatesBlock() {

                this.showTemplates = false;
            },
            changeTextEdit(text) {

                this.editContentTextTemp = text;
            },

            changeCursor(line, charset) {

                this.cursorLine = line;
                this.cursorCh = charset;
            },

            fetchFileConf() {

                const idFile = this.$route.params.id;
                this.$http.get('/nginx-configuration/' + idFile).then((response) => {
                    this.contentFile = response.body.body;
                    this.fileName = response.body.fileName;
                })

            },


        },
        computed: {

            autocompleteWords() {

                return _.map(this.documentation, 'directive');
            },
            documentationHelpText: function () {

                let emptyObject = {

                    directive: '',
                    syntax: "",
                    defaultValue: '',
                    context: '',
                    description: ''
                };
                let currentEditText = _.split(this.editContentTextTemp, '\n');
                const regex = /^\s*(\w+)/gm;
                let m;

                m = regex.exec(currentEditText[this.cursorLine]);

                if (m === null) {

                    return emptyObject;
                }


                let findDocumentationIndex = _.findIndex(this.documentation, function (o) {
                    return o.directive === m[1];
                });

                if (findDocumentationIndex === -1) {

                    return emptyObject;
                }

                let result = this.documentation[findDocumentationIndex];

                if (_.has(result.description, this.currentLanguage)) {

                    result.descriptionText = result.description;

                }

                result.descriptionText = result.description;


                return result;

            },
        }
    }
</script>