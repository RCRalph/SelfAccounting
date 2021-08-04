<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-money-check"></i>
                Payment
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="row">
                    <div class="col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center">
                        <div class="h4 font-weight-bold m-lg-0">Package</div>
                    </div>

                    <div class="col-lg-5">
                        <select class="form-control" v-model="selectedBundle">
                            <option value="p-1">Premium - 1 month</option>
                            <option value="p-12">Premium - 12 months</option>
                            <option v-for="item in bundles" :key="item.id" :value="item.id">
                                {{ item.title }}
                            </option>
                        </select>
                    </div>
                </div>

                <hr class="hr">

                <div class="row mb-3">
                    <div class="col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center">
                        <div class="h4 font-weight-bold m-lg-0">Payment amount</div>
                    </div>

                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text">
                                €
                            </span>

                            <input disabled type="text" class="form-control" :value="Number(price).toFixed(2)">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center">
                        <div class="h4 font-weight-bold m-lg-0">Payment note</div>
                    </div>

                    <div class="col-lg-5">
                        <input disabled type="text" class="form-control" :value="`${user}:${selectedBundle}`">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <a href="https://www.paypal.me/RCRalph" role="button" class="big-button-primary" target="_blank">Finalize payment</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 h5 font-weight-bold text-center my-2 text-danger">
                        Remember to enter the note given above!
                    </div>

                    <div class="col-12 h5 text-center my-2">
                        The transaction should be finished within 24 hours after receiving the payment.
                    </div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../Loading.vue";

export default {
    props: ["user", "id"],
    components: {
        Loading
    },
    data() {
        return {
            ready: false,
            bundles: [],
            selectedBundle: "p-1"
        }
    },
    computed: {
        price() {
            if (typeof this.selectedBundle == "string") {
                return this.selectedBundle == "p-12" ? 15 : 1.5;
            }

            return this.bundles.filter(item => item.id == this.selectedBundle)[0].price;
        }
    },
    mounted() {
        this.selectedBundle = Number(this.id) || "p-1";
        axios.get("/webapi/payment", {})
            .then(response => {
                this.bundles = response.data.bundles;
                this.ready = true;
            })
    }
}
</script>
