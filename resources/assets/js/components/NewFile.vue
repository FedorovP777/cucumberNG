<template>
    <div class="flex-center position-ref full-height">
        <div class="top-static-block">
            <head-panel></head-panel>
            <div class="container">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title">
                            Create new file in /etc/nginx/sites-enabled/
                        </h5>
                        <div class="form-group">
                            <input v-model="nameFile" type="email" class="form-control">
                        </div>
                        <button @click="createNewFile" type="button" class="float-right m-3 btn btn-primary">Ok</button>
                        <router-link tag="button" to="/" type="button" class="float-right m-3 btn btn-secondary">
                            Cancel
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            let nameFile = '';
            return {nameFile};
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {

            createNewFile() {

                console.log(this.nameFile);
                this.createNewFileAjax(this.nameFile).then((response) =>{

                    console.log(response);
                });
            },
            createNewFileAjax(fileName){

                return  this.$http.post('nginx-configuration',{fileName});
            }
        }
    }
</script>
