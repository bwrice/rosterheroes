<template>
    <div>
        <span class="subtitle-1 font-weight-light">{{message}}</span>
        <v-divider></v-divider>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ProvinceEvent from "../../../../models/ProvinceEvent";

    export default {
        name: "SquadLeavesProvince",
        props: {
            provinceEvent: {
                type: ProvinceEvent,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_provinceByUuid'
            ]),
            message() {
                let message = this.provinceEvent.squad.name + ' leaves ';
                message += this._provinceByUuid(this.provinceEvent.provinceUuid).name + ' to ';
                message += this._provinceByUuid(this.provinceEvent.extra.to.uuid).name;
                return message;
            }
        }
    }
</script>

<style scoped>

</style>
