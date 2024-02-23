<template>
    <v-dialog
        v-model="dialog"
        max-width="500"
    >
        <template v-slot:activator="{ props: dialogProps }">
            <v-hover v-slot="{ isHovering, props: hoverProps }">
                <v-avatar
                    v-bind="{ ...dialogProps, ...hoverProps }"
                    :size="pictureSize"
                >
                    <img
                        :src="props.modelValue"
                        :width="pictureSize"
                        :height="pictureSize"
                        alt="User's profile picture"
                    >

                    <div
                        class="d-flex align-center justify-center position-absolute hover-icon"
                        :style="{ opacity: isHovering ? 1 : 0}"
                    >
                        <v-icon
                            size="x-large"
                            icon="mdi-camera"
                        ></v-icon>
                    </div>
                </v-avatar>
            </v-hover>
        </template>

        <v-card>
            <CardTitleWithButtons title="Change profile picture"></CardTitleWithButtons>

            <v-card-text>
                <v-file-input
                    v-model="picture"
                    accept="image/png, image/jpeg, image/bmp, image/jpg"
                    prepend-icon="mdi-camera"
                    label="Choose profile picture"
                    show-size
                ></v-file-input>

                <div
                    v-if="picture"
                    class="d-flex justify-center"
                >
                    <div
                        class="div-img-rounded"
                        :style="imageStyles"
                    ></div>
                </div>
            </v-card-text>

            <CardActionsSubmitComponent
                :loading="!!loading.submit"
                :can-submit="!!picture"
                @submit="submit"
            ></CardActionsSubmitComponent>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import axios from "axios"
import { computed, ref } from "vue"

import { useDisplay } from "vuetify"
import { useStatusStore } from "@stores/status"
import { useUserStore } from "@stores/user"
import useComponentState from "@composables/useComponentState"

import CardActionsSubmitComponent from "@components/global/card/CardActionsSubmitComponent.vue"
import CardTitleWithButtons from "@components/global/card/CardTitleWithButtonsComponent.vue"

const props = defineProps<{
    modelValue: string
}>()

const emit = defineEmits<{
    "update:modelValue": [payload: string]
}>()

const display = useDisplay()
const status = useStatusStore()
const user = useUserStore()

function usePicture() {
    const picture = ref<File[]>()

    const pictureSize = computed(() => display.mobile.value ? 100 : 225)

    const imageStyles = computed(() => ({
        width: pictureSize.value + "px",
        height: pictureSize.value + "px",
        backgroundImage: `url(${picture.value ? URL.createObjectURL(picture.value[0]) : ""})`,
    }))

    function submit() {
        if (!picture.value) {
            throw new Error("Submitting empty picture array")
        }

        loading.value.submit = true

        const formData = new FormData()
        formData.append("picture", picture.value[0])

        axios.post(
            "/web-api/profile/picture",
            formData,
            {
                headers: {"Content-type": "multipart/form-data"},
            })
            .then(response => {
                emit("update:modelValue", response.data.picture)
                user.updateProfilePicture(response.data.picture)
                status.showSuccess("updated profile picture")

                dialog.value = false
                loading.value.submit = false
                picture.value = undefined
            })
            .catch(err => {
                console.error(err)
                setTimeout(() => status.showError(), 1000)
                setTimeout(() => loading.value.submit = false, 2000)
            })
    }

    return {imageStyles, picture, pictureSize, submit}
}

const {dialog, loading} = useComponentState()
const {imageStyles, picture, pictureSize, submit} = usePicture()
</script>
