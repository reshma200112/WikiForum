<template>
    <div>
        <div v-if="screenWidth >= 1268">
        <Header @handeDrawer="drawer =! drawer"/>
      <Sidebar :drawer="drawer"/>
    </div>
    <div v-else>
        <ResponsiveHeader @handeDrawer="drawerr =! drawerr"/>
      <ResponsiveSidebar :drawerr="drawerr"/>

    </div>

    <section class="page_404">
        <div class="container">
            <div class="row">	
            <div class="col-sm-12 ">
            <div class="col-sm-10 col-sm-offset-1  text-center">
            <div class="four_zero_four_bg">

            
            
            </div>
            
            <div class="contant_box_404">
            <h3 class="h22">
            Look like you're lost
            </h3>
            
            <p>the page you are looking for not avaible!</p>
            
            <a href="/dashboard" class="link_404" style="text-decoration:none">Go to Home</a>
        </div>
            </div>
            </div>
            </div>
        </div>
    </section> </div>
</template>

<script>
import Header from '../../src/components/Header.vue'
import Sidebar from '../../src/components/Sidebar.vue'
import ResponsiveSidebar from '@/components/ResponsiveSidebar.vue'
import ResponsiveHeader from '@/components/ResponsiveHeader.vue'
export default {
name : "Error-page",
components: {Sidebar,Header,ResponsiveSidebar,ResponsiveHeader},
data(){
    return{
        cards: ["Today", "Yesterday"],
    drawer: true,
    drawerr:false,
    screenWidth: window.innerWidth,

    }
    
},
mounted() {
  window.addEventListener('resize', this.updateScreenWidth);
},
beforeDestroy() {
  window.removeEventListener('resize', this.updateScreenWidth);
},
methods: {
        updateScreenWidth() {
    this.screenWidth = window.innerWidth;
  },}
}
</script>

<style>




.page_404{ padding:40px 0; background:#fff; font-family: 'Arvo', serif;
}

.page_404  img{ width:100%;}

.four_zero_four_bg{
 
 background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
    height: 400px;
    background-position: center;
 }
 
 
 .four_zero_four_bg h1{
 font-size:80px;
 }
 
  .four_zero_four_bg h3{
font-size:80px;
}
 
    .link_404{			 
	color: #fff!important;
    padding: 10px 20px;
    background: #39ac31;
    margin: 20px 0;
    display: inline-block;
    text-decoration:none;
}

	.contant_box_404{ margin-top:-50px;}
</style>