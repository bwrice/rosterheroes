<template>
    <v-flex class="xs12">
        <v-card>
            <svg xmlns="http://www.w3.org/2000/svg"
                 version="1.1"
                 viewBox="0 0 315 240">

                <rect width="315" height="240" rx="5" ry="5"
                      style="fill: #c9e5ea; stroke: #E3EFEB; stroke-width: 2"
                ></rect>
                <Province v-for="(province, uuid) in this._provinces" :key="uuid" :province="province"></Province>
            </svg>
        </v-card>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import Province from "./Province";

    export default {
        name: "MapMain",
        components: {Province},
        created: () => {
            console.log("Map Created!");
        },
        computed: {
            ...mapGetters([
                '_provinces'
            ])
        }
    }
</script>

<style scoped>

</style>