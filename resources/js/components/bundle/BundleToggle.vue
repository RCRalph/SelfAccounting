<template>
    <button
        class="big-button-primary"
        :disabled="!ready"
        @click="changeStatus"
    >
        <div v-if="ready">
            {{ buttonText }}
        </div>

        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-else></span>
    </button>
</template>

<script>
export default {
    props: ["enable", "id", "directory"],
    data() {
        return {
            ready: true,
            enabled: false
        }
    },
    computed: {
        buttonText() {
            return this.enabled ? "Disable" : "Enable";
        }
    },
    methods: {
        changeStatus() {
            if (this.ready) {
                this.ready = false;

                axios
                    .post(`/webapi/bundles/${this.id}/toggle`, {})
                    .then(() => {
                        this.enabled = !this.enabled;

                        if (this.enabled) {
                            window.location = `/bundles/${this.directory}`
                        }
                        else {
                            location.reload();
                        }
                    })
                    .catch(() => {
                        this.ready = true;
                    })
            }
        }
    },
    mounted() {
        this.enabled = !!this.enable;
    }
}
</script>
