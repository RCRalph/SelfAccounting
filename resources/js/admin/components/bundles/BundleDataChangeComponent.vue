<template>
    <div>
        <form id="bundle-form" :action="`/admin/bundles/${data.id}`" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" :value="CSRF_TOKEN">
            <input type="hidden" name="_method" value="PATCH">

            <div class="form-group row">
                <label class="col-lg-3 offset-lg-1 col-form-label text-lg-right">Title</label>
                <div class="col-lg-7">
                    <input type="text" placeholder="Enter title here..." maxlength="64" v-model="data.title" name="title" :class="[
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

                    <input type="number" step="0.01" placeholder="Enter price here..." v-model="data.price" name="price" :class="[
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

            <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'">

            <div>
                <div class="h3 text-center">Short Description</div>
                <div class="col-lg-8 offset-lg-2 my-3">
                    <textarea v-model="data.short_description" name="short_description" placeholder="Shortly describe this bundle..." :class="[
                        'form-control',
                        'mb-2',
                        !validShortDescription && 'is-invalid'
                    ]"></textarea>
                    <div v-html="markdownToHTML(data.short_description)"></div>
                </div>
            </div>

            <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'">

            <div>
                <div class="h3 text-center">Description</div>
                <div class="col-lg-8 offset-lg-2 my-3">
                    <textarea v-model="data.description" name="description" placeholder="Describe this bundle..." :class="[
                        'form-control',
                        'mb-2',
                        !validDescription && 'is-invalid'
                    ]"></textarea>
                    <div v-html="markdownToHTML(data.description)"></div>
                </div>
            </div>

            <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'" >

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

export default {
    props: {
        data: Object,
        titles: Array,
        darkmode: Boolean
    },
    components: {
        SaveResetChanges
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
