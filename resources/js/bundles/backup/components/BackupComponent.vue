<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-hdd"></i>
                Backup data
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div style="display: none;">
                    <input id="file-input" type="file" @change="readFile" accept=".selfacc">
                </div>

                <div class="row">
                    <div class="col-lg-4 offset-lg-2 my-lg-0 my-2">
                        <button class="big-button-primary"
                            :disabled="!canCreate || createSpinner"
                            @click="createBackup"
                            :data-toggle="!canCreate && 'tooltip'"
                            :data-placement="!canCreate && 'bottom'"
                            :title="!canCreate && 'You can only create one backup per day'"
                        >
                            <div v-if="!createSpinner">
                                Create backup
                            </div>

                            <span
                                v-else
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </div>

                    <div class="col-lg-4 my-lg-0 my-2">
                        <button class="big-button-primary" :disabled="!canRestore" @click="restoreData">Restore from file</button>
                    </div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading.vue";

export default {
    components: {
        Loading
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            canCreate: false,
            canRestore: false,
            createSpinner: false
        }
    },
    methods: {
        createBackup() {
            this.createSpinner = true;
            axios
                .get("/webapi/bundles/backup/create")
                .then(response => {
                    const data = response.data;

                    // Create file
                    const download = document.createElement("a");
                    download.style.display = "none";
                    download.href = `data:application/octet-stream;charset:utf-8,${encodeURIComponent(JSON.stringify(data))}`;
                    download.download = `${new Date().toISOString().split("T")[0]}.selfacc`;

                    // Download file
                    document.body.appendChild(download);
                    download.click();
                    document.body.removeChild(download);

                    this.canCreate = false;
                })
                .catch(err => {
                    console.error(err);
                })
                .finally(() => {
                    this.createSpinner = false;
                })
        },
        restoreData() {
            document.getElementById("file-input").click();
        },
        readFile() {
            const file = document.getElementById("file-input").files[0];
            const fileHandler = new File([file], { type: "application/json" });
            fileHandler.text()
                .then(data => JSON.parse(data))
                .then(data => {
                    console.log(data);
                })
                .catch(err => console.error(err));
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get("/webapi/bundles/backup", {})
            .then(response => {
                const data = response.data;

                this.canCreate = data.canCreate;
                this.canRestore = data.canRestore;

                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    }
}
</script>
