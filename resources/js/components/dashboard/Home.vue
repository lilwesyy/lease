<template>
    <div>
        <Card>
            <template #title>Recent Bookings</template>
            <template #content>
                <DataTable :value="bookings">
                    <Column field="id" header="ID"></Column>
                    <Column field="customer" header="Customer"></Column>
                    <Column field="vehicle" header="Vehicle"></Column>
                    <Column field="date" header="Date"></Column>
                </DataTable>
            </template>
        </Card>

        <Card class="mt-5">
            <template #title>Revenue</template>
            <template #content>
                <Chart type="line" :data="revenueData"></Chart>
            </template>
        </Card>

        <Card class="mt-5">
            <template #title>Available Vehicles</template>
            <template #content>
                <ul>
                    <li v-for="vehicle in availableVehicles" :key="vehicle.id">{{ vehicle.name }}</li>
                </ul>
            </template>
        </Card>
    </div>
</template>

<script>
import Card from 'primevue/card';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Chart from 'primevue/chart';

export default {
    name: 'Home',
    components: {
        Card,
        DataTable,
        Column,
        Chart
    },
    data() {
        return {
            bookings: [
                { id: 1, customer: 'John Doe', vehicle: 'Toyota Corolla', date: '2025-03-15' },
                { id: 2, customer: 'Jane Smith', vehicle: 'Honda Civic', date: '2025-03-16' }
            ],
            revenueData: {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [
                    {
                        label: 'Revenue',
                        data: [5000, 7000, 8000, 6000, 9000],
                        fill: false,
                        borderColor: '#42A5F5'
                    }
                ]
            },
            availableVehicles: [
                { id: 1, name: 'Toyota Corolla' },
                { id: 2, name: 'Honda Civic' },
                { id: 3, name: 'Ford Focus' }
            ]
        };
    }
};
</script>

<style scoped>
.m-0 {
    margin: 0;
}
</style>
