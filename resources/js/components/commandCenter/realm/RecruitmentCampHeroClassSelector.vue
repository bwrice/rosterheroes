<template>
    <v-sheet :color="sheetColor" :elevation="elevation" class="rounded rh-clickable" @click.native="handleClick">
        <v-row no-gutters class="pa-1">
            <v-col cols="12">
                <HeroClassIcon :hero-class="heroClass"></HeroClassIcon>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <span class="text-body-1 text-md-h6 font-weight-light text-primary">{{heroClass.name.toUpperCase()}}</span>
        </v-row>
    </v-sheet>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import HeroClass from "../../../models/HeroClass";
    import HeroClassIcon from "../../icons/heroClasses/HeroClassIcon";

    export default {
        name: "RecruitmentCampHeroClassSelector",
        components: {HeroClassIcon},
        props: {
            heroClass: {
                type: HeroClass,
                required: true
            }
        },
        methods: {
            ...mapActions([
                'setRecruitmentHeroClass'
            ]),
            handleClick() {
                this.setRecruitmentHeroClass(this.heroClass);
            }
        },
        computed: {
            ...mapGetters([
                '_recruitmentHeroClass'
            ]),
            isSelected() {
                if (!this._recruitmentHeroClass) {
                    return false;
                }
                return this._recruitmentHeroClass.id === this.heroClass.id;
            },
            elevation() {
                return this.isSelected ? 0 : 6;
            },
            sheetColor() {
                return this.isSelected ? '#447075' : '#445866';
            }
        }
    }
</script>

<style scoped>

</style>
