<template>
    <div class="row">
        <div class="col-sm-6 my-2 my-sm-0">
            <button
                type="button"
                class="big-button-success"
                @click="$emit('save')"
                :disabled="disableAll || disableSave || spinner"
            >
                <div v-if="!spinner">
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

        <div class="col-sm-6 my-2 my-sm-0">
            <button
                type="button"
                class="big-button-danger"
                @click="$emit('reset')"
                :disabled="disableAll || disableReset || spinner"
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
        spinner: {
            type: Boolean,
            required: false,
            default: false
        }
    }
}
</script>
