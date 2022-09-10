@extends('main')

@section('content')

<div class="card">
	<div class="card-header">Dashboard</div>
	<div class="card-body">

		@if(Auth::user()->user_image != '')
		<a class="nav-link" href="#"><b>Welcome <img src="{{ asset('images/' . Auth::user()->user_image ) }}" width="35" class="rounded-circle" />&nbsp; {{ Auth::user()->name }}</b></a>
		@else
		<a class="nav-link" href="#"><b>Welcome <img src="{{ asset('images/no-image.jpg') }}" width="35" class="rounded-circle" />&nbsp;{{ Auth::user()->name }}</b></a>
		@endif
		
		
	</div>
</div>

@endsection('content')

<script>

	var conn = new WebSocket('ws://127.0.0.1:8090/');
	
	conn.onopen = function(e){	
		console.log("Connection established!");
	};
	
	conn.onmessage = function(e){
	
	};
	
</script>