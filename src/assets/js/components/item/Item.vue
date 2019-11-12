<template>
    <div>
        <breadcrumb></breadcrumb>
        <v-card flat class="mx-3" color="rgb(0, 0, 0, 0)">
            <v-card-actions>
                <h1 class="heading">Item Detail</h1>
                <v-spacer></v-spacer>
                <v-btn color="primary">
                    <v-icon>edit</v-icon>
                    Edit
                </v-btn>
            </v-card-actions>

        </v-card>
        <v-row
            class="ma-3"
        >
            <v-col
                md="3"
                cols="12"
            >
                <v-row>
                    <v-col>
                        <v-card
                        >
                            <v-img
                                class="white--text align-end"
                                height="200px"
                                src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                            >
                                <v-card-title>Item Name</v-card-title>
                            </v-img>

                            <v-card-subtitle class="pb-0">Number 10</v-card-subtitle>

                            <v-card-text class="text--primary">
                                <div>Whitehaven Beach</div>

                                <div>Whitsunday Island, Whitsunday Islands</div>
                            </v-card-text>

                            <v-divider class="mx-4"></v-divider>
                            <v-list-item>
                                <v-list-item-title>Building</v-list-item-title>

                                <v-list-item-subtitle class="text-right">
                                    Building A
                                </v-list-item-subtitle>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-title>Daily Price</v-list-item-title>

                                <v-list-item-subtitle class="text-right">
                                    S$ 2,323.26
                                </v-list-item-subtitle>
                            </v-list-item>
                        </v-card>
                    </v-col>
                </v-row>

            </v-col>

            <v-col>
                <v-card class="ma-3">
                    <v-tabs v-model="tab">
                        <v-tab>Booking</v-tab>
                        <v-tab>Floor Plan</v-tab>
                    </v-tabs>
                    <v-divider></v-divider>
                    <v-tabs-items v-model="tab">
                        <v-tab-item transition="fade-transition">
                            <v-card flat>
                                <v-card-text>Have no booking</v-card-text>
                            </v-card>
                        </v-tab-item>

                        <v-tab-item>
                            <v-card flat
                                class="mx-auto"
                            >

                                <v-img width="100%"
                                    src="https://images.squarespace-cdn.com/content/v1/5283ecd9e4b01ee65ae9d2be/1555602348164-SQ1A1KZVED3QNVR9890T/ke17ZwdGBToddI8pDm48kHzN0HRM2ydXDHS-3xCy7H17gQa3H78H3Y0txjaiv_0fDoOvxcdMmMKkDsyUqMSsMWxHk725yiiHCCLfrh8O1z4YTzHvnKhyp6Da-NYroOW3ZGjoBKy3azqku80C789l0pN2QtVPLaH3pRsOEMeFnQKxgv1ROvbjdMx-7VInpTQ7Mk0-N3gr7rCBmKVHYtvjbw/423-Elizabeth-Floorplan+%283%29.png"
                                ></v-img>

                                <v-card-text>
                                    Visit ten places on our planet that are undergoing the biggest changes today.
                                </v-card-text>

                                <v-card-actions>
                                    <v-btn
                                        text
                                        color="deep-purple accent-4"
                                    >
                                        Read
                                    </v-btn>
                                    <v-btn
                                        text
                                        color="deep-purple accent-4"
                                    >
                                        Bookmark
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn icon>
                                        <v-icon>mdi-heart</v-icon>
                                    </v-btn>
                                    <v-btn icon>
                                        <v-icon>mdi-share-variant</v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-card>

                        </v-tab-item>
                    </v-tabs-items>
                </v-card>

            </v-col>
        </v-row>
    </div>
</template>

<script>
    import breadcrumb from "@backoffice/js/components/breadcrumb";

    export default {
        components: {
            breadcrumb: breadcrumb
        },
        name: "AssetItem",
        data: () => ({
            tab: 0
        })
    }
</script>

<style scoped>

</style>
