<template>
    <div v-if="isVisible" class="spinner-border" id="site-spinner" role="status">
      <span class="sr-only">Loading...</span>
    </div>
</template>


<script>
    export default {
        name: "Loader",
        props: {
            isVisible: {type: Boolean, required: true},
            text: {type: String, required: false, default: ""},
        },
}
</script>


<style type="text/css">
    #site-spinner{
        position: fixed;
        z-index: 1060;
        top: 50%;
        left: 50%;
    }
</style>