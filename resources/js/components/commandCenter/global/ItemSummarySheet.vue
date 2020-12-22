<template>
    <v-row align="center" class="pa-2" no-gutters>
        <v-col cols="8">
            <template v-if="item.shopPrice">
                <v-row no-gutters align="center">
                    <v-col cols="3">
                        <v-row no-gutters align="center">
                            <span class="subtitle-2 font-went-bold pa-2">({{item.shopPriceTruncated}})</span>
                        </v-row>
                    </v-col>
                    <v-col cols="9">
                        <v-row no-gutters align="center">
                            <v-col cols="12" class="text-truncate">
                                <span class="subtitle-2 font-weight-light pa-2">{{item.name}}</span>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </template>
            <template v-else>
                <v-row no-gutters align="center">
                    <v-col cols="12" class="text-truncate">
                        <span class="subtitle-2 font-weight-light pa-2">{{item.name}}</span>
                    </v-col>
                </v-row>
            </template>
        </v-col>
        <v-col cols="4">
            <v-row no-gutters justify="end" align="center">
                <slot name="before-show-icon" :item="item">
                    <!-- Slot -->
                </slot>
                <v-btn
                       fab
                       dark
                       x-small
                       color="primary"
                       @click="handleViewClicked"
                >
                    <v-icon>visibility</v-icon>
                </v-btn>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import Item from "../../../models/Item";

    export default {
        name: "ItemSummarySheet",
        props: {
            item: {
                type: Item,
                required: true
            }
        },
        methods: {
            handleViewClicked (event) {
                this.$emit('viewItem', this.item, event);
            }
        }
    }
</script>

<style scoped>

</style>
