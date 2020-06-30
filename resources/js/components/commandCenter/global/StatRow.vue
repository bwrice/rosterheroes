<template>
    <v-sheet
        tile
        color="rgba(0,0,0,.4)"
        class="px-2"
        style="margin-bottom: 2px">
        <v-row no-gutters justify="space-between">
            <span class="font-weight-thin text-body-2 text-md-body-1">{{name.toUpperCase()}}</span>
            <span class="font-weight-regular text-body-2 text-md-body-1 rh-op-85">{{value}}</span>
        </v-row>
    </v-sheet>
</template>

<script>
    export default {
        name: "StatRow",
        props: {
            name: {
                type: String,
                required: true
            },
            value: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
