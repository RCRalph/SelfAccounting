<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
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
                            <option value="premium-1">Premium - 1 month</option>
                            <option value="premium-12">Premium - 12 months</option>
                            <option v-for="item in bundles" :key="item.id" :value="item.id">
                                {{ item.title }}
                            </option>
                        </select>
                    </div>
                </div>

                <hr :class="darkmode ? 'hr-darkmode' : 'hr-lightmode'">

                <div class="form-group row">
                    <div class="col-lg-5 d-flex justify-content-lg-end justify-content-start align-items-center">
                        <div class="h4 font-weight-bold m-lg-0">Payment amount</div>
                    </div>

                    <div class="col-lg-5 input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">€</div>
                        </div>

                        <input disabled type="text" class="form-control" :value="price">
                    </div>
                </div>

                <div class="form-group row">
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
            darkmode: false,
            ready: false,
            bundles: [],
            selectedBundle: "premium-1"
        }
    },
    computed: {
        price() {
            if (typeof this.selectedBundle == 'string') {
                if (this.selectedBundle == "premium-1") {
                    return 15;
                }

                return 1.5;
            }

            return this.bundles.filter(item => item.id == this.selectedBundle)[0].price;
        },
        title() {

        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        this.selectedBundle = Number(this.id) || "premium-1";
        axios.get("/webapi/payment", {})
            .then(response => {
                this.bundles = response.data.bundles;
                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
