<template>
    <div>

        <div class="card mb-3 mt-3">
            <div class="card-body p-1">
                <input v-model="searchText" class="col-sm-12 form-control search-form-input"
                       placeholder="Search on name template">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 text-right">
                <button @click="clickClose()" class="btn btn-primary btn-sm float-right">Close</button>
            </div>
        </div>
        <div class="card mb-2" v-for="confTemplate in configTemplatesSort">
            <div class="card-body">
                <h5 class="card-title text-muted">{{ confTemplate.name }}</h5>
                <div class="row">
                    <pre class="code-template-preview ml-3">{{ confTemplate.content}}</pre>
                </div>
                <button @click="clickPaste(confTemplate.content)" class="btn btn-primary btn-sm float-right">Paste</button>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['configTemplates'],
        data: function () {
            let searchText = '';
            return {searchText};
        },
        computed: {

            configTemplatesSort() {

                return this.configTemplates.filter((item) => {

                    let searchText = this.searchText.toLowerCase();
                    let resultSearch = !!~item.name.toLowerCase().indexOf(searchText);

                    return resultSearch;
                });
            }
        },
        methods: {

            clickClose() {
                this.$emit('closeTemplatesBlock');
            },
            clickPaste(text) {
                this.$emit('pasteTemplate',text);
            }
        }
    }

</script>
<style>
    .code-template-preview {
        max-height: 300px;
        background-color: #b9b9b9;
        white-space: pre-wrap;
        width: 100%;
        font-size: 9px;
    }
</style>