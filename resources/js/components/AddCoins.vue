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
								<form @submit.prevent="addCoins(account_id, dust_coins_num)">
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
									  	<button type="submit" class="btn btn-danger btn-block">
									  		Add coins
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

			getHeaders() {
				return {
					headers: {
						Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNmMTE2Nzc3LWViMzEtNDA0Yy1hNWZmLTFjMWQyYmU3ZmIxMCJ9.eyJpc3MiOiJodHRwOlwvXC9kdXN0LmdhbWVzIiwiYXVkIjoiaHR0cDpcL1wvZHVzdC5nYW1lcyIsImp0aSI6ImNmMTE2Nzc3LWViMzEtNDA0Yy1hNWZmLTFjMWQyYmU3ZmIxMCIsImlhdCI6MTU4Nzk4NjAxMiwibmJmIjoxNTg3OTg2MDEyLCJleHAiOjE1ODgwNzI0MTIsInN1YiI6ImY5MTE2Njg5LWVkYjUtNDM1My1iZTM0LWJkZDI0ODE2ZWJhYiJ9.TD2c7NudjuAT26bPl8wK-qZuD7Ki2-ubQNQA6g0lmNnpPO1p8GLSkFD-I8BNkw7Fo8QXAzsjvg5bcGbvvSCWag'
					}
				};	
			}
		}
	}
</script>