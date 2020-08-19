<template>
    <thead>
        <tr>
            <th v-for="(cell, index) in cells"
                :key="index"
                :scope="cell.text && 'col'"
                :class="cell.text && 'h5 font-weight-bold'"
                :data-toggle="cell.tooltip && 'tooltip'"
                :data-placement="cell.tooltip && 'bottom'"
                :title="cell.tooltip && cell.tooltip"
            >
                {{ cell.text }}
            </th>
        </tr>
    </thead>
</template>

<script>
    export default {
        props: {
            cells: Array
        }
    }
</script>
