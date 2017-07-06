<template>
    <div class="row full-height new-conf-panel">
        <div class="panel panel-default panel-create-config">
            <div class="panel-body panel-body-create-config">
                <div class="col-md-12">
                    <div class="form-group col-md-12 has-feedback"
                         :class="{ 'has-error' : checkAlreadyConfigFileName == false}"
                    >
                        <label class="control-label">
                            <h3>{{ captions['ENTER_NEW_NAME_FILE'] }}</h3></label>

                        <input type="text" v-model="newNameConfig" class="form-control">
                        <p v-show="checkAlreadyConfigFileName === false" class="bg-danger col-md-12">
                            {{ captions['FILE_EXIST_OR_NAME_UNACCEPTABLE'] }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary form-control"
                            @click="clickCancelBtn">{{ captions['CANCEL'] }}
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-warning form-control"
                            @click="clickRenameBtn">{{ captions['RENAME'] }}
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

        props: ['itemOperation', 'listConfig'],
        data: function () {
            let newNameConfig = '';

            return {newNameConfig};
        },
        methods: {

            clearFileName: function () {

                this.newNameConfig = '';

            },

            clickRenameBtn: function () {


                this.$emit('renameConfigFile', this.newNameConfig, this.itemOperation.configPathBase64);
                this.clearFileName();

            },
            clickCancelBtn: function () {

                this.$emit('closeComponent');
                this.clearFileName();

            },
        },
        computed: {

            captions: function () {

                return this.$store.getters.captions;
            },

            checkAlreadyConfigFileName: function () {

                for (let item of this.listConfig) {

                    if (item.type === 'host' && item.configFileName === this.newNameConfig) {

                        return false;
                    }
                }
                return true;
            },

        }
    }
</script>