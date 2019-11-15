<template>
    <v-container>
        <v-row justify="center" align="center">
            <span class="display-1 rh-op-80">{{quest.name}}</span>
        </v-row>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';

    export default {
        name: "QuestView",
        computed: {
            ...mapGetters([
                '_questBySlug'
            ]),
            quest() {
                let slug = this.$route.params.questSlug;
                return this._questBySlug(slug);
            }
        }
    }
</script>

<style scoped>

</style>
