<template>
    <div class="row full-height">
        <div class="scroll-content">
            <div class="">
                <ul class="list-group">
                    <li>
                        <div class="col-md-12"> <a @click="close" class="pull-right" href="#">Close</a></div>

                    </li>
                    <li class="list-group-item" v-for="(item,index) in filterConfigTemplates">
                        <strong>{{ item.name }}</strong>
                        <a href="#" @click="showCode(index)">{{ captions['SHOW'] }}</a>
                        <button class="btn btn-primary btn-xs pull-right" @click="pasteCodeTemplate(item.content)">
                            {{ captions['PASTE'] }}
                        </button>
                        <pre class="code-template-preview" v-show="showCodeItem === index">{{ item.content }}</pre>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default{

        data: function () {

            let showCodeItem = null;
            return {showCodeItem}
        },

        methods: {

            showCode: function (index) {

                if (this.showCodeItem === index) {

                    this.showCodeItem = null;
                    return;
                }

                this.showCodeItem = index;

            },

            close: function () {

                this.$emit('close');
            },
            pasteCodeTemplate: function (content) {

                this.$emit('codePasteTemplate', content);
            }

        },
        computed: {

            filterConfigTemplates: function () {

                return this.$store.state.templates.filter(item => item);
            },

            configTemplates: function () {

                return this.$store.state.templates;
            },
            captions: function () {

                return this.$store.getters.captions;
            }
        }
    }

</script>
<style>

    .code-template-preview {

        font-size: 9px;
    }
</style>