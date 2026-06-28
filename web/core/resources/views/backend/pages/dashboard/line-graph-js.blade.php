<script>
        $(document).ready(function () {
         function cssvar(name) {
          return getComputedStyle(document.documentElement).getPropertyValue(name);
         }

         cssvar();

         let lineChartCustomer;
         let lineChartListings;
         let ctxCustomer = document.getElementById('lineChartCustomer').getContext('2d');
         let ctxListings = document.getElementById('lineChartListings').getContext('2d');

         function createChartCustomer(data) {
          if (lineChartCustomer) {
           lineChartCustomer.destroy();
          }

          lineChartCustomer = new Chart(ctxCustomer, {
           type: 'line',
           data: {
            labels: data.labels,
            datasets: [{
             label: 'Total Users',
             data: data.data,
             borderColor: cssvar('--blue'),
             borderWidth: 2,
             fill: true,
             backgroundColor: 'rgba(0, 128, 255, .05)',
             pointBorderWidth: 2,
             pointBackgroundColor: cssvar('--white'),
             pointRadius: 2,
             pointHoverRadius: 5,
             pointHoverBackgroundColor: cssvar('--blue'),
             lineTension: .5,
            }]
           },
           options: {
            responsive: true,
            plugins: {
             legend: {
              display: true,
              labels: {
               color: cssvar('--body-color'),
              },
             }
            },
            scales: {
             x: {
              ticks: {
               beginAtZero: true,
               color: cssvar('--body-color'),
              },
              grid: {
               borderColor: cssvar('--border-color'),
               lineWidth: 1,
              }
             },
             y: {
              ticks: {
               beginAtZero: true,
               color: cssvar('--body-color'),
              },
              grid: {
               borderColor: cssvar('--border-color'),
               lineWidth: 1,
              }
             }
            },
           },
          });
         }
         function createChartServices(data) {
          if (lineChartListings) {
           lineChartListings.destroy();
          }

          lineChartListings = new Chart(ctxListings, {
           type: 'line',
           data: {
            labels: data.labels,
            datasets: [{
             label: 'Total Services',
             data: data.data,
             borderColor: cssvar('--blue'),
             borderWidth: 2,
             fill: true,
             backgroundColor: 'rgba(0, 128, 255, .05)',
             pointBorderWidth: 2,
             pointBackgroundColor: cssvar('--white'),
             pointRadius: 2,
             pointHoverRadius: 5,
             pointHoverBackgroundColor: cssvar('--blue'),
             lineTension: .5,
            }]
           },
           options: {
            responsive: true,
            plugins: {
             legend: {
              display: true,
              labels: {
               color: cssvar('--body-color'),
              },
             }
            },
            scales: {
             x: {
              ticks: {
               beginAtZero: true,
               color: cssvar('--body-color'),
              },
              grid: {
               borderColor: cssvar('--border-color'),
               lineWidth: 1,
              }
             },
             y: {
              ticks: {
               beginAtZero: true,
               color: cssvar('--body-color'),
              },
              grid: {
               borderColor: cssvar('--border-color'),
               lineWidth: 1,
              }
             }
            },
           },
          });
         }
         function fetchDataCustomer(interval) {
          $.ajax({
           url: "{{ route('admin.get.user.graph.data') }}",
           method: 'GET',
           data: { interval: interval },
           success: function(response) {
            let labels = [];
            let data = [];

            for (const [key, value] of Object.entries(response)) {
                labels.push(key);
                data.push(value);
            }

            createChartCustomer({ labels: labels, data: data });
           },
           error: function(error) {
            console.error('Error fetching data:', error);
           }
          });
         }
         function fetchDataServices(interval) {
          $.ajax({
           url: "{{ route('admin.get.service.graph.data') }}",
           method: 'GET',
           data: { interval: interval },
           success: function(response) {
            let labels = [];
            let data = [];
            for (const [key, value] of Object.entries(response)) {
                labels.push(key);
                data.push(value);
            }

            createChartServices({ labels: labels, data: data });
           },
           error: function(error) {
           }
          });
         }

      $(document).on('change','#timeIntervalSelect',function(){
          let interval = $(this).val();
          fetchDataCustomer(interval);
         });

     $(document).on('change','#serviceTimeIntervalSelect',function(){
          let interval = $(this).val();
             fetchDataServices(interval);
         });
         fetchDataCustomer('0');
         fetchDataServices('0');
        });
 </script>
