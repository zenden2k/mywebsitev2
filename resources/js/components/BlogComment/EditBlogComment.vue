<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Comment</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'blogcomments'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all blog comments </router-link>
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
                                                    <select class="form-control" v-model="item.blog_post_id">
                                                        <option v-for="choice in posts" v-bind:value="choice.id">{{ choice.title_ru }}</option>
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
import {showToast} from "../../utils/admin";
export default {
    data() {
        return {
            item: {
            },
            posts: [],
            errors: [],
        }
    },
    created() {
        axios.get('/api/blogcomment/options').then(response => {
            this.posts = response.data.data.posts;
            if (this.$route.params.id) {
                axios.get(`/api/blogcomment/${this.$route.params.id}`)
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
                axios.patch(`/api/blogcomment/${this.$route.params.id}`, this.item)
                    .then(response => {
                        showToast(response.data.success, 'Comment has been updated successfully.');
                        this.$router.push({name: 'blogcomments'});

                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            } else {
                axios.post(`/api/blogcomment`, this.item)
                    .then(response => {
                        showToast(response.data.success, 'Blog comment has been created successfully.');
                        this.$router.push({name: 'blogcomments'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
    },
}
</script>
