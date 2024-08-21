<template>
    <div class="page-pagination-fixed">
        <v-card
            class="pa-1 flex-grow-1"
            elevation="6"
        >
            <v-pagination
                v-model="model"
                :length="props.length"
                variant="elevated"
                class="flex-grow-1"
            ></v-pagination>
        </v-card>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    length: number
}>()

const model = defineModel<number>({required: true})
</script>

<style scoped lang="scss">
$page-pagination-width: 40vw;

.page-pagination-fixed {
    position: fixed;
    bottom: 12px;
    width: calc($page-pagination-width - 56px - 16px - 16px);
    margin-left: calc((100vw - $page-pagination-width) / 2 - 56px);
    height: 65px;
    display: flex;
    justify-content: center;
    align-items: center;
}

@media (hover: none) {
    .page-pagination-fixed {
        width: calc(50vw - 56px - 16px - 16px);
        margin-left: calc((100vw - $page-pagination-width) / 2);
    }
}
</style>