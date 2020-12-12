@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

@include('projects.menus')

<div class="card">
    <div class="card-body">
        <a class="btn btn-primary btn-sm mb-2" href="#" id="addBoard"><i class="fas fa-plus"></i> Add Board</a>
        <div id="board"></div>
    </div>
</div>


@endsection

@push('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        function displayMessage(message) {
            $(".response").html(""+message+"");
            setInterval(function() { $(".success").fadeOut(); }, 1000);
        }

        var kanban = new jKanban({
            element          : '#board',                                           // selector of the kanban container
            gutter           : '5px',                                       // gutter of the board
            widthBoard       : '300px',                                      // width of the board
            responsivePercentage: false,                                    // if it is true I use percentage in the width of the boards and it is not necessary gutter and widthBoard
            dragItems        : true,                                         // if false, all items are not draggable
            boards           : @json($boards),                                           // json of boards
            dragBoards       : true,                                         // the boards are draggable, if false only item can be dragged
            addItemButton    : true,                                        // add a button to board for easy item creation
            buttonContent    : '+',                                          // text or html content of the board button
            itemHandleOptions: {
                enabled             : false,                                 // if board item handle is enabled or not
                handleClass         : "item_handle",                         // css class for your custom item handle
                customCssHandler    : "drag_handler",                        // when customHandler is undefined, jKanban will use this property to set main handler class
                customCssIconHandler: "drag_handler_icon",                   // when customHandler is undefined, jKanban will use this property to set main icon handler class. If you want, you can use font icon libraries here
                customHandler       : "<span class='item_handle'>+</span> %s"// your entirely customized handler. Use %s to position item title
            },
            click            : function (el) {
                window.location.href = "/tasks/"+el.getAttribute('data-eid');
            },                             // callback when any board's item are clicked
            dragEl           : function (el, source) {},                     // callback when any board's item are dragged
            dragendEl        : function (el) {},                             // callback when any board's item stop drag
            dropEl           : function (el, target, source, sibling) {

                var taskIds = [].map.call(target.children, function (e) {
                    return e.getAttribute('data-eid')
                })

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        section_id: target.parentElement.getAttribute('data-id'),
                        orders: taskIds
                    },
                    type: 'PUT',
                    url: '/tasks/'+el.getAttribute('data-eid')
                });
            },    // callback when any board's item drop in a board
            dragBoard        : function (el, source) {

            },                     // callback when any board stop drag
            dragendBoard     : function (el) {
                var boardIds = [].map.call(el.parentNode.children, function (e) {
                    return e.getAttribute('data-id')
                })

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {

                        project_id: {{$project->id}},
                        orders: boardIds
                    },
                    type: 'POST',
                    url: '/sections/orders'
                });
            },                             // callback when any board stop drag
            buttonClick      : function(el, boardId) {
                var formItem = document.createElement("form");
                formItem.setAttribute("class", "itemform");
                formItem.innerHTML =
                    '<div class="form-group"><textarea class="form-control" rows="2" autofocus></textarea></div><div class="form-group"><button type="submit" class="btn btn-primary btn-xs pull-right">Submit</button><button type="button" id="CancelBtn" class="btn btn-default btn-xs pull-right">Cancel</button></div>';

                kanban.addForm(boardId, formItem);
                formItem.addEventListener("submit", function(e) {
                    e.preventDefault();
                    var text = e.target[0].value;
                    kanban.addElement(boardId, {
                        title: text,
                        class: "traqker-kanban-item"
                    });
                    formItem.parentNode.removeChild(formItem);



                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            project_id: {{$project->id}},
                            section_id: boardId,
                            name: text
                        },
                        type: 'POST',
                        url: '/tasks'
                    });
                });
                document.getElementById("CancelBtn").onclick = function() {
                    formItem.parentNode.removeChild(formItem);
                };
            }                      // callback when the board's button is clicked
        })

        var addBoardDefault = document.getElementById("addBoard");
        addBoardDefault.addEventListener("click", function() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    project_id: {{$project->id}},
                    name: "Default"
                },
                type: 'POST',
                url: '/sections',
                success: function(data) {
                    kanban.addBoards([
                        {
                            id: data.id,
                            title: "Default",

                            item: [

                            ]
                        }
                    ]);
                }
            });
        });
    </script>
@endpush
