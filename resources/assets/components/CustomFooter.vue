<template>
    <div id="footer">
        <div :class="containerClass">
            <p>The information presented on this website about <strong>Redemption</strong>, both literal and graphical, is copyrighted by Cactus Game Design Inc.</p>
            <p>This website is not produced, endorsed, supported, or affiliated with Cactus Game Design Inc.</p>
        </div>
    </div>
</template>
<script>
    export default {
        computed: {
            containerClass() {
                return `${this.$route.name}-container`;
            }
        }
    }
</script>
<style>

    #footer {
        margin-top: 1.125em;
        align-content: center;
    }

    #footer p {
        font-size: 10px;
        color: #767676 !important;
        margin-bottom: 5px;
        margin-top: 5px;
        text-align: center;
    }
</style>