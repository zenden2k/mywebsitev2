<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Tab</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'tabs'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all tabs </router-link>
                </p>

                <div class="card card-primary card-outline card-tabs">
<!--                    <div class="card-header p-0 pt-1 border-bottom-0">-->
<!--                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">-->
<!--                            <li class="nav-item">-->
<!--                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true" style="">General</a>-->
<!--                            </li>-->
<!--                            <li class="nav-item">-->
<!--                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-meta-information" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false" style="">Meta Information</a>-->
<!--                            </li>-->
<!--                            <li class="nav-item">-->
<!--                                <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-page-blocks" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Page Blocks</a>-->
<!--                            </li>-->
<!--                            <li class="nav-item">-->
<!--                                <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-common-sidebar-blocks" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Common Sidebar Blocks</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->
                    <div class="card-body">
                        <form @submit.prevent="updateTab">
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
                                                <label>URL</label>
                                                <input type="text" class="form-control" v-model="item.url">
                                            </div>
                                            <div class="form-group">
                                                <label>Order Number</label>
                                                <input type="text" class="form-control" v-model="item.orderNumber">
                                            </div>
                                            <div class="form-group">
                                                <label>Alias</label>
                                                <input type="text" class="form-control" v-model="item.alias">
                                            </div>
                                            <div class="form-group">
                                                <label><input type="checkbox" v-model="item.active"/> Active</label>
                                            </div>
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
        axios.get('/api/tab/options').then(response => {
            this.pages = response.data.data.pages;
            if (this.$route.params.id) {
                axios.get(`/api/tab/${this.$route.params.id}`)
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
        updateTab() {
            if (this.$route.params.id) {
                axios.patch(`/api/tab/${this.$route.params.id}`, this.item)
                    .then(response => {
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            subtitle: '',
                            body: 'Tab has been updated successfully.',
                            autohide: true,
                            delay: 3000,
                        });
                        this.$router.push({name: 'tabs'});

                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            } else {
                axios.post(`/api/tab`, this.item)
                    .then(response => {
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            subtitle: '',
                            body: 'Tab has been created successfully.',
                            autohide: true,
                            delay: 3000,
                        });
                        this.$router.push({name: 'tabs'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
    },
}
</script>
