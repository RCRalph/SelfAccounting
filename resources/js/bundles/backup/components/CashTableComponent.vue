<template>
    <div class="col-lg-6 offset-lg-3">
        <div class="restore-table">
            <div class="header">
                Cash:
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
</template>

<script>
export default {
    props: ["data", "currencies"],
    data() {
        return {
            header: ["Currency", "Face Value", "Amount"]
        }
    }
}
</script>
