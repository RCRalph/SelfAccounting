<template>
    <button
        class="big-button-golden"
        :disabled="!premium || !ready"
        :data-toggle="!premium && 'tooltip'"
        :data-placement="!premium && 'top'"
        :title="!premium && 'Buy Premium to use this feature'"
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
    props: ["premium", "enable", "id", "directory"],
    data() {
        return {
            ready: true,
            enabled: false
        }
    },
    computed: {
        buttonText() {
            if (!this.premium) {
                return "Start using with premium";
            }

            return this.enabled ? "Stop using with Premium" : "Start using with Premium";
        }
    },
    methods: {
        changeStatus() {
            if (this.ready) {
                this.ready = false;

                axios
                    .post(`/webapi/bundles/${this.id}/toggle-premium`, {})
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
