<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Pages</h1></div></section>
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
                    <router-link :to="{name: 'addpage'}" tag="button" class="btn btn-info"><i class="fa fa-plus"></i> Add Page</router-link>
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
                                        <th>Alias</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Comments</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="page in laravelData.data" :key="page.id">
                                        <td>{{ page.id }}</td>
                                        <td>{{ page.title_ru }} <a v-bind:href="'/ru/'+page.alias" target="_blank"><i class="ion-md-open"></i></a></td>
                                        <td>{{ page.alias }}</td>
                                        <td>{{ page.createdAt | formatDate }}</td>
                                        <td>{{ page.modifiedAt | formatDate}}</td>
                                        <td><div v-if="page.showComments"> <router-link :to="{name: 'comments', query: { pageId: page.id }}">{{ page.comments_count}}</router-link></div></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <router-link :to="{name: 'editpage', params: { id: page.id }}" class="btn btn-primary">Edit</router-link>

                                                <button class="btn btn-danger" @click="deletePage(page.id)">Delete</button>
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
import {showToast} from "../../utils/admin";

export default {
    data() {
        return {
            pages: [],
            laravelData: {},
            searchQuery: ''
        }
    },
    created() {
        this.getResults();
    },
    methods: {
        getResults(page = 1) {
            axios.get('/api/page?page=' + page + "&query=" + encodeURIComponent(this.searchQuery))
                .then(response => {
                    //this.pages = response.data.data;
                    this.laravelData = response.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        },
        deletePage(id) {
            axios.delete(`/api/page/${id}`)
                .then(response => {
                    let i = this.laravelData.data.map(item => item.id).indexOf(id); // find index of your object
                    this.laravelData.data.splice(i, 1)
                    showToast(response.data.success, 'Page has been deleted.');
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    },
}
</script>
