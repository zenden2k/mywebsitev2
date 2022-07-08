<template>
    <div>
        <section class="content-header"><div class="container-fluid"><h1>Download stats</h1></div></section>
        <section class="content">
            <div class="container-fluid">
                <p>
                    <router-link :to="{name: 'downloads'}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i> Back to all downloads </router-link>
                </p>

                <div class="card card-primary card-outline card-tabs">

                    <div class="card-body" style="width:60vw">

                            <MyChart v-if="loaded" :chart-data="chartData" :width="width"
                                  :height="height" :chart-options="chartOptions"/>

                    </div>

                </div>

            </div>
        </section>
    </div>
</template>

<script>
import { Line } from 'vue-chartjs/legacy'
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, PointElement, LineElement} from 'chart.js'
ChartJS.register(/*Line, */Title, Tooltip, Legend, CategoryScale, LinearScale, PointElement, LineElement)

export default {
    name: 'LineChart',
    components: { 'MyChart': Line },
    data() {
        return {
            loaded: false,
            chartData: null
        }
    },
    props: {
        /*chartId: {
            type: String,
            default: 'bar-chart'
        },
        datasetIdKey: {
            type: String,
            default: 'label'
        },*/
        width: {
            type: Number,
            default: 400
        },
        height: {
            type: Number,
            default: 250
        },
        /*cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Object,
            default: () => {}
        },*/
        chartOptions: {
            type: Object,
            default: () => {
                return {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        }
    },
    created() {
        this.loaded = false
        if (this.$route.params.id) {
            axios.get(`/api/download/${this.$route.params.id}`)
                .then(response => {
                    this.chartData = response.data.data;
                    //console.log(this.chartData);
                    this.loaded = true;
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    },
    methods: {
        updateTab() {

        },
    },
}
</script>
