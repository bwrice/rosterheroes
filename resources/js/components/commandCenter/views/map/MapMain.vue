<template>
    <v-flex class="xs12">
        <v-card>
            <svg xmlns="http://www.w3.org/2000/svg"
                 version="1.1"
                 viewBox="0 0 315 240">

                <rect width="315" height="240" rx="5" ry="5"
                      style="fill: #c9e5ea; stroke: #E3EFEB; stroke-width: 2"
                ></rect>

<!--                <rh-province v-for="province in provinces" :key="province.id"-->
<!--                             v-bind:province="province"-->
<!--                ></rh-province>-->

<!--                <g v-if="_hasHoveredProvince" transform="translate(220,198)" width="60" height="18">-->
<!--                    <rect rx="3" ry="3" width="60" height="18"-->
<!--                          v-bind:style="'fill:' + _hoveredProvince.color + ';fill-opacity:.3;stroke:'+_hoveredProvince.color+';stroke-width:3;'">-->
<!--                    </rect>-->
<!--                    <text x="4" y="12"-->
<!--                          style="font-size: 8px;-->
<!--                    stroke: #000000;-->
<!--                    fill: #000000;">-->
<!--                        {{_hoveredProvince.name}}-->
<!--                    </text>-->
<!--                </g>-->
            </svg>
        </v-card>
    </v-flex>
</template>

<script>
    export default {
        name: "MapMain",
        created: () => {
            console.log("Map Created!");
        }
    }
</script>

<style scoped>

</style>