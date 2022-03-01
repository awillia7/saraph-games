<template>
    <div class="post-feed">
        <div class="post-summaries">
                <div v-if="$store.state.auth" class="new-post">
                    <router-link :to="{ name: 'post', params: { post: 'new' } }">
                        New Post
                    </router-link>
                </div>
                
                <post-summary
                    v-for="post in posts"
                    :post="post"
                    :key="post.key"
                ></post-summary>
        </div>
    </div>
</template>
<script>
    import PostSummary from './PostSummary.vue';

    export default {
        props: [ 'posts' ],
        components: {
            PostSummary
        }
    }
</script>
<style>

    .post-summaries {
        display: flex;
        flex-flow: row wrap;
        justify-content: space-around;
    }

    .new-post a {
        flex: 1 1 auto;
        display: block;
        cursor: pointer;
        border-radius: 4px;
        padding-top: 12px;
        padding-bottom: 12px;
        padding-right: 12px;
        padding-left: 12px;
        border: #f0890e;
        background-color: #f0890e;
        color: #ffffff;
        text-decoration: none;
    }

</style>