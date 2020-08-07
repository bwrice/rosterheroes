<template>
    <v-container>
            <v-row>
                <v-col cols="12">
                    <v-row no-gutters justify="center" class="py-md-2">
                        <span class="rh-op-85 text-center text-h4 text-h2-md">{{_recruitmentCamp.name}}</span>
                    </v-row>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" md="5" offset-md="1" lg="4" offset-lg="2" xl="3" offset-xl="3">
                    <HeroPostTypeSelectorPanel
                        v-for="(heroPostType, id) in _recruitmentCamp.heroPostTypes"
                        :key="id"
                        :hero-post-type="heroPostType"
                    ></HeroPostTypeSelectorPanel>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" md="5" offset-md="0" lg="4" xl="3">
                    <v-row no-gutters>
                        <v-col cols="4" v-for="(heroClass, id) in heroClasses" :key="id" class="px-1">
                            <RecruitmentCampHeroClassSelector
                                :hero-class="heroClass"
                            ></RecruitmentCampHeroClassSelector>
                        </v-col>
                    </v-row>
                    <v-row no-gutters justify="center">
                        <div v-if="_recruitmentHeroRace" class="hero-type-icon">
                            <HeroRaceIcon :hero-race="_recruitmentHeroRace"></HeroRaceIcon>
                        </div>
                        <div v-else class="empty-hero-type"></div>
                        <div v-if="_recruitmentHeroClass" class="hero-type-icon">
                            <HeroClassIcon :hero-class="_recruitmentHeroClass"></HeroClassIcon>
                        </div>
                        <div v-else class="empty-hero-type"></div>
                    </v-row>
                </v-col>
            </v-row>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import RecruitmentCampHeroClassSelector from "../../../realm/RecruitmentCampHeroClassSelector";
    import HeroPostTypeSelectorPanel from "../../../realm/HeroPostTypeSelectorPanel";
    import HeroRaceIcon from "../../../../icons/heroRaces/HeroRaceIcon";
    import HeroClassIcon from "../../../../icons/heroClasses/HeroClassIcon";

    export default {
        name: "RecruitmentCampView",
        components: {
            HeroClassIcon,
            HeroRaceIcon,
            HeroPostTypeSelectorPanel,
            RecruitmentCampHeroClassSelector
        },
        mounted() {
            this.maybeUpdateRecruitmentCamp();
        },
        methods: {
            ...mapActions([
                'updateRecruitmentCamp'
            ]),
            maybeUpdateRecruitmentCamp() {
                let recruitmentCampSlug = this.$route.params.recruitmentCampSlug;
                if (this._recruitmentCamp.slug !== recruitmentCampSlug) {
                    this.updateRecruitmentCamp(this.$route);
                }
            },
        },
        computed: {
            ...mapGetters([
                '_recruitmentCamp',
                '_heroClassByID',
                '_recruitmentHeroRace',
                '_recruitmentHeroClass',
                '_recruitmentHeroPostType'
            ]),
            heroClasses() {
                return this._recruitmentCamp.heroClassIDs.map(heroClassID => this._heroClassByID(heroClassID));
            }
        }
    }
</script>

<style scoped>
    .empty-hero-type {
        height: 53px;
        width: 44px;
        border-radius: 4px;
        border-style: dotted;
        border-color: rgba(168, 168, 168, 0.8);
        border-width: thin;
        margin: 2px;
    }
    .hero-type-icon {
        height: 60px;
        width: 48px;
    }
</style>
