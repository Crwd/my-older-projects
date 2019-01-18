<template>
  <v-toolbar app fixed clipped-left>
    <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
    <v-toolbar-title>Mein Ghetto</v-toolbar-title>

    <!--<v-img :src="require('./assets/logo.png')" height="100%" contain position="left"></v-img>-->

    <v-spacer></v-spacer>

    <v-btn flat to="/login">Anmeldung</v-btn>
    <v-btn color="primary" to="/register">Mitglied werden</v-btn>

    <v-menu offset-y content-class="dropdown-menu" transition="slide-y-transition">
      <v-btn icon slot="activator">
        <v-badge color="primary" right overlap>
          <span slot="badge" dark small>3</span>
          <v-icon color="grey lighten-1" middle>
            notifications
          </v-icon>
        </v-badge>
      </v-btn>

      <v-card>
        <v-list dense>
          <v-list-tile>
            <v-list-tile-title>Benachrichtung #1</v-list-tile-title>
          </v-list-tile>
          <v-list-tile>
            <v-list-tile-title>Benachrichtung #2</v-list-tile-title>
          </v-list-tile>
          <v-list-tile>
            <v-list-tile-title>Benachrichtung #3</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-card>
    </v-menu>
  </v-toolbar>
</template>

<script>
  export default {
  }
</script>

<style>

</style>
