<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-box-open"></i>
                Create Bundle
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <form id="bundle-form" action="/admin/bundles/create" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" :value="CSRF_TOKEN">

                    <div class="form-group row">
                        <label class="col-lg-3 offset-lg-1 col-form-label text-lg-right">Title</label>
                        <div class="col-lg-7">
                            <input type="text" placeholder="Enter title here..." @input="changed.title = true" maxlength="64" v-model="bundleData.title" name="title" :class="[
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

                            <input type="number" step="0.01" placeholder="Enter price here..." @input="changed.price = true" v-model="bundleData.price" name="price" :class="[
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

                    <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'" >

                    <div>
                        <div class="h3 text-center">Short Description</div>
                        <div class="col-lg-8 offset-lg-2 my-3">
                            <textarea v-model="bundleData.short_description" name="short_description" @input="changed.short_description = true" placeholder="Shortly describe this bundle..." :class="[
                                'form-control',
                                'mb-2',
                                !validShortDescription && 'is-invalid'
                            ]"></textarea>
                            <div v-html="markdownToHTML(bundleData.short_description)"></div>
                        </div>
                    </div>

                    <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'" >

                    <div>
                        <div class="h3 text-center">Description</div>
                        <div class="col-lg-8 offset-lg-2 my-3">
                            <textarea v-model="bundleData.description" name="description" @input="changed.description = true" placeholder="Describe this bundle..." :class="[
                                'form-control',
                                'mb-2',
                                !validDescription && 'is-invalid'
                            ]"></textarea>
                            <div v-html="markdownToHTML(bundleData.description)"></div>
                        </div>
                    </div>

                    <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'" >

                    <div class="row">
                        <div class="col-md-4 offset-md-4">
                            <button class="big-button-primary" @click="submit" :disabled="submitted || !canSubmit">
                                <div v-if="!submitted">
                                    Submit
                                </div>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-else></span>
                            </button>
                        </div>
                    </div>
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
import marked from 'marked';
import DOMPurify from 'dompurify';

export default {
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
            titles: [],
            changed: {
                title: false,
                price: false,
                thumbnail: false,
                short_description: false,
                description: false
            },
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
            return !this.changed.title || !!title && title.length <= 64 && !this.titles.includes(title.toLowerCase());
        },
        validPrice() {
            const price = Number(this.bundleData.price);
            return !this.changed.price || this.bundleData.price !== "" && !isNaN(price) && price >= 0 && price < 1000;
        },
        validShortDescription() {
            const text = this.bundleData.short_description;
            return !this.changed.short_description || text.length > 0;
        },
        validDescription() {
            const text = this.bundleData.description;
            return !this.changed.description || text.length > 0;
        },
        canSubmit() {
            return this.validTitle && this.changed.title
                && this.validPrice && this.changed.price
                && this.validThumbnail && this.changed.thumbnail
                && this.validShortDescription && this.changed.short_description
                && this.validDescription && this.changed.description;
        }
    },
    methods: {
        checkThumbnail() {
            this.changed.thumbnail = true;
            this.validThumbnail = document.getElementById("thumbnail").files[0].type.includes("image");
        },
        markdownToHTML(markdown) {
            return DOMPurify.sanitize(marked(markdown));
        },
        submit() {
            this.submitted = true;
            document.getElementById("bundle-form").submit();
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get("/webapi/admin/bundles/create", {})
            .then(response => {
                this.titles = response.data.titles;
                this.ready = true;
            });
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
