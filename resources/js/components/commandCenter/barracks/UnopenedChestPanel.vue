<template>
    <v-sheet color="#526e5a"
             tile
             style="margin: 1px 0 1px 0"
    >
        <v-row align="center" justify="center" class="mx-2">
            <span class="subtitle-2 font-weight-light pa-2">{{unopenedChest.description}}</span>
            <div class="flex-grow-1"></div>
            <v-btn
                fab
                dark
                x-small
                color="rgba(0, 0, 0, .4)"
                @click="openChestClicked"
            >
                <v-icon>lock_open</v-icon>
            </v-btn>
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
