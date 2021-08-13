<template>
    <div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="restore-table">
                    <div class="header">
                        Reports:
                    </div>

                    <div class="restore-table-content">
                        <table>
                            <thead>
                                <tr>
                                    <th
                                        v-for="(item, i) in header.reports"
                                        :key="i"
                                        scope="col"
                                    >{{ item }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(item, i) in data" :key="i">
                                    <td>
                                        {{ item.title }}
                                    </td>
                                    <td>
                                        <i :class="[
                                            'fas',
                                            'h3',
                                            'my-auto',
                                            item.income_addition ? 'text-success' : 'text-danger',
                                            item.income_addition ? 'fa-check-square' : 'fa-times-circle'
                                        ]"></i>
                                    </td>
                                    <td>
                                        <i :class="[
                                            'fas',
                                            'h3',
                                            'my-auto',
                                            item.sort_dates_desc ? 'text-success' : 'text-danger',
                                            item.sort_dates_desc ? 'fa-check-square' : 'fa-times-circle'
                                        ]"></i>
                                    </td>
                                    <td>
                                        <i :class="[
                                            'fas',
                                            'h3',
                                            'my-auto',
                                            item.calculate_sum ? 'text-success' : 'text-danger',
                                            item.calculate_sum ? 'fa-check-square' : 'fa-times-circle'
                                        ]"></i>
                                    </td>
                                    <td>
                                        {{ item.show_columns || 127 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="queries.length">
            <hr class="hr-dashed">

            <div class="restore-table">
                <div class="header">
                    Report queries:
                </div>

                <div class="restore-table-content">
                    <table>
                        <thead>
                            <tr>
                                <th
                                    v-for="(item, i) in header.queries"
                                    :key="i"
                                    scope="col"
                                >{{ item }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in queries" :key="i">
                                <td>
                                    {{ item.report }}
                                </td>

                                <td>
                                    {{ item.query_data | capitalize }}
                                </td>

                                <td>
                                    {{ item.min_date || "N/A" }}
                                </td>

                                <td>
                                    {{ item.max_date || "N/A" }}
                                </td>

                                <td>
                                    {{ item.title || "N/A" }}
                                </td>

                                <td>
                                    {{ item.min_amount === null ? "N/A" : item.min_amount }}
                                </td>

                                <td>
                                    {{ item.max_amount === null ? "N/A" : item.max_amount }}
                                </td>

                                <td>
                                    {{ item.min_price === null ? "N/A" : item.min_price }}
                                </td>

                                <td>
                                    {{ item.max_price === null ? "N/A" : item.max_price }}
                                </td>

                                <td>
                                    {{ item.currency_id === null ? "N/A" : currencies[item.currency_id - 1].ISO }}
                                </td>

                                <td>
                                    {{ item.category_id === 0  ? "N/A" : categories[item.category_id - 1].name }}
                                </td>

                                <td>
                                    {{ item.mean_id === 0  ? "N/A" : means[item.mean_id - 1].name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="additionalEntries.length">
            <hr class="hr-dashed">

            <div class="restore-table">
                <div class="header">
                    Report additional entries:
                </div>

                <div class="restore-table-content">
                    <table>
                        <thead>
                            <tr>
                                <th
                                    v-for="(item, i) in header.additionalEntries"
                                    :key="i"
                                    scope="col"
                                >{{ item }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in additionalEntries" :key="i">
                                <td>
                                    {{ item.report }}
                                </td>

                                <td>
                                    {{ item.date }}
                                </td>

                                <td>
                                    {{ item.title }}
                                </td>

                                <td>
                                    {{ item.amount }}
                                </td>

                                <td>
                                    {{ item.price }} {{ currencies[item.currency_id - 1].ISO }}
                                </td>

                                <td>
                                    {{ item.category_id === 0  ? "N/A" : categories[item.category_id - 1].name }}
                                </td>

                                <td>
                                    {{ item.mean_id === 0  ? "N/A" : means[item.mean_id - 1].name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="users.length">
            <hr class="hr-dashed">

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="restore-table">
                        <div class="header">
                            Report shared users:
                        </div>

                        <div class="restore-table-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th
                                            v-for="(item, i) in header.users"
                                            :key="i"
                                            scope="col"
                                        >{{ item }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(item, i) in users" :key="i">
                                        <td>
                                            {{ item.report }}
                                        </td>

                                        <td>
                                            {{ item.user }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["data", "currencies", "categories", "means"],
    data() {
        return {
            header: {
                reports: ["Title", "Income addition", "Sort dates desc", "Calculate sum", "Show columns"],
                queries: ["Report", "Type", "Min date", "Max date", "Title", "Min amount", "Max amount", "Min Price", "Max Price", "Currency", "Category", "Mean"],
                additionalEntries: ["Report", "Date", "Title", "Amount", "Price", "Category", "Mean"],
                users: ["Report", "Email"]
            }
        }
    },
    computed: {
        queries() {
            let retArr = [];
            this.data.forEach(item => {
                item.queries.forEach(item1 => {
                    retArr.push({ ...item1, report: item.title })
                });
            });

            return retArr;
        },
        additionalEntries() {
            let retArr = [];
            this.data.forEach(item => {
                item.additionalEntries.forEach(item1 => {
                    retArr.push({ ...item1, report: item.title })
                });
            });

            return retArr;
        },
        users() {
            let retArr = [];
            this.data.forEach(item => {
                item.users.forEach(item1 => {
                    retArr.push({ user: item1, report: item.title })
                });
            });

            return retArr;
        }
    },
    filters: {
        capitalize(text) {
            return text.charAt(0).toUpperCase() + text.slice(1)
        }
    }
}
</script>
