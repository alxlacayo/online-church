<template>
	<div class="container-fluid p-0">
		<div class="latest-sermon d-flex mb-60 flex-grow-1">
			<div class="row no-gutters flex-grow-1">
				<div class="col-10 col-lg-6 d-flex mb-60 flex-column move-up">
					<div class="d-flex ml-30 ml-md-60 flex-column justify-content-center flex-grow-1">
						<h1 class="huge text-white">{{ $_broadcastMixin_title }}</h1>
						<span class="text-muted">{{ $_broadcastMixin_nextBroadcastTime }}<br>is our next broadcast</span>
					</div>
					<router-link
						v-if="$_broadcastMixin_isBroadcastOpen"
						:to="{ name: 'broadcast', params: { broadcast_id: broadcast.id } }"
						class="d-inline-block ml-30 ml-md-60 pb-10 align-self-start text-white font-weight-bold text-uppercase border-bottom-heavy"
					>
						Join In Progress Broadcast
					</router-link>
					<router-link
						v-else
						:to="{ name: 'schedule' }"
						class="d-inline-block ml-30 ml-md-60 pb-10 align-self-start text-white font-weight-bold text-uppercase border-bottom-heavy"
					>View Broadcast Schedule</router-link>
				</div>
				<div class="col-7 offset-5 col-lg-10 offset-lg-2 sermon-image-container mb-lg-60">
					<img :src="$_broadcastMixin_image">
				</div>
	 			<div class="col-3 offset-3 d-none d-lg-flex flex-column py-60 pr-30 pr-md-60 move-up">
<!-- 	 				<div class="flex-grow-1">
	 					<span class="d-block text-white font-weight-bold">Speaker</span>
	 					<span class="d-block mb-48 text-muted">Dr. Ed Young</span>
	 					<span class="d-block text-white font-weight-bold">Broadcasts</span>
	 					<span class="d-block text-muted">{{ broadcastDates }}</span>
	 				</div>
					<p class="mb-0 text-muted">{{ $_broadcastMixin_description }}</p> -->
				</div>				
			</div>
		</div>
		<div>
			<div class="row no-gutters">
				<div class="col col-xl-10 offset-xl-1">
					<h2 class="px-30 px-md-60">Recent Sermons</h2>
					<div class="row px-md-45 no-gutters">
						<div
							v-for="sermon in sermons"
							:key="sermon.id"
							class="col-12 col-md-6 px-md-15"
						>
							<div class="mb-60">
								<router-link :to="{ name: 'sermon', params: { sermon_id: sermon.id }}">
									<img :src="sermon.image" class="w-100">
									<div class="ml-30 ml-md-0">
										<span class="d-block pt-20 xlarge font-weight-bold">{{ sermon.title }}</span>
										<span class="small text-muted">{{ publishedDate(sermon.publish_on) }}</span>
									</div>
								</router-link>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import broadcastMixin from '../mixins/broadcastMixin'
	import { mapState } from 'vuex'

	export default {
		mixins: [broadcastMixin],
		data: function() {
			return {
				sermons: []
			}
		},
		computed: {
			...mapState([
				'nextBroadcast',
			]),
			broadcast: function() {
				return this.nextBroadcast;
			},
			route: function() {
				return this.$route.params.username
			},
			startTime: function() {
				return Moment.utc(this.broadcast.starts_at)
					.local()
					.calendar(null, {
					    sameDay: '[Today at] H:mm a',
					    nextDay: '[Tomorrow]',
					    nextWeek: 'dddd',
					    lastDay: '[Yesterday]',
					    lastWeek: '[Last] dddd',
					    sameElse: 'DD/MM/YYYY'
					});
			},
			broadcastDates: function() {
				const moment = Moment.utc(this.broadcast.sermon.publish_on).local()
				const starts = moment.format('MMM D');
				const ends = moment.add(1, 'weeks').format('MMM D');

				return starts + ' - ' + ends;
			}
		},
		methods: {
			publishedDate: function(timestamp) {
				return Moment.utc(timestamp)
					.local()
					.format('MMMM D, Y')
					.toString();
			}
		},
		created: function() {
			axios
				.get('/w/api/home')
				.then(response => {
					this.sermons = response.data.sermons;
				})
				.catch(error => {

				});
		}
	}
</script>