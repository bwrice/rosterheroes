<template>
    <v-sheet color="rgba(0,0,0,.4)" class="ma-1 pa-1">
        <v-row no-gutters align="center">
            <v-col cols="3" sm="2">
                <v-row no-gutters justify="center">
                    <slot>
                        <!-- Default Slot -->
                    </slot>
                </v-row>
            </v-col>
            <v-col cols="9" sm="10">
                <v-row no-gutters justify="center">
                    <span class="title font-weight-regular">{{name.toUpperCase()}}</span>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    export default {
        name: "SquadReadinessPanel",
        props: {
            name: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
