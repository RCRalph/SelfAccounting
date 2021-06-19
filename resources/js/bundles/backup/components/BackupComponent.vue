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
                            :title="!canCreate && 'You can only create one backup per 24 hours'"
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
                        <button class="big-button-primary"
                            :disabled="!canRestore"
                            @click="restoreData"
                            :data-toggle="!canRestore && 'tooltip'"
                            :data-placement="!canRestore && 'bottom'"
                            :title="!canRestore && (restoreDate.length ? `Contact the developer or wait until ${restoreDate} to enable this option` : 'You can restore data once per 24 hours')"
                        >Restore from file</button>
                    </div>
                </div>

                <div class="row" v-if="dataToDisplay === false">
                    <div class="h3 w-100 mt-3 font-weight-bold text-danger text-center">
                        This file is invalid. Please try a&nbsp;different file.
                    </div>
                </div>

                <div v-else-if="dataToDisplay !== true">
                    <RestoreTablesComponent
                        :data="dataToDisplay"
                        :currencies="currencies"
                        :bundles="hasBundles"
                    ></RestoreTablesComponent>

                    <hr class="hr">

                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <button class="big-button-success" @click="submitData" :disabled="submitSpinner">
                                <div v-if="!submitSpinner">
                                    Restore data
                                </div>

                                <span
                                    v-else
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                            </button>
                        </div>
                    </div>

					<div class="h3 font-weight-bold text-danger mt-3 text-center">
						Warning! Performing this action will result in all of your data being erased!
					</div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading.vue";
import RestoreTablesComponent from "./RestoreTablesComponent.vue";

