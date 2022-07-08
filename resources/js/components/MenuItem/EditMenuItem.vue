<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Menu Item</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'menuitems'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all menu items </router-link>
                </p>

                <div class="card card-primary card-outline card-tabs">
                    <div class="card-body">
                        <form @submit.prevent="updateItem">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tab</label>
                                                <select class="form-control" v-model="item.tab_id">
                                                    <option v-for="choice in tabs" v-bind:value="choice.id">{{ choice.title_ru }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                    <label>Target Page</label>
                                                    <select class="form-control" v-model="item.target_page_id">
                                                        <option></option>
                                                        <option v-for="choice in pages" v-bind:value="choice.id">{{ choice.title_ru }}</option>
                                                    </select>
                                                </div>
                                            <div class="form-group">
                                                <label>Title (ru)</label>
                                                <input type="text" class="form-control" v-model="item.title_ru">
                                            </div>
                                            <div class="form-group">
                                                <label>Title (en)</label>
                                                <input type="text" class="form-control" v-model="item.title_en">
                                            </div>
                                            <div class="form-group">
                                                <label>URL (ru)</label>
                                                <input type="text" class="form-control" v-model="item.url_ru">
                                            </div>
                                            <div class="form-group">
                                                <label>URL (en)</label>
                                                <input type="text" class="form-control" v-model="item.url_en">
                                            </div>
                                            <div class="form-group">
                                                <label>Order Number</label>
                                                <input type="text" class="form-control" v-model="item.order_number">
                                            </div>
                                            <div class="form-group">
                                                <label><input type="checkbox" v-model="item.status"/> Active</label>
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
            pages: [],
            tabs: [],
            errors: [],
        }
    },
    created() {
        axios.get('/api/menuitem/options').then(response => {
            this.pages = response.data.data.pages;
            this.tabs = response.data.data.tabs;
            if (this.$route.params.id) {
                axios.get(`/api/menuitem/${this.$route.params.id}`)
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
        updateItem() {
            if (this.$route.params.id) {
                axios.patch(`/api/menuitem/${this.$route.params.id}`, this.item)
                    .then(response => {
                        showToast(response.data.success, 'Menu item has been updated successfully.');
                        this.$router.push({name: 'menuitems'});

                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            } else {
                axios.post(`/api/menuitem`, this.item)
                    .then(response => {
                        showToast(response.data.success, 'Menu item has been created successfully.');
                        this.$router.push({name: 'menuitems'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
    },
}
</script>
