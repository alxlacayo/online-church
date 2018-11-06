<template>
	<div class="container-fluid px-30">
		<div class="row">
			<div class="col col-lg-7 m-auto">
	
					<div
						v-for="day in schedule"
						class="mb-40"
					>
						<h1 class="mt-80 mb-56 line-height-1">{{ day.title }}</h1>
						<div
							v-for="broadcast in day.broadcasts"
							class="row no-gutters py-20 border-bottom"
						>
							<span class="col-3 col-md-2 font-weight-bold">{{ broadcast.time }}</span>
							<div class="col-18 d-flex pl-20 pl-md-40 flex-grow-1">
								<span
									v-if="broadcast.live"
									class="flex-grow-1"
								>{{ broadcast.name }}</span>
								<span
									v-else
								>{{ broadcast.name }}</span>
								<span
									v-if="broadcast.live"
									class="text-danger"
								>Live<span class="d-none d-md-inline"> broadcast</span></span>
							</div>
							<!-- <span class="col-2 font-weight-bold text-right">Get reminder</span> -->
						</div>
					</div>

			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				schedule: []
			}
		},
		mounted: function() {
			axios
				.get('/w/api/schedule/')
				.then(response => {
					this.schedule = response.data.reduce((accumulator, broadcast) => {
						const time = Moment.utc(broadcast.starts_at).local();
						const key = time.format('MM_DD_Y');
						const title = time.calendar(null, {
						    
						        lastDay : '[Yesterday], MMM D',
						        sameDay : '[Today], MMM D',
						        nextDay : '[Tomorrow], MMM D',
						        lastWeek : '[Last] dddd, MMM D',
						        nextWeek : 'dddd, MMM D',
						        sameElse : 'dddd, MMM D'

						});

						if (! accumulator.hasOwnProperty(key)) {
							accumulator[key] = {
								title: title, 
								broadcasts: []
							};
						}

						broadcast.time = time.format('h:mm a');

						accumulator[key].broadcasts.push(broadcast);

						return accumulator;

					}, {});
				});
		}
	}
</script>