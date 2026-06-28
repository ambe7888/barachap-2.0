<script>
    $(document).ready(function () {
        let chartElements;

        if ($("#sales_pipeline").length > 0) {
            let options = {
                series: [{
                    name: "{{ __('Total Income') }}",
                    data: []
                }],
                chart: {
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '10%',
                        distributed: true,
                        borderRadius: 10,
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false,
                },
                fill: {
                    colors: [
                        "var(--blue)", "var(--purple)", "var(--orange)",
                        "var(--brown)", "var(--green)", "var(--sky)",
                        "var(--pink)", "var(--primary)"
                    ],
                },
                grid: {
                    show: true,
                    borderColor: 'var(--border-color)',
                },
                xaxis: {
                    categories: [],
                    axisTicks: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                        color: "var(--border-color)",
                        width: '1px',
                    },
                    labels: {
                        style: {
                            colors: "var(--paragraph-color)",
                        }
                    },
                },
                yaxis: {
                    axisTicks: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                        color: "var(--border-color)",
                        width: '1px',
                    },
                    labels: {
                        style: {
                            colors: "var(--paragraph-color)",
                        }
                    },
                },
                responsive: [{
                    breakpoint: 575,
                    options: {
                        legend: {
                            position: 'bottom',
                            itemMargin: { horizontal: 5, vertical: 5 },
                        }
                    }
                }]
            };

            chartElements = new ApexCharts(document.querySelector("#sales_pipeline"), options);
            chartElements.render();
        }

        function fetchDataTotalIncome(interval) {
            $.ajax({
                url: "{{ route('provider.get.total.income.graph.data') }}",
                method: 'GET',
                data: { interval: interval },
                success: function(response) {
                    let labels = [];
                    let data = [];

                    // Clear previous chart data
                    chartElements.updateSeries([{ data: [] }]);
                    const currentMonth = new Date().getMonth(); // 0 = Jan, 11 = Dec

                    // Set different labels and initialize data based on the interval
                    switch (interval) {
                        case '0': // Today
                            labels = Object.values(response.total_earnings).map(item => item.day);
                            data = Object.values(response.total_earnings).map(item => item.total_income);// Placeholder for 24hour
                            break;
                        case '1': // Yesterday
                            labels = Object.values(response.total_earnings).map(item => item.day);
                            data = Object.values(response.total_earnings).map(item => item.total_income);// Placeholder for 24hour
                            break;
                        case '2': // Recent Week
                            labels = Object.values(response.total_earnings).map(item => item.day);
                            data = Object.values(response.total_earnings).map(item => item.total_income);// Placeholder for 7 days
                        break;
                        case '3': // Last Week
                            labels = Object.values(response.total_earnings).map(item => item.day);
                            data = Object.values(response.total_earnings).map(item => item.total_income);// Placeholder for 7 days
                        break;
                        case '4': // Recent Month
                            labels = ['week1', 'week2', 'week3', 'week4','week5'];
                            data =  Object.values(response.total_earnings).map(item => item.total_income);
                            break;
                        case '5': // Last Month
                            labels = ['week1', 'week2', 'week3', 'week4','week5'];
                            data = Object.values(response.total_earnings).map(item => item.total_income);
                            break;
                        case '6': // Year

                            labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug','Sep','Oct','Nov',"Dec"];
                            data =Object.values(response.total_earnings).map(item => item.total_income);
                            break;
                        case '7': // Last Year

                            labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug','Sep','Oct','Nov',"Dec"];
                            data = Object.values(response.total_earnings).map(item => item.total_income);
                            break;
                        default:
                            labels = [];
                    }


                    // Directly assign total earnings for the yearly case (or any other case with a single value)
                    /*if (response.total_earnings !== undefined && interval !='6') {
                        data[0] = response.total_earnings;
                    }*/

                    // Update the chart with new data
                    chartElements.updateOptions({
                        xaxis: {
                            categories: labels
                        },
                        series: [{
                            name: "{{ __('Total Income') }}",
                            data: data
                        }]
                    });

                },
                error: function(error) {
                }
            });
        }

        // Event listener for the interval selector
        $(document).on('change','#totalIncomeIntervalSelectAll',function(){
            let interval = $(this).val();
            fetchDataTotalIncome(interval);
        });

        // Initial load for default interval (weekly)
        fetchDataTotalIncome('0');
    });
</script>
