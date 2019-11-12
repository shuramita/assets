<template>
    <div>
        <breadcrumb></breadcrumb>
        <v-card flat color="rgb(0, 0, 0, 0)" class="mx-3">
            <v-card-actions>
                <h1 class="heading">Settings</h1>
            </v-card-actions>

        </v-card>
    </div>

</template>

<script>
    import breadcrumb from "@backoffice/js/components/breadcrumb";
    export default {
        name: "Setting",
        components:{
            breadcrumb: breadcrumb
        }
    }
</script>

<style scoped>

</style>
