<template>
    <div class="lexicon-container">
        <form class="card-search" v-on:submit.prevent="onSubmit">
                
                <div class="form-group">
                    <input v-model="newGlobalSearch" placeholder="Search for cards">
                    <input type="submit" value="Search" class="btn">
                </div>

                <div class="form-group">
                    <span>Search Parameters:</span>
                </div>

                <div class="form-group">
                    <span class="field-row">
                        <input type="checkbox" id="globalSearchNameFlag" v-model="newGlobalSearchNameFlag">
                        <label for="globalSearchNameFlag">Name</label>
                    </span>
                    <span class="field-row">
                        <input type="checkbox" id="globalSearchTypesFlag" v-model="newGlobalSearchTypesFlag">
                        <label for="globalSearchTypesFlag">Types</label>
                    </span>
                    <span class="field-row">
                        <input type="checkbox" id="globalSearchTextFlag" v-model="newGlobalSearchTextFlag">
                        <label for="globalSearchTextFlag">Special Ability</label>
                    </span>
                </div>

        </form>

        <hr />

        <div class="search-results" v-if="loading">
            Loading...
        </div>
        <div class="search-results" v-else>
            Found {{ results.length }} cards.
        </div>
        <div class="card-results-wrapper">
            <div 
                class="card-result" 
                v-for="card in cards"
                :key="card.id"
            >
                <router-link :to="{ name: 'lexicon-card', params: { card: card.id } }">
                    <card-image :card="card"></card-image>
                    <span class="card-summary-title">{{ card.title }}</span>
                    <span class="card-summary-expansion">{{card.set }}</span>
                </router-link>
            </div>
        </div>
    </div>
</template>
<script>
    import { populateCard } from '../js/helpers';
    import { buildQueryGroup } from '../js/helpers';
    
    import CardImage from './CardImage.vue';

    export default {
        data() {
            return {
                newGlobalSearch: '',
                newGlobalSearchNameFlag: true,
                newGlobalSearchTypesFlag: false,
                newGlobalSearchTextFlag: false,
                loading: false,
                results: [],
                cards: []
            }
        },
        computed: {
            lastGlobalSearch() {
                return this.$store.state.lastGlobalSearch;
            }
        },
        methods: {
            onSubmit: function() {
                
                if (this.newGlobalSearch.length &&
                    (this.newGlobalSearchNameFlag || this.newGlobalSearchTypesFlag || this.newGlobalSearchTextFlag)
                ) {
                    this.cards = [];

                    this.loading = true;
                    
                    let lastGlobalSearch = this.newGlobalSearch;
                    let lastGlobalSearchNameFlag = this.newGlobalSearchNameFlag;
                    let lastGlobalSearchTypesFlag = this.newGlobalSearchTypesFlag;
                    let lastGlobalSearchTextFlag = this.newGlobalSearchTextFlag;
                    
                    let firstChar = "?";
                    let apiUrl = '/api/lexicon/cards';

                    if (this.newGlobalSearchNameFlag) {
                        apiUrl += firstChar + buildQueryGroup('title', this.newGlobalSearch, 0);
                        firstChar = "&";
                    }
                    if (this.newGlobalSearchTypesFlag) {
                        apiUrl += firstChar + buildQueryGroup('types', this.newGlobalSearch, 1);
                        firstChar = "&";
                    }
                    if (this.newGlobalSearchTextFlag) {
                        apiUrl += firstChar + buildQueryGroup('special_ability', this.newGlobalSearch, 2);
                        firstChar = "&";
                        apiUrl += firstChar + buildQueryGroup('play_as', this.newGlobalSearch, 3);
                    }
                    
                    this.$http.get(apiUrl).then((res) => {
                        
                        this.$store.commit('updateLastGlobalSearch', lastGlobalSearch);
                        this.$store.commit('updateLastGlobalSearchNameFlag', lastGlobalSearchNameFlag);
                        this.$store.commit('updateLastGlobalSearchTypesFlag', lastGlobalSearchTypesFlag);
                        this.$store.commit('updateLastGlobalSearchTextFlag', lastGlobalSearchTextFlag);
                        this.results = res.data.cards;
                        
                        // add paging later...
                        for (let i = 0; i < this.results.length; i++) {
                            this.cards[i] = populateCard(this.results[i]);
                        }

                        this.loading = false;
                    });
                }
            }
        },
        mounted() {
            this.newGlobalSearch = this.$store.getters.getLastGlobalSearch();
            this.newGlobalSearchNameFlag = this.$store.getters.getLastGlobalSearchNameFlag();
            this.newGlobalSearchTypesFlag = this.$store.getters.getLastGlobalSearchTypesFlag();
            this.newGlobalSearchTextFlag = this.$store.getters.getLastGlobalSearchTextFlag();
            if (this.newGlobalSearch.length) {
                this.loading = true;
                this.onSubmit();
            }
        },
        components: {
            CardImage
        }
    }

</script>
<style>

    .card-results-wrapper {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        padding: 0;
        margin: 0;
    }

    .card-result {
        width: 240px;
        padding: 10px 10px;
    }

    .card-result a {
        text-decoration: none;
        line-height: 1;
        color: #f0890e;
        padding-bottom: 8px;
        letter-spacing: 0.5px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .card-summary-title {
        font-size: 16px;
        padding-bottom: .25em;
    }

    .card-summary-expansion {
        font-size: 14px;
    }

    .card-search {
        border-bottom: 1px solid #e4e4e4;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        padding: 1.25rem;
    }

    .card-search .form-group {
        margin-bottom: 10px;
    }

    .form-group .field-row {
        display: flex;
        flex-direction: row;
    }

    @media (max-width: 630px) {
        .card-search {
            padding-bottom: 1.25rem;
            border-bottom: none;
            box-shadow: none;
            width: 75%;
        }

        .form-group {
            flex-direction: column !important;
        }
    }

    @media (orientation: portrait) {
        .card-search {
            padding-bottom: 1.25rem;
            border-bottom: none;
            box-shadow: none;
            width: 75%;
        }

        .form-group {
            flex-direction: column !important;
        }
    }

    input {
        background-color: #f0890e;
        background-color: transparent;
        padding: 11px;
        border: 1px solid #dbdbdb;
        border-radius: 2px;
        box-sizing: border-box;
    }

    input.btn {
        background-color: #f0890e;
        color: #ffffff;
        cursor: pointer;
        border: #f0890e;
        border-radius: 4px;
        padding-top: 12px;
        padding-bottom: 12px;
        margin-top: 1.25rem;
    }

</style>