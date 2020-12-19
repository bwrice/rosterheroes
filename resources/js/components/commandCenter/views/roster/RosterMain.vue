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

    import EditSpiritButton from "../../roster/EditSpiritButton";
    import PlayerSpiritPanel from "../../roster/PlayerSpiritPanel";

    import { mapGetters } from 'vuex'

    import SingleColumnLayout from "../../layouts/SingleColumnLayout";
    import AddSpiritRouterButton from "../../global/AddSpiritRouterButton";
    import SpiritEssenceCard from "../../roster/SpiritEssenceCard";
    import PaginationBlock from "../../global/PaginationBlock";
    import HeroRosterCard from '../../roster/HeroRosterCard';
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import EssenceAndRosterColumn from "../../roster/EssenceAndRosterColumn";
    import PlayerSpiritPool from "../../roster/PlayerSpiritPool";

    export default {
        name: "RosterMain",
        components: {
            PlayerSpiritPool,
            EssenceAndRosterColumn,
            PaginationBlock,
            SpiritEssenceCard,
            AddSpiritRouterButton,
            SingleColumnLayout,
            HeroRosterCard,
            EditSpiritButton,
            RemoveSpiritButton,
            PlayerSpiritPanel
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
