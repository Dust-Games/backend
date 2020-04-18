<template>
	<div class="card bg-dark text-light" style="width: 35vw;">
		<div class="card-header text-center">
			<h5>Registration</h5>
		</div>
		<div class="card-body">
			<form @submit.prevent="register">
			  <div class="form-group">
			    <label for="username">Username</label>
			    <input type="text" class="form-control input-dark" id="username" v-model="username">
			  </div>
			  <div class="form-group">
			    <label for="email">Email address</label>
			    <input type="email" class="form-control input-dark" id="email" v-model="email">
			  </div>
			  <div class="form-group">
			    <label for="password">Password</label>
			    <input type="password" class="form-control input-dark" id="password" v-model="password">
			  </div>

			  	<button type="submit" class="btn btn-outline-primary btn-block mt-4">
			  		Submit
				</button>
			</form>
			<div class="text-center m-2">
				or login with
			</div>
			<div class="d-flex justify-content-center">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-outline-primary"
					@click="oauthRegister('steam')">
						Steam
					</button>
					<button type="button" class="btn btn-outline-danger"
					@click="oauthRegister('twitch')">
						Twitch
					</button>
					<button type="button" class="btn btn-outline-info"
					@click="oauthRegister('battlenet')">
						Battle.net
					</button>
					<button type="button" class="btn btn-outline-secondary" 
					@click="oauthRegister('discord')">
						Discord
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				username: '',
				email: '',
				password: '',
			};
		},

		methods: {
			register() {
				
			},

			oauthRegister(provider) {
				axios.get(`/oauth/${provider}/register`)
				.then(resp => {
					window.location = resp.data.redirect_url;
				});
			}
		},
	}		
</script>	
