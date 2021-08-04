<template>
    <div :class="!(prepend || append) && 'input-group-row'">
        <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center" v-if="!(prepend || append)">
            <div class="h5 font-weight-bold m-md-0">
                {{ capitalize((name != 'password_confirmation' ? name : 'confirm')) }}
            </div>
        </div>

		<!-- Select field -->
        <div v-if="type == 'select'" class="col-md-7">
            <select
                v-if="optionKeyAndValue"
                :class="[
                    'form-control',
                    invalid && 'is-invalid'
                ]"
                v-model="value"
                :disabled="disabled"
            >
                <option v-if="nullValue" :value="null">{{ nullValue }}</option>

                <option
                    v-for="(item, i) in selectOptions"
                    :key="i"
                    :value="item"
                    :selected="value == item || selectOptions.length == 1"
                >
                    {{ capitalizeOptions ? capitalize(item) : item }}
                </option>
            </select>

            <select
                v-else
                :class="[
                    'form-control',
                    invalid && 'is-invalid'
                ]"
                v-model="value"
                :disabled="disabled"
            >
                <option v-if="nullValue" :value="null">{{ nullValue }}</option>

                <option
                    v-for="(item, i) in selectOptions"
                    :key="i"
                    :value="item[optionValueKey]"
                    :selected="value == item[optionValueKey] || selectOptions.length == 1"
                >
                    {{ item[optionTextKey] }}
                </option>
            </select>

            <span v-if="invalid" class="invalid-feedback" role="alert">
                <strong>{{ capitalize(name) }} is invalid</strong>
            </span>
        </div>

        <!-- Input field with prepend or append -->
        <div v-else-if="prepend || append" class="input-group-row">
            <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center">
                <div class="h5 font-weight-bold m-md-0">
                    {{ capitalize((name != 'password_confirmation' ? name : 'confirm')) }}
                </div>
            </div>

            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text" v-if="prepend">
                        {{ prepend }}
                    </span>

                    <input
                        :type="type"
                        :name="name"
                        v-model="value"
                        :class="[
                            'form-control',
                            invalid && 'is-invalid'
                        ]"
                        :min="min"
                        :max="max"
                        :minlength="minlength"
                        :maxlength="maxlength"
                        :autocomplete="autocomplete"
                        :placeholder="placeholder"
                        :list="datalistName"
                        :step="step"
                        :disabled="disabled"
                    >

                    <span class="input-group-text" v-if="append">
                        {{ append }}
                    </span>

                    <datalist v-if="datalist" :id="datalistName">
                        <option v-for="(item, i) in datalist" :key="i">{{ item }}</option>
                    </datalist>

                    <span v-if="invalid" class="invalid-feedback" role="alert">
                        <strong>{{ capitalize(name) }} is invalid</strong>
                    </span>
                </div>
            </div>
        </div>

        <!-- Normal input field -->
        <div v-else class="col-md-7">
            <input
                :type="type"
                :name="name"
                v-model="value"
                :class="[
                    'form-control',
                    invalid && 'is-invalid'
                ]"
                :min="min"
                :max="max"
                :minlength="minlength"
                :maxlength="maxlength"
                :autocomplete="autocomplete"
                :placeholder="placeholder"
                :list="datalistName"
                :step="step"
                :disabled="disabled"
            >

            <datalist v-if="datalist" :id="datalistName">
                <option v-for="(item, i) in datalist" :key="i">{{ item }}</option>
            </datalist>

            <span v-if="invalid" class="invalid-feedback" role="alert">
                <strong>{{ capitalize(name) }} is invalid</strong>
            </span>
        </div>
    </div>
</template>

<script>
import SliderCheckbox from "./SliderCheckbox.vue";

export default {
    props: {
        name: {
            required: true,
            type: String
        },
        type: {
            required: false,
            type: String,
            default: "text"
        },
        value: {
            required: true
        },
        invalid: {
            required: false,
            type: Boolean,
            default: false
        },
        min: {
            required: false
        },
        max: {
            required: false
        },
        minlength: {
            required: false
        },
        maxlength: {
            required: false
        },
        prepend: {
            required: false,
            type: String
        },
        append: {
            required: false,
            type: String
        },
        autocomplete: {
            required: false,
            type: String,
            default: "off"
        },
        placeholder: {
            required: false,
            type: String
        },
        datalistName: {
            required: false,
            type: String
        },
        datalist: {
            required: false,
            type: Array
        },
        step: {
            required: false,
            type: String,
            default: "1"
        },
        selectOptions: {
            required: false,
            type: Array
        },
        optionValueKey: {
            required: false,
            type: String
        },
        optionTextKey: {
            required: false,
            type: String
        },
        optionKeyAndValue: {
            required: false,
            type: Boolean,
            default: false
        },
        capitalizeOptions: {
            required: false,
            type: Boolean,
            default: false
        },
        nullValue: {
            required: false,
            type: String,
            default: ""
        },
        disabled: {
            required: false,
            type: Boolean,
            default: false
        }
    },
    components: {
        SliderCheckbox
    },
    methods: {
        capitalize(text) {
            return (text.charAt(0).toUpperCase() + text.slice(1)).replace("_", " ")
        }
    },
    watch: {
        value() {
            this.$emit("input", this.value);
        }
    }
}
</script>
