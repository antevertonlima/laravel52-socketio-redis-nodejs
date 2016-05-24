@extends('layouts.app')

@section('content')
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>

<style type="text/css">
    #messages{
        border: 1px solid black;
        height: 300px;
        margin-bottom: 8px;
        overflow: scroll;
        padding: 5px;
    }
</style>

<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Chat Message Module</div>

                <div class="panel-body">
                
                <div class="row">
                    <div class="col-sm-12" >
                      <div id="messages" ></div>
                    </div>
                    <div class="col-sm-12" >
                            <form id="sendmessage" action="sendmessage" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="user" value="{{ Auth::user()->name }}" >
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control msg" />
                                    <span class="input-group-btn">
                                        <input type="submit" value="Send" class="btn btn-success send-msg">
                                    </span>
                                    
                                </div>
                            </form>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var user =  '{!!Auth::user()->name!!}';
    var socketConnect = function(room) {
      return io.connect('http://127.0.0.1:8890', { query: "channel=" + room +"&usuario=" + user
       });
    };
    
    //var socket = io.connect('http://127.0.0.1:8890');
    
    //Pode ser qualquer identificador
    var socket = socketConnect('teste');

    socket.on('message', function (data) {
        data = jQuery.parseJSON(data);
        console.log(data.user);
        $( "#messages" ).append( "<strong>"+data.user+":</strong><p>"+data.message+"</p>" );
      });

    //$(".send-msg").click(function(e){
    $('#sendmessage').submit(function(e){
        e.preventDefault();
        var token = $("input[name='_token']").val();
        var user = $("input[name='user']").val();
        var msg = $(".msg").val();

        if(msg != ''){
            $.ajax({
                type: "POST",
                url: '{!! URL::to("sendmessage") !!}',
                dataType: "json",
                data: {'_token':token,'message':msg,'user':user},
                success:function(data){
                    console.log(data);
                    $(".msg").val('');
                }
            });
        }else{
            alert("Please Add Message.");
        }
        return false;
    });
  
</script>
@endsection
