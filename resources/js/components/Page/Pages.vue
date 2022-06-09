<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Pages</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                    <div class="card-body">
                        <table class="table table-bordered">
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
                            <tr v-for="page in pages" :key="page.id">
                                <td>{{ page.id }}</td>
                                <td>{{ page.title_ru }}</td>
                                <td>{{ page.alias }}</td>
                                <td>{{ page.created_at }}</td>
                                <td>{{ page.modified_at }}</td>
                                <td> </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link :to="{name: 'editpage', params: { id: page.id }}" class="btn btn-primary">Edit</router-link>

                                        <button class="btn btn-danger" @click="deletePage(page.id)">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top: 10px">
                             <router-link to="/admin/page/add" tag="button" class="btn btn-info">Add Page</router-link>
                        </div>
<!--                        <button type="button" class="btn btn-info" @click="this.$router.push('/page/add')">Add Page</button>-->
                    </div>

                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item"><a class="page-link" href="#">«</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">»</a></li>
                        </ul>
                    </div>
                </div>

        </section>
    </div>
</template>

<script>
import axios from 'axios';
//import LaravelVuePagination from 'laravel-vue-pagination';
export default {
    /*components: {
        'Pagination': LaravelVuePagination
    },*/
    data() {
        return {
            pages: []
        }
    },
    created() {
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.get('/api/page')
                .then(response => {
                    this.pages = response.data.data.items;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        deletePage(id) {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.delete(`/api/page/${id}`)
                    .then(response => {
                        let i = this.pages.map(item => item.id).indexOf(id); // find index of your object
                        this.pages.splice(i, 1)
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            subtitle: '',
                            body: 'Page has been deleted.',
                            autohide: true,
                            delay: 3000,
                        })
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
