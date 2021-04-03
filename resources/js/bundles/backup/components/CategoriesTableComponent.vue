<template>
    <div class="restore-table">
        <div class="header">
            Categories:
        </div>

        <div class="restore-table-content">
            <table>
                <thead>
                    <th
                        v-for="(item, i) in header"
                        :key="i"
                        scope="col"
                    >{{ item }}</th>
                </thead>

                <tbody>
                    <tr v-for="(item, i) in data" :key="i">
                        <td class="font-weight-bold">
                            {{ currencies.find(item1 => item1.id == item.currency_id).ISO }}
                        </td>
                        <td>
                            {{ item.name }}
                        </td>
                        <td>
                            <i :class="[
                                'fas',
                                'h4',
                                'my-auto',
                                item.income_category ? 'fa-check-square' : 'fa-times-circle',
                                item.income_category ? 'text-success' : 'text-danger'
                            ]"></i>
                        </td>
                        <td>
                            <i :class="[
                                'fas',
                                'h4',
                                'my-auto',
                                item.outcome_category ? 'fa-check-square' : 'fa-times-circle',
                                item.outcome_category ? 'text-success' : 'text-danger'
                            ]"></i>
                        </td>
                        <td>
                            <i :class="[
                                'fas',
                                'h4',
                                'my-auto',
                                item.count_to_summary ? 'fa-check-square' : 'fa-times-circle',
                                item.count_to_summary ? 'text-success' : 'text-danger'
                            ]"></i>
                        </td>
                        <td>
                            <i :class="[
                                'fas',
                                'h4',
                                'my-auto',
                                item.show_on_charts ? 'fa-check-square' : 'fa-times-circle',
                                item.show_on_charts ? 'text-success' : 'text-danger'
                            ]"></i>
                        </td>
                        <td>
                            {{ item.start_date || "N/A" }}
                        </td>
                        <td>
                            {{ item.end_date || "N/A"}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: ["data", "currencies"],
    data() {
        return {
            header: ["Currency", "Name", "Income", "Outcome", "Summary", "Charts", "Start", "End"]
        }
    }
}
</script>
