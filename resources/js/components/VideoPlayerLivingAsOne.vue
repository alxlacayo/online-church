<template>
	<div>
		<slot/>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				scriptId: 'livingAsOneScript'
			}
		},
		mounted: function() {
			if (document.getElementById(this.scriptId) === null) {
				const script = document.createElement('script');

				script.id = this.scriptId;
				script.src = '//control.livingasone.com/webplayer/require.js'
				script.setAttribute('data-main', '//control.livingasone.com/webplayer/loader.js');

				document.body.appendChild(script);
			} else {
				window.la1InitWebPlayer();
			}			
		}
	}
</script>