<template>
  <v-card>
    <v-btn icon @click="show = !show">
      <v-icon>{{ show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
    </v-btn>

    <strong>Meine News</strong>

    <v-expand-transition>
      <v-card-text v-show="show">
        <v-carousel height="200px">
          <v-carousel-item src="https://www.fritz.de/content/dam/rbb/frz/aus-plato/183/23/45982.jpg.jpg/size=708x398.jpg"></v-carousel-item>
          <v-carousel-item src="https://www.fritz.de/content/dam/rbb/frz/aus-plato/183/23/45982.jpg.jpg/size=708x398.jpg"></v-carousel-item>
          <v-carousel-item src="https://www.fritz.de/content/dam/rbb/frz/aus-plato/183/23/45982.jpg.jpg/size=708x398.jpg"></v-carousel-item>
        </v-carousel>
      </v-card-text>
    </v-expand-transition>
  </v-card>
</template>

<script>
  export default {
    data: function() {
      return {
        show: true
      }
    }
  }
</script>

<style>

</style>
