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
        name: "SquadEntersProvince",
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
                let message = this.provinceEvent.squad.name + ' enters ';
                message += this._provinceByUuid(this.provinceEvent.provinceUuid).name + ' from ';
                message += this._provinceByUuid(this.provinceEvent.extra.from.uuid).name;
                return message;
            }
        }
    }
</script>

<style scoped>

</style>
