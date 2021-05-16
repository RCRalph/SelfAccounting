<template>
    <div class="row">
        <div class="col-sm-4 my-2 my-sm-0">
            <button
                type="button"
                class="big-button-danger"
                @click="$emit('delete')"
                :disabled="disableAll || disableDelete || spinnerDelete"
            >
                <div v-if="!spinnerDelete">
                    Delete {{ deleteText }}
                </div>

                <span
                    v-else
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                ></span>
            </button>
        </div>

        <div class="col-sm-4 my-2 my-sm-0">
            <button
                type="button"
                class="big-button-success"
                @click="$emit('save')"
                :disabled="disableAll || disableSave || spinnerSave"
            >
                <div v-if="!spinnerSave">
                    Save changes
                </div>

                <span
                    v-else
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                ></span>
            </button>
        </div>

        <div class="col-sm-4 my-2 my-sm-0">
            <button
                type="button"
                class="big-button-primary"
                @click="$emit('reset')"
                :disabled="disableAll || disableReset"
            >
                Reset changes
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        disableAll: {
            type: Boolean,
            required: false,
            default: false
        },
        disableSave: {
            type: Boolean,
            required: false,
            default: false
        },
        disableReset: {
            type: Boolean,
            required: false,
            default: false
        },
        disableDelete: {
            type: Boolean,
            required: false,
            default: false
        },
        spinnerDelete: {
            type: Boolean,
            required: false,
            default: false
        },
        spinnerSave: {
            type: Boolean,
            required: false,
            default: false
        },
        deleteText: {
            type: String,
            required: false,
            default: ""
        }
    }
}
</script>
