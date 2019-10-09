<template>
    <transition name="fade">
        <v-card>
            <v-card-title class="primary-title">
                {{ playerName }}
            </v-card-title>
            <v-card-text>
                <v-row no-gutters>
                    <v-col cols="8">
                        <p class="ma-0">{{ gameDescription }}</p>
                        <p class="ma-0">Essence Cost: {{ essenceCost }}</p>
                        <p class="ma-0">Energy: {{ energy }}</p>
                    </v-col>
                    <v-col cols="4">
                        <slot name="spirit-actions">
                            <!-- Slotted Spirit Actions -->
                        </slot>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12">
                        <PositionChipList :positions="playerSpiritPositions"></PositionChipList>
                    </v-col>
                </v-row>
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
</style>
