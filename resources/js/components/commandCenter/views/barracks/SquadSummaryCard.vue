<template>
    <v-row no-gutters>
        <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
            <span class="display-5 font-weight-bold" style="color: rgba(255, 255, 255, .75)">Gold: {{_squad.gold.toLocaleString()}}</span>
        </v-col>
        <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
            <span class="display-5 font-weight-bold" style="color: rgba(255, 255, 255, .75)">Experience: {{_squad.experience.toLocaleString()}}</span>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
        name: "SquadSummaryCard",

        computed: {
            ...mapGetters([
                '_squad',
            ])
        }
    }
</script>

<style scoped>

</style>
