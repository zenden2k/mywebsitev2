<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Page</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <router-link :to="{name: 'pages'}" class="btn btn-primary">&lt;&lt;&lt; Back to all pages </router-link>

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
                                    Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-common-sidebar-blocks" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                                    Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
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
            page: {},
            tabs: []
        }
    },
    created() {
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.get('/api/page/options').then(response => {
                    this.tabs = response.data.data.tabs;
                })
                .catch(function (error) {
                    console.error(error);
                });
            axios.get(`/api/page/${this.$route.params.id}`)
                .then(response => {
                    this.page = response.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        updatePage() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.patch(`/api/page/${this.$route.params.id}`, this.page)
                    .then(response => {
                        this.$router.push({name: 'pages'});
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
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
