<template>
  <div class="py-4" style="padding-top: 3px !important">
    <div id="logo" class="google-logo" title="Google">
      <img :src="require('@/assets/wiki.png')" alt="" class="wf-logoo" />
    </div>

    <v-card class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg" id="cardA">
      <div class="text-subtitle-1 text-medium-emphasis">Username</div>

      <v-text-field density="compact" placeholder="Username" prepend-inner-icon="mdi-account-circle" variant="outlined"
        v-model.trim="username" counter="30" @blur="v$.username.$touch()" @keyup="v$.username.$touch()" v-bind:class="{
          error: v$.username.$error,
          valid: v$.username.$dirty && !v$.username.$invalid,
        }" autocomplete="off"></v-text-field>
      <span class="error-show-questionadd" v-for="error in v$.username.$errors" :key="error.$uid"
 >{{ error.$message }}</span>

      <div class="text-subtitle-1 text-medium-emphasis">Email</div>

      <v-text-field density="compact" placeholder="Email address" @keydown.space.prevent prepend-inner-icon="mdi-email-outline"
        variant="outlined" v-model.trim="e_Mail" @blur="v$.e_Mail.$touch()" @keyup="v$.e_Mail.$touch()" v-bind:class="{
          error: v$.e_Mail.$error,
          valid: v$.e_Mail.$dirty && !v$.e_Mail.$invalid,
        }" autocomplete="off"></v-text-field>
      <span class="error-show-questionadd" v-for="(error,index) in v$.e_Mail.$errors" :key="error.$uid"
       ><div v-if="index == 0">
          {{ error.$message }}
        </div></span>

      <div class="
          text-subtitle-1 text-medium-emphasis
          d-flex
          align-center
          justify-space-between
        ">
        Password
      </div>

      <v-text-field @keydown.space.prevent :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'"
        density="compact" placeholder="Enter your password" prepend-inner-icon="mdi-lock-outline" variant="outlined"
        @click:append-inner="visible = !visible" v-model="password" @blur="v$.password.$touch()"
        @keyup="v$.password.$touch()" v-bind:class="{
          error: v$.password.$error,
          valid: v$.password.$dirty && !v$.password.$invalid,
        }" autocomplete="off"></v-text-field>
      <span class="error-show-questionadd" v-for="(error,index) in v$.password.$errors" :key="error.$uid"
        >   <div v-if="index == 0">
          {{ error.$message }}
        </div></span>
      <div class="text-subtitle-1 text-medium-emphasis">Confirm password</div>

      <v-text-field @keydown.space.prevent :append-inner-icon="visible1 ? 'mdi-eye-off' : 'mdi-eye'" :type="visible1 ? 'text' : 'password'"
        density="compact" placeholder="Enter your password" prepend-inner-icon="mdi-lock-outline" variant="outlined"
        @click:append-inner="visible1 = !visible1" v-model="cpassword" @blur="v$.cpassword.$touch()"
        @keyup="v$.cpassword.$touch()" v-bind:class="{
          error: v$.cpassword.$error,
          valid: v$.cpassword.$dirty && !v$.cpassword.$invalid,
        }" autocomplete="off"></v-text-field>
      <span class="error-show-questionadd" v-for="(error,index) in v$.cpassword.$errors" :key="error.$uid"
  
>   <div v-if="index == 0">
          {{ error.$message }}
        </div></span>


      <v-card class="mb-12" color="surface-variant" variant="tonal">

      </v-card>
      <br />
      <v-btn block class="mb-8" style="margin-top: -13%" color="blue" size="large" variant="tonal"
        v-on:click="register">
        Register
      </v-btn>
      <p class="loginOr">Already have an account!</p>
 
      <v-card-text class="text-center">
        <a class="text-blue text-decoration-none" href="/" rel="noopener noreferrer" style="text-align: center" id="signup">
          Log in <v-icon icon="mdi-chevron-right"></v-icon>
        </a>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>

import http from '../../http-common'
import { useToast, POSITION } from "vue-toastification";
import { useVuelidate } from "@vuelidate/core";
import {
  required,
  maxLength,
  email,
  sameAs,
  helpers,
} from "@vuelidate/validators";

