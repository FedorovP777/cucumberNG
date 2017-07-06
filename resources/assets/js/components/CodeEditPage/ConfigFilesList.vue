<template>
    <div class="row full-height">

        <div class="col-md-12">
            <form class="form-horizontal col-md-8 filter-search">
                <div class="form-group">
                    <div class="col-sm-11">
                        <input type="email" class="form-control" v-model="filterText" :placeholder="captions['FILTER']">
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-primary pull-right col-md-4" @click="addItem">{{captions['ADD'] }}</button>
        </div>
        <div class="col-md-12 full-height no-right-paddind">
            <div class="scroll-left-list">
                <div class="panel panel-default nomargin-bottom element-host"
                     v-for="hostItem in filteredListConfigs"
                     @click="clickItem(hostItem.configPathBase64)">
                    <div class="panel-body nopadding-vertical"
                         :class="{'active-config-item' : (hostItem.configPathBase64 === currentViewHost.configPathBase64)}">
                        <div class="row">
                            <div class="col-md-12 nopadding">
                                <div class="col-md-8">
                                    <h4>{{ hostItem.configFileName }}</h4>
                                </div>
                                <div class="col-md-3 text-right pull-right">
                                    <div class="btn-group">
                                        <!--<button type="button" class="btn btn-default btn-xs">-->
                                            <span @click="changeNameItem(hostItem)" class="glyphicon glyphicon-pencil"
                                                  aria-hidden="true"></span>
                                        <!--</button>-->
                                        <!--<button type="button" class="btn btn-default btn-xs">-->
                                            <span @click="removeItem(hostItem)" class="glyphicon glyphicon-remove"
                                                  aria-hidden="true"></span>
                                        <!--</button>-->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-12" v-show="$route.path === '/hosts/'">
                                <p><strong>{{captions['HOSTNAME'] }}: </strong> {{ hostItem.hostnames }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    export default{

        props: ['listConfig', 'currentViewHost'],
        data: function () {

            let filterText = '';

            return {filterText}

        },
        methods: {


            addItem: function () {

                this.$emit('addItem')
            },

            removeItem: function (idItem) {

                console.log(idItem);
                this.$emit('removeConfig', idItem);
            },
            changeNameItem: function (item) {


                this.$emit('changeNameConfig', item);

            },
            clickItem: function (path) {

                this.$emit('selectItem', path);
            },


            filterSearchText: function (item) {


                if (this.filterText.length === 0) {

                    return true;
                }


                if (_.has(item, 'configFileName') && item.configFileName.indexOf(this.filterText) !== -1) {

                    return true;
                }
                if (_.has(item, 'hostnames') && item.hostnames.indexOf(this.filterText) !== -1) {

                    return true;
                }


                return false;

            },

            viewFirstHost: function () {

                if (this.filteredListConfigs.length === 0) {

                    return;
                }


                this.$emit('selectItem', this.filteredListConfigs[0].configPathBase64);

            },
        },

        computed: {

            filteredListConfigs: function () {


                if (this.$route.path === '/mainconf/') {

                    return this.listConfig.filter(object => object.type === 'mainConf').filter(object => this.filterSearchText(object));
                }


                return this.listConfig.filter(object => object.type !== 'mainConf').filter(object => this.filterSearchText(object));

            },
            captions:function () {

                return this.$store.getters.captions;
            }
        },

        watch: {

            listConfig: function (val, oldVal) {

                if (oldVal.length === 0 && val.length > 0) {

                    this.viewFirstHost();
                }

            },
        },


    }
</script>
<style>

    .button-action {
        margin-top: 10px;
        width: 35px;
    }
    .nopadding{

        padding:0;
    }
    .no-right-paddind{

        padding-right: 0;
    }
</style>