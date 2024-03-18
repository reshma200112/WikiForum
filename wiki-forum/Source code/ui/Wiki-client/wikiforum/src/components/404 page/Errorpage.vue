<template>
  <div class="responsive" >
    <br /><br />
    <h2 class="ab">404 Error Page</h2>
    <h4>Sorry this page not found</h4>
    <br />
    <div class="Erimg">
      <img class="ErrorImg"
        :src="require('@/assets/Error_page.jpg')"
        alt=""
      />
    </div>
    <br />
    <v-btn depressed color="primary" @click="$router.push('/dashboard')">
      Back to home
    </v-btn>
  </div>
</template>
  
  <script>
//   import axios from 'axios'
export default {
  name: "adds-c",
};
</script>
  
  <style scoped>
.ab {
  color: red;
}
*{
  text-align: center;
  text-decoration-color: antiquewhite;

}
.Erimg{
  justify-content: center;display: flex;
}
.ErrorImg{
  width: 51%;
}
</style>