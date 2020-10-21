<template>
    <div>
        <h2 v-if="valid">{{timestamp}}</h2>
    </div>
</template>

<script>
    import { DateTime, Interval } from "luxon";
    export default {
        name: "Timer",
        props: {
            enterTime: '',
            description: ''
        },
        data() {
            return {
                timestamp: "",
                valid: true,
                intId:''
            }
        },
        created() {
            this.intId = setInterval(this.getNow, 1000)
        },
        methods: {
            getNow() {
                const now = DateTime.local();
                const entTime = DateTime.fromISO(this.enterTime);
                const inter = Interval.fromDateTimes(now, entTime);
                if(inter.isValid) {
                    const dur = inter.toDuration(['hours', 'minutes', 'seconds']).toFormat("hh':'mm':'ss");
                    this.timestamp = this.description + ' ' +dur;
                } else {
                    this.valid = false;
                    clearInterval(this.intId);
                }

            }
        }
    }
</script>

<style scoped>

</style>