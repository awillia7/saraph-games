<template>
    <div class="home-container">
        <post-feed
            :posts="posts"
            v-if="!loading"
        ></post-feed>
    </div>
</template>
<script>
    import PostFeed from './PostFeed.vue';

    import { populatePostSummary } from '../js/helpers';

    export default {
        data() {
            return {
                posts: [],
                loading: true
            }
        },
        components: {
            PostFeed
        },
        created() {

            let apiUrl = '/api/posts/';
            if (this.$store.getters.getAuth()) {
                apiUrl = '/api/admin/posts';
            }

            this.$http.get(apiUrl).then((res) => {
                
                this.loading = false;
                let results = res.data.feed;
                
                // add paging later...
                for (let i = 0; i < results.length; i++) {
                    this.posts[i] = populatePostSummary(results[i]);
                }
            });
        }
    }
</script>