<template>
    <div>
        <div id="toolbar">
            <router-link :to="{ name: 'home' }">
                <img class="icon" :src="logoUrl">
            </router-link>
            <ul class="links">
                <li>
                    <router-link :to="{ name: 'lexicon-home' }">
                        Lexicon
                    </router-link>
                </li>
                <li v-if="$store.state.auth">
                    <a @click="logout">Log Out</a>
                    <form
                        style="display: hidden"
                        action="/logout"
                        method="POST"
                        id="logout"
                    >
                        <input type="hidden" name="_token" :value="csrf_token"/>
                    </form>
                </li>
            </ul>
        </div>
        <router-view></router-view>
        <custom-footer></custom-footer>
    </div>
</template>
<script>
    import CustomFooter from './CustomFooter.vue';
    
    export default {
        computed: {
            logoUrl() {
                return `${window.cdn_url || ''}images/logo.svg`;
            }
        },
        components: {
            CustomFooter
        },
        data() {
            return {
                csrf_token: window.csrf_token
            }
        },
        methods: {
            logout() {
                document.getElementById('logout').submit();
            }
        }
    }
</script>
<style>
    #toolbar {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #e4e4e4;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.125rem;
    }

    #toolbar a {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    #toolbar .icon {
        width: 200px;
        margin: 20px;
    }

    #toolbar ul {
        display: flex;
        align-items: center;
        list-style: none;
        padding: 0 24px 0 0;
        margin: 0;
    }

    #toolbar ul li {
        padding: 10px 10px 0 10px;
    }

    #toolbar ul li a {
        text-decoration: none;
        line-height: 1;
        color: #f0890e;
        font-size: 18px;
        padding-bottom: 8px;
        letter-spacing: 0.5px;
        cursor: pointer;
    }

    #toolbar ul li a:hover {
        border-bottom: 2px solid #f0890e;
        padding-bottom: 6px;
    }

    @media (max-width: 373px) {
        #toolbar ul {
            padding-right: 12px;
        }
    }

</style>