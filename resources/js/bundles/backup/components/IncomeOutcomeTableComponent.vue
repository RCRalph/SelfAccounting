<template>
    <div class="restore-table">
        <div class="header">
            {{ title }}:
        </div>

        <div class="restore-table-content">
            <table>
                <thead>
                    <tr>
                        <th
                            v-for="(item, i) in header"
                            :key="i"
                            scope="col"
                        >{{ item }}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, i) in data" :key="i">
                        <td class="font-weight-bold">
                            {{ item.date }}
                        </td>
                        <td>
                            {{ item.title }}
                        </td>
                        <td>
                            {{ item.amount }}
                        </td>
                        <td>
                            {{ item.price }} {{ currencies.find(item1 => item1.id == item.currency_id).ISO }}
                        </td>
                        <td>
                            {{ !item.category_id ? "N/A" : categories[item.category_id - 1].name }}
                        </td>
                        <td>
                            {{ !item.mean_id ? "N/A" : means[item.mean_id - 1].name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: ["data", "currencies", "title", "categories", "means"],
    data() {
        return {
            header: ["Date", "Title", "Amount", "Price", "Category", "Mean"]
        }
    }
}
</script>
