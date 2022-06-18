<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Edit Blog Post</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'blogposts'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all blog posts </router-link>
                </p>

                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true" style="">General</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updateItem">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select class="form-control" v-model="post.category_id">
                                                        <option v-for="choice in categories" v-bind:value="choice.id">{{ choice.title_ru }}</option>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Title (ru)</label>
                                                            <input type="text" class="form-control" v-model="post.title_ru">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Title (en)</label>
                                                            <input type="text" class="form-control" v-model="post.title_en">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alias</label>
                                                    <input type="text" class="form-control" v-model="post.alias">
                                                </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label>Foreword (ru)</label>
                                            <editor
                                                api-key="pxz4k1qpydqkj97lp1sb2qctqa2uc4acsa7xsermn9k5rrga"
                                                v-model="post.foreword_ru"
                                                :init="{
         height: 500,
         menubar: false,
         plugins: [
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen',
           'insertdatetime media table paste code help wordcount'
         ],
         toolbar:
           'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent image | removeformat | help'
       }"
                                            />
<!--                                            <vue-editor v-model="post.foreword_ru" />-->
                                        </div>
                                        <div class="form-group">
                                            <label>Foreword (en)</label>
                                            <editor
                                                api-key="pxz4k1qpydqkj97lp1sb2qctqa2uc4acsa7xsermn9k5rrga"
                                                v-model="post.foreword_en"
                                                :init="{
         height: 500,
         menubar: false,
         plugins: [
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen',
           'insertdatetime media table paste code help wordcount'
         ],
         toolbar:
           'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent image | removeformat | help'
       }"
                                            />
<!--                                            <vue-editor v-model="post.foreword_en" class="mt-1"/>-->
                                        </div>
                                        <div class="form-group">
                                            <label>Content (ru)</label>
                                            <editor
                                                api-key="pxz4k1qpydqkj97lp1sb2qctqa2uc4acsa7xsermn9k5rrga"
                                                v-model="post.content_ru"
                                                :init="{
         height: 500,
         menubar: false,
         plugins: [
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen',
           'insertdatetime media table paste code help wordcount'
         ],
         toolbar:
           'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent | removeformat | help'
       }"
                                            />
<!--                                            <vue-editor v-model="post.content_ru" />-->
                                        </div>
                                        <div class="form-group">
                                            <label>Content (en)</label>
                                            <editor
                                                api-key="pxz4k1qpydqkj97lp1sb2qctqa2uc4acsa7xsermn9k5rrga"
                                                v-model="post.content_en"
                                                :init="{
         height: 500,
         menubar: false,
         plugins: [
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen',
           'insertdatetime media table paste code help wordcount'
         ],
         toolbar:
           'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent | removeformat | help'
       }"
                                            />
<!--                                            <vue-editor v-model="post.content_en" class="mt-1"/>-->
                                        </div>
                                    </div>
                                        <div class="form-group">
                                            <label><input type="checkbox" v-model="post.status"/> Active</label>
                                        </div>
                                        <div class="form-group">
                                            <label><input type="checkbox" v-model="post.enable_comments"> Show comments block</label>
                                        </div>

                                </div>
                            </div>
                            <div>
                                <div v-for="(v, k) in errors" :key="k">
<!--                                    {{k}}:-->
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
import Editor from '@tinymce/tinymce-vue'
export default {
    components: {
        'editor': Editor
    },
    data() {
        return {
            post: {
                blocks: [],
                sidebarBlocks: [],
            },
            categories: [],
            errors: [],
            sidebarBlock: null,
            allSidebarBlocks: []
        }
    },
    created() {
        axios.get('/api/blogpost/options').then(response => {
            this.categories = response.data.data.categories;
        })
            .catch(function (error) {
                console.error(error);
            });

        if (this.$route.params.id) {
            axios.get(`/api/blogpost/${this.$route.params.id}`)
                .then(response => {
                    this.post = response.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    },
    methods: {
        updateItem() {
                if (this.$route.params.id) {
                    axios.patch(`/api/blogpost/${this.$route.params.id}`, this.post)
                        .then(response => {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: '',
                                body: 'Blog post has been updated successfully.',
                                autohide: true,
                                delay: 3000,
                            });
                            this.$router.push({name: 'blogposts'});

                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                        });
                } else {
                    axios.post(`/api/blogpost`, this.post)
                        .then(response => {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: '',
                                body: 'Blog post has been created successfully.',
                                autohide: true,
                                delay: 3000,
                            });
                            this.$router.push({name: 'blogposts'});
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                        });
                }
        },
    },
}
</script>
