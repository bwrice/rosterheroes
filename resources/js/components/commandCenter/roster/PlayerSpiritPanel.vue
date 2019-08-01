<template>
    <transition name="fade">
        <v-card>
            <v-card-title class="primary-title">
                {{ playerName }}
            </v-card-title>
            <v-card-text>
                <v-layout>
                    <v-flex class="xs8">
                        <p class="ma-0">{{ gameDescription }}</p>
                        <p class="ma-0">Essence Cost: {{ essenceCost }}</p>
                        <p class="ma-0">Energy: {{ energy }}</p>
                    </v-flex>
                    <v-flex class="xs4">
                        <slot name="spirit-actions">
                            <!-- Slotted Spirit Actions -->
                        </slot>
                    </v-flex>
                </v-layout>
                <v-layout>
                    <v-flex class="xs12">
                        <PositionChipList :positions="playerSpiritPositions"></PositionChipList>
                    </v-flex>
                </v-layout>
            </v-card-text>
        </v-card>
    </transition>
</template>

<script>
    import PositionChipList from "./PositionChipList";
    import { playerSpiritMixin } from '../../../mixins/playerSpiritMixin';

    export default {
        name: "PlayerSpiritPanel",
        components: {PositionChipList},
        props: ['playerSpirit'],
        mixins: [
            playerSpiritMixin
        ]
    }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>