<template>
    <div>
        <div class="col-lg-6 offset-lg-3" v-if="cash.length">
            <div class="restore-table">
                <div class="header">
                    Cash:
                </div>

                <div class="restore-table-content">
                    <table>
                        <thead>
                            <tr>
                                <th
                                    v-for="(item, i) in headerCash"
                                    :key="i"
                                    scope="col"
                                >{{ item }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in cash" :key="i">
                                <td class="font-weight-bold">
                                    {{ currencies.find(item1 => item1.id == item.currency_id).ISO }}
                                </td>

                                <td>
                                    {{ item.value }}
                                </td>

                                <td>
                                    {{ item.amount }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr class="hr-dashed">

        <div class="col-lg-6 offset-lg-3">
            <div class="restore-table">
                <div class="header">
                    Cash means of payment:
                </div>

                <div class="restore-table-content">
                    <table>
                        <thead>
                            <tr>
                                <th
                                    v-for="(item, i) in headerCashMeans"
                                    :key="i"
                                    scope="col"
                                >{{ item }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in cashMeans" :key="i">
                                <td class="font-weight-bold">
                                    {{ currencies.find(item1 => item1.id == item.currency_id).ISO }}
                                </td>

                                <td>
                                    {{ means[item.mean_id - 1].name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["cash", "cashMeans", "currencies", "means"],
    data() {
        return {
            headerCash: ["Currency", "Face Value", "Amount"],
            headerCashMeans: ["Currency", "Name"]
        }
    }
}
</script>