export default {
  validations() {
    return {
      username: {
        required: helpers.withMessage("User name is required.", required),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 30 characters",
          maxLength(30)
        ),
        $autoDirty: true,
      },
      e_Mail: {
        required: helpers.withMessage("Email is required.", required),
        email: helpers.withMessage("Enter valid email address", email),
        $autoDirty: true,
      },
      password: {
        required: helpers.withMessage("Password is required", required),
        containsPasswordRequirement: helpers.withMessage(
          `Enter a password that contains an uppercase, lowercase, number and special character,8 characters long`,
          (value) =>/^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])(?=.*\d)[A-Za-z\d!@#$%^&*]{8,}$/.test(value)
        ),
        $autoDirty: true,
      },

      cpassword: {
        required: helpers.withMessage("Confirm password is required.", required),
        sameAsPassword: helpers.withMessage(
          "Password not match",
          sameAs(this.password)
        ),
      },
    };
  },
  data: () => ({
    username: "",
    e_Mail: "",
    password: "",
    v$: useVuelidate(),
    cpassword: "",
    usernameRules: [],
    emailRules: [],
    passwordRules: [],
    cpasswordRules: [],
    visible: false,
    visible1: false,
  }),
  computed: {
    checkVal: function () {
      return this.password > 5 ? false : true;
    },
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  
  methods: {
    async register() {
      this.v$.$validate();
      if (
        this.v$.username.$error ||
        this.v$.e_Mail.$error ||
        this.v$.password.$error ||
        this.v$.cpassword.$error
      ) {
        console.log();
      } else {
        let uName = this.username.split(" ").filter(Boolean).join(" ");
        let result = await http
          .post("/register", {
            username: uName,
            email: this.e_Mail,
            password: this.password,
            password_confirm: this.cpassword,
          })
          .then((res) => {
            if (res.status == 200) {
              localStorage.setItem("TOKENN", JSON.stringify(res.data.token));
              localStorage.setItem(
                "email",
                JSON.stringify(res.data.User.email)
              );

              this.toast.success("Registered successfully", {
                position: POSITION.TOP_CENTER,
                timeout: 1700,
              });
              this.$router.push("vregistereduser");
            }
          })
          .catch((error) => {
            console.log("Eeee", error.response.data.message);
            let msg = "Unable to process !! " + error.response.data.message;
            this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          });

        console.log("1111111", result.data.User.email);
 
      }
    },
  },
};
</script>
<style scoped>
.loginOr{
  text-align: center; color: grey; padding-bottom: 1%
}
.glogo {
  margin-top: 7%;
  margin-left: 43%;
  display: flex;
  flex-direction: row;
  width: 100%;
}

.ui.button.google-auth__button {
  padding: 12px 16px;
  background: #fff;
  box-shadow: 0 0 1px 0 rgba(0, 0, 0, 0.12), 0 1px 1px 0 rgba(0, 0, 0, 0.24);
  border-radius: 2px;
  font-size: 16px;
  line-height: 1.5em;
  letter-spacing: 0.22px;
  color: rgba(0, 0, 0, 0.54);
}

.ui.button.google-auth__button:hover {
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.12), 0 2px 2px 0 rgba(0, 0, 0, 0.24);
}

.ui.button.google-auth__button .google-auth__logo {
  display: inline-block;
  margin-right: 16px;
  height: 24px;
  width: 24px;
  line-height: 24px;
  vertical-align: top;
}

.ui.button.google-auth__button.disabled,
.ui.button.google-auth__button:disabled {
  background: #eee;
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.12), 0 2px 2px 0 rgba(0, 0, 0, 0.24);
}
@media screen and (max-width: 2000px) {
.py-4{
  height: fit-content;
  margin-top: -90px;
  /* background-color: aqua; */
}
}
@media screen and (max-width: 720px) {
#cardA{
  width: 366px;

    display: inline-block; 
}

.wf-logoo {
    margin-left: -24%;
  
}
}
@media screen and (max-width: 498px) {
#cardA{
  width: 366px;
    left: -25%;
    display: inline-block; 
    
}
.wf-logoo {
    margin-left: -34%;
  
}

}
@media screen and (max-width: 430px) {
#cardA{
  width: 300px;
    left: -35%;
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
  width: 270px;
    left: -33%;
    display: inline-block; 
    
}
.wf-logoo {
    margin-left: -54%;
  
}
#signup{
  margin-left:-65px
}
}
@media screen and (max-width: 316px) {
#cardA{
  width: 250px;
    left: -35%;
    display: inline-block; 
    
}
#signup{
  margin-left:-65px
}
.wf-logoo {
    margin-left: -34%;
  
}

}
</style>
