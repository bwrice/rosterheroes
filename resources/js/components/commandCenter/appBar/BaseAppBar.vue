<template>
    <v-app-bar
        fixed
        app
        elevate-on-scroll
    >
        <v-app-bar-nav-icon
            @click.stop="drawer = !drawer"
            class="accent--text"
        ></v-app-bar-nav-icon>
        <slot name="before-title">
            <!-- slot -->
        </slot>
        <v-toolbar-title>
            {{ appBarTitle }}
        </v-toolbar-title>
    </v-app-bar>
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
