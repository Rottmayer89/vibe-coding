@props(['data', 'labels', 'year'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Monthly Expenses') }} - {{ $year }}</h3>
        <div id="monthly-expense-chart" class="h-80"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const options = {
            series: [{
                name: '{{ __("Total Expenses") }}',
                data: @json($data)
            }],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '70%',
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#6366F1'],
            xaxis: {
                categories: @json($labels),
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                title: {
                    text: '{{ __("Amount") }}'
                },
                labels: {
                    formatter: function (value) {
                        return new Intl.NumberFormat().format(value);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return new Intl.NumberFormat().format(value);
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#monthly-expense-chart"), options);
        chart.render();
    });
</script>
@endpush
