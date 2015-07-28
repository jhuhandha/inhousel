@extends('layout.login')

@section('mainRecuperar')
	
		<div class="box" style="height:100% !important;">
			<div class="col-lg-12">
				@if(session()->has('status'))
					<p>@session('status')</p>
				@endif
				@if(session()->has('error'))
					<p>@session('error')</p>
				@endif	
				<hr>	
				<h2 class="intro-text text-center">{{ trans('front/password.title') }}</h2>
				<hr>
				<p>{{ trans('front/password.info') }}</p>		
				{!! Form::open(['url' => 'password/email', 'method' => 'post', 'role' => 'form']) !!}	

					<div class="row">

						{!! Form::text('email', null, ['class'=>'form-control']) !!}
						{!! Form::submit(trans('front/form.send'), ['col-lg-12']) !!}
						
					</div>

				{!! Form::close() !!}

			</div>
		</div>
	
@stop