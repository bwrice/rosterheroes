<template>
    <v-sheet color="#526e5a"
             style="margin: 1px 0 1px 0"
             class="py-1 rounded-sm"
    >
        <v-row no-gutters align="center" justify="center" class="mx-2">
            <v-col cols="8">
                <v-row no-gutters align="center" justify="center" class="py-1">
                    <v-col cols="12" class="text-truncate">
                        <span class="text-body-2 font-weight-light px-2">{{unopenedChest.description}}</span>
                    </v-col>
                    <v-col cols="12" class="text-truncate rh-op-90 pb-1" v-if="unopenedChest.source">
                        <span class="caption font-weight-light">{{unopenedChest.source.type}}: </span>
                        <span class="caption font-weight-regular">{{unopenedChest.source.name}}</span>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="4">
                <v-row no-gutters justify="end">
                    <v-btn
                        fab
                        dark
                        x-small
                        color="rgba(0, 0, 0, .4)"
                        @click="openChestClicked"
                        class="py-1"
                    >
                        <v-icon>lock_open</v-icon>
                    </v-btn>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import UnopenedChest from "../../../models/UnopenedChest";

    export default {
        name: "UnopenedChestPanel",
        props: {
            unopenedChest: {
                type: UnopenedChest,
                required: true
            }
        },
        methods: {
            openChestClicked(event) {
                this.$emit('openChestClicked', this.unopenedChest, event);
            }
        }
    }
</script>

<style scoped>

</style>
