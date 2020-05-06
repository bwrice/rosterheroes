<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">{{title.toUpperCase()}}</span>
        </v-col>
        <slot>
            <!-- Default Slot -->
        </slot>
    </v-row>
</template>

<script>
    export default {
        name: "CardBlock",
        props: {
            title: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
