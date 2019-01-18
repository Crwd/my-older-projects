<template>
  <v-app dark>
    <Navigation></Navigation>

    <ToolBar></ToolBar>

    <v-content>
      <v-container fluid style="padding:0;" class="container-content">
        <transition name="fade">
          <router-view/>
        </transition>
      </v-container>
    </v-content>

    <v-footer app fixed>
      <span>&copy; 2019</span>
    </v-footer>
  </v-app>
</template>

<style>
@import url('https://fonts.googleapis.com/css?family=Roboto');

.container-content {
  font-family:'Roboto', sans-serif;
}

html{
  overflow-y: auto;
}

.fade-enter-active, .fade-leave-active {
  transition-property: opacity;
  transition-duration: .25s;
}

.fade-enter-active {
  transition-delay: .25s;
}

.fade-enter, .fade-leave-active {
  opacity: 0
}
</style>

<script>
import ToolBar from './components/Toolbar'
import Navigation from './components/Navigation'

export default {
  name: 'App',
  data () {
    return {
      drawer: true
    }
  },

  components: {
    ToolBar,
    Navigation
  },

  props: {
    source: String
  }
}

</script>
