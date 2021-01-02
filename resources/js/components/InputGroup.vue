<template>
    <div class="form-group row">
        <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center">
            <div class="h5 font-weight-bold m-md-0">{{ (name != 'password_confirmation' ? name : 'confirm') | capitalize }}</div>
        </div>

		<!-- Select field -->
        <div v-if="type == 'select'" class="col-md-7">
            <select class="form-control" v-model="value" :disabled="disabled">
                <option
                    v-for="(item, i) in selectOptions"
                    :key="i"
                    :value="item[optionValueKey]"
                    :selected="value == item[optionValueKey] || selectOptions.length == 1"
                >
                    {{ item[optionTextKey] }}
                </option>
            </select>
        </div>

        <!-- Normal input field -->
        <div v-else :class="[
            'col-md-7',
            (prepend || append) && 'input-group'
        ]">
            <div v-if="prepend" class="input-group-prepend">
                <div class="input-group-text">{{ prepend }}</div>
            </div>

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

            <div v-if="append" class="input-group-append">
                <div class="input-group-text">{{ append }}</div>
            </div>

            <span v-if="invalid" class="invalid-feedback" role="alert">
                <strong>{{ name | capitalize }} is invalid</strong>
            </span>
        </div>
    </div>
</template>

<script>
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
        disabled: {
            required: false,
            type: Boolean,
            default: false
        }
    },
    filters: {
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
