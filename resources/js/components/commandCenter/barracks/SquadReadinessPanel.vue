<template>
    <v-sheet color="rgba(0,0,0,.4)" class="ma-1 pa-1 rounded-sm">
        <v-row no-gutters align="center">
            <v-col cols="3" sm="2">
                <v-row no-gutters justify="center">
                    <v-icon
                        v-if="readiness === 'full'"
                        x-large
                        color="success"
                    >
                        check_circle
                    </v-icon>
                    <v-icon
                        v-else-if="readiness === 'partial'"
                        x-large
                        color="accent"
                    >
                        error
                    </v-icon>
                    <v-icon
                        v-else
                        x-large
                        color="error"
                    >
                        cancel
                    </v-icon>
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
            },
            readiness: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
