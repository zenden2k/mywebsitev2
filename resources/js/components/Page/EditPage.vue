<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Page</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'pages'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all pages </router-link>
                </p>
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true" style="">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-meta-information" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false" style="">Meta Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-page-blocks" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Page Blocks</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-common-sidebar-blocks" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Common Sidebar Blocks</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updatePage">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Title (ru)</label>
                                                            <input type="text" class="form-control" v-model="page.title_ru">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Title (en)</label>
                                                            <input type="text" class="form-control" v-model="page.title_en">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alias</label>
                                                    <input type="text" class="form-control" v-model="page.alias">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tab</label>
                                                    <select class="form-control" v-model="page.tabId">
                                                        <option v-for="choice in tabs" v-bind:value="choice.id">{{ choice.title_ru }}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label><input type="checkbox" v-model="page.showComments"> Show comments block</label>

                                                </div>
                                                <div class="form-group">
                                                    <vue-editor v-model="page.text_ru" rows="5" cols="60"/>
                                                    <vue-editor v-model="page.text_en" rows="5" cols="60"/>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-meta-information" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Meta Keywords</label>
                                                    <input type="text" class="form-control" v-model="page.meta_keywords_ru">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta Description</label>
                                                    <input type="text" class="form-control" v-model="page.meta_description_ru">
                                                </div>
                                                <div class="form-group">
                                                    <label>Open Graph image url</label>
                                                    <input type="text" class="form-control" v-model="page.open_graph_image_ru">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta Keywords (en)</label>
                                                    <input type="text" class="form-control" v-model="page.meta_keywords_en">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta Description (en)</label>
                                                    <input type="text" class="form-control" v-model="page.meta_description_en">
                                                </div>
                                                <div class="form-group">
                                                    <label>Open Graph image url (en)</label>
                                                    <input type="text" class="form-control" v-model="page.open_graph_image_en">
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-page-blocks" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                                    <div class="">
                                        <div class="col-sm-6">
                                            <div v-for="(block, index) in page.blocks" :key="index" :row="block">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label>Title (ru)</label>
                                                            <input type="text" class="form-control" v-model="block.title_ru">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label>Title (en)</label>
                                                            <input type="text" class="form-control" v-model="block.title_en">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <button @click.prevent="removeBlock(index)">Delete block</button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Order Number</label>
                                                            <input type="text" class="form-control" v-model="block.orderNumber">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Alias</label>
                                                            <input type="text" class="form-control" v-model="block.alias">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label><input type="checkbox" v-model="block.showInSidebar"> Show in sidebar</label>

                                                </div>
                                                <div class="form-group">
                                                    <textarea v-model="block.content_ru" rows="5" cols="60"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <textarea v-model="block.content_en" rows="5" cols="60"></textarea>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <button @click.prevent="addBlock" class="btn btn-secondary">Add Block</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-common-sidebar-blocks" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                                    <div class="col-sm-6">
                                        <div v-for="(sidebarBlock, index) in page.sidebarBlocks" :key="index" :row="sidebarBlock">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{sidebarBlock.title_ru}}
<!--                                                        <label>Title (ru)</label>-->
<!--                                                        <input type="text" class="form-control" v-model="block.title_ru">-->
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <button @click.prevent="removeSidebarBlock(index)">Delete block</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" v-model="sidebarBlock">
                                                <option v-for="block in allSidebarBlocks" v-bind:value="block.id">{{ block.title_ru }}</option>
                                            </select>
                                            <button @click.prevent="addSidebarBlock" class="btn btn-secondary">Add Sidebar Block</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div v-for="(v, k) in errors" :key="k">
<!--                                    {{k}}:-->
                                    <p v-for="error in v" :key="error" class="text-sm">
                                        {{ error }}
                                    </p>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>

                </div>

            </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            page: {
                blocks: []
            },
            tabs: [],
            errors: [],
            sidebarBlock: null,
            allSidebarBlocks: []
        }
    },
    created() {
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.get('/api/page/options').then(response => {
                    this.tabs = response.data.data.tabs;
                    this.allSidebarBlocks = response.data.data.sidebarBlocks;
                })
                .catch(function (error) {
                    console.error(error);
                });
            if (this.$route.params.id) {
                axios.get(`/api/page/${this.$route.params.id}`)
                    .then(response => {
                        this.page = response.data.data;
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            }
        })
    },
    methods: {
        updatePage() {
                if (this.$route.params.id) {
                    axios.patch(`/api/page/${this.$route.params.id}`, this.page)
                        .then(response => {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: '',
                                body: 'Page has been updated successfully.',
                                autohide: true,
                                delay: 3000,
                            });
                            this.$router.push({name: 'pages'});

                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                        });
                } else {
                    axios.post(`/api/page`, this.page)
                        .then(response => {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: '',
                                body: 'Page has been created successfully.',
                                autohide: true,
                                delay: 3000,
                            });
                            this.$router.push({name: 'pages'});
                        })
                        .catch(error => {
                            console.error(error);
                            this.errors = error.response.data.errors;
                        });
                }
        },
        addBlock() {
            this.page.blocks.push({});
        },
        removeBlock: function(index) {
            this.page.blocks.splice(index, 1);
        }
    },
    /*beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }*/
}
</script>
