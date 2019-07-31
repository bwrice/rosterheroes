<template>
    <v-container class="pa-2">
        <v-card>
            <v-btn :to="rosterPage">
                <v-icon dark left>arrow_back</v-icon>Back
            </v-btn>
            <HeroRosterCard :hero="_hero" v-if="_hero">
                <template slot="body">
                    <template v-if="_hero.playerSpirit">
                        <PlayerSpiritPanel :player-spirit="_hero.playerSpirit">
                            <template v-slot:spirit-actions>
                                <RemoveSpiritButton :hero="_hero" :player-spirit="_hero.playerSpirit"></RemoveSpiritButton>
                            </template>
                        </PlayerSpiritPanel>
                    </template>
                </template>
            </HeroRosterCard>
            <v-data-iterator
                    :items="_playerSpiritsPool"
                    content-tag="v-layout"
                    hide-default-footer
                    row
                    wrap
            >
                <template v-slot:header>
                    <v-toolbar
                            class="mb-2"
                            color="secondary"
                            dark
                            text
                    >
                        <v-toolbar-title>Select Player Spirit</v-toolbar-title>
                    </v-toolbar>
                </template>
                <template v-slot:item="props">
                    <v-flex
                            fluid
                            py-1
                            xs12
                    >
                        <PlayerSpiritPanel :player-spirit="props.item">
                            <template v-slot:spirit-actions>
                                <AddSpiritButton :hero="_hero" :player-spirit="props.item"></AddSpiritButton>
                            </template>
                        </PlayerSpiritPanel>
                    </v-flex>
                </template>
            </v-data-iterator>
        </v-card>
    </v-container>
</template>

<script>
    import { mapGetters } from 'vuex';

    import PlayerSpiritPanel from '../../roster/PlayerSpiritPanel';
    import AddSpiritButton from "../../roster/AddSpiritButton";
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import HeroRosterCard from "../../roster/HeroRosterCard";

    export default {
        name: "HeroRosterView",

        components: {
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

        computed: {
            ...mapGetters([
                '_squad',
                '_currentWeek',
                '_hero',
                '_playerSpiritsPool'
            ]),
            rosterPage() {
                // TODO use navigation mixin?
                return '/command-center/' + this.$route.params.squadSlug + '/roster' ;
            }
        },
    }
</script>

<style scoped>

</style>