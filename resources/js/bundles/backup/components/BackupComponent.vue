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
                <div class="row">
                    <div class="col-lg-4 offset-lg-2">
                        <button class="big-button-primary" :disabled="!canCreate || createSpinner" @click="createBackup">
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
                    <div class="col-lg-4">
                        <button class="big-button-primary" :disabled="!canRestore">Restore from file</button>
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
                    download.body.removeChild(download);

                    this.canCreate = false;
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(() => {
                    this.createSpinner = false;
                })
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
    }
}
</script>
