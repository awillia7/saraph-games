<template>
    <div class="card-container" v-if="loading">
        Loading...
    </div>
    <div class="card-container" v-else>
        <card-image :card="card"></card-image>
        <div class="card-info">
            <h1>{{ card.title }}</h1>
            <br />
            <div class="card-attribute" v-if="card.types">
                <div class="attribute-label">Types:</div>
                <div class="attribute-value"><span v-for="type in card.types" :key="type">{{ type }} </span></div>
            </div>
            <div class="card-attribute">
                <div class="attribute-label">Brigade:</div> 
                <div class="attribute-value"><span v-for="brigade in card.brigades" :key="brigade">{{ brigade }} </span></div>
            </div>
            <div class="card-attribute" v-if="card.strength">
                <div class="attribute-label">Stats:</div> 
                <div v-if="card.strength === 'None'" class="attribute-value">{{ card.strength }}</div>
                <div v-else class="attribute-value">{{ card.strength }}/{{ card.toughness }}</div>
            </div>
            <div class="card-attribute" v-if="card.class">
                <div class="attribute-label">Class:</div>
                <div class="attribute-value">{{ card.class }}</div>
            </div>
            <div class="card-attribute" v-if="card.special_ability">
                <div class="attribute-label">Special Ability:</div>
                <div class="attribute-value">{{ card.special_ability }}</div>
            </div>
            <div class="card-attribute">
                <div class="attribute-label">Identifiers:</div>
                <div class="attribute-value"><span v-for="identifier in card.identifiers" :key="identifier">{{ identifier }} </span></div>
            </div>
            <div class="card-attribute" v-if="card.reference">
                <div class="attribute-label">Reference:</div>
                <div class="attribute-value">{{ card.reference }}</div>
            </div>
            <div class="card-attribute" v-if="card.artist">
                <div class="attribute-label">Artist:</div>
                <div class="attribute-value">{{ card.artist }}</div>
            </div>
            <div class="card-attribute" v-if="card.rarity">
                <div class="attribute-label">Rarity:</div>
                <div class="attribute-value">{{ card.rarity }}</div>
            </div>
            <div class="card-attribute" v-if="card.play_as">
                <div class="attribute-label">Play As:</div>
                <div class="attribute-value">{{ card.play_as }}</div>
            </div>
            <div class="card-attribute" v-if="card.errata">
                <div class="attribute-label">Errata:</div>
                <div class="attribute-value">{{ card.errata }}</div>
            </div>
            <div class="card-attribute" v-if="card.set">
                <div class="attribute-label">Set:</div>
                <div class="attribute-value">{{ card.set }}</div>
            </div>
        </div>
    </div>
</template>
<script>
    import { populateCard } from '../js/helpers';
    
    import CardImage from './CardImage.vue';
    
    export default {
        data() {
            return {
                card: null,
                loading: true
            }
        },
        created() {
            let apiUrl = '/api/lexicon/cards/'.concat(this.$route.params.card);
            this.$http.get(apiUrl).then((res) => {
                this.loading = false;
                this.card = populateCard(res.data.card);
            });
        },
        components: {
            CardImage
        }
    }
</script>
<style>

    .card-container {
        display: flex;
        align-items: flex-start;
    }

    .card-info {
        margin: 20px auto;
        background-color: rgb(0, 0, 0, 0.1);
        color: inherit;
        border: 1px solid #111111;
        padding: 10px;
        border-radius: 10px;
        width: 330px;
        min-height: 360px;
        position: relative;
    }

    .card-info h1 {
        font-size:  20px;
        font-weight: bold;
        text-align: center;
        color: inherit;
    }

    .card-attribute {
        margin-bottom: 5px;
        display: flex;
    }

    .attribute-label {
        width: 30%;
        font-size: 14px;
        font-weight: bold;
        text-align: right;
    }

    .attribute-value {
        width: 70%;
        font-size: 14px;
        text-align: left;
        margin-left: 5px;
    }
</style>