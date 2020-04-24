@component('vendor.notifications.email')
	@slot('header')
		<tr>
			<td class="header">
				<a href="{{ config('app.url') }}" class="logo">
				<img src="http://api.dust.game/logo.svg" class="logo" alt="Laravel Logo">

				</a>
			</td>
		</tr>		
	@endslot
	<h1>
		{{ __('email.greetings', ['name' => $user->getUsername()]) }}
	</h1>
	<div class="">
		{{ __('email.verification.start') }}
	</div>
		<div class="action">
			<a class="button button-blue" href="{{ $url }}">
				{{ __('email.verification.button') }}
			</a>
		</div>
	<div class="">
		{{ __('email.verification.end') }}
	</div>
	@slot('footer')
	yyyyyyyeeeeeeeeeeaaaaaaaaaaaaaaaaaaaahhhhhhhhhhhhhhhhhhhhhh
	@endslot
@endcomponent