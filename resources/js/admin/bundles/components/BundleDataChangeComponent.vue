<template>
    <div>
        <form id="bundle-form" :action="`/admin/bundles/${data.id}`" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" :value="CSRF_TOKEN">
            <input type="hidden" name="_method" value="PATCH">

            <InputGroup
                type="text"
                name="title"
                v-model="data.title"
                maxlength="64"
                :invalid="!validTitle"
                placeholder="Enter title here..."
            ></InputGroup>

            <InputGroup
                type="text"
                name="code"
                v-model="data.code"
                maxlength="6"
                :invalid="!validCode"
                placeholder="Enter code here..."
            ></InputGroup>

            <InputGroup
                type="number"
                step="0.01"
                name="price"
                v-model="data.price"
                :invalid="!validPrice"
                placeholder="Enter price here..."
                prepend="€"
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
                    <textarea v-model="data.short_description" name="short_description" placeholder="Shortly describe this bundle..." :class="[
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
                    <textarea v-model="data.description" name="description" placeholder="Describe this bundle..." :class="[
                        'form-control',
                        'mb-2',
                        !validDescription && 'is-invalid'
                    ]"></textarea>
                    <div v-html="markdownToHTML(data.description)"></div>
                </div>
            </div>

            <hr class="hr-dashed">

            <SaveResetChanges
                :disableAll="submitted"
                :spinner="submitted"
                :disableSave="!canSubmit"
                @save="submit"
                @reset="reset"
            ></SaveResetChanges>
        </form>
    </div>
</template>

<script>
import marked from "marked";
import DOMPurify from "dompurify";

import SaveResetChanges from "../../../components/SaveResetChanges.vue";
import InputGroup from "../../../components/InputGroup.vue";

export default {
    props: {
        data: Object,
        titles: Array,
        codes: Array
    },
    components: {
        SaveResetChanges,
        InputGroup
    },
    data() {
        return {
            validThumbnail: true,
            submitted: false
        }
    },
    computed: {
        CSRF_TOKEN() {
			return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
        },
        validTitle() {
            const title = this.data.title;
            return !!title && title.length <= 64 && !this.titles.includes(title.toLowerCase());
        },
        validCode() {
            const code = this.data.code;
            return code.length == 6 && !this.codes.includes(code.toLowerCase());
        },
        validPrice() {
            const price = Number(this.data.price);
            return this.data.price !== "" && !isNaN(price) && price >= 0 && price < 1000;
        },
        validShortDescription() {
            const text = this.data.short_description;
            return text.length > 0;
        },
        validDescription() {
            const text = this.data.description;
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
        submit() {
            this.submitted = true;
            document.getElementById("bundle-form").submit();
        },
        reset() {
            this.$emit("reset-form");
            this.validThumbnail = true;
            document.getElementById("thumbnail").value = "";
        }
    }
}
</script>
