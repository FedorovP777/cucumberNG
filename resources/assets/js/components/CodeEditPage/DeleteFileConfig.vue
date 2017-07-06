<template>
    <div class="row full-height new-conf-panel">
        <div class="panel panel-default panel-create-config">
            <div class="panel-body panel-body-create-config">
                <div class="col-md-12">
                    <p><strong>{{ captions['DELETE_FILE'] }} {{ itemOperation.configPath }}?</strong></p>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary form-control"
                            @click="clickCancelBtn">{{ captions['CANCEL'] }}
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-warning form-control"
                            @click="clickDeleteBtn">{{ captions['DELETE'] }}
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

        props: ['itemOperation'],
        methods: {

            clickDeleteBtn: function () {


                this.$emit('deleteItem', this.itemOperation.configPathBase64);


            },

            clickCancelBtn: function () {

                this.$emit('closeComponent');
            },

        },
        computed:{

            captions:function () {

                return this.$store.getters.captions;
            }
        }
    }
</script>