<template>
    <Doughnut
        :data="data"
        :options="themedOptions"
    ></Doughnut>
</template>

<script lang="ts">
import _ from "lodash"
import {Chart as ChartJS, ArcElement, Tooltip, Legend} from "chart.js"
import {Doughnut} from "vue-chartjs"
import main from "&/mixins/main"

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
    components: {Doughnut},
    props: {
        options: {
            required: true,
            type: Object,
        },
        data: {
            required: true,
            type: Object,
        },
        theme: {
            required: true,
            type: Object,
        },
    },
    mixins: [main],
    computed: {
        themedOptions() {
            let result = _.cloneDeep(this.options)

            Object.keys(this.theme).forEach(item => {
                result = this.changeJsonValue(
                    result, item,
                    this.theme[item][Number(this.$vuetify.theme.dark)],
                )
            })

            return result
        },
    },
    watch: {
        "$vuetify.theme.dark"() {
            this.$forceUpdate()
        },
    },
}
</script>
