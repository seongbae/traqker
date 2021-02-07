@extends('canvas::admin.layouts.app')

@section('title', __('Tasks'))
@section('content')



    <div class="container">
        <div class="row">
            <div class="col-md-10">

                <div class="card">
                    <div class="card-body">

                        {!! $html->table() !!}
                        {!! $html->scripts() !!}

                    </div>
                    <div class="card-footer px-3">
                        <a href="/tasks" class="text-secondary m-1"><i class="far fa-square" title="Active Tasks"></i></a>
                        <a href="/tasks/completed" class="text-secondary m-1"><i class="far fa-check-square" title="Completed Tasks"></i></a>
                        <a href="/tasks/deleted" class="text-secondary m-1"><i class="far fa-trash-alt " title="Deleted Tasks"></i></a>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div id='slider' style="background-color:#E6E6E6">
        <div class="card">
            <div class="card-header">
                <span id="task-name"></span>
            </div>
            <div class="card-body">


            </div>
        </div>
    </div>

{{--    <div class="cd-panel__container">--}}
{{--        <div class="cd-panel__content">--}}
{{--            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam magnam accusamus obcaecati nisi eveniet quo veniam quibusdam veritatis autem accusantium doloribus nam mollitia maxime explicabo nemo quae aspernatur impedit cupiditate dicta molestias consectetur, sint reprehenderit maiores. Tempora, exercitationem, voluptate. Sapiente modi officiis nulla sed ullam, amet placeat, illum necessitatibus, eveniet dolorum et maiores earum tempora, quas iste perspiciatis quibusdam vero accusamus veritatis. Recusandae sunt, repellat incidunt impedit tempore iusto, nostrum eaque necessitatibus sint eos omnis! Beatae, itaque, in. Vel reiciendis consequatur saepe soluta itaque aliquam praesentium, neque tempora. Voluptatibus sit, totam rerum quo ex nemo pariatur tempora voluptatem est repudiandae iusto, architecto perferendis sequi, asperiores dolores doloremque odit. Libero, ipsum fuga repellat quae numquam cumque nobis ipsa voluptates pariatur, a rerum aspernatur aliquid maxime magnam vero dolorum omnis neque fugit laboriosam eveniet veniam explicabo, similique reprehenderit at. Iusto totam vitae blanditiis. Culpa, earum modi rerum velit voluptatum voluptatibus debitis, architecto aperiam vero tempora ratione sint ullam voluptas non! Odit sequi ipsa, voluptatem ratione illo ullam quaerat qui, vel dolorum eligendi similique inventore quisquam perferendis reprehenderit quos officia! Maxime aliquam, soluta reiciendis beatae quisquam. Alias porro facilis obcaecati et id, corporis accusamus? Ab porro fuga consequatur quisquam illo quae quas tenetur.</p>--}}

{{--            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Neque similique, at excepturi adipisci repellat ut veritatis officia, saepe nemo soluta modi ducimus velit quam minus quis reiciendis culpa ullam quibusdam eveniet. Dolorum alias ducimus, ad, vitae delectus eligendi, possimus magni ipsam repudiandae iusto placeat repellat omnis veritatis adipisci aliquam hic ullam facere voluptatibus ratione laudantium perferendis quos ut. Beatae expedita, itaque assumenda libero voluptatem adipisci maiores voluptas accusantium, blanditiis saepe culpa laborum iusto maxime quae aperiam fugiat odit consequatur soluta hic. Sed quasi beatae quia repellendus, adipisci facilis ipsa vel, aperiam, consequatur eaque mollitia quaerat. Iusto fugit inventore eveniet velit.</p>--}}

