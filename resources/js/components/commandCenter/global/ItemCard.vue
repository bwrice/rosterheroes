<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-row no-gutters justify="center" align="center">
                <span class="headline text-center mx-3 my-2">{{item.name}}</span>
            </v-row>
            <v-row no-gutters class="pb-2">
                <v-col
                    cols="6"
                    v-for="stat in stats"
                    :key="stat.name"
                >
                    <v-sheet class="rounded-sm mx-1 px-1" style="margin-bottom: 1px" color="rgba(0,0,0,.3)">
                        <v-row class="no-gutters" justify="space-between">
                            <span class="text-body-2 font-weight-light">{{stat.name.toUpperCase()}}:</span>
                            <span class="text-body-2 font-weight-bold rh-op-85">{{stat.value}}</span>
                        </v-row>
                    </v-sheet>
                </v-col>
            </v-row>
            <v-row class="no-gutters">
                <v-col cols="12" class="px-1">
                    <h4 v-if="item.attacks.length">Attacks:</h4>
                    <AttackPanel v-for="attack in item.attacks" v-bind:key="attack.name" :attack="attack"></AttackPanel>
                </v-col>
            </v-row>
            <v-row class="no-gutters">
                <v-col cols="12" class="px-1">
                    <h4 v-if="item.enchantments.length">Enchantments:</h4>
                    <EnchantmentSheet v-for="enchantment in item.enchantments" v-bind:key="enchantment.name" :enchantment="enchantment"></EnchantmentSheet>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import Item from "../../../models/Item";
    import AttackPanel from "./AttackPanel";
    import EnchantmentSheet from "./EnchantmentSheet";

    export default {
        name: "ItemCard",
        components: {EnchantmentSheet, AttackPanel},
        props: {
            item: {
                type: Item,
                required: true
            },
            color: String
        },

        computed: {
            sheetColor() {
                return this.color ? this.color : '#576269';
            },
            stats() {
                return [
                    {
                        name: 'type',
                        value: this.item.itemType.name
                    },
                    {
                        name: 'base',
                        value: this.item.itemType.itemBase.name
                    },
                    {
                        name: 'class',
                        value: this.item.itemClass.name
                    },
                    {
                        name: 'material',
                        value: this.item.material.name
                    },
                    {
                        name: 'protection',
                        value: this.item.protection
                    },
                    {
                        name: 'block %',
                        value: this.item.blockChance
                    },
                    {
                        name: 'weight',
                        value: this.item.weight
                    },
                    {
                        name: 'value',
                        value: this.item.value
                    },
                ]
            }
        }
    }
</script>

<style scoped>

</style>
