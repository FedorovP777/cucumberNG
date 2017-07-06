<template>
    <div class="row full-height new-conf-panel">

        <div class="panel panel-default panel-create-config">
            <div class="panel-body panel-body-create-config">
                <div class="col-md-12 text-right" @click="closeComponent">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </div>

                <div class="form-group col-md-12 has-feedback"
                     :class="{ 'has-error' : checkAlreadyConfigFileName == false}"
                >
                    <label class="control-label">
                        <h3>{{ captions['ENTER_NEW_NAME_FILE'] }}</h3></label>

                    <input type="text" v-model="fileName" class="form-control">
                    <p v-show="checkAlreadyConfigFileName === false" class="bg-danger col-md-12">
                        {{ captions['FILE_EXIST_OR_NAME_UNACCEPTABLE'] }}</p>
                </div>

                <div class="col-md-6 col-md-offset-6">
                    <button class="btn btn-primary form-control"
                            :class="{ 'disabled' : checkAlreadyConfigFileName == false}"
                            @click="createConfig">{{ captions['CREATE'] }}
                    </button>
                </div>
            </div>
            <div class="panel-footer">

            </div>
        </div>

    </div>

</template>
<script>
    export default{

        props: ['listConfig'],

        data: function () {

            let fileName = '';

            return {fileName};
        },
        methods: {

            clearFileName: function () {

                this.fileName = '';

            },

            closeComponent: function () {

                this.clearFileName();
                this.$emit('closeCreateComponent');
            },

            createConfig: function () {

                this.$emit('createNewConfig', this.fileName);
                this.clearFileName();

            },

        },
        computed: {

            captions: function () {

                return this.$store.getters.captions;
            },
            checkAlreadyConfigFileName: function () {

                for (let item of this.listConfig) {

                    if (item.type === 'host' && item.configFileName === this.fileName) {

                        return false;
                    }
                }
                return true;
            },

        }

    }

</script>
<style>

    .panel-create-config {

        height: calc(100% - 130px);
    }

    .panel-body-create-config {

        height: 100%;
    }

    .new-conf-panel {

        margin-left: 5px;
    }
</style>