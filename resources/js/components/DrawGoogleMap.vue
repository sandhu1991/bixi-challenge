<template>
  <div>
    <gmap-map
      :center="center"
      :zoom="10"
      style="width:100%;  height: 555px;">
      <gmap-marker
        :key="index"
        v-for="(gmp, index) in locations"
        :position="gmp"
        @click="center=gmp"
      ></gmap-marker>
    </gmap-map>
  </div>
</template>
 
<script>
export default {
  name: "DrawGoogleMap",
  props: {
    locations: Array
  },
  data() {
    return {
      center: { 
          lat: 43.653225, 
          lng: -79.383186 
      },
      currentLocation: null
    };
  },
 
  mounted() {
    this.setLocationLatLng();
  },
 
  methods: {
    setPlace(loc) {
      this.currentLocation = loc;
    },
    setLocationLatLng: function() {
        navigator.geolocation.getCurrentPosition(geolocation => {
          this.center = {
            lat: geolocation.coords.latitude,
            lng: geolocation.coords.longitude
          };
        });
    }
  }
};
</script>