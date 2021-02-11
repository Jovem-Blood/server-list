import Vue from 'vue';
import Timer from "./components/Timer.vue";
import Edit from "./components/EditForm.vue";
//Vue.config.productionTip = false;


new Vue({
    el: '#app',
    components: {
        Timer,
        Edit
    }
});
