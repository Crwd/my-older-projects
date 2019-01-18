<template>
  <v-navigation-drawer clipped fixed v-model="drawer" app width="200">
    <v-list dense>
      <v-list-tile to="/">
        <v-list-tile-action>
          <v-icon>home</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Start</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile to="/news">
        <v-list-tile-action>
          <v-icon>import_contacts</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Rap News</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  export default {
  }
</script>

<style>

</style>
