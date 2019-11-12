<template>
    <div>
        <breadcrumb></breadcrumb>
        <v-card flat color="rgb(0, 0, 0, 0)" class="mx-3">
            <v-card-actions>
                <h1 class="heading">Items</h1>
                <v-spacer></v-spacer>
                <v-btn color="primary"><v-icon>add</v-icon>Add New</v-btn>
            </v-card-actions>

        </v-card>
        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="desserts"
            :single-select="singleSelect"
            item-key="name"
            show-select
            class="ma-5 elevation-1"

        >
            <template v-slot:item.floorPlan="{ item }">
                <v-icon>remove_red_eye</v-icon>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon>more_vert</v-icon>
            </template>
            <template v-slot:item.status="{ item }">
                <v-chip
                    class="ma-2"
                    x-small
                    :color="getStatusColor(item.status)"
                >{{item.status}}</v-chip>
            </template>
            <template v-slot:item.dimensions="{ item }">
                {{item.dimensions}} x {{item.dimensions}} (m)
            </template>
            <template v-slot:item.name="{ item }" >
                <v-list-item class="pa-0"  mandatory v-on:click="gotoItemDetailPage(item)"
                >
                    <v-list-item-avatar tile>
                        <v-img src="https://cdn.vuetifyjs.com/images/lists/5.jpg" class="" ></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title v-text="item.name" class="bold"></v-list-item-title>
                        <v-list-item-subtitle>In Business Unit</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-data-table>
    </div>
</template>

<script>
    import breadcrumb from "@backoffice/js/components/breadcrumb";
    export default {
        name: "ItemList",
        components:{
            breadcrumb: breadcrumb
        },
        data () {
            return {
                singleSelect: false,
                selected: [],
                headers: [
                    {
                        text: 'Items name',
                        align: 'left',
                        sortable: false,
                        value: 'name',
                    },
                    { text: 'Item ID', value: 'uuid' },
                    { text: 'Dimensions', value: 'dimensions' },
                    { text: 'Status', value: 'status', align: 'center' },
                    { text: 'Floor Plan', value: 'floorPlan' },
                    { text: 'Action', value: 'actions', align: 'right' },
                ],
                desserts: [
                    {
                        name: 'Frozen Yogurt',
                        uuid: 159,
                        dimensions: 6.0,
                        status: 'Available',
                        floorPlan: 4.0,
                        actions: '1%',
                    },
                    {
                        name: 'Ice cream sandwich',
                        uuid: 237,
                        dimensions: 9.0,
                        status: 'Available',
                        floorPlan: 4.3,
                        actions: '1%',
                    },
                    {
                        name: 'Eclair',
                        uuid: 262,
                        dimensions: 16.0,
                        status: 'Available',
                        floorPlan: 6.0,
                        actions: '7%',
                    },
                    {
                        name: 'Cupcake',
                        uuid: 305,
                        dimensions: 3.7,
                        status: 'Available',
                        floorPlan: 4.3,
                        actions: '8%',
                    },
                    {
                        name: 'Gingerbread',
                        uuid: 356,
                        dimensions: 16.0,
                        status: 'Available',
                        floorPlan: 3.9,
                        actions: '16%',
                    },
                    {
                        name: 'Jelly bean',
                        uuid: 375,
                        dimensions: 0.0,
                        status: 'Available',
                        floorPlan: 0.0,
                        actions: '0%',
                    },
                    {
                        name: 'Lollipop',
                        uuid: 392,
                        dimensions: 0.2,
                        status: 'Available',
                        floorPlan: 0,
                        actions: '2%',
                    },
                    {
                        name: 'Honeycomb',
                        uuid: 408,
                        dimensions: 3.2,
                        status: 'Available',
                        floorPlan: 6.5,
                        actions: '45%',
                    },
                    {
                        name: 'Donut',
                        uuid: 452,
                        dimensions: 25.0,
                        status: 'Archive',
                        floorPlan: 4.9,
                        actions: '22%',
                    },
                    {
                        name: 'KitKat',
                        uuid: 518,
                        dimensions: 26.0,
                        status: 'Archive',
                        floorPlan: 7,
                        actions: '6%',
                    },
                ],
                items: [
                    {
                        text: 'Dashboard',
                        disabled: false,
                        href: 'breadcrumbs_dashboard',
                    },
                    {
                        text: 'Link 1',
                        disabled: false,
                        href: 'breadcrumbs_link_1',
                    },
                    {
                        text: 'Link 2',
                        disabled: true,
                        href: 'breadcrumbs_link_2',
                    },
                ],
            }
        },
        methods: {
            getColor (uuid) {
                if (uuid > 400) return 'red'
                else if (uuid > 200) return 'orange'
                else return 'green'
            },
            getStatusColor(status){
                switch (status) {
                    case  'Available':
                        return 'primary'
                    case  'Archive':
                        return 'secondary'

                }
            },
            gotoItemDetailPage(item){
                this.$router.push({ name: 'asset.items.detail', params: { id: '1' } })
            }
        },
    }
</script>

<style scoped>

</style>
