<template>
    <div 
        class="card-image" 
        :style="cardImageStyle"
    >
    </div>
</template>
<script>
export default {
    props: [ 'card' ],
    computed: {
        cardImageStyle() {
            return {
                'background-image': `url(${this.card.image})`
            };
        }
    }
}
</script>
<style>

    .card-image {
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-color: #5f5f5f;
        cursor: pointer;
        position: relative;
        width: 240px;
        height: 360px;
        border: 1px solid #111111;
        border-radius: 10px;
        margin: 20px auto;
    }

</style>