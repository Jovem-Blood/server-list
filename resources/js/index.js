import Vue from 'vue';
import Timer from "./components/Timer.vue";

Vue.config.productionTip = false;

new Vue({
    el: '#app',
    components:{
        Timer
    }
});
