<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Blog Posts</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-info" style="width: 400px;">
                    <div class="card-header">
                        <h3 class="card-title">Search</h3>
                    </div>

                    <form @submit.prevent="getResults()">

                        <div class="card-body">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" v-model="searchQuery">
                                <span class="input-group-append">
<button type="submit" class="btn btn-info btn-flat">Go!</button>
</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="mb-2">
                    <router-link :to="{name: 'addblogpost'}" tag="button" class="btn btn-info"><i class="fa fa-plus"></i> Add Blog Post</router-link>
                </div>
                    <div class="">
                        <div class="card">
<!--                            <div class="card-header">-->
<!--                                <h3 class="card-title">Condensed Full Width Table</h3>-->
<!--                            </div>-->

                            <div class="card-body p-0">
                                <table class="table table-hover table-sm">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Comments</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="post in laravelData.data" :key="post.id">
                                        <td>{{ post.id }}</td>
                                        <td>{{ post.title_ru }} <a v-bind:href="'/ru/'+post.alias" target="_blank"><i class="icon ion-android-open"></i></a></td>
                                        <td>{{ post.category.title_ru }}</td>
                                        <td>{{ post.created_at | formatDate }}</td>
                                        <td><span :class="post.status ? 'badge bg-success' : 'badge bg-danger'"><span v-if="post.status">Active</span><span v-else>Inactive</span></span></td>
                                        <td><div v-if="post.enable_comments"> <router-link :to="{name: 'blogcomments', query: { post_id: post.id }}">{{ post.comments_count}}</router-link></div></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <router-link :to="{name: 'editblogpost', params: { id: post.id }}" class="btn btn-primary">Edit</router-link>

                                                <button class="btn btn-danger" @click="deletePost(post.id)">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>


                    </div>

                    <div class="mt-2 clearfix">
                        <pagination :data="laravelData" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>

        </section>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            laravelData: {},
            searchQuery: ''
        }
    },
    created() {
        this.getResults();
    },
    methods: {
        getResults(page = 1) {
            axios.get('/api/blogpost?page=' + page + "&query=" + encodeURIComponent(this.searchQuery))
                .then(response => {
                    this.laravelData = response.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        },
        deletePost(id) {
            axios.delete(`/api/blogpost/${id}`)
                .then(response => {
                    let i = this.laravelData.data.map(item => item.id).indexOf(id); // find index of your object
                    this.laravelData.data.splice(i, 1)
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        subtitle: '',
                        body: 'Blog post has been deleted.',
                        autohide: true,
                        delay: 3000,
                    })
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    },
}
</script>
