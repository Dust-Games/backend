<template>
	<div class="card bg-dark text-light" style="width: 80vw; height: 80vh;">
		<div class="card-header text-center">
			<h5>Add coins</h5>
		</div>
		<div class="card-body">
			<div class="container">
				<div class="row">
					<div class="col-6">
						<div class="card bg-dark text-light">				
							<div class="card-header text-center">
								<h5>Add coins</h5>
							</div>
							<div class="card-body">							
								<form @submit.prevent="">
									<div class="form-group">
									  	<label for="account_id">Account ID</label>
									  	<input type="text" class="form-control input-dark" 
									  	id="account_id" v-model="account_id">
									</div>

									<div class="form-group">
									  	<label for="dust_coins_num">Dust coins</label>
									  	<input type="number" class="form-control input-dark" 
									  	id="dust_coins_num" v-model="dust_coins_num">
									</div>

									<div class="form-group">
									  	<button @click="setCoins(account_id, dust_coins_num)" 
									  	class="btn btn-success btn-block">
									  		Set coins
									  	</button>
									</div>
									<div class="form-group">
									  	<button @click="addCoins(account_id, dust_coins_num)"
									  	class="btn btn-primary btn-block">
									  		Add coins
									  	</button>
									</div>
									<div class="form-group">
									  	<button @click="reduceCoins(account_id, dust_coins_num)"
									  	class="btn btn-danger btn-block">
									  		Reduce coins
									  	</button>
									</div>
								</form>					
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card bg-dark text-light">		
							<div class="card-header text-center">
								<h5>History</h5>
							</div>
							<div class="card-body">
								<table class="table table-dark table-sm" v-if="history.length !== 0">
									<thead>
										<tr>
											<th scope="col">ID</th>
											<th scope="col">Total coins</th>
										</tr>			
									</thead>
									<tbody>
										<tr v-for="row in history">
											<th scope="row">{{ row.account_id }}</th>
											<td>{{ row.billing.dust_coins_num }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="card bg-dark text-light">
							<div class="card-header">
								<h5>Log</h5>
							</div>
							<div class="card-body">
								{{ this.log.message }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
</template>

<script>
	export default {
		data() {
			return {
				account_id: '',
				dust_coins_num: '',
				history: [],
				log: {},
			};
		},

		methods: {
			async addCoins(account_id, dust_coins_num) {
				let resp = await axios.put('https://bot.dust.games/users/billing/add-coins', {
					account_id: account_id,
					platform: 2,
					dust_coins_num: dust_coins_num,
				}, this.getHeaders());

				this.log = resp.data;

				let resp2 = await axios.post('https://bot.dust.games/users/billing', {
					account_id: account_id,
					platform: 2,
				}, this.getHeaders());
				
				this.history.push(resp2.data);
			},

			async reduceCoins(account_id, dust_coins_num) {
				let resp = await axios.put('https://bot.dust.games/users/billing/reduce-coins', {
					account_id: account_id,
					platform: 2,
					dust_coins_num: dust_coins_num,
				}, this.getHeaders());

				this.log = resp.data;

				let resp2 = await axios.post('https://bot.dust.games/users/billing', {
					account_id: account_id,
					platform: 2,
				}, this.getHeaders());
				
				this.history.push(resp2.data);
			},

			async setCoins(account_id, dust_coins_num) {
				let resp = await axios.put('https://bot.dust.games/users/billing/set-coins', {
					account_id: account_id,
					platform: 2,
					dust_coins_num: dust_coins_num,
				}, this.getHeaders());

				this.log = resp.data;

				let resp2 = await axios.post('https://bot.dust.games/users/billing', {
					account_id: account_id,
					platform: 2,
				}, this.getHeaders());
				
				this.history.push(resp2.data);
			},

			async getInfo(account_id) {
				let resp2 = await axios.post('https://bot.dust.games/users/billing', {
					account_id: account_id,
					platform: 2,
				}, this.getHeaders());
				
				this.history.push(resp2.data);				
			},

			getHeaders() {
				return {
					headers: {
						Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImQ1YjhhODgyLWI4ZmEtNDdmOC05NGI5LTdiYjA4Yzg4N2M4YSJ9.eyJpc3MiOiJodHRwOlwvXC9kdXN0LmdhbWUiLCJhdWQiOiJodHRwOlwvXC9kdXN0LmdhbWUiLCJqdGkiOiJkNWI4YTg4Mi1iOGZhLTQ3ZjgtOTRiOS03YmIwOGM4ODdjOGEiLCJpYXQiOjE1ODgwODc1NTksIm5iZiI6MTU4ODA4NzU1OSwiZXhwIjoxNTg4MTczOTU5LCJzdWIiOiJmOTExNjY4OS1lZGI1LTQzNTMtYmUzNC1iZGQyNDgxNmViYWIifQ.blRuPkiy3CYsKmjUvj_w1LPyuF6OI3snS-99r-aqtQWTLiMAaVCJY8QuQx-DUJZVsXikRLKcDmU1_RleQdoorg'
					}
				};	
			}
		}
	}
</script>