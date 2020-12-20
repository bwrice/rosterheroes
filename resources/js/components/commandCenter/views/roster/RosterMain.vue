<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" offset-lg="1" lg="5" offset-xl="2" xl="4">
                <EssenceAndRosterColumn :heroes="_heroes"></EssenceAndRosterColumn>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row no-gutters class="mb-2">
                            <span class="title font-weight-thin">SPIRIT POOL</span>
                        </v-row>
                    </v-col>
                    <v-col cols="12">
                        <PlayerSpiritPool></PlayerSpiritPool>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>


    import { mapGetters } from 'vuex'

    import EssenceAndRosterColumn from "../../roster/EssenceAndRosterColumn";
    import PlayerSpiritPool from "../../roster/PlayerSpiritPool";

    export default {
        name: "RosterMain",
        components: {
            PlayerSpiritPool,
            EssenceAndRosterColumn
        },
        computed: {
            ...mapGetters([
                '_heroes',
                '_playerSpirits',
            ])
        },
    }
</script>

<style scoped>

</style>
