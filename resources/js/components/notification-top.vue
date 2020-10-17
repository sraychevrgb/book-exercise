<template>
    <transition name="fade">
        <div v-if="visible" class="my-top-notification-bar alert alert-dismissible" :class="status">
            <button type="button" class="close" @click="hideAlert();">&times;</button>
            <strong>{{messageType}}!</strong> {{message}}
        </div>
    </transition>
</template>


<script>
    export default {
        props: [],
        data () {
            return {
                visible: false,
                message: "",
                messageType: "",
                status: '',
                time_notification_is_visible: 3000 //3 seconds
            }
        },
        mounted() {
            this.$root.$on("showDangerTopMessage", (message) => { 
                this.messageType = "Error";
                this.message = message;
                this.visible = true;
                this.status = 'alert-danger';

                this.hideAfter(this.time_notification_is_visible);
            });
            this.$root.$on("showSuccessNotificationTopMessage", (message) => {
                this.messageType = "Success";
                this.message = message;
                this.visible = true;
                this.status = 'alert-success';

                this.hideAfter(this.time_notification_is_visible);
            });
        },
        methods: {
            hideAlert: function(){  
                this.visible = false;
            },
            hideAfter: function(seconds){
                setTimeout(()=>{
                    this.hideAlert();
                },seconds);
            }
        }
    }
</script>

<style type="text/css">
    .my-top-notification-bar{
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 10;
        border-radius: 0;
    }
    .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
    }
</style>