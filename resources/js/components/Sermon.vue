<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1">	 	 
		<div class="position-relative d-flex flex-column flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black justify-content-center video-content">
			<div class="d-flex mx-30 mx-md-60 flex-shrink-0 align-items-center bar video-header">
				<span
					@click="goBack"
					class="close"
				></span>
			</div>
			<video-player-vimeo
				v-if="videoLoaded"
				:video-id="sermon.vimeo_id"
				:autoplay="false"
				class="px-0 px-lg-60"
				ref="video"
			/> 
		</div>

		<div class="d-flex flex-column flex-grow-1 mh-0 video-sidebar">
			<div class="d-flex flex-column flex-grow-1 overflow-y">
				<div class="mx-30 mx-md-40 my-40">
					<h1>{{ sermon.title }}</h1>
					<p>{{ sermon.description }}</p>
					<div v-html="sermon.notes"></div>
				</div>
			</div>
		</div>

	</div>
</template>

<script>
	import VideoPlayerVimeo from '../components/VideoPlayerVimeo'

	export default {
		components: {
			VideoPlayerVimeo
		},
		data: function() {
			return {
				sermon: {},
				videoLoaded: false,
				previousPage: {}
			}
		},
		beforeRouteEnter (to, from, next) {
			next(vm => vm.setData(from));
		},
		methods: {
			setData: function(from) {
				this.previousPage = from;
			},
		    goBack () {
		    	if (this.previousPage.name === null) {
		    		this.$router.push({name: 'home'});
		    	} else {
		    		this.$router.go(-1);
		    	}
		    }
		},
		created: function() {
			axios
				.get('/w/api/sermons/' + this.$route.params.sermon_id)
				.then(response => {
					this.sermon = response.data;
					this.videoLoaded = true;
				})
				.catch(error => {
					console.log(error.response.status);
				})
				.then(() => {
					
				});
		}
	}
</script>