<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,320,320" style="display: block">
        <path d="M120,290 A1,1 0 0,0 120,30" :fill="highGroundFill" :stroke="highGroundStroke"/>
        <path d="M120,260 A1,1 0 0,0 120,60" :fill="backLineFill" :stroke="backLineStroke"/>
        <path d="M120,220 A1,1 0 0,0 120,100" :fill="frontLineFill" :stroke="frontLineStroke"/>
    </svg>
</template>

<script>
    export default {
        name: "DefenderIcon",
        props: {
            combatPositionName: {
                type: String,
                required: true
            }
        },
        computed: {
            highGroundFill() {
                return (this.combatPositionName === 'High Ground') ? '#fc4c1c' : '#808080';
            },
            backLineFill() {
                return (this.combatPositionName === 'Back Line') ? '#fc4c1c' : '#808080';
            },
            frontLineFill() {
                return (this.combatPositionName === 'Front Line') ? '#fc4c1c' : '#808080';
            },
            highGroundStroke() {
                if (this.combatPositionName === 'High Ground') {
                    return '#fff';
                }
                return '';
            },
            backLineStroke() {
                // We adjust front-line stroke for back-line border
                if (this.combatPositionName === 'Front Line') {
                    return '#383838';
                }
                return '#fff';
            },
            frontLineStroke() {
                if (this.combatPositionName === 'High Ground') {
                    return '#383838';
                }
                return '#fff';
            }
        }
    }
</script>

<style scoped>

</style>
