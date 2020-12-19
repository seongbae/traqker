@extends('canvas::admin.layouts.app')

@section('title', __('Team'))
@section('content')

    @include('teams.menus')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="dp"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="/js/daypilot-all.min.js?v=2018.2.232" type="text/javascript"></script>

<script>
    $(document).ready(() => {
      let url = location.href.replace(/\/$/, "");

      if (location.hash) {
        const hash = url.split("#");
        $('#myTab a[href="#'+hash[1]+'"]').tab("show");
        url = location.href.replace(/\/#/, "#");
        history.replaceState(null, null, url);
        setTimeout(() => {
          $(window).scrollTop(0);
        }, 400);
      }

      $('a[data-toggle="tab"]').on("click", function() {
        let newUrl;
        const hash = $(this).attr("href");
        if(hash == "#home") {
          newUrl = url.split("#")[0];
        } else {
          newUrl = url.split("#")[0] + hash;
        }
        newUrl += "/";
        history.replaceState(null, null, newUrl);
      });
    });

    $(document).ready(function(){
        $(document).on("click", "#editMember", function () {
          $('#updateMember #user_id').val($(this).data('id'));
          $('#updateMember #email').val($(this).data('email'));
          $('#updateMember #access').val($(this).data('access'));
          $('#updateMember #title').val($(this).data('title'));
          $('#modalLabel').text('Edit Member');
          $('#updateMember').modal('show');
        });

        $('#updateMember').on('hidden.bs.modal', function () {
            $('#modalLabel').text('Add Member');
        });
    });
</script>
<script type="text/javascript">

    var dp = new DayPilot.Calendar("dp");

    // view
    dp.startDate = "2020-05-03";
    dp.viewType = "Week";
    dp.heightSpec = "Full";
    dp.headerDateFormat = "dddd";
    dp.init();

    $.ajax({
         url: '/team/{{$team->id}}/availability/',
         type: 'get',
         dataType: 'json',
         success: function(response){

            if(response['data'] != null){
               var members = response['data'].length;

               if(members > 0){
                    var colorCodes = ['#1066a8','#a85210','#5210a8','#66a810','#10a878','#2c10a8','#a8101a','#a89e10'];
                    var colorCount = 0;
                     for(var i=0; i<members; i++) {

                        var availabilitySet = response['data'][i].availability;
                        //console.log(availabilitySet);

                        for(var j=0; j<availabilitySet.length; j++) {
                                //console.log(availabilitySet[j]);
                                var dt = "2020-05-03T";

                                switch(availabilitySet[j].dayofweek) {
                                    case 0:
                                      // code
                                      break;
                                    case 1:
                                      dt = "2020-05-04T";
                                      break;
                                    case 2:
                                      dt = "2020-05-05T";
                                      break;
                                    case 3:
                                      dt = "2020-05-06T";
                                      break;
                                    case 4:
                                      dt = "2020-05-07T";
                                      break;
                                    case 5:
                                      dt = "2020-05-08T";
                                      break;
                                    case 6:
                                      dt = "2020-05-09T";
                                      break;
                                    default:
                                        break;
                              }
                              // code block
                                var e = new DayPilot.Event({
                                    start: new DayPilot.Date(dt+availabilitySet[j].start),
                                    end: new DayPilot.Date(dt+availabilitySet[j].end),
                                    id: availabilitySet[j].id,
                                    text: response['data'][i].name,
                                    barColor: colorCodes[colorCount]
                                });
                                dp.events.add(e);
                          }
                          colorCount = colorCount+1;
                          if (colorCount>7)
                            colorCount = 0;
                        }
                  }
                }
              }
            });

</script>
@endpush
