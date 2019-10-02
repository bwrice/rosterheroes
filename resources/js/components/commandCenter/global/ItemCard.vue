<template>
    <v-sheet color="rgba(255, 255, 255, 0.1)">
        <v-card-text class="pa-1">
            <v-row>
                <v-col cols="12" class="text-center">
                    <span class="headline">{{item.name}}</span>
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-col cols="6" class="px-2">
                    <p class="caption ma-0">
                        Type: {{item.itemType.name}}<br>
                        Base: {{item.itemType.itemBase.name}}<br>
                        Class: {{item.itemClass.name}}<br>
                        Material: {{item.material.name}}<br>
                    </p>
                </v-col>
                <v-col cols="6" class="px-2">
                    <p class="caption ma-0">
                        Protection: {{item.protection}}<br>
                        Block %: {{item.blockChance}}<br>
                        Burden: {{item.burden}}<br>
                        Value: {{item.value}}<br>
                    </p>
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
        </v-card-text>
    </v-sheet>
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
            }
        }
    }
</script>

<style scoped>

</style>
