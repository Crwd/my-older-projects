<template>
	<div>
		<v-parallax class="header" :src="require('../assets/header.jpg')">
			<div class="header-overlay" fill-height>
				<div class="header-text">
					<h2>Willkommen auf Mein Ghetto</h2>
					<span>Lorem ipsum dolor sit amet consetetur sadipscing</span>
				</div>
			</div>
		</v-parallax>

		<v-container fluid grid-list-md>
				<v-layout row>
		      <v-flex xs6>
		      	<LatestNews></LatestNews>
		      </v-flex>

		      <v-flex xs6>
		      	<LatestNewcomer></LatestNewcomer>
		      </v-flex>
		    </v-layout>
		</v-container>
	</div>
</template>

<style>
@import url('https://fonts.googleapis.com/css?family=Fjalla+One');
@import url('https://fonts.googleapis.com/css?family=Open+Sans');

.header {
	max-height:250px;
	width:100%;
}

.header .header-overlay {
	width:100%;
	height:100%;
	background:rgba(100, 15, 15,0.6);
	text-align:center;
	display: table;
}

.v-parallax__content {
	padding:0;
}

.header .header-overlay .header-text {
	height:auto;
	width:100%;
	display:table-cell;
	vertical-align: middle;
	font-family:'Fjalla One', sans-serif;
	text-shadow: 0 0 1rem #000, 0 0 1rem #000;
}

.header .header-overlay h2 {
	text-transform: uppercase;
	font-family:'Fjalla One', sans-serif;
	font-size:3rem;
	text-shadow: 0 0 1rem #000, 0 0 1rem #000;
	color:#fff;
}

.header .header-overlay span {
	font-size:1.2rem;
	font-family:'Open Sans', sans-serif;
	color:#fff;
}
</style>

<script>
import LatestNews from '../components/LatestNews'
import LatestNewcomer from '../components/LatestNewcomer'

export default {
	components: {
	  LatestNews,
	  LatestNewcomer
	},

	data: function() {
		return {
			show: true
		}
	}
}
</script>
