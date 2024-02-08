<template>
    <v-tooltip location="bottom">
        <template v-slot:activator="{ props: iconProps }">
            <v-icon
                v-bind="iconProps"
                class="ml-1"
                icon="mdi-calculator"
            ></v-icon>
        </template>

        Supported operations: <strong>+ - * / ^</strong>
    </v-tooltip>
</template>