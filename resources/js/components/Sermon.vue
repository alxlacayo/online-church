<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1">	 	 
		<div class="position-relative d-flex flex-column flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black video-content">
			<div class="d-flex mx-30 mx-md-60 flex-shrink-0 align-items-center bar">
				<span
					@click="goBack"
					class="close"
				></span>
			</div>
			<div class="d-flex mx-0 mx-lg-60 flex-grow-1 flex-shrink-1 video-wrapper">
				<video-player-vimeo
					v-if="videoLoaded"
					:video-id="sermon.vimeo_id"
					:autoplay="false"
					ref="video"
				/>
			</div>
			<div class="d-none d-md-flex flex-shrink-0 justify-content-center bar">
				<salvation-button
					@show-salvation-confirmation="$_salvationMixin_showSalvationConfirmation"
					:isSmallScreenButton="false"
				></salvation-button>
			</div>
		</div>
		<div class="d-flex flex-column flex-grow-1 mh-0 video-sidebar">
			<div class="d-flex flex-column flex-grow-1 overflow-y">
				<salvation-button
					@show-salvation-confirmation="$_salvationMixin_showSalvationConfirmation"
					class="d-md-none flex-shrink-0 justify-content-center salvation-button--small-screen"
				></salvation-button>
				<div class="mx-30 mx-md-40 my-40">
					<h1>{{ sermon.title }}</h1>
					<p>{{ sermon.description }}</p>
					<div v-html="sermon.notes"></div>
				</div>
			</div>
		</div>
		<salvation-confirmation
			@hide-salvation-confirmation="$_salvationMixin_hideSalvationConfirmation"
			:isSalvationConfirmationVisible="$data.$_salvationMixin_isSalvationConfirmationVisible"
		></salvation-confirmation>
	</div>
</template>

<script>
	import VideoPlayerVimeo from '../components/VideoPlayerVimeo'
	import SalvationButton from '../components/SalvationButton'
	import SalvationConfirmation from '../components/SalvationConfirmation'
	import salvationMixin from '../mixins/salvationMixin'

	export default {
		components: {
			VideoPlayerVimeo,
			SalvationButton,
			SalvationConfirmation
		},
		mixins: [salvationMixin],
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