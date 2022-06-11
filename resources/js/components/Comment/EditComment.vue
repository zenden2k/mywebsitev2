<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Comment</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'comments'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all comments </router-link>
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
                        <form @submit.prevent="updateComment">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Page</label>
                                                    <select class="form-control" v-model="item.pageId">
                                                        <option v-for="choice in pages" v-bind:value="choice.id">{{ choice.title_ru }}</option>
                                                    </select>
                                                </div>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" v-model="item.name">
                                            </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" v-model="item.email">
                                                </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Text</label>
                                                <textarea class="form-control" v-model="item.text" rows="10" cols="90"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Answer</label>
                                                <textarea class="form-control" v-model="item.answer" rows="10" cols="90"/>
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
        axios.get('/api/comment/options').then(response => {
            this.pages = response.data.data.pages;
            if (this.$route.params.id) {
                axios.get(`/api/comment/${this.$route.params.id}`)
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
        updateComment() {
            if (this.$route.params.id) {
                axios.patch(`/api/comment/${this.$route.params.id}`, this.item)
                    .then(response => {
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            subtitle: '',
                            body: 'Comment has been updated successfully.',
                            autohide: true,
                            delay: 3000,
                        });
                        this.$router.push({name: 'comments'});

                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            } else {
                axios.post(`/api/comment`, this.item)
                    .then(response => {
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            subtitle: '',
                            body: 'Comment has been created successfully.',
                            autohide: true,
                            delay: 3000,
                        });
                        this.$router.push({name: 'comments'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
    },
}
</script>
