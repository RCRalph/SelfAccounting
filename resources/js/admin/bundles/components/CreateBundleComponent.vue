<template>
    <div class="card">
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

                    <InputGroup
                        type="text"
                        name="title"
                        v-model="data.title"
                        maxlength="64"
                        :invalid="!validTitle"
                        placeholder="Enter title here..."
                        @input="changed.title = true"
                    ></InputGroup>

                    <InputGroup
                        type="text"
                        name="code"
                        v-model="data.code"
                        maxlength="6"
                        :invalid="!validCode"
                        placeholder="Enter code here..."
                        @input="changed.code = true"
                    ></InputGroup>

                    <InputGroup
                        type="number"
                        step="0.01"
                        name="price"
                        v-model="data.price"
                        :invalid="!validPrice"
                        placeholder="Enter price here..."
                        prepend="€"
                        @input="changed.price = true"
                    ></InputGroup>

                    <div class="input-group-row">
                        <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center">
                            <div class="h5 fw-bold m-md-0">Thumbnail</div>
                        </div>

                        <div class="col-lg-7">
                            <input @change="checkThumbnail" id="thumbnail" type="file" name="thumbnail" :class="[
                                'form-control',
                                'form-control-file',
                                !validThumbnail && 'is-invalid'
                            ]">
                        </div>
                    </div>

                    <hr class="hr-dashed">

                    <div>
                        <div class="h3 text-center fw-bold">Short Description</div>
                        <div class="col-lg-8 offset-lg-2 my-3">
                            <textarea v-model="data.short_description" name="short_description" @input="changed.short_description = true" placeholder="Shortly describe this bundle..." :class="[
                                'form-control',
                                'mb-2',
                                !validShortDescription && 'is-invalid'
                            ]"></textarea>
                            <div v-html="markdownToHTML(data.short_description)"></div>
                        </div>
                    </div>

                    <hr class="hr-dashed">

                    <div>
                        <div class="h3 text-center fw-bold">Description</div>
                        <div class="col-lg-8 offset-lg-2 my-3">
                            <textarea v-model="data.description" name="description" @input="changed.description = true" placeholder="Describe this bundle..." :class="[
                                'form-control',
                                'mb-2',
                                !validDescription && 'is-invalid'
                            ]"></textarea>
                            <div v-html="markdownToHTML(data.description)"></div>
                        </div>
                    </div>

                    <hr class="hr-dashed">

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

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import marked from 'marked';
import DOMPurify from 'dompurify';

import InputGroup from "../../../components/InputGroup.vue";
import Loading from "../../../components/Loading.vue";

export default {
    components: {
        InputGroup,
        Loading
    },
    data() {
        return {
            ready: false,
            data: {
                title: "",
                code: "",
                price: "",
                short_description: "",
                description: ""
            },
            titles: [],
            codes: [],
            changed: {
                title: false,
                code: false,
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
            const title = this.data.title;
            return !this.changed.title || !!title && title.length <= 64 && !this.titles.includes(title.toLowerCase());
        },
        validCode() {
            const code = this.data.code;
            return !this.changed.code || code.length == 6 && !this.codes.includes(code.toLowerCase());
        },
        validPrice() {
            const price = Number(this.data.price);
            return !this.changed.price || this.data.price !== "" && !isNaN(price) && price >= 0 && price < 1000;
        },
        validShortDescription() {
            const text = this.data.short_description;
            return !this.changed.short_description || text.length > 0;
        },
        validDescription() {
            const text = this.data.description;
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
    mounted() {
        axios
            .get("/webapi/admin/bundles/create", {})
            .then(response => {
                this.codes = response.data.codes;
                this.titles = response.data.titles;
                this.ready = true;
            });
    }
}
</script>
