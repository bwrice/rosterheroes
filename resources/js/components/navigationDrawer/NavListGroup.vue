<template>
    <v-list-group color="primary">
        <template v-slot:activator>
            <v-list-item-content>
                <v-list-item-title>{{groupTitle}}</v-list-item-title>
            </v-list-item-content>
        </template>
        <NavListItem
            :title="homeTitle"
            :to="homeRoute"
        ></NavListItem>
        <slot>
            <!-- Default Slot -->
        </slot>
    </v-list-group>
</template>

<script>
    import NavListItem from "./NavListItem";
    export default {
        name: "NavListGroup",
        components: {NavListItem},
        props: {
            groupTitle: {
                type: String,
                required: true
            },
            mainRouteName: {
                type: String,
                required: true
            }
        },
        computed: {
            homeTitle() {
                return this.groupTitle + ' Home';
            },
            homeRoute() {
                let squadSlug = this.$route.params.squadSlug;
                return {
                    name: this.mainRouteName,
                    params: {
                        squadSlug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
