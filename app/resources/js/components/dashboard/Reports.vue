<template>
    <div>
      <DataTable :value="reports">
        <Column field="id" header="ID"></Column>
        <Column field="car" header="Car"></Column>
        <Column field="customer" header="Customer"></Column>
        <Column field="rentalDate" header="Rental Date"></Column>
        <Column field="returnDate" header="Return Date"></Column>
        <Column field="status" header="Status" :body="statusTemplate"></Column>
        <Column field="amount" header="Amount"></Column>
        <Column field="paymentStatus" header="Payment Status" :body="paymentStatusTemplate"></Column>
      </DataTable>
      <Chart type="polarArea" :data="chartData" :options="chartOptions" class="w-full md:w-[30rem]" />
    </div>
  </template>

  <script>
  import { ref } from 'vue';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Chart from 'primevue/chart';

  export default {
    name: 'Reports',
    components: {
      DataTable,
      Column,
      Chart
    },
    data() {
      return {
        reports: [
          { id: 1, car: 'Toyota Corolla', customer: 'John Doe', rentalDate: '2025-02-01', returnDate: '2025-02-10', status: 'Returned', amount: 300, paymentStatus: 'Paid' },
          { id: 2, car: 'Honda Civic', customer: 'Jane Smith', rentalDate: '2025-02-05', returnDate: '2025-02-15', status: 'Overdue', amount: 400, paymentStatus: 'Unpaid' },
          { id: 3, car: 'Ford Focus', customer: 'Alice Johnson', rentalDate: '2025-02-10', returnDate: '2025-02-20', status: 'Rented', amount: 350, paymentStatus: 'Paid' }
        ],
        chartData: {
          labels: ['Rented', 'Returned', 'Overdue'],
          datasets: [
            {
              label: 'Number of Cars',
              backgroundColor: '#42A5F5',
              data: [1, 1, 1] // Initial data, will be updated in mounted()
            }
          ]
        },
        chartOptions: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top'
            },
            title: {
              display: true,
              text: 'Rental Car Status'
            }
          }
        }
      };
    },
    methods: {
      statusTemplate(rowData) {
        let icon = '';
        if (rowData.status === 'Returned') {
          icon = 'pi pi-check-circle';
        } else if (rowData.status === 'Overdue') {
          icon = 'pi pi-exclamation-circle';
        } else if (rowData.status === 'Rented') {
          icon = 'pi pi-clock';
        }
        return `<i class="${icon}"></i>`;
      },
      paymentStatusTemplate(rowData) {
        let icon = '';
        if (rowData.paymentStatus === 'Paid') {
          icon = 'pi pi-dollar';
        } else if (rowData.paymentStatus === 'Unpaid') {
          icon = 'pi pi-exclamation-circle';
        }
        return `<i class="${icon}"></i>`;
      },
      updateChartData() {
        const statusCounts = this.reports.reduce((counts, report) => {
          counts[report.status] = (counts[report.status] || 0) + 1;
          return counts;
        }, {});

        this.chartData.datasets[0].data = [
          statusCounts['Rented'] || 0,
          statusCounts['Returned'] || 0,
          statusCounts['Overdue'] || 0
        ];
      }
    },
    mounted() {
      this.updateChartData();
    }
  }
  </script>
