<template>
    <v-list-group color="primary">
        <template v-slot:activator>
            <v-list-item-content>
                <v-list-item-title>{{groupTitle}}</v-list-item-title>
            </v-list-item-content>
        </template>
        <slot>
            <!-- Default Slot -->
        </slot>
    </v-list-group>
</template>

<script>
    export default {
        name: "NavListGroup",
        props: {
            groupTitle: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
