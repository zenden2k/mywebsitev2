<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Sidebar Block</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'sidebarblocks'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all sidebar blocks </router-link>
                </p>

                <div class="card card-primary card-outline card-tabs">
                    <div class="card-body">
                        <form @submit.prevent="updateSidebarBlock">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Title (ru)</label>
                                                <input type="text" class="form-control" v-model="item.title_ru">
                                            </div>
                                            <div class="form-group">
                                                <label>Title (en)</label>
                                                <input type="text" class="form-control" v-model="item.title_en">
                                            </div>
                                            <div class="form-group">
                                                <label>Alias</label>
                                                <input type="text" class="form-control" v-model="item.alias">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <vue-editor v-model="item.content_ru" rows="5" cols="60"/>
                                            <vue-editor v-model="item.content_en" rows="5" cols="60" class="mt-1"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div v-for="(v, k) in errors" :key="k">
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
            item: {
            },
            pages: [],
            errors: [],
        }
    },
    created() {
        axios.get('/api/sidebarblock/options').then(response => {
            this.pages = response.data.data.pages;
            if (this.$route.params.id) {
                axios.get(`/api/sidebarblock/${this.$route.params.id}`)
                    .then(response => {
                        this.item = response.data.data;
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            }
        })
            .catch(function (error) {
                console.error(error);
            });

    },
    methods: {
        updateSidebarBlock() {
            if (this.$route.params.id) {
                axios.patch(`/api/sidebarblock/${this.$route.params.id}`, this.item)
                    .then(response => {
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            subtitle: '',
                            body: 'Sidebar block has been updated successfully.',
                            autohide: true,
                            delay: 3000,
                        });
                        this.$router.push({name: 'sidebarblocks'});

                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            } else {
                axios.post(`/api/sidebarblock`, this.item)
                    .then(response => {
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            subtitle: '',
                            body: 'Sidebar block has been created successfully.',
                            autohide: true,
                            delay: 3000,
                        });
                        this.$router.push({name: 'sidebarblocks'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
    },
}
</script>
