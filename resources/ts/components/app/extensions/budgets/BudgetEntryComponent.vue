<template>
    <v-row>
        <v-col cols="12" class="text-h5 text-center">
            <v-icon :icon="format.iconName(props.category.icon)"></v-icon>

            {{ props.category.name }}
        </v-col>

        <v-col cols="7">
            <ValueFieldComponent
                title="Current value"
                iso="PLN"
                :value="props.currentValue"
            ></ValueFieldComponent>
        </v-col>

        <v-col cols="5">
            <v-text-field
                v-model="props.entry.value"
                suffix="PLN"
                variant="underlined"
                label="Target value"
            ></v-text-field>
        </v-col>
    </v-row>

    <v-tooltip
        :text="progressBarTooltip"
        location="bottom"
    >
        <template v-slot:activator="{ props }">
            <v-progress-linear
                v-bind="props"
                :model-value="progressBarValue"
                :color="progressBarColor"
                height="20"
                rounded
            >
                {{ progressBarValue == Infinity ? "∞" : progressBarValue }}%
            </v-progress-linear>
        </template>
    </v-tooltip>
</template>

<script setup lang="ts">
import type { Category } from "@interfaces/Category"
import type { BudgetEntry } from "@interfaces/Budget"

import useFormats from "@composables/useFormats"
import ValueFieldComponent from "@components/global/ValueFieldComponent.vue"
import { computed } from "vue"

const props = defineProps<{
    category: Category,
    entry: BudgetEntry,
    currentValue: number,
}>()

const format = useFormats()

function useProgressBar() {
    const progressBarValue = computed(() => {
        if (!Number(props.entry.value)) {
            return Number(props.currentValue) ? Infinity : 100
        }

        return Math.round(props.currentValue / props.entry.value * 100)
    })

    const progressBarColor = computed(() => {
        if (!Number(props.entry.value)) {
            return "primary"
        }

        switch (props.entry.transaction_type) {
            case "income":
                return props.currentValue / props.entry.value < 1 ? "error" : "success"
            case "expenses":
                return props.currentValue / props.entry.value <= 1 ? "success" : "error"
        }
    })

    const progressBarTooltip = computed(() => {
        if (!Number(props.entry.value)) {
            return "Undefined progress (target value is 0)"
        }

        switch (props.entry.transaction_type) {
            case "income":
                return props.currentValue / props.entry.value < 1 ?
                    "Income falls behind target value" :
                    "Income within expected value"
            case "expenses":
                return props.currentValue / props.entry.value <= 1 ?
                    "Expenses within expected value" :
                    "Expenses exceed target value"
        }
    })

    return {progressBarValue, progressBarColor, progressBarTooltip}
}

const {progressBarValue, progressBarColor, progressBarTooltip} = useProgressBar()
</script>