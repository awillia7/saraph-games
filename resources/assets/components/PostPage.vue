<template>
    <div class="post-container" v-if="loading">
        Loading...
    </div>
    <div class="post-container" v-else>

        <form 
            class="post-form" 
            v-if="newPost"
            action="#"
            method="post"
            @submit.prevent="createPost()"
        >
            <input type="hidden" name="_token" :value="csrf_token"/>
            
            <div class="form-group">
                <label>Post Key:</label>
                <input name="key" class="input-control" v-model="post.key" placeholder="Post Key"/>
                <label>Title:</label>
                <input name="title" class="input-control" v-model="post.title" placeholder="Post Title"/>
            </div>

            <div class="form-group">
                <label>Image URL:</label>
                <input name="image" class="input-control" v-model="post.image" placeholder="Post Image URL"/>
            </div>

            <div class="form-group">
                <label>Author:</label>
                <input name="author" class="input-control" v-model="post.author" placeholder="Post Author"/>
                <label>Published Date:</label>
                <input name="published" class="input-control" v-model="post.published" placeholder="yyyy/mm/dd"/>
            </div>
            
            <div class="form-group">
                <label>Content:</label>
                <textarea name="content" rows="10" class="input-control" v-model="post.content" placeholder="Content"/>
            </div>

            <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-save">Save</button>
                <router-link :to="{ name: 'home' }" 
                    class="btn btn-cancel"
                >
                    Cancel
                </router-link>
            </div>
        </form>

        <div class="post-wrapper">

            <figure
                :style="postImageStyle"
            ></figure>

            <div class="post-text-wrapper">
                <span class="post-title">{{ post.title }}</span>
                <span>by <a href="#" class="post-author">{{ post.author }}</a> - {{ publishedDate }}</span>
                <div class="post-content">
                    <div class="post-content-body" v-html="post.content"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { populatePost } from '../js/helpers';
    import { prettyDate } from '../js/helpers';

    export default {
        data() {
            return {
                post: null,
                loading: true,
                newPost: false,
                csrf_token: window.csrf_token
            }
        },
        computed: {
            oldPost() {
                var key = this.$route.params.post;
                return this.$store.getters.getPost(key);
            },
            postImageStyle() {
                return {
                    'background-image': `url(${this.post.image})`                    
                };
            }, publishedDate() {
                if (this.newPost) {
                    return prettyDate(this.post.published);
                } else {
                    return this.post.published;
                }
            }
        },
        methods: {
            createPost() {
                this.$http.post('/api/posts', this.post)
                    .then((res) => {
                        this.$router.replace({ name: 'post', params: { post: res.post.key } });
                    })
                    .catch((err) => console.error(err));
            }
        },
        created() {
            let apiUrl = '/api/posts/'.concat(this.$route.params.post);

            if (this.$route.params.post == 'new'
                && this.$store.getters.getAuth()
            ) {
                
                this.post = {
                    key: "",
                    author: "",
                    content: "",
                    image: "",
                    published: "",
                    title: ""
                };

                this.loading = false;
                this.newPost = true;
            } else {

                if (this.$store.getters.getAuth())
                {
                    apiUrl = '/api/admin/posts/'.concat(this.$route.params.post);
                }

                this.$http.get(apiUrl).then((res) => {
                    this.post = populatePost(res.data.post[0]);
                    
                    if (this.post.key) {
                        this.loading = false;   
                    } else {
                        window.location.href = '/';
                    }
                });
            }
        }
     };
</script>
<style>

    .form-group {
        display: flex;
        flex-direction: row;
    }

    .form-group label {
        flex: none;
        display: block;
        width: 100px;
        font-weight: bold;
        font-size: 0.8em;
        margin-right: 10px;
    }

    .form-group .input-control {
        flex: 1 1 auto;
        display: block;
        margin-bottom: 10px;
        margin-right: 8px;
        padding: 4px;
        margin-top: -4px;
    }

    .form-group .btn {
        flex: 1 1 auto;
        display: block;
        cursor: pointer;
        border-radius: 4px;
        padding-top: 12px;
        padding-bottom: 12px;
        margin-right: 12px;
        margin-left: 12px;
    }

    .form-group .btn-save {
        border: #f0890e;
        background-color: #f0890e;
        color: #ffffff;
    }

    .form-group .btn-cancel {
        border: rgb(0, 0, 0, 0.4);
        background-color: rgb(0, 0, 0, 0.4);
        color: #ffffff;
    }

    .post-wrapper {
        margin: 0 auto;
        display: flex;
        align-items: center;
        align-content: center;
        justify-content: center;
        width: 80%;
    }

    @media (max-width: 1024px) {
        .post-wrapper {
            flex-direction: column;
            width: 100%;
            margin: 0;
        }
    }

    @media (orientation: portrait) {
        .post-wrapper {
            flex-direction: column;
            width: 100%;
            margin: 0;
        }
    }

    .post-wrapper figure {
        position: relative;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-color: #5f5f5f;
        cursor: pointer;
        width: 350x;
        height: 400px;
        min-width: 350px;
        min-height: 400px;
        border: 1px solid #111111;
        border-radius: 10px;
        margin: 20px 0 20px 20px;
    }

    
    @media (max-width: 350px) {
        .post-wrapper figure {
            margin: 0;
        }
    }

    @media (orientation: portrait) {
        .post-wrapper figure {
            margin: 0;
        }
    }

    .post-text-wrapper {
        padding: 0 1.125rem;
        margin: 20px 20px;
        display: flex;
        flex-direction: column;
    }

    .post-text-wrapper span {
        padding: 0 1.125rem;
        color: #f0890e;
    }

    .post-title {
        font-size: 3.2rem;
    }

    .post-author {
        font-weight: 1000;
        color: inherit;
    }

    .post-content {
        margin: 20px auto;
        background-color: rgb(0, 0, 0, 0.1);
        color: inherit;
        border: 1px solid #111111;
        border-radius: 10px;
        padding: 0 1.125rem;
        line-height: 1.5;
    }

    .post-content p a {
        color: #f0890e;
    }

    .post-content p a:hover {
        border-bottom: 2px solid #f0890e;
    }

</style>