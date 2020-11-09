<template>
    <div>
        <v-tabs
            v-model="tab"
            mobile-breakpoint="10"
            centered
            background-color="#32343d"
            color="#b3c9c3"
            slider-color="primary"
        >
            <v-tab
                v-for="(item, key) in items"
                :key="key"
            >
                <slot name="tab" :item="item"></slot>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" style="background-color: transparent">
            <div
                v-for="(item, key) in items"
                :key="key"
            >
                <slot :item="item"></slot>
            </div>
        </v-tabs-items>
    </div>
</template>

<script>
    import SideQuestMinionTab from "../views/campaign/SideQuestMinionTab";
    export default {
        name: "TabbedItems",
        components: {SideQuestMinionTab},
        props: {
            items: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                tab: null
            }
        }
    }
</script>

<style scoped>

</style>
