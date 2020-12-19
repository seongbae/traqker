<script>
    export default {
        props: ['attributes'],

        computed:{
            showReply: {
                get:function(){
                    return this.show;
                },
                set:function(newValue){
                    this.show = newValue;
                }

            }
        },

        data() {
            return {
                editing: false,
                body: this.attributes.body,
                show: true
            }
        },

        methods: {
            update() {
                axios.patch('/replies/'+this.attributes.id, {
                    body: this.body
                });

                this.editing = false;

                swal({
                    title: '',
                    text: 'Successfully updated.',
                    type: 'success',
                    timer: 3000,
                    showConfirmButton:false
                });
            },
            deleteItem() {
                if(confirm("Are you sure?")) {
                    axios.post('/replies/' + this.attributes.id, {
                        _method: 'delete'
                    });

                    this.show = false;
                }

                swal({
                    title: '',
                    text: 'Successfully deleted.',
                    type: 'success',
                    timer: 3000,
                    showConfirmButton:false
                });
            }


        }
    }
</script>
