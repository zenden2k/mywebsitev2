<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Downloads</h1></div></section>
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
                                    <th>URL</th>
                                    <th>Number of downloads</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in laravelData.data" :key="item.id">
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.url }}</td>
                                    <td>{{item.cnt}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <router-link :to="{name: 'downloadchart', params: { id: item.id }}" class="btn btn-primary">Show graph</router-link>
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
            //pages: [],
            laravelData: {},
            searchQuery: ""
        }
    },
    created() {
        this.getResults();
    },
    methods: {
        getResults(page = 1) {
            axios.get('/api/download?page=' + page + "&query=" + encodeURIComponent(this.searchQuery))
                .then(response => {
                    //this.pages = response.data.data;
                    this.laravelData = response.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    },
}
</script>
