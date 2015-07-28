@extends('layout.login')

@section('mainLogin')
	@if(session()->has('error'))
		<p class="help-block">{{ session('error') }}</p>
	@endif
	{!! Form::open(['url' => 'auth/login', 'method' => 'post', 'role' => 'form']) !!}

        <h1>Login	</h1>
        <div>
			<input type="text" name="log" class="form-control" placeholder="Username">
        </div>
        <div>
			<input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div>
        	{!! Form::checkbox('memory','Recordarme') !!}
        </div>
        <div>
        	{!! Form::submit('Log in', ['class'=>'btn btn-default submit']) !!}
            <a class="reset_pass" href="/password/email">Lost your password?</a>
        </div>
        <div class="clearfix"></div>
        <div class="separator">

            <p class="change_link">New to site?
                <a href="#toregister" class="to_register"> Create Account </a>
            </p>
            <div class="clearfix"></div>
            <br />
            <div>
                <h1><i class="fa fa-home" style="font-size: 26px;"></i> InHouse</h1>

                <p>©2015 All Rights Reserved. InHouse. Privacy and Terms</p>
            </div>
        </div>
	{!! Form::close() !!}
@stop

@section('mainRegistro')

	{!! Form::open(['url' => 'auth/register', 'method' => 'post', 'role' => 'form']) !!}
        <h1>Create Account</h1>
        <div>
            {!! Form::text('username', null, ['class'=>'form-control', 'placeholder'=>'username']) !!}
        </div>
        <div>
            {!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'email']) !!}
        </div>
        <div>
            <input type="password" name="password"	class="form-control" placeholder='password' />
			<input type="password" name="password_confirmation"	class="form-control" placeholder='repetir password'/>
        </div>
        <div>
            {!! Form::submit(trans('front/form.send'), ['btn btn-success']) !!}
        </div>
        <div class="clearfix"></div>
        <div class="separator">

            <p class="change_link">Already a member ?
                <a href="#tologin" class="to_register"> Log in </a>
            </p>
            <div class="clearfix"></div>
            <br />
						<div>
                <h1><i class="fa fa-home" style="font-size: 26px;"></i> InHouse</h1>

                <p>©2015 All Rights Reserved. InHouse. Privacy and Terms</p>
            </div>
        </div>
    {!! Form::close() !!}
@stop
