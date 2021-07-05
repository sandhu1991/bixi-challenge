<template>
   <div id="app">
       <div class="nav">
            <a style="float:left; padding:23px;" href="/"><u>Go Back</u></a>
            <h2>Google Maps with Multiple Markers</h2>
        </div>
        <DrawGoogleMap :locations="results"></DrawGoogleMap>
   </div>
</template>
 
<script>
import DrawGoogleMap from "./DrawGoogleMap";
 
export default {
    name: 'Map',
    components: {
        DrawGoogleMap
    },
    mounted (){
        console.log('Component mainxs mounted.');
        this.getStations();
    },
    data(){
        return {
            results: [],
        }
    },
    methods: {
        getStations: function() {
            axios.get('/api/cordinates').then(response => {
                console.log("ressss", response.data)
                this.results = response.data;
            });
        }
    }
}
</script>
 
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #000000;
  margin-top: 50px;
}
</style>


