<template>
    <v-chip :small="true" :color="color" text-color="#FFFFFF">{{position.abbreviation}}</v-chip>
</template>

<script>
    export default {
        name: "PositionChip",
        props: ['position'],
        computed: {
            color: function() {
                switch(this.position.sport_id) {
                    case 1:
                        return 'success';
                    case 2:
                        return 'warning';
                    case 3:
                        return 'primary';
                    case 4:
                    default:
                        return 'error';
                }
            }
        }
    }
</script>

<style>

</style>