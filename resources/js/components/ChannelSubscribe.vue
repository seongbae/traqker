<template>
    <div>
        <button type="submit" class="btn btn-sm w-100 mb-2" v-bind:class="{ 'btn-danger': user_subscribed, 'btn-primary': !user_subscribed}" @click="update_subscription">
            <span v-if="user_subscribed"><i class="fas fa-check"></i> Subscribed</span>
            <span v-if="!user_subscribed"><i class="fas fa-plus"></i> Subscribe to Channel</span>
        </button>
        <div class="small text-muted">
            <span v-if="user_subscribed">You are receiving notifications.</span>
            <span v-if="!user_subscribed">Subscribe to receive updates.</span>

        </div>
    </div>
</template>

<script>
    export default {
        props: ['user', 'channel', 'subscribed'],
        mounted() {
        },
        data() {
            return {
                user_subscribed: this.subscribed
            };
        },
        computed: {
            buttonClass: function() {
                return "btn btn-primary btn-sm"
            }
        },
        methods: {
            update_subscription: function (event) {
                this.user_subscribed = !this.user_subscribed;
                axios.post('/discuss/subscribe/channel/'+this.channel.id, {channel_id: this.channel.id, user_id: this.user.id, subscribe: this.user_subscribed }).then(res => {
                    console.log(res.data);
                }).catch(e => {
                    console.log(e);
                });
            }
        }
    }

</script>
