<template>
    <div class="flex-center position-ref full-height">
        <div class="top-static-block">
            <head-panel></head-panel>
            <div class="container search-file-card">
                <div class="card">
                    <div class="card-body p-1">
                        <input v-model="searchText" class="form-control search-form-input"
                               placeholder="Search on file name or domain name">
                    </div>
                </div>
            </div>
        </div>
        <div class="container list-files-config">
            <div class="card file-config-item" v-for="file in sortedConfigFiles">
                <div class="card-body">
                    <h4 class="card-title">{{file.fileName}}</h4>
                    <div class="row">
                        <h6 class="text-muted col-md-3">Path:</h6>
                        <p class="card-text col-md-9 mb-0">{{ file.fileName }}</p>
                        <h6 v-show="file.domainName.length > 0" class="text-muted col-md-3">Domain:</h6>
                        <p v-show="file.domainName.length > 0" class="card-text col-md-9 mb-0">{{ file.domainName }}</p>
                    </div>
                    <div class="row">
                        <router-link tag="button" :to="{ path: '/edit-file/'+file.id}" type="button" class="btn btn-primary col-sm-2 m-2">
                            View
                        </router-link>
                        <div class="col"></div>
                        <router-link tag="button" to="/" type="button" class="btn btn-warning col-sm-2 m-2">
                            Delete
                        </router-link>
                        <router-link tag="button" to="/" type="button" class="btn btn-danger col-sm-2 m-2">
                            Rename
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <router-link tag="i" to="new-file" title="Add file"  class="material-icons mr-4 mb-4 md-48 md-prim-color add-file-button pointer">add_circle</router-link>
    </div>
</template>
<script>
    export default {

        data() {
            let searchText = '';
            let configFiles = [];
            return {configFiles, searchText};
        },
        methods: {

            updateConfigList() {

                this.ajaxLoadFilesList().then((response) => {

                    this.configFiles.splice(0, this.configFiles.length - 1);
                    response.body.forEach((item) => {
                        this.configFiles.push(item);
                    });
                });
            },

            ajaxLoadFilesList() {

                return this.$http.get('nginx-configuration');
            }
        },
        mounted() {

            this.updateConfigList();
        },
        computed: {

            sortedConfigFiles() {

                return this.configFiles.filter((item) => {

                    let searchText = this.searchText.toLowerCase();
                    let findInPath = !!~item.path.toLowerCase().indexOf(searchText);
                    let findInDomainName = !!~item.domainName.toLowerCase().indexOf(searchText);

                    return findInPath || findInDomainName;
                });
            }
        }
    }
</script>

