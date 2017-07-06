<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-horizontal col-md-8">
                <div class="form-group">
                    <label class="control-label col-sm-4">{{ captions['LANGUAGE'] }}</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="options.language_current"
                                @change="changeCurrentLanguage($event.srcElement.value)">
                            <option v-for="item in options.language_all" :value="item" :key="item">{{ item }}</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    export default{


        data: function () {


            return {};
        },


        computed: {
            captions: function () {

                return this.$store.getters.captions;
            },
            options: function () {

                return this.$store.state.options;
            }
        },
        methods: {

            changeCurrentLanguage: function (value) {

                console.log(value);
                this.$store.dispatch('changeCurrentLanguage', value);
                this.getDocumentationLanguage(value);
            },
            
            getDocumentationLanguage:function (value) {


                this.$http.get('/documentation/'+value+'.json').then(response => {

                    this.$store.dispatch('documentation',response.body);

                });

            }
            
        }

        

    }

</script>