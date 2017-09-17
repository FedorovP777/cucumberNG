<template>
    <div class="row full-height">
        <div class="col-md-3 full-height">
            <div class="row full-height">
                <div class="col-md-12 full-height">
                    <div class="scroll-left-list">
                        <router-link to="options" tag="div">
                            <div class="panel panel-default nomargin-bottom element-host">
                                <div class="panel-body nopadding-vertical">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>{{ captions['OPTIONS'] }}</h4>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </router-link>

                            <div class="panel panel-default nomargin-bottom element-host">
                                <div class="panel-body nopadding-vertical">
                                    <div class="row">
                                        <div class="col-md-12" @click="toggleTemplates">
                                            <h4>{{ captions['TEMPLATES'] }}
                                                <span v-show="showTemplates" class="glyphicon glyphicon-menu-up pull-right"
                                                      aria-hidden="true"></span>
                                                <span v-show="(showTemplates == false)" class="glyphicon glyphicon-menu-down pull-right"
                                                      aria-hidden="true"></span>
                                            </h4>
                                        </div>
                                        <div class="col-md-12" v-for="(item, index) in templates" v-show="showTemplates">
                                            <router-link :to="{path:'/setting/templates/'+index}">{{ item.name }}
                                            </router-link>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                        <div class="panel panel-default nomargin-bottom element-host" v-show="false">
                            <div class="panel-body nopadding-vertical">
                                <div class="row">
                                    <div class="col-md-12"  @click="toggleDocumentation">
                                        <h4>{{ captions['DOCUMENTATION'] }}
                                            <span v-show="showDocumentation" class="glyphicon glyphicon-menu-up pull-right"
                                                aria-hidden="true"></span>
                                            <span v-show="(showDocumentation == false)" class="glyphicon glyphicon-menu-down pull-right"
                                                  aria-hidden="true"></span>
                                        </h4>
                                    </div>

                                    <div class="col-md-12" v-for="(item, index) in documentation" v-show="showDocumentation">
                                        <router-link :to="{path:'/setting/documentation/'+index}">{{ item.directive }}
                                        </router-link>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 full-height">
            <div class="row full-height">
                <div class="col-md-12 full-height">
                    <router-view></router-view>
                </div>
                <div class="col-md-12">&nbsp;</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {

            let showDocumentation = false;
            let showTemplates = false;

            return {showDocumentation, showTemplates}
        },

        mounted() {


        },
        computed: {

            captions: function () {

                return this.$store.getters.captions;
            },
            documentation: function () {

                return this.$store.state.documentation;
            },
            templates:function () {

                return this.$store.state.templates;
            }
        },
        methods:{

            toggleDocumentation:function () {

                this.showDocumentation = this.showDocumentation === true ? false : true;

            },
            toggleTemplates:function () {
                this.showTemplates = this.showTemplates === true ? false : true;
            }

        }
    }
</script>