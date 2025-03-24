<template>
    <h1 class="font-bold text-3xl">Booking List</h1>
    <Breadcrumb :model="items" class="custom-breadcrumb" />

    <Card>
        <template #content>
            <header class="inline-header">
                <div class="left-controls">
                    <Button label="Add Booking" icon="pi pi-plus" @click="$router.push('/dashboard/add-booking')" />
                    <IconField>
                        <InputIcon class="pi pi-search" />
                        <InputText v-model="searchTerm" placeholder="Search" />
                    </IconField>
                </div>
            </header>

            <div v-if="bookings.length > 0">
                <DataTable :value="bookings"
          selectionMode="single"
          class="p-datatable-no-gridlines"
          @row-click="onRowClick"
          :paginator="true"
          :rows="10">
    <Column field="id" header="Booking ID"></Column>
    <Column field="customerName" header="Customer"></Column>
    <Column field="vehicleName" header="Vehicle"></Column>
    <Column field="startDate" header="Start Date">
        <template #body="slotProps">
            {{ formatDateShort(slotProps.data.startDate) }}
        </template>
    </Column>
    <Column field="endDate" header="End Date">
        <template #body="slotProps">
            {{ formatDateShort(slotProps.data.endDate) }}
        </template>
    </Column>
    <Column field="duration" header="Duration (Days)"></Column>
    <Column field="amount" header="Total Amount">
        <template #body="slotProps">
            {{ formatCurrency(slotProps.data.amount) }}
        </template>
    </Column>
    <Column field="deposit" header="Deposit">
        <template #body="slotProps">
            {{ formatCurrency(slotProps.data.deposit) }}
        </template>
    </Column>
    <Column field="status" header="Status" :body="statusTemplate"></Column>
    <Column field="paymentStatus" header="Payment" :body="paymentStatusTemplate"></Column>
    <Column field="pickupLocation" header="Pickup Location"></Column>
    <Column field="dropoffLocation" header="Drop-off Location"></Column>
</DataTable>
            </div>
            <div v-else>
                <p class="text-center text-gray-500 mt-5">No bookings found. Please add a booking.</p>
            </div>
        </template>
    </Card>

    <Dialog v-model="showDialog" header="Booking Details" :modal="true" class="p-fluid">
        <div v-if="selectedRow" class="grid">
            <div class="col-12 md:col-6">
                <h3 class="mb-3 font-semibold">Customer Information</h3>
                <p><strong>Name:</strong> {{ selectedRow.customerName }}</p>
                <p><strong>Email:</strong> {{ selectedRow.customer?.email }}</p>
                <p><strong>Phone:</strong> {{ selectedRow.customer?.phone }}</p>
            </div>
            <div class="col-12 md:col-6">
                <h3 class="mb-3 font-semibold">Vehicle Information</h3>
                <p><strong>Vehicle:</strong> {{ selectedRow.vehicleName }}</p>
                <p><strong>License Plate:</strong> {{ selectedRow.vehicle?.licensePlate }}</p>
                <p><strong>Year:</strong> {{ selectedRow.vehicle?.year }}</p>
            </div>
            <div class="col-12">
                <h3 class="mb-3 font-semibold">Booking Details</h3>
                <p><strong>Start Date:</strong> {{ formatDate(selectedRow.startDate) }}</p>
                <p><strong>End Date:</strong> {{ formatDate(selectedRow.endDate) }}</p>
                <p><strong>Duration:</strong> {{ selectedRow.duration }} days</p>
                <p><strong>Total Amount:</strong> {{ formatCurrency(selectedRow.amount) }}</p>
                <p><strong>Deposit:</strong> {{ formatCurrency(selectedRow.deposit) }}</p>
                <p><strong>Status:</strong> {{ selectedRow.status }}</p>
                <p><strong>Payment Status:</strong> {{ selectedRow.paymentStatus }}</p>
            </div>
            <div class="col-12">
                <h3 class="mb-3 font-semibold">Location Details</h3>
                <p><strong>Pickup Location:</strong> {{ selectedRow.pickupLocation }}</p>
                <p><strong>Drop-off Location:</strong> {{ selectedRow.dropoffLocation }}</p>
            </div>
        </div>
        <template #footer>
            <Button label="Close" icon="pi pi-times" @click="showDialog = false" class="p-button-text"/>
            <Button label="Edit" icon="pi pi-pencil" @click="editBooking" />
        </template>
    </Dialog>
</template>

<script>
import { ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Breadcrumb from 'primevue/breadcrumb';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import axios from 'axios';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';

export default {
    name: 'Bookings',
    components: {
        DataTable,
        Column,
        Button,
        Card,
        Breadcrumb,
        InputText,
        IconField,
        InputIcon,
        Dialog
    },
    data() {
        return {
            items: [
                { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
                { label: 'Booking List', url: '/dashboard/bookings' }
            ],
            bookings: [],
            searchTerm: '',
            showDialog: false,
            selectedRow: null,
            filters: {
                global: { value: null, matchMode: 'contains' },
            }
        };
    },
    watch: {
        searchTerm(newValue) {
            this.filters.global.value = newValue;
        }
    },
    methods: {
        formatDateShort(date) {
        if (!date) return '';
        const d = new Date(date);
        return d.toLocaleDateString('it-IT', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    },
        fetchBookings() {
            axios.post('/booking')
                .then(response => {
                    this.bookings = response.data.map(booking => {
                        const customerName = booking.customer ?
                            `${booking.customer.firstName} ${booking.customer.lastName}` : 'N/A';

                        const vehicleName = booking.vehicle && booking.vehicle.make && booking.vehicle.model ?
                            `${booking.vehicle.make.name} ${booking.vehicle.model.name}` : 'N/A';

                        const pickupLocation = booking.pickup_location ? booking.pickup_location.name : 'N/A';
                        const deliveryLocation = booking.delivery_location ? booking.delivery_location.name : 'N/A';

                        return {
                            ...booking,
                    customerName,
                    vehicleName,
                    pickupLocation,
                    dropoffLocation: deliveryLocation,
                    duration: this.calculateDuration(booking.startDate, booking.endDate),
                    customer: booking.customer,
                    vehicle: booking.vehicle
                        };
                    });
                })
                .catch(error => {
                    console.error('Error fetching bookings:', error);
                });
        },
        statusTemplate(slotProps) {
            const severity = {
                'Confirmed': 'success',
                'Pending': 'warning',
                'Cancelled': 'danger',
                'In Progress': 'info',
                'Completed': 'success'
            };

            return h('span', { class: `p-tag p-tag-${severity[slotProps.data.status] || 'info'}` },
                   slotProps.data.status);
        },
        paymentStatusTemplate(slotProps) {
            const severity = {
                'Paid': 'success',
                'Unpaid': 'danger',
                'Partial': 'warning',
                'Refunded': 'info'
            };

            return h('span', { class: `p-tag p-tag-${severity[slotProps.data.paymentStatus] || 'info'}` },
                   slotProps.data.paymentStatus);
        },
        onRowClick(event) {
            this.selectedRow = event.data;
            this.showDialog = true;
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(value);
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },
        calculateDuration(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        },
        editBooking() {
            if (this.selectedRow) {
                this.$router.push(`/dashboard/edit-booking/${this.selectedRow.id}`);
            }
        }
    },
    mounted() {
        this.fetchBookings();
    }
};
</script>

<style scoped>
.custom-breadcrumb {
    margin-bottom: 1rem;
}

.inline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.left-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.p-dialog {
    max-width: 90vw;
}

@media (min-width: 768px) {
    .p-dialog {
        max-width: 800px;
    }
}
</style>
