<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-box-open"></i>
                Edit Bundle
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <BundleDataChange
                    :data="bundleData"
                    :titles="titles"
                    :codes="codes"
                    :darkmode="darkmode"
                    @reset-form="resetForm"
                ></BundleDataChange>

                <hr class="hr">

                <div>
                    <div class="d-flex justify-content-between align-items-center mx-xl-4 mb-3">
                        <div class="h3 font-weight-bold m-xl-0">
                            Gallery
                        </div>

                        <div>
                            <a role="button" :href="`/admin/bundles/${bundleData.id}/add-image`" class="big-button-primary">
                                Add Image
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive-xl" v-if="gallery.length">
                        <table class="responsive-table-bordered table-themed">
                            <thead>
                                <tr>
                                    <th scope="col" class="h3 font-weight-bold">ID</th>
                                    <th scope="col" class="h3 font-weight-bold">Image</th>
                                    <th scope="col" class="h3 font-weight-bold">Delete</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(item, i) in gallery" :key="i">
                                    <th scope="row" class="h5 my-auto font-weight-bold" style="min-width: 100px;">{{ item.id }}</th>

                                    <td>
                                        <div class="admin-bundle-gallery-image" :style="`background-image: url(${item.image})`"></div>
                                    </td>

                                    <td class="trashbin" @click="deleteImage(i)">
                                        <i class="fas fa-trash"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <EmptyPlaceholder v-else></EmptyPlaceholder>

                    <hr class="hr-dashed">

                    <SaveResetChanges
                        :disableAll="imageSubmit"
                        :spinner="imageSubmit"
                        @save="saveImages"
                        @reset="resetImages"
                    ></SaveResetChanges>
                </div>

                <hr class="hr">

                <div class="row">
                    <div class="col-12 col-sm-6 offset-sm-3">
                        <a :href="`/admin/bundles/${bundleData.id}/delete`" role="button" class="big-button-danger">
                            <i class="fas fa-trash"></i>
                            Delete this bundle
                        </a>
                    </div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import SaveResetChanges from "../../../components/SaveResetChanges.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";
import Loading from "../../../components/Loading.vue";

import BundleDataChange from "./BundleDataChangeComponent.vue";

export default {
    props: ["id"],
    components: {
        SaveResetChanges,
        BundleDataChange,
        EmptyPlaceholder,
        Loading
    },
    data() {
        return {
            darkmode: false,
            ready: false,

            bundleData: {
                title: "",
                code: "",
                price: "",
                short_description: "",
                description: ""
            },
            bundleDataCopy: {},
            titles: [],
            codes: [],

            gallery: [],
            galleryCopy: [],
            imageSubmit: false
        };
    },
    methods: {
        resetForm() {
            this.bundleData = _.cloneDeep(this.bundleDataCopy);
        },
        saveImages() {
            this.imageSubmit = true;

            axios
                .patch(`/webapi/admin/bundles/${this.bundleData.id}/update-gallery`, {
                    gallery: this.gallery.map(item => item.id)
                })
                .then(response => {
                    this.gallery = response.data.gallery;
                    this.gallery = _.cloneDeep(response.data.gallery);
                    this.imageSubmit = false;
                })
                .catch(() => {
                    this.imageSubmit = false;
                })
        },
        resetImages() {
            this.gallery = _.cloneDeep(this.galleryCopy);
        },
        deleteImage(index) {
            this.gallery.splice(index, 1);
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get(`/webapi/admin/bundles/${this.id}`, {})
            .then(response => {
                this.bundleData = response.data.bundle;
                this.bundleDataCopy = _.cloneDeep(response.data.bundle);
                this.titles = response.data.titles;
                this.codes = response.data.codes;

                this.gallery = response.data.gallery;
                this.galleryCopy = _.cloneDeep(response.data.gallery);

                this.ready = true;
            });
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
