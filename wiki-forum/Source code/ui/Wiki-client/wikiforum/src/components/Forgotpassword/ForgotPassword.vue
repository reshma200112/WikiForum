<template>
  <div class="py-44">
    <div id="logo" class="google-logo" title="Google">
      <img :src="require('@/assets/wiki.png')" alt="" class="wf-logoo" />
    </div>


    <v-card class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg" id="cardA">

      <div class="text-subtitle-1 text-medium-emphasis">Forgot password</div>

      <v-text-field density="compact" @keydown.space.prevent placeholder="Email address" 
        prepend-inner-icon="mdi-email-outline" variant="outlined" v-model="e_Mail" @blur="v$.e_Mail.$touch()"
        @keyup="v$.e_Mail.$touch()" v-bind:class="{
          error: v$.e_Mail.$error,
          valid: v$.e_Mail.$dirty && !v$.e_Mail.$invalid,
        }" autocomplete="off"></v-text-field>
      <span class="error-show-questionadd" v-for="error in v$.e_Mail.$errors" :key="error.$uid"
       >{{ error.$message }}</span>
        <p style="color:red" v-if="alert==true">{{alertMessage}}</p>
     


      <v-card class="mb-12" color="surface-variant" variant="tonal">
 
      </v-card>
      <loading v-model:active="isLoading" :can-cancel="true" :on-cancel="onCancel" :is-full-page="fullPage" />
      <v-btn block class="mb-8" color="blue" size="large" variant="tonal" v-on:click="fPassword" :disabled="pausedFor2Seconds">
        Submit
      </v-btn>


    </v-card>
  </div>
</template>
<script>

import { required, email, helpers } from "@vuelidate/validators";

import Loading from 'vue-loading-overlay';
import { useVuelidate } from "@vuelidate/core";
import { useToast, POSITION } from "vue-toastification";
import http from '../../http-common'


export default {
  data() {
    return {
    pausedFor2Seconds:false,

      e_Mail: '',
      emailRules: [],
      v$: useVuelidate(),
      isLoading: false,
      fullPage: true,
      alert:false,
      alertMessage:''
    }
  },
  components: {
    Loading
  },
  validations() {
    return {

      e_Mail: {
        required: helpers.withMessage("Email is required", required),
        email: helpers.withMessage("Enter valid email address", email),
        $autoDirty: true,

      },

    };
  },
  
  setup() {
    const toast = useToast();
    return { toast };
  },
  methods: {
    async fPassword() {


      this.v$.$validate();
      if (
        this.v$.e_Mail.$error

      ) {
        console.log();
      }
      else {
        this.isLoading = true;
        // alert("A")
        let result = await http
          .post("/user/forgot-password", {

            email: this.e_Mail,

          }).then((result) => {

            console.log(result.data.Message)

            this.toast.success(result.data.Message, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
           
            this.$router.push("/")

          }).catch((error) => {
            this.pausedFor2Seconds = true;
    setTimeout(() => this.pausedFor2Seconds = false, 1500);
            this.isLoading = false;
            let msg = error.response.data.message
        this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
     
          //   console.log("err", error.response.data.message);
     
          //   this.alert=true;
          //  this.alertMessage=error.response.data.message;

          //  this.closeAlert();
          
          });
        setTimeout(() => {
          this.isLoading = false
        }, 3000)
          .catch((error) => {
            console.log("ss", error);
            
          });
        if (result.status == 400) {
          console.log("rr", result)
        }
        console.log("re", result)
        if (result.status == 200) {
          window.location.href = "/"
        }
      }


    },
     closeAlert()
    {
      setTimeout(()=>{
      this.alert=false
    },1700)
    }



  },


}

</script>
<style >
.py-44{
  padding-top: 2px !important;
}


@media screen and (max-width: 2000px) {
.py-44{
  height: fit-content;
  margin-top: -90px;
  /* margin-top: -15% */
}
}
@media screen and (max-width: 650px) {
#cardA{
  width: 390px;

    display: inline-block; 
}

.wf-logoo {
    margin-left: -24%;
  
}
}
@media screen and (max-width: 498px) {
#cardA{
  width: 366px;
    left: -11%;
    display: inline-block; 
    
}
.wf-logoo {
    margin-left: -34%;
  
}

}
@media screen and (max-width: 430px) {
#cardA{
  width: 300px;
    left: -9%;
    display: inline-block;
     
}
#signup{
  margin-left:-45px
}
.wf-logoo {
    margin-left: -54%;
  
}
}
@media screen and (max-width: 360px) {
#cardA{
  width: 290px;
    left: -22%;
    display: inline-block; 

}
#googleButton{
  margin-left: 2%;
}
.wf-logoo {
    margin-left: -54%;
  
}
}
@media screen and (max-width: 316px) {
#cardA{
  width: 250px;
    left: -11%;
    display: inline-block; 
    
}
#googleButton{
  margin-left: 2%;
}
#signup{
  margin-left:-65px
}
.wf-logoo {
    margin-left: -34%;
  
}

}
</style>