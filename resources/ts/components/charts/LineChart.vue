<template>
    <Line
        :data="data"
        :options="themedOptions"
    ></Line>
</template>

<script lang="ts">
import _ from "lodash"
import {Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Tooltip, Legend} from "chart.js"
import {Line} from "vue-chartjs"
import main from "&/mixins/main"

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Tooltip, Legend)

export default {
    components: {Line},
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
