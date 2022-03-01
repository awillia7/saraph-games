<template>
    <div class="post-wrapper">
        <router-link :to="{ name: 'post', params: { post: post.key } }">
            <div class="post-summary">
                <figure class="post-image" :style="getBgImg(post.image)">
                </figure>
                <div class="post-details">
                    <span>{{ post.title }}</span>
                    <span>{{ post.published }} by {{ post.author }}</span>
                </div>
            </div>
        </router-link>
    </div>
</template>
<script>

    export default {
        props: [ 'post' ],
        methods: {
            getBgImg(src) {
                return { backgroundImage: `url(${src})` }
            }
        }
    }

</script>
<style>

    .post-wrapper {
        width: 350px;
        text-decoration: none;
        color: inherit;
        padding: 1.25rem 1.25rem;
    }

    .post-wrapper a {
        text-decoration: none;
        color: inherit;
    }

    .post-summary {
        display: flex;
        width: 350px;
        flex-direction: column;
    }

    .post-image {
        position: relative;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-color: #5f5f5f;
        cursor: pointer;
        width: 350px;
        height: 400px;
        border: 1px solid #111111;
        border-radius: 10px;
        margin: 10px auto;
    }

    .post-details {
        display: flex;
        flex-direction: column;
        color: #f0890e; 
    }

    .post-details span {
        text-align: center;
    }

</style>