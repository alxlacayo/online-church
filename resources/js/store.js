import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		user: window.AppUser,
		introVideo: window.AppIntroVideo,
		broadcasts: window.AppBroadcasts,
		layout: null
	},
	getters: {
		isUserAuthenticated: state => {
			return state.user !== null; 
		},
		isUserHost: state => {
			return state.user !== null && state.user.is_host === true;
		},
		openBroadcasts: state => {
			return state.broadcasts.filter(broadcast => {
				return broadcast.status == 'broadcast_open'
					|| broadcast.status == 'broadcast_in_progress'
			});
		}
	},
	mutations: {
		setUser (state, user) {
			state.user = user;
		},
		setUserProfilePicture (state, picture) {
			state.user.profile_picture = picture;
		},
		setBroadcasts (state, broadcasts) {
			state.broadcasts = broadcasts;
		},
		setLayout (state, layout = 'default-layout') {
			state.layout = layout;
		}
	},
	actions: {
		logUserOut (context) {
			context.commit('setUser', null);
			window.AppUser = null;
		},
		updateBroadcast (context, broadcast) {
			let broadcasts = context.state.broadcasts;
			let id = broadcast.id;
			let index = broadcasts.findIndex(broadcast => broadcast.id == id);

			// if index == -1 still check if its disabled
			if (index > -1 && broadcast.enabled) {
				broadcasts[index] = broadcast;
			} else if (index > -1 && !broadcast.enabled) {
				broadcasts.splice(index, 1);
			} else if (index == -1 && broadcast.enabled) {
				broadcasts.push(broadcast);
			}

			broadcasts.sort((f, s) => {
				return Moment.utc(f.starts_at) - Moment.utc(s.starts_at);
			});

			context.commit('setBroadcasts', broadcasts);
		}
	}
});