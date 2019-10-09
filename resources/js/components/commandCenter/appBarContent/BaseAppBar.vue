<template>
    <div>
        <slot name="before-title">
            <!-- slot -->
        </slot>
        <v-toolbar-title>
            {{ appBarTitle }}
        </v-toolbar-title>
    </div>
</template>

<script>
    export default {
        name: "BaseAppBar",
        props: {
            appBarTitle: {
                type: String,
                default: ''
            }
        }
    }
</script>

<style scoped>

</style>
