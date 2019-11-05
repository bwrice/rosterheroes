<template>
    <SvgIconSheet
        :color="'#C8DDE0'"
        :svg="heroClass.svg"
        :classes-object="{'pa-1': true}"
    >
    </SvgIconSheet>
</template>

<script>
    import SvgIconSheet from "./SvgIconSheet";

    import {mapGetters} from 'vuex';
    import HeroClass from "../../../models/HeroClass";

    export default {
        name: "HeroClassIcon",
        components: {SvgIconSheet},
        props: {
            heroClass: {
                type: HeroClass,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
