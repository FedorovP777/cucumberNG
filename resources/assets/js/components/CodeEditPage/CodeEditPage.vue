<template>
    <div class="row full-height">

        <!--ModalWindows-->

        <div class="modal container" v-bind:style="{ display: (showWindow === true) ? 'block' : 'none'}">
            <div class="modal-content col-md-4 col-md-offset-4 panel panel-default" @click="closeModal()">
                <div class="row panel-body">
                    <span class="close" @click="closeModal()">&times;</span>
                    <div class="col-md-12">
                        <div v-show="errorFlag">
                            <strong>{{ captions['CONFIG_ERROR'] }}</strong>
                            <p>{{ errorText }}</p>
                        </div>
                        <p v-show="errorFlag===false">{{ captions['CONFIG_GOOD'] }}</p>
                    </div>
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary form-control" @click="closeModal">OK
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--End modal windows-->

        <div class="col-md-3 full-height">
            <delete-file-config
                    v-show="leftBlockComponent == 'DeleteFileConfig'"
                    @deleteItem="deleteItem"
                    @closeComponent="showListHostsComponent"
                    :itemOperation="currentDeleteConfig"
            >
            </delete-file-config>
            <change-name-config-file
                    v-show="leftBlockComponent == 'ChangeNameConfigFile'"
                    @closeComponent="showListHostsComponent"
                    @renameConfigFile="renameConfigFile"
                    :itemOperation="currentRenameConfig"
                    :listConfig="listHosts"
            >
            </change-name-config-file>

            <create-new-config-file
                    v-show="leftBlockComponent == 'CreateNewConfigFile'"
                    @closeCreateComponent="showListHostsComponent"
                    :listConfig="listHosts"
                    @createNewConfig="createNewConfig"
            >
            </create-new-config-file>
            <config-files-list
                    v-show="leftBlockComponent == 'configFilesList'"
                    :listConfig="listHosts"
                    :currentViewHost="currentViewHost"
                    @selectItem="selectItem"
                    @removeConfig="removeConfig"
                    @addItem="showAddConfigComponent"
                    @changeNameConfig="changeNameFile"
            ></config-files-list>
            <template-block
                    v-show="leftBlockComponent == 'TemplateBlock'"
                    @close="showListHostsComponent"
                    @codePasteTemplate="codePasteTemplate"
            ></template-block>
        </div>
        <div class="col-md-9 full-height">
            <div class="row full-height">
                <div class="col-md-8 full-height">
                    <div class="scroll-content-main-code-block" @keydown.ctrl.83.prevent="saveClick">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <code-mirror :text-for-edit="currentViewHost.content"
                                             :autocompleteWords="autocompleteWords"
                                             :markRows="markRows"
                                             @changeCursor="changeCursor"
                                             @changeTextEdit="changeTextEdit"
                                             watch-text="true"
                                             uniqname="config-edit-codemirror"
                                ></code-mirror>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">&nbsp;</div>
                    <button type="button" class="btn btn-primary pull-right col-md-3" @click="saveClick">
                        {{ captions['SAVE'] }}
                    </button>
                </div>
                <div class="col-md-4 scroll-documentation">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-md-12 text-left">
                                <strong>{{ captions['LINE']
                                    }}:</strong>{{ this.cursorLine + 1}} <strong>{{ captions['CHARSET']
                                }}:</strong>{{this.cursorCh + 1}}
                                <a href="#" @click="showTemplateBlock"><- {{ captions['PASTE_TEMPLATE'] }}
                                </a>
                            </div>
                            <div v-show="this.documentationHelpText.directive !== ''">
                            <h4 class="col-md-12 text-center">{{ captions['DIRECTIVE'] }}</h4>
                            <p class="col-md-12 text-left">{{ this.documentationHelpText.directive }}</p>
                            <h4 class="col-md-12 text-center">{{ captions['SYNTAX'] }}</h4>
                            <p class="col-md-12 text-left">{{ this.documentationHelpText.syntax }}</p>
                            <h4 class="col-md-12 text-center">{{ captions['DEFAULT_VALUE'] }}</h4>
                            <p class="col-md-12 text-left">{{ this.documentationHelpText.defaultValue }}</p>
                            <h4 class="col-md-12 text-center">{{ captions['CONTEXT'] }}</h4>
                            <p class="col-md-12 text-left"> {{ this.documentationHelpText.context }}</p>
                            <h4 class="col-md-12 text-center">{{ captions['DESCRIPTION'] }}</h4>
                            <p class="col-md-12 text-left" v-html="this.documentationHelpText.descriptionText">
                                
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {


            let leftBlockComponent = 'configFilesList';
            let currentViewHost = {

                configFileName: "",
                configPath: "",
                configPathBase64: "",
                content: "",
                hostnames: "",
                type: "",
            };
            let editContentTextTemp = '';
            let editContentText = '';
            let showWindow = false;
            let errorText = '';
            let goodText = 'Конфигурация успешно сохранена';
            let errorFlag = false;
            let errors = [];
            let filterText = '';
            let cursorLine = 0;
            let cursorCh = 0;
            let currentDeleteConfig = '';
            let currentRenameConfig = null;


            return {

                editContentText,
                currentViewHost,
                showWindow,
                goodText,
                errorText,
                errorFlag,
                errors,
                filterText,
                cursorLine,
                cursorCh,
                leftBlockComponent,
                currentDeleteConfig,
                currentRenameConfig,
                editContentTextTemp
            }
        },

        computed: {


            captions: function () {

                return this.$store.getters.captions;
            },

            currentLanguage: function () {

                return this.$store.state.options.language_current;
            },

            documentation: function () {

                return this.$store.state.documentation;
            },

            listHosts: function () {

                return this.$store.state.hosts;
            },


            markRows: function () {

                let result = [];

                for (let error of this.errors) {

                    result.push(parseInt(error.rowError) - 1);
                }

                return result;
            },

            autocompleteWords: function () {

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

        },

        methods: {


            codePasteTemplate: function (code) {


                let text = this.editContentTextTemp.split('\n');

                let result = '';
                for (let row in text) {


                    if (this.cursorLine == (row)) {

                        result += code;
                    }
                    result += text[row] + '\n';

                }
                this.currentViewHost.content = result;

            },
            showTemplateBlock: function () {

                this.leftBlockComponent = 'TemplateBlock';

            },

            changeNameFile: function (item) {

                this.leftBlockComponent = 'ChangeNameConfigFile';
                this.currentRenameConfig = item;
            },

            createNewConfig: function (newHostName) {

                this.$store.dispatch('addHost', newHostName);
                this.showListHostsComponent();
            },
            deleteItem: function (fileName) {

                this.$store.dispatch('deleteConfigFile', fileName);
                this.showListHostsComponent();

            },

            renameConfigFile: function (newName, name) {

                console.log(newName, name);
                this.$store.dispatch('renameConfigFile', {newName, name});
                this.showListHostsComponent();

//                this.getListHosts().then(response => {
//
//                    this.listHosts = response.body;
//                    this.showListHostsComponent();
//                });
            },

            showListHostsComponent: function () {

                this.leftBlockComponent = 'configFilesList';

            },

            addConfig: function () {

                this.$store.dispath('addHost',);

                this.getListHosts().then(response => {

                    this.listHosts = response.body;
                    this.showListHostsComponent();

                });

            },

            showAddConfigComponent: function () {

                this.leftBlockComponent = 'CreateNewConfigFile';
            },

            removeConfig: function (item) {

                this.leftBlockComponent = 'DeleteFileConfig';
                this.currentDeleteConfig = item;

            },

            changeTextEdit: function (text) {

                this.editContentTextTemp = text;
            },

            changeCursor: function (line, charset) {

                this.cursorLine = line;
                this.cursorCh = charset;
            },


            saveClick: function () {

                this.sendTextForSave(this.currentViewHost.configPathBase64, this.editContentTextTemp).then(response => {

                    this.errorFlag = false;
                    this.showWindow = true;

                }, error => {

                    if (error.code = 451) {

                        this.addErrors(error.body);

                    }

                });

                this.errors = [];

                return false;
            },


            closeModal: function () {

                this.showWindow = false;

            },

            sendTextForSave: function (file, content) {

                return this.$http.put('/config/' + file, {'text': content}, {'headers': {'X-CSRF-TOKEN': window.Laravel.csrfToken}});
            },
            getListHosts: function () {

                return this.$http.get('/config');
            },

            loadConfigContent: function (configPath) {

                return this.$http.get('/config/' + configPath);
            },
            selectItem: function (configPath) {

                this.loadConfigContent(configPath).then(response => {

                    this.currentViewHost.configFileName = response.body.configFileName;
                    this.currentViewHost.configPath = response.body.configPath;
                    this.currentViewHost.configPathBase64 = response.body.configPathBase64;
                    this.currentViewHost.hostnames = response.body.hostnames;
                    this.currentViewHost.type = response.body.type;
                    this.currentViewHost.content = response.body.content;

                });

            },

            addErrors: function (val) {

                this.errorFlag = true;
                this.showWindow = true;
                this.errors = val.errorParse;
                this.errorText = val.errorText;


            },


        },


    }
</script>
<style>


    .scroll-documentation {
        height: calc(100%);
        overflow: auto;
    }

    .scroll-content-main-code-block {

        height: calc(100% - 150px);
        overflow: auto;
    }

    .scroll-content {

        height: 100%;
        overflow: auto;
    }

    .full-height {

        height: 100%;

    }

    .filter-search {

        padding-left: 0px;
    }

    .nomargin-bottom {
        margin-bottom: 5px;
    }

    .nopadding-vertical {
        padding-top: 0;
        padding-bottom: 0;
    }

    .element-host {
        cursor: pointer;
    }

    .element-host:hover {

        border-color: #00974d;
    }

    /*.description-help-text {*/
    /*overflow: hidden;*/
    /*text-overflow: ellipsis;*/
    /*display: -webkit-box;*/
    /*line-height: 16px;     !* fallback *!*/
    /*max-height: 400px;      !* fallback *!*/
    /*-webkit-line-clamp: 4; !* number of lines to show *!*/
    /*-webkit-box-orient: vertical;*/
    /*}*/
</style>
