<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-box-open"></i>
                Edit Bundle
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <form id="bundle-form" :action="`/admin/bundles/${bundleData.id}`" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" :value="CSRF_TOKEN">
                    <input type="hidden" name="_method" value="PATCH">

                    <div class="form-group row">
                        <label class="col-lg-3 offset-lg-1 col-form-label text-lg-right">Title</label>
                        <div class="col-lg-7">
                            <input type="text" placeholder="Enter title here..." maxlength="64" v-model="bundleData.title" name="title" :class="[
                                'form-control',
                                !validTitle && 'is-invalid'
                            ]">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 offset-lg-1 col-form-label text-lg-right">Price</label>
                        <div class="col-lg-7 input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">€</div>
                            </div>

                            <input type="number" step="0.01" placeholder="Enter price here..." v-model="bundleData.price" name="price" :class="[
                                'form-control',
                                !validPrice && 'is-invalid'
                            ]">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 offset-lg-1 col-form-label text-lg-right">Thumbnail</label>
                        <div class="col-lg-7">
                            <input @change="checkThumbnail" id="thumbnail" type="file" name="thumbnail" :class="[
                                'form-control',
                                'form-control-file',
                                !validThumbnail && 'is-invalid'
                            ]">
                        </div>
                    </div>

                    <hr :class="darkmode ? 'hr-darkmode' : 'hr-lightmode'" style="background-color: transparent; border-top-style: dashed; border-width: 1px;">

                    <div>
                        <div class="h3 text-center">Short Description</div>
                        <div class="col-lg-8 offset-lg-2 my-3">
                            <textarea v-model="bundleData.short_description" name="short_description" placeholder="Shortly describe this bundle..." :class="[
                                'form-control',
                                'mb-2',
                                !validShortDescription && 'is-invalid'
                            ]"></textarea>
                            <div v-html="markdownToHTML(bundleData.short_description)"></div>
                        </div>
                    </div>

                    <hr :class="darkmode ? 'hr-darkmode' : 'hr-lightmode'" style="background-color: transparent; border-top-style: dashed; border-width: 1px;">

                    <div>
                        <div class="h3 text-center">Description</div>
                        <div class="col-lg-8 offset-lg-2 my-3">
                            <textarea v-model="bundleData.description" name="description" placeholder="Describe this bundle..." :class="[
                                'form-control',
                                'mb-2',
                                !validDescription && 'is-invalid'
                            ]"></textarea>
                            <div v-html="markdownToHTML(bundleData.description)"></div>
                        </div>
                    </div>

                    <hr :class="darkmode ? 'hr-darkmode' : 'hr-lightmode'" style="background-color: transparent; border-top-style: dashed; border-width: 1px;">

                    <SaveResetChanges
                        :disableAll="submitted"
                        :spinner="submitted"
                        :disableSave="!canSubmit"
                        @save="submitForm"
                        @reset="resetForm"
                    ></SaveResetChanges>
                </form>
            </div>

            <div class="d-flex justify-content-center my-2" v-else>
                <div
                    class="spinner-grow"
                    role="status"
                    style="width: 3rem; height: 3rem;"
                >
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import marked from "marked";
import DOMPurify from "dompurify";

import SaveResetChanges from "../../../components/SaveResetChanges.vue";

export default {
    props: ["id"],
    components: {
        SaveResetChanges
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            bundleData: {
                title: "",
                price: "",
                short_description: "",
                description: ""
            },
            bundleDataCopy: {},
            titles: [],
            validThumbnail: true,
            submitted: false
        };
    },
    computed: {
        CSRF_TOKEN() {
			return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
        },
        validTitle() {
            const title = this.bundleData.title;
            return !!title && title.length <= 64 && !this.titles.includes(title.toLowerCase());
        },
        validPrice() {
            const price = Number(this.bundleData.price);
            return this.bundleData.price !== "" && !isNaN(price) && price >= 0 && price < 1000;
        },
        validShortDescription() {
            const text = this.bundleData.short_description;
            return text.length > 0;
        },
        validDescription() {
            const text = this.bundleData.description;
            return text.length > 0;
        },
        canSubmit() {
            return this.validTitle && this.validPrice && this.validThumbnail && this.validShortDescription && this.validDescription
        }
    },
    methods: {
        checkThumbnail() {
            this.validThumbnail = document.getElementById("thumbnail").files[0].type.includes("image");
        },
        markdownToHTML(markdown) {
            return DOMPurify.sanitize(marked(markdown));
        },
        submitForm() {
            this.submitted = true;
            document.getElementById("bundle-form").submit();
        },
        resetForm() {
            this.bundleData = _.cloneDeep(this.bundleDataCopy);
            this.validThumbnail = true;
            document.getElementById("thumbnail").value = "";
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
                this.ready = true;
            });
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
