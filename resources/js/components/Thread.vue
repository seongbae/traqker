<template>
    <div class="row">
        <div class="col-lg-2">
            <a class="btn btn-outline-primary  btn-sm w-100 mb-2" v-bind:href="'/teams/'+ thread.channel.slug + '/discuss'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to discussion</a>
            <hr>
            <button v-if="user.length > 0" type="submit" class="btn btn-sm w-100 mb-2" v-bind:class="{ 'btn-danger': user_subscribed, 'btn-primary': !user_subscribed}" @click="update_subscription">
                <span v-if="user_subscribed"><i class="fas fa-check"></i> Subscribed</span>
                <span v-if="!user_subscribed"><i class="fas fa-plus"></i> Subscribe</span>
            </button>
            <div class="small text-muted">Get notified on new replies</div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div v-if="editing" class="card-body">
                    <form method="POST" action="/threads">
                        <div class="form-group">
                            <label for="channel_id">Category</label>
                            <select name="channel_id" id="channel_id" class="form-control" v-model="channel" required>
                                <option value="">Choose One...</option>
                                <option v-for="ch in channelList"  v-bind:value="ch.id"> {{ ch.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" v-model="title" required>
                        </div>

                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea name="body" id="body" class="form-control" rows="8" v-model="body" required></textarea>
                        </div>
                        <button type="button" class="btn btn-primary btn-xs" @click="update()">Update</button>
                        <button class="btn  btn-xs" @click="editing = false">Cancel</button>
                    </form>

                </div>
                <div v-else>

                    <div class="card-body" style="white-space: pre-line;">
                        <div class="mb-4">
                            <div class="float-left">
                                <img :src="userImage" class="rounded-circle mr-2" width="40px">
                                <a href="#" v-b-popover.hover.right="thread.user.email" :title="thread.user.name">{{ thread.user.name }}</a> posted {{ thread.created_at_human_readable }} in <a :href="channelURL">{{ thread.channel.name}}</a>
                            </div>
                            <div v-if="user.id == thread.user.id" class="float-right">
                                <div class="dropdown show">
                                    <a href="#" type="text" class="text-muted" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </a>
                                    <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" id="threadEditBtn" @click="onEditClick()">Edit</a>
                                        <a class="dropdown-item" href="#" v-on:click="deleteItem">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                        <div class="mt-4">
                            <p><strong><span v-text="form.title"></span></strong></p>
                            <span v-text="form.body"></span>
                        </div>
                        <form :action="thread.path" method="post" id="deleteForm">
                            <input type="hidden" name="_token" :value="csrf">
                            <input type="hidden" name="_method" value="delete" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['thread', 'channels', 'user','subscribed', 'imagefield', 'imagepath', 'defaultimage'],

        data() {
            return {
                editing: false,
                title: this.thread.title,
                body: this.thread.body,
                channel: this.thread.channel_id,
                channelList: this.channels,
                form: {
                    channel: this.thread.channel_id,
                    title: this.thread.title,
                    body: this.thread.body
                },
                user_subscribed: this.subscribed,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')

            }
        },

        computed: {
            userImage: function () {
                if (this.imagefield == "")
                    return this.defaultimage;
                else
                    return this.imagepath+this.thread.user[this.imagefield];
            },
            channelURL: function() {
                return '/discuss/'+this.thread.channel.slug;
            },
        },

        created() {
            console.log('imagefield: '+this.imagefield);
        },

        methods: {
            update() {
                axios.patch('/discuss/'+this.thread.channel.slug+'/'+this.thread.slug, {
                    channel_id: this.channel,
                    title: this.title,
                    body: this.body
                }).then(() => {
                    this.editing = false;

                    this.form.body = this.body;
                    this.form.title = this.title;
                    this.channel = this.form.channel_id;

                    swal({
                        title: '',
                        text: 'Successfully updated.',
                        type: 'success',
                        timer: 3000,
                        showConfirmButton:false
                    }).catch(swal.noop);;
                })
            },

            deleteItem : function(){
                if(confirm("Are you sure?")) {
                    document.getElementById('deleteForm').submit();
                }
            },

            update_subscription: function (event) {
                this.user_subscribed = !this.user_subscribed;
                axios.post('/discuss/subscribe/thread/'+this.thread.id, {
                    thread_id: this.thread.id,
                    user_id: this.user.id,
                    type: 'thread',
                    subscribe: this.user_subscribed
                }).then(res => {
                    console.log(res.data);
                }).catch(e => {
                    console.log(e);
                });
            },

            onEditClick(){
                this.editing = true;
            }


        }
    }
</script>
