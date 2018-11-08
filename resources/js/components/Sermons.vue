<template>
	<div class="container-fluid px-0 px-md-15 px-lg-45">
		<div class="row no-gutters">
			<div class="col col-xl-10 offset-xl-1 px-30 px-md-15">
				<h1 class="py-45 huge text-center">Each Tuesday we<br class="d-none d-md-block"><span class="d-md-none">&nbsp;</span>publish a new<br>sermon.</h1>
			</div>
		</div>
		<div class="row no-gutters">
<!-- 			<div class="col-1 d-none d-xl-block">
				<span class="small line-height-1 text-vertical font-weight-bold">
					<span class="mb-32">Facebook</span>
					<span class="mb-32">Instagram</span>
					<span class="d-block pr-4">is the next broadcast</span>
				</span>
			</div> -->
			<div class="col col-xl-10 offset-xl-1">
				<div class="row no-gutters">
					<div
						v-for="sermon in sermons"
						:key="sermon.id"
						class="col-12 col-md-6 px-md-15"
					>
						<div class="mb-60">
							<router-link :to="{ name: 'sermon', params: { sermon_id: sermon.id }}">
								<img v-bind:src="sermon.image" class="w-100">
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
		<div class="row no-gutters">
			<div class="col-12 my-30 text-center">
				<span
					v-if="showLoadMoreButton"
					@click="loadSermons"
					class="d-inline-block px-48 py-20 font-weight-bold text-white bg-black clickable"
				>Load more</span>
				<span v-else>That's all the sermons.</span>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				sermons: [],
				firstPage: '/w/api/sermons/',
				nextPage: null,
				showLoadMoreButton: false
			}
		},
		computed: {
			endpoint: function() {
				return this.nextPage === null ? this.firstPage : this.nextPage;
			}
		},
		methods: {
			loadSermons: function() {
				axios
					.get(this.endpoint)
					.then(response => {
						this.sermons.push(...response.data.data);
						this.nextPage = response.data.next_page_url;
						this.showLoadMoreButton = this.nextPage === null ? false : true;
					});
			},
			publishedDate: function(timestamp) {
				return Moment.utc(timestamp)
					.local()
					.format('MMMM D, Y')
					.toString();
			}
		},
		created: function() {
			this.loadSermons();
		}
	}
</script>