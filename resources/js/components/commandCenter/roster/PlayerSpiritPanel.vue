<template>
    <v-card elevation="5">
        <v-card-title class="primary-title">
            <div>
                <h3 class="headline mb-0">{{ this.playerSpirit.playerName }}</h3>
                <v-layout class="row wrap">
                    <v-flex class="xs8">
                        <v-layout class="row wrap">
                            <v-flex class="xs12">
                                {{ this.playerSpirit.gameDescription }}
                            </v-flex>
                            <v-flex class="xs12">
                                Essence Cost: {{ this.playerSpirit.essence_cost }}
                            </v-flex>
                            <v-flex class="xs12">
                                Energy: {{ this.playerSpirit.energy }}
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

    export default {
        name: "PlayerSpiritPanel",
        props: ['playerSpirit']
    }
</script>

<style scoped>

</style>