{{--            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Neque similique, at excepturi adipisci repellat ut veritatis officia, saepe nemo soluta modi ducimus velit quam minus quis reiciendis culpa ullam quibusdam eveniet. Dolorum alias ducimus, ad, vitae delectus eligendi, possimus magni ipsam repudiandae iusto placeat repellat omnis veritatis adipisci aliquam hic ullam facere voluptatibus ratione laudantium perferendis quos ut. Beatae expedita, itaque assumenda libero voluptatem adipisci maiores voluptas accusantium, blanditiis saepe culpa laborum iusto maxime quae aperiam fugiat odit consequatur soluta hic. Sed quasi beatae quia repellendus, adipisci facilis ipsa vel, aperiam, consequatur eaque mollitia quaerat. Iusto fugit inventore eveniet velit.</p>--}}

{{--            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam magnam accusamus obcaecati nisi eveniet quo veniam quibusdam veritatis autem accusantium doloribus nam mollitia maxime explicabo nemo quae aspernatur impedit cupiditate dicta molestias consectetur, sint reprehenderit maiores. Tempora, exercitationem, voluptate. Sapiente modi officiis nulla sed ullam, amet placeat, illum necessitatibus, eveniet dolorum et maiores earum tempora, quas iste perspiciatis quibusdam vero accusamus veritatis. Recusandae sunt, repellat incidunt impedit tempore iusto, nostrum eaque necessitatibus sint eos omnis! Beatae, itaque, in. Vel reiciendis consequatur saepe soluta itaque aliquam praesentium, neque tempora. Voluptatibus sit, totam rerum quo ex nemo pariatur tempora voluptatem est repudiandae iusto, architecto perferendis sequi, asperiores dolores doloremque odit. Libero, ipsum fuga repellat quae numquam cumque nobis ipsa voluptates pariatur, a rerum aspernatur aliquid maxime magnam vero dolorum omnis neque fugit laboriosam eveniet veniam explicabo, similique reprehenderit at. Iusto totam vitae blanditiis. Culpa, earum modi rerum velit voluptatum voluptatibus debitis, architecto aperiam vero tempora ratione sint ullam voluptas non! Odit sequi ipsa, voluptatem ratione illo ullam quaerat qui, vel dolorum eligendi similique inventore quisquam perferendis reprehenderit quos officia! Maxime aliquam, soluta reiciendis beatae quisquam. Alias porro facilis obcaecati et id, corporis accusamus? Ab porro fuga consequatur quisquam illo quae quas tenetur.</p>--}}
{{--        </div> <!-- cd-panel__content -->--}}
{{--    </div> <!-- cd-panel__container -->--}}




@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.slidereveal.min.js') }}"></script>

    <script type="text/javascript">

        // const button = document.querySelector('.toggle');
       // const pane = document.querySelector('.pane');
        //
        // button.addEventListener('onclick', () => {
        //     pane.classList.toggle('open');
        // });

        $(document).ready(function(){

            var slider = $('#slider').slideReveal({
                trigger: $(".trigger"),
                position: "right",
                push: false,
                overlay: true,
                width: 500,
                speed: 700,
                zindex: 4000
            });

            $('#tasks-table').on('click', '.trigger', function(){
                var id = this.dataset['id'];
                var name = "Task name 1";

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    url: '/tasks/'+id,
                    success:function(data) {
                        $('#task-name').text(data.name);
                        $('#task-description').text(data.description);
                    }
                });



                slider.slideReveal("show")
            });
        });

        // const button = document.querySelector('.toggle');
        // const pane = document.querySelector('.pane');
        //
        // button.onclick = pane.classList.toggle('open');

        // button.addEventListener('click', () => {
        //     pane.classList.toggle('open');
        // });

        // $(".toggle").click(function() {
        //     alert('hello');
        //     pane.classList.toggle('open');
        // });
        // var slideout = new Slideout({
        //     'panel': document.getElementById('panel'),
        //     'menu': document.getElementById('menu'),
        //     'padding': 256,
        //     'tolerance': 70,
        //     'side': 'right'
        // });
        //
        // // Toggle button
        // document.querySelector('.toggle-button').addEventListener('click', function() {
        //     slideout.toggle();
        // });
    </script>
@endpush
