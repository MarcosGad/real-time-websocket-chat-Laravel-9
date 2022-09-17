@extends('main')

@section('content')

@if(Auth::user()->user_image != '')
<a class="nav-link" href="#"><b>Welcome <img src="{{ asset('images/' . Auth::user()->user_image ) }}" width="35" class="rounded-circle" />&nbsp; {{ Auth::user()->name }}</b></a>
@else
<a class="nav-link" href="#"><b>Welcome <img src="{{ asset('images/no-image.jpg') }}" width="35" class="rounded-circle" />&nbsp;{{ Auth::user()->name }}</b></a>
@endif

<div class="row">
	<div class="col-md-3">
		<div class="card">
			<div class="card-header"><b>Connected User</b></div>
			<div class="card-body">
				
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col col-md-6"><b>Chat Area</b></div>
					<div class="col col-md-6"></div>
				</div>
			</div>
			<div class="card-body">
				
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card" style="height:255px; overflow-y: scroll;">
			<div class="card-header">
				<input type="text" class="form-control" placeholder="Search User..." autocomplete="off" onkeyup="search_user('{{ Auth::id() }}', this.value);" />
			</div>
			<div class="card-body">
				<div id="search_people_area" class="mt-3"></div>
			</div>
		</div>
		<br />
		<div class="card" style="height:255px; overflow-y: scroll;">
			<div class="card-header"><b>Notification</b></div>
			<div class="card-body">
				<ul class="list-group">
					
				</ul>
			</div>
		</div>
	</div>
</div>

<style>

#chat_area
{
	min-height: 500px;
	/*overflow-y: scroll*/;
}

#chat_history
{
	min-height: 500px; 
	max-height: 500px; 
	overflow-y: scroll; 
	margin-bottom:16px; 
	background-color: #ece5dd;
	padding: 16px;
}

#user_list
{
	min-height: 500px; 
	max-height: 500px; 
	overflow-y: scroll;
}
</style>

@endsection('content')

<script>

var conn = new WebSocket('ws://127.0.0.1:8090/?token={{ auth()->user()->token }}');

var from_user_id = "{{ Auth::user()->id }}";

var to_user_id = "";

conn.onopen = function(e){

	console.log("Done");

	load_unconnected_user(from_user_id);

};

conn.onmessage = function(e){

	var data = JSON.parse(e.data);

	if(data.response_load_unconnected_user|| data.response_search_user)
	{
		var html = '';

		if(data.data.length > 0)
		{
			html += '<ul class="list-group">';

			for(var count = 0; count < data.data.length; count++)
			{
				var user_image = '';

				if(data.data[count].user_image != null)
				{
					user_image = `<img src={{ asset("images/") }}/`+data.data[count].user_image+` width="40" class="rounded-circle" />`;
				}
				else
				{
					user_image = `<img src="{{ asset('images/no-image.jpg') }}" width="40" class="rounded-circle" />`
				}

				html += `
				<li class="list-group-item">
					<div class="row">
						<div class="col col-9">`+user_image+`&nbsp;`+data.data[count].name+`</div>
						<div class="col col-3">
							<button type="button" name="send_request" class="btn btn-primary btn-sm float-end" onclick="send_request(this, `+from_user_id+`, `+data.data[count].id+`)"><i class="fas fa-paper-plane"></i></button>
						</div>
					</div>
				</li>
				`;
			}

			html += '</ul>';
		}
		else
		{
			html = 'No User Found';
		}

		document.getElementById('search_people_area').innerHTML = html;
	}

	if(data.response_from_user_chat_request)
	{
		//search_user(from_user_id, document.getElementById('search_people').value);
	}

};

function load_unconnected_user(from_user_id)
{
	var data = {
		from_user_id : from_user_id,
		type : 'request_load_unconnected_user'
	};

	conn.send(JSON.stringify(data));
}

function search_user(from_user_id, search_query)
{
	if(search_query.length > 0)
	{
		var data = {
			from_user_id : from_user_id,
			search_query : search_query,
			type : 'request_search_user'
		};

		conn.send(JSON.stringify(data));
	}
	else
	{
		load_unconnected_user(from_user_id);
	}
}

function send_request(element, from_user_id, to_user_id)
{
	var data = {
		from_user_id : from_user_id,
		to_user_id : to_user_id,
		type : 'request_chat_user'
	};

	element.disabled = true;

	conn.send(JSON.stringify(data));
}


</script>
