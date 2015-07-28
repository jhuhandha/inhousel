<?php

namespace InHouse\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Request;
use Illuminate\Contracts\Auth\Guard;

use InHouse\Http\Controllers\Controller;
use InHouse\Repositories\UserRepository;
use InHouse\Services\MaxValueDelay;
use InHouse\Jobs\SendMail;

class AuthController extends Controller
{

	use AuthenticatesAndRegistersUsers;

	/**
	* Create a new authentication controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	* Handle a login request to the application.
	*
	* @param  App\Http\Requests\LoginRequest  $request
	* @param  App\Services\MaxValueDelay  $maxValueDelay
	* @param  Guard  $auth
	* @return Response
	*/
	public function postLogin(MaxValueDelay $maxValueDelay, Guard $auth)
	{
		//Se obtiene los datos que llegan del formulario
		$request = Request::all();

		//Se almacena en una variable el campo de email-username
		$logValue = $request['log'];

		//Se verifica que no se encuentre en cache - maxValueDelay se encuentra en servicios
		if($maxValueDelay->check($logValue))
		{
			return redirect('/auth/login')
			->with('error', trans('front/login.maxattempt'));
		}

		//Valida que valor ingresaron, email - username
		$logAccess = filter_var($logValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		//Se almacenan en un objeto
		$credentials = [
			$logAccess  => $logValue,
			'password'  => $request['password']
		];

		if(!$auth->validate($credentials)) {
			//Guarda en cache el intento erroneo
			$maxValueDelay->increment($logValue);

			return redirect('/auth/login')
			->with('error', trans('front/login.credentials'))
			->withInput($request);
		}

		//Obtiene el ultimo intento realizado
		$user = $auth->getLastAttempted();

		//Si el ultimo intento que realizo es 1 ingresa
		//Valida que si ya esta logueado no ingrese al loguin
		if($user->confirmed) {

			$c = isset($request['memory'])?true:false;
			$auth->login($user, $c);

			if(Request::session()->has('user_id'))	{
				Request::session()->forget('user_id');
			}

			return redirect('/home');
		}

		Request::session()->put('user_id', $user->id);

		return redirect('/auth/login')->with('error', trans('front/verify.again'));
	}


	/**
	* Handle a registration request for the application.
	*
	* @param  App\Http\Requests\RegisterRequest  $request
	* @param  App\Repositories\UserRepository $user_gestion
	* @return Response
	*/
	public function postRegister(UserRepository $user_gestion)
	{

		$user = $user_gestion->store(Request::all(), $confirmation_code = str_random(30));

		//$this->dispatch(new SendMail($user));

		return redirect('/')->with('ok', trans('front/verify.message'));
	}

	/**
	* Handle a confirmation request.
	*
	* @param  App\Repositories\UserRepository $user_gestion
	* @param  string  $confirmation_code
	* @return Response
	*/
	public function getConfirm(UserRepository $user_gestion, $confirmation_code)
	{
		$user = $user_gestion->confirm($confirmation_code);

		return redirect('/')->with('ok', trans('front/verify.success'));
	}

	/**
	* Handle a resend request.
	*
	* @param  App\Repositories\UserRepository $user_gestion
	* @param  Illuminate\Http\Request $request
	* @return Response
	*/
	public function getResend(UserRepository $user_gestion, Request $request)
	{
		if($request->session()->has('user_id'))	{
			$user = $user_gestion->getById($request->session()->get('user_id'));

			$this->dispatch(new SendMail($user));

			return redirect('/')->with('ok', trans('front/verify.resend'));
		}

		return redirect('/');
	}

}
