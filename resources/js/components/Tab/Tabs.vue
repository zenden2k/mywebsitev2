<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Tabs</h1></div></section>
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
                    <router-link :to="{name: 'addtab'}" tag="button" class="btn btn-info"><i class="fa fa-plus"></i> Add Tab</router-link>
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
                                        <th>URL</th>
                                        <th>Order number</th>
                                        <th>Alias</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in laravelData.data" :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.title_ru }}</td>
                                        <td>{{ item.url }}</td>
                                        <td>{{ item.orderNumber }}</td>
                                        <td>{{ item.alias }}</td>
                                        <td><span :class="item.active ? 'badge bg-success' : 'badge bg-danger'"><span v-if="item.active">Active</span><span v-else>Inactive</span></span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <router-link :to="{name: 'edittab', params: { id: item.id }}" class="btn btn-primary">Edit</router-link>

                                                <button class="btn btn-danger" @click="deleteItem(item.id)">Delete</button>
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
            laravelData: {},
            searchQuery: ""
        }
    },
    created() {
        this.getResults();
    },
    methods: {
        getResults(page = 1) {
            const pageCondition = this.$route.query.pageId ? '&pageId=' + encodeURIComponent(this.$route.query.pageId) : '';
            axios.get('/api/tab?page=' + page + "&query=" + encodeURIComponent(this.searchQuery) + pageCondition)
                .then(response => {
                   //this.pages = response.data.data;
                    this.laravelData = response.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        },
        deleteItem(id) {
            axios.delete(`/api/tab/${id}`)
                .then(response => {
                    let i = this.laravelData.data.map(item => item.id).indexOf(id); // find index of your object
                    this.laravelData.data.splice(i, 1)
                    showToast(response.data.success, 'Tab has been deleted.');
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    },
}
</script>
