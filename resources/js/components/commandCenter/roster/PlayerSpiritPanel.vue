<template>
    <v-card color="blue-grey darken-1">
        <v-card-title class="primary-title">
            <div>
                <h4 class="headline mb-0">{{ playerName }}</h4>
                <v-layout class="row wrap">
                    <v-flex class="xs8">
                        <v-layout class="row wrap">
                            <v-flex class="xs12">
                                {{ gameDescription }}
                            </v-flex>
                            <v-flex class="xs12">
                                Essence Cost: {{ essenceCost }}
                            </v-flex>
                            <v-flex class="xs12">
                                Energy: {{ energy }}
                            </v-flex>
                            <v-flex class="xs12">
                                <PositionChipList :positions="playerSpiritPositions"></PositionChipList>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex class="xs4">
                        <slot name="spirit-actions">
                            <!-- Slotted Spirit Actions -->
                        </slot>
                    </v-flex>
                </v-layout>
            </div>
        </v-card-title>
    </v-card>
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

</style>