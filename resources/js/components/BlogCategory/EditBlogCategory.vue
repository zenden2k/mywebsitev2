<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Blog Category</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'blogcategories'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to blog categories </router-link>
                </p>

                <div class="card card-primary card-outline card-tabs">
                    <div class="card-body">
                        <form @submit.prevent="updateItem">
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
import {showToast} from "../../utils/admin";
export default {
    data() {
        return {
            item: {
                active: true
            },
            pages: [],
            tabs: [],
            errors: [],
        }
    },
    created() {
        if (this.$route.params.id) {
            axios.get(`/api/blogcategory/${this.$route.params.id}`)
                .then(response => {
                    this.item = response.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    },
    methods: {
        updateItem() {
            if (this.$route.params.id) {
                axios.patch(`/api/blogcategory/${this.$route.params.id}`, this.item)
                    .then(response => {
                        showToast(response.data.success, 'Blog category has been updated successfully.');
                        this.$router.push({name: 'blogcategories'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            } else {
                axios.post(`/api/blogcategory`, this.item)
                    .then(response => {
                        showToast(response.data.success, 'Blog category has been created successfully.');
                        this.$router.push({name: 'blogcategories'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
    },
}
</script>
