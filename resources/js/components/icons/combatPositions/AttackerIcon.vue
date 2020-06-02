<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,320,320" style="display: block">
        <path d="M210,30 A1,1 0 0,0 210,290" :fill="highGroundFill" :stroke="highGroundStroke"/>
        <path d="M210,60 A1,1 0 0,0 210,260" :fill="backLineFill" :stroke="backLineStroke"/>
        <path d="M210,100 A1,1 0 0,0 210,220" :fill="frontLineFill"  :stroke="frontLineStroke"/>
    </svg>
</template>

<script>
    export default {
        name: "AttackerIcon",
        props: {
            combatPositionName: {
                type: String,
                required: true
            }
        },
        computed: {
            highGroundFill() {
                return (this.combatPositionName === 'High Ground') ? '#00ffd1' : '#808080';
            },
            backLineFill() {
                return (this.combatPositionName === 'Back Line') ? '#00ffd1' : '#808080';
            },
            frontLineFill() {
                return (this.combatPositionName === 'Front Line') ? '#00ffd1' : '#808080';
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
