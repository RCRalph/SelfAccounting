<template>
    <v-card-actions class="d-flex justify-space-between">
        <div :class="display.mobile.value && 'd-flex flex-wrap flex-column-reverse'">
            <v-btn
                :disabled="disableDelete"
                class="ma-1"
                color="error"
                variant="outlined"
                icon="mdi-delete"
                size="40"
                @click="emit('remove')"
            ></v-btn>

            <v-btn
                :disabled="page <= 0"
                class="ma-1"
                variant="outlined"
                icon="mdi-arrow-collapse-left"
                size="40"
                @click="emit('update:page', 0)"
            ></v-btn>

            <v-btn
                :disabled="page <= 0"
                class="ma-1"
                variant="outlined"
                icon="mdi-arrow-left"
                size="40"
                @click="emit('update:page', page - 1)"
            ></v-btn>
        </div>

        <div :class="display.mobile.value && 'd-flex flex-wrap flex-column'">
            <slot></slot>
        </div>

        <div :class="display.mobile.value && 'd-flex flex-wrap flex-column'">
            <v-btn
                :disabled="page >= dataLength - 1"
                class="ma-1"
                variant="outlined"
                icon="mdi-arrow-right"
                size="40"
                @click="emit('update:page', page + 1)"
            ></v-btn>

            <v-btn
                :disabled="page >= dataLength - 1"
                class="ma-1"
                variant="outlined"
                icon="mdi-arrow-collapse-right"
                size="40"
                @click="emit('update:page', dataLength - 1)"
            ></v-btn>

            <v-btn
                class="ma-1"
                color="primary"
                variant="outlined"
                icon="mdi-plus"
                size="40"
                @click="emit('add')"
            ></v-btn>
        </div>
    </v-card-actions>

    <div
        v-if="props.dataLength > 1"
        class="text-center text-caption pb-2"
    >
        {{ props.page + 1 }} / {{ props.dataLength }}
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { useDisplay } from "vuetify"

const display = useDisplay()

const props = defineProps<{
    page: number,
    dataLength: number,
    canBeEmpty?: boolean
}>()

const emit = defineEmits<{
    "update:page": [payload: number],
    "add": [],
    "remove": [],
}>()

const disableDelete = computed(() => props.dataLength <= 1 && props.canBeEmpty && props.dataLength == 0)
</script>