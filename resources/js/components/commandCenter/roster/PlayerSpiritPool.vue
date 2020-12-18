<template>
    <v-card>
        <v-virtual-scroll
            :items="playerSpirits"
            :height="456"
            :item-height="76"
            :bench="4"
        >
            <template v-slot:default="{ item }">
                <PlayerSpiritPanel
                    :player-spirit="item"
                >
                    <template v-slot:spirit-actions>
                        <AddSpiritButton
                            v-if="hero"
                            :hero="hero"
                            :player-spirit="item"
                        ></AddSpiritButton>
                    </template>
                </PlayerSpiritPanel>
                <v-divider></v-divider>
            </template>
        </v-virtual-scroll>
    </v-card>
</template>

<script>
    import PlayerSpiritPanel from "./PlayerSpiritPanel";
    import Hero from "../../../models/Hero";
    import AddSpiritButton from "./AddSpiritButton";
    export default {
        name: "PlayerSpiritPool",
        components: {AddSpiritButton, PlayerSpiritPanel},
        props: {
            playerSpirits: {
                type: Array,
                required: true
            },
            hero: {
                type: Hero
            }
        }
    }
</script>

<style scoped>

</style>
