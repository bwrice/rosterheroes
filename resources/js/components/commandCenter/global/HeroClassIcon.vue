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

    export default {
        name: "HeroClassIcon",
        components: {SvgIconSheet},
        props: {
            heroClassId: {
                type: Number,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_heroClassByID'
            ]),
            heroClass() {
                return this._heroClassByID(this.heroClassId);
            }
        }
    }
</script>

<style scoped>

</style>
