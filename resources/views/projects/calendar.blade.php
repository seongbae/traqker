<div id='calendar'></div>

@push('scripts')
    <script>
        $(document).ready(function () {
            //var SITEURL = "{{url('/')}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
        function displayMessage(message) {
            $(".response").html(""+message+"");
            setInterval(function() { $(".success").fadeOut(); }, 1000);
        }
    </script>
@endpush