export default {
    components: {
        Loading,
        RestoreTablesComponent
    },
    data() {
        return {
            ready: false,
            canCreate: false,
            canRestore: false,
            restoreDate: "",
            createSpinner: false,
            submitSpinner: false,
            dataToDisplay: true,
            currencies: [],
            hasBundles: {}
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
		isValidString(str, min, max) {
			return typeof str == "string" && str.length >= min && str.length <= max;
		},
		isBetweenNumbers(num, min, max) {
			return typeof num == "number" && num >= min && num <= max;
		},
		isObject(obj) {
			return typeof obj == "object" && !Array.isArray(obj) && obj !== null;
		},
        isDate(str) {
            return typeof str == "string" && !isNaN(Date.parse(str));
        },
        isCurrency(cur) {
            return typeof cur == "number" && this.currencies.map(item => item.id).includes(cur);
        },
		checkCategories(categories) {
			// Validate data
            let validationResult = [];
			categories.forEach(item => {
                let validation = [
                    this.isCurrency(item.currency_id),
                    this.isValidString(item.name, 1, 32),
                    typeof item.income_category == "boolean",
                    typeof item.outcome_category == "boolean",
                    typeof item.count_to_summary == "boolean",
                    item.show_on_charts === undefined || typeof item.show_on_charts == "boolean",
                    item.start_date === null || this.isDate(item.start_date),
                    item.end_date === null || this.isDate(item.end_date) && Date.parse(item.start_date) <= Date.parse(item.end_date),
                ];

                validationResult.push(validation.reduce((item1, item2) => item1 && item2));
			});

            if (validationResult.length && !validationResult.reduce((item1, item2) => item1 && item2)) {
                return false;
            }

            // Map categories to their currencies if data is valid
            let mappedCategories = {};
            categories.forEach((item, index) => {
                mappedCategories[index + 1] = item.currency_id;
            });

            return mappedCategories;
		},
        checkMeans(means) {
            // Validate data
            let validationResult = [];
            means.forEach(item => {
                let validation = [
                    this.isCurrency(item.currency_id),
                    this.isValidString(item.name, 1, 32),
                    typeof item.income_mean == "boolean",
                    typeof item.outcome_mean == "boolean",
                    typeof item.count_to_summary == "boolean",
                    item.show_on_charts === undefined || typeof item.show_on_charts == "boolean",
                    this.isDate(item.first_entry_date),
                    this.isBetweenNumbers(item.first_entry_amount, -1e11 + 0.01, 1e11 - 0.01)
                ];

                validationResult.push(validation.reduce((item1, item2) => item1 && item2));
            })

            if (validationResult.length && !validationResult.reduce((item1, item2) => item1 && item2)) {
                return false;
            }

            // Map means to currencies and first entries if data is valid
            let mappedMeans = {};
            means.forEach((item, index) => {
                mappedMeans[index + 1] = {
                    currency: item.currency_id,
                    first_entry: item.first_entry_date
                };
            });

            return mappedMeans;
        },
		checkIncomeOutcomeData(data, categories, means) {
			// Validate
			data.forEach(item => {
				const validMean = item.mean_id == 0 || means[item.mean_id].currency == item.currency_id,
					validCategory = item.category_id == 0 || categories[item.category_id] == item.currency_id;
				if (validMean && validCategory) {
					let validation = [
						this.isDate(item.date) && Date.parse(item.date) >= Date.parse(item.mean_id == 0 ? "1970" : means[item.mean_id].first_entry),
						this.isValidString(item.title, 1, 64),
						this.isCurrency(item.currency_id),
                        this.isBetweenNumbers(item.amount, 0.001, 1e7 - 0.001),
                        this.isBetweenNumbers(item.price, 0.01, 1e11 - 0.01)
					];

                    if (!validation.reduce((item1, item2) => item1 && item2)) {
                        return false;
                    }
				}
				else {
					return false;
				}
			});

			return true
		},
        checkBundleData(data, categories, means) {
            // Validate cash
            if (this.hasBundles.cashan && data.cash != undefined && data.cashMeans != undefined) {
                if (!Array.isArray(data.cash) || !Array.isArray(data.cashMeans)) {
                    return false;
                }

                data.cash.forEach(item => {
                    let validation = [
                        this.isCurrency(item.currency_id),
                        this.isBetweenNumbers(item.value, 0.001, 1e8 - 0.01),
                        this.isBetweenNumbers(item.amount, 1, Math.pow(2, 63) - 1)
                    ]

                    if (!validation.reduce((item1, item2) => item1 && item2)) {
                        return false;
                    }
                })

                // Validate cash means
                data.cashMeans.forEach(item => {
                    let validation = [
                        this.isCurrency(item.currency_id),
                        this.isBetweenNumbers(item.mean_id, 1, means.length),
                        means[item.mean_id - 1].currency_id == item.currency_id
                    ];

                    if (!validation.reduce((item1, item2) => item1 && item2)) {
                        return false;
                    }
                })
            }

            // Validate reports
            if (this.hasBundles.report && data.reports != undefined) {
                if (!Array.isArray(data.reports)) {
                    return false;
                }

                data.reports.forEach(item => {
                    const validation = [
                        // Validate report data
                        typeof this.isValidString(item.title, 1, 64),
                        typeof item.income_addition == "boolean",
                        typeof item.sort_dates_desc == "boolean",
                        typeof item.calculate_sum == "boolean",

                        // Validate report queries
                        Array.isArray(item.queries) && item.queries.map(item1 => [
                            ["income", "outcome"].includes(item1.query_data),

                            item1.min_date === null ||
                            this.isDate(item1.min_date) &&
                            (
                                !item1.max_date ||
                                Date.parse(item1.min_date) <= Date.parse(item1.max_date)
                            ),

                            item1.max_date === null ||
                            !isNaN(Date.parse(item1.max_date)) &&
                            (
                                !item1.min_date ||
                                Date.parse(item1.min_date) <= Date.parse(item1.max_date)
                            ),

                            item1.min_amount === null ||
                            typeof item1.min_amount == "number" &&
                            (
                                item1.max_amount === null ||
                                item1.min_amount <= item1.max_amount
                            ) && this.isBetweenNumbers(item1.min_date, 0, 1e7 - 0.001),

                            item1.max_amount === null ||
                            typeof item1.max_amount == "number" &&
                            (
                                item1.min_amount === null ||
                                item1.min_amount <= item1.max_amount
                            ) && this.isBetweenNumbers(item1.min_date, 0, 1e7 - 0.001),

                            item1.min_price === null ||
                            typeof item1.min_price == "number" &&
                            (
                                item1.max_price === null ||
                                item1.min_price <= item1.max_price
                            ) && this.isBetweenNumbers(item1.min_date, 0, 1e11 - 0.01),

                            item1.max_price === null ||
                            typeof item1.max_price == "number" &&
                            (
                                item1.min_price === null ||
                                item1.min_price <= item1.max_price
                            ) && this.isBetweenNumbers(item1.min_date, 0, 1e11 - 0.01),

                            item1.currency_id === null ||
                            this.isCurrency(item1.currency_id),

                            item1.category_id === 0 ||
                            categories[item1.category_id - 1].currency_id == item1.currency_id,

                            item1.mean_id === 0 ||
                            means[item1.mean_id - 1].currency_id == item1.currency_id
                        ]).reduce((item1, item2) => item1 && item2),

                        // Validate report additional entries
                        Array.isArray(item.additionalEntries) && item.additionalEntries.map(item1 => [
                            this.isDate(item1.date),
                            this.isValidString(item1.title, 1, 64),
                            this.isBetweenNumbers(item1.amount, 0.001, 1e7 - 0.001),
                            this.isBetweenNumbers(item1.price, 0.01, 1e11 - 0.01),
                            this.isCurrency(item1.currency_id),
                            item1.category_id == 0 || categories[item1.category_id].currency == item1.currency_id,
                            item1.mean_id == 0 || means[item1.mean_id].currency == item1.currency_id
                        ]).reduce((item1, item2) => item1 && item2),

                        // Validate report users
                        Array.isArray(item.users) && item.additionalEntries.map(item1 =>
                            this.isValidString(item1.title, 1, 64) &&
                            /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(item1.title)
                        ).reduce((item1, item2) => item1 && item2)
                    ];

                    if (!validation.reduce((item1, item2) => item1 && item2)) {
                        return false;
                    }
                });
            }

            return true;
        },
        readFile() {
            // Get file content
            const file = document.getElementById("file-input").files[0];
            const fileHandler = new File([file], { type: "application/json" });
            fileHandler.text()
                .then(data => JSON.parse(data))
                .then(data => {
					if (!this.isObject(data)) {
						throw new Error("Invalid data wrapper (wrapper not an object)");
					}

					const keys = ["categories", "means", "income", "outcome"];
					// Check data types of wrappers
					keys.forEach(item => {
						if (data[item] === undefined || !Array.isArray(data[item])) {
							throw new Error(`Invalid data type (${item} not an array)`);
						}
					});

					// Check data inside wrappers
					let results = {};
					keys.forEach(item => {
						switch (item) {
							case "categories":
								results[item] = this.checkCategories(data.categories);
								break;
							case "means":
								results[item] = this.checkMeans(data.means);
								break;
							case "income":
							case "outcome":
								results[item] = this.checkIncomeOutcomeData(data[item], results.categories, results.means);
								break;
						}

						if (results[item] === false) {
							throw new Error(`Invalid ${item} data`);
						}
					});

                    // Check bundle data
                    if (data.bundleData === undefined || !(data.bundleData instanceof Object)) {
                        throw new Error("Invalid data type (bundle data not an object)");
                    }

                    if (this.checkBundleData(data.bundleData, data.categories, data.means) === false) {
                        throw new Error(`Invalid bundle data`);
                    }

                    this.dataToDisplay = data;
                })
                .catch(err => {
                    this.dataToDisplay = false;
                    console.error(err);
                });
        },
        submitData() {
            this.submitSpinner = true;

            axios.post("/webapi/bundles/backup/restore", { ...this.dataToDisplay })
                .then(() => {
                    window.location.href = "/summary";
                })
                .catch(err => {
                    console.error(err);
                    this.submitSpinner = false;
                })
        }
    },
    mounted() {
        axios
            .get("/webapi/bundles/backup", {})
            .then(response => {
                const data = response.data;

                this.canCreate = data.canCreate;
                this.canRestore = data.canRestore;
                this.restoreDate = data.restoreDate;
                this.currencies = data.currencies;
                this.hasBundles = data.hasBundles;

                this.ready = true;
            })
    },
    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    }
}
</script>
