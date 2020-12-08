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
    props: ["premium", "enable", "id"],
    data() {
        return {
            ready: true,
            enabled: false
        }
    },
    computed: {
        buttonText() {
            return (this.enabled || this.premium) ? "Stop using with Premium" : "Start using with Premium";
        }
    },
    methods: {
        changeStatus() {
            if (this.ready) {
                this.ready = false;

                axios
                    .post("/webapi/bundles/premium/toggle", {
                        id: Number(this.id)
                    })
                    .then(response => {
                        this.enabled = !this.enabled;
                        this.ready = true;
                    })
                    .catch(err => {
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
