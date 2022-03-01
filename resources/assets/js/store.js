import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

import router from './router';

export default new Vuex.Store({
    state: {
        lastGlobalSearch: '',
        lastGlobalSearchNameFlag: true,
        lastGlobalSearchTypesFlag: false,
        lastGlobalSearchTextFlag: false,
        auth: false
    },
    mutations: {
        addData(state, { route, data }) {
            if (data.auth) {
                state.auth = data.auth;
            }
        },
        updateLastGlobalSearch(state, data) {
            state.lastGlobalSearch = data;
        },
        updateLastGlobalSearchNameFlag(state, data) {
            state.lastGlobalSearchNameFlag = data;
        },
        updateLastGlobalSearchTypesFlag(state, data) {
            state.lastGlobalSearchTypesFlag = data;
        },
        updateLastGlobalSearchTextFlag(state, data) {
            state.lastGlobalSearchTextFlag = data;
        }
    },
    getters: {
        getLastGlobalSearch(state) {
            return lastGlobalSearch => state.lastGlobalSearch;
        },
        getLastGlobalSearchNameFlag(state) {
            return lastGlobalSearchNameFlag => state.lastGlobalSearchNameFlag;
        },
        getLastGlobalSearchTypesFlag(state) {
            return lastGlobalSearchTypesFlag => state.lastGlobalSearchTypesFlag;
        },
        getLastGlobalSearchTextFlag(state) {
            return lastGlobalSearchTextFlag => state.lastGlobalSearchTextFlag;
        },
        getAuth(state) {
            return auth => state.auth;
        }
    }
});