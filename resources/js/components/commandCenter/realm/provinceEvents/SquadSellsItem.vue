<template>
    <ProvinceEventScrollItem :province-event="provinceEvent">
        <ProvinceEventSquadText :name="squadName"></ProvinceEventSquadText>
        sells {{itemsCount}} {{itemsCountLabel}} for {{gold}} gold to
        <span class="info--text">{{shopName}}</span>
    </ProvinceEventScrollItem>
</template>

<script>
    import ProvinceEventScrollItem from "./ProvinceEventScrollItem";
    import ProvinceEventSquadText from "./ProvinceEventSquadText";
    import ProvinceEvent from "../../../../models/ProvinceEvent";

    export default {
        name: "SquadSellsItem",
        components: {ProvinceEventSquadText, ProvinceEventScrollItem},
        props: {
            provinceEvent: {
                type: ProvinceEvent,
                required: true
            }
        },
        computed: {
            itemsCount() {
                return this.provinceEvent.extra.itemsCount
            },
            itemsCountLabel() {
                return this.itemsCount === 1 ? 'item' : 'items';
            },
            gold() {
                return numeral(this.provinceEvent.extra.gold).format('0.00a');
            },
            squadName() {
                return this.provinceEvent.squad.name;
            },
            shopName() {
                return this.provinceEvent.extra.shop.name;
            }
        }
    }
</script>

<style scoped>

</style>
