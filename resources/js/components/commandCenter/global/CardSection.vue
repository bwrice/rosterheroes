<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">{{title}}</span>
        </v-col>
        <v-col cols="12">
            <slot>
                <!-- Default Slot -->
            </slot>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "CardSection",
        props: {
            title: {
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
