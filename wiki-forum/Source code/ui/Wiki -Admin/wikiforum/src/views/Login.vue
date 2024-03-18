<template>

    
    <div class="l-form">

                <div class="shape1"></div>
                <div class="shape2"></div>
    
                <div class="form">
                  <img          :src="require('../assets/Login.jpg')" alt="" class="form__img">
                    
    
                    <div class="form__content">
                        <h1 class="form__title">Admin Login</h1>
    
                        <v-form @submit.prevent="submitHandler" ref="form">
              <v-card-text>
                <v-text-field
                  v-model="e_Mail"
                  :rules="emailRules"
                  type="email"
                
                  placeholder="Email"
                  prepend-inner-icon="mdi-account"
                  required
                />
                <v-text-field
                        v-model="password"
                        :rules="passwordRules"
                        :type="passwordShow?'text':'password'"
                      
                        placeholder="Password"
                        prepend-inner-icon="mdi-key"
                        :append-icon="passwordShow ? 'mdi-eye':'mdi-eye-off'"
                        @click:append="passwordShow = !passwordShow"
                        required
                />

              </v-card-text>
              <v-card-actions class="justify-center">
                <v-btn :loading="loading" type="submit" class="form__button" color="black" :disabled="pausedFor2Seconds">
                  <span class="white--text px-8">Login</span>
                </v-btn>
              </v-card-actions>
            </v-form>
                  
    

    
                    
                    </div>
                </div>
    
            
          </div>
  </template>
  
  <script>
  import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
  import http from '../../src/http-common'
  
  export default {
  name:"logged-in",


  data(){
    return{
      // valuee: false,
      loading:false,
      pausedFor2Seconds:false,
      snackbar:false,
      passwordShow:false,
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /^[a-zA-Z][a-zA-Z0-9.+_]+@[a-z]+(?:\.[a-z-]+)$/.test(v) || 'E-mail must be valid',

      ],
      password: '',
      passwordRules: [
        v => !!v || 'Password is required',
        // v => (v && v.length >= 6) || 'Password must be 6  characters or more!',
        (value) =>
            /^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])(?=.*\d)[A-Za-z\d!@#$%^&*]{8,}$/.test(
                value
              ) || 'Enter a valid password'
      ],
    }
  
  },
  methods: {
    async submitHandler() {

      if (!this.$refs.form.validate()){
        console.log("www");
      }
      else{
     

      this.alert = 0;
   
        let result = await http
          .post("/admin/login", {
            email: this.e_Mail,
            password: this.password,
          })
          .then((res) => {
           
            console.log(res);
            console.log(res.data.usertag);
            if (res.data.status == 0) {
              console.log();
     
            } else if (res.data.type == 2) {
              localStorage.setItem("TOKEN", JSON.stringify(res.data.token));
              window.location.href = "/dashboard";
              
    
              
            } 
    
          })
          .catch((error) => 
          {
          console.log(error)
console.log("11",error.response.data.message);
console.log("11",error.response.data);

let errMessage=error.response.data.message
            this.$toast.error({
    title:errMessage
    ,
    message:''
  })
  this.pausedFor2Seconds = true;
    setTimeout(() => this.pausedFor2Seconds = false, 1000);

            let msg = "Unable to process !! " + error.response.data.message;
            this.toast.error(msg, {
           
              timeout: 1700,
            });
          });
   
        console.log(result.data);
        }
      
    },
  
  },
  }
  </script>
  
  <style>

  @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap");
  
  /*===== VARIABLES CSS =====*/
  
  /*=== Colores ===*/

  /*=== Fuente y tipografia ===*/
  :root{
    --first-color: #12192C;
    --text-color: #8590AD;
    --body-font: 'Roboto', sans-serif;
    --big-font-size: 2rem;
    --normal-font-size: 0.938rem;
    --smaller-font-size: 0.875rem;
  }
  @media screen and (min-width: 768px){
    :root{
        --big-font-size: 2.5rem;
        --normal-font-size: 1rem;
    }  
  }
  
  /*===== BASE =====*/
  *,::before,::after{
    box-sizing: border-box;
  }
  body{
    margin: 0;
    padding: 0;
    font-family: var(--body-font);
    color: var(--first-color);
  }
  h1{
    margin: 0;
  }
  a{
    text-decoration: none;
  }
  img{
    max-width: 100%;
    height: auto;
  }
  /*===== FORM =====*/
  .l-form{
  
    height: 100vh;
    overflow: hidden;
  }
  
  /*=== Shapes ===*/
  .shape1,
  .shape2{
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
  }
  .shape1{
    top: -7rem;
    left: -3.5rem;
    background: linear-gradient(180deg, var(--first-color) 0%, rgba(196,196,196,0) 100%);
  }
  .shape2{
    bottom: -6rem;
    right: -5.5rem;
    background: linear-gradient(180deg, var(--first-color) 0%, rgba(196,196,196,0) 100%);
    transform: rotate(180deg);
  }
  /*=== Form ===*/
  .form{
    height: 100vh;
    display: grid;
    justify-content: center;
    align-items: center;
    padding: 0 1rem;
  }
  .form__content{
    width: 290px;
  }
  .form__img{
    display: none;
  }
  .form__title{
    padding-left:17px;
    font-size: var(--big-font-size);
    font-weight: 500;
    margin-bottom: 2rem;
  }
  .form__div{
    position: relative;
    display: grid;
    grid-template-columns: 7% 93%;
    margin-bottom: 1rem;
    padding: .25rem 0;
    border-bottom: 1px solid var(--text-color);
  }
  /*=== Div focus ===*/
  .form__div.focus{
    border-bottom: 1px solid var(--first-color);
  }
  
  .form__div-one{
    margin-bottom: 3rem;
  }
  
  .form__icon{
    font-size: 1.5rem;
    color: var(--text-color);
    transition: .3s;
  }
  /*=== Icon focus ===*/
  .form__div.focus .form__icon{
    color: var(--first-color);
  }
  
  .form__label{
    display: block;
    position: absolute;
    left: .75rem;
    top: .25rem;
    font-size: var(--normal-font-size);
    color: var(--text-color);
    transition: .3s;
  }
  /*=== Label focus ===*/
  .form__div.focus .form__label{
    top: -1.5rem;
    font-size: .875rem;
    color: var(--first-color);
  }
  
  .form__div-input{
    position: relative;
  }
  .form__input{
    
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    background: none;
    padding: .5rem .75rem;
    font-size: 1.2rem;
    color: var(--first-color);
    transition: .3s;
  }
  .form__forgot{
    display: block;
    text-align: right;
    margin-bottom: 2rem;
    font-size: var(--normal-font-size);
    color: var(--text-color);
    font-weight: 500;
    transition: .5;
  }
  .form__forgot:hover{
    color: var(--first-color);
    transition: .5s;
  }
  .form__button{
    width: 100%;
    padding: 1rem;
    font-size: var(--normal-font-size);
    outline: none;
    border: none;
    margin-bottom: 3rem;
    background-color: var(--first-color);
    color: #fff;
    border-radius: .5rem;
    cursor: pointer;
    transition: .3s;
  }
  .form__button:hover{
    box-shadow: 0px 15px 36px rgba(0,0,0,.15);
  }
  
  /*=== Form social===*/
  .form__social{
    text-align: center;
  }
  .form__social-text{
    display: block;
    font-size: var(--normal-font-size);
    margin-bottom: 1rem;
  }
  .form__social-icon{
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 30px;
    height: 30px;
    margin-right: 1rem;
    padding: .5rem;
    background-color: var(--text-color);
    color: #fff;
    font-size: 1.25rem;
    border-radius: 50%;
  }
  .form__social-icon:hover{
    background-color: var(--first-color);
  }
  /*===== MEDIA QUERIS =====*/
  @media screen and (min-width: 968px){
    .shape1{
        width: 400px;
        height: 400px;
        top: -11rem;
        left: -6.5rem;
    }
    .shape2{
        width: 300px;
        height: 300px;
        right: -6.5rem;
    }
  
    .form{
        grid-template-columns: 1.5fr 1fr;
        padding: 0 2rem;
    }
    .form__content{
        width: 320px;
    }
    .form__img{
        display: block;
        width: 700px;
        justify-self: center;
    }
  }

  .socket {
  width: 200px;
  height: 200px;
  position: absolute;
  left: 50%;
  margin-left: -100px;
  top: 50%;
  margin-top: -100px;
}

.hex-brick {
  background: #000000;
  width: 30px;
  height: 17px;
  position: absolute;
  top: 5px;
  animation-name: fade00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  -webkit-animation-name: fade00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
}

.h2 {
  transform: rotate(60deg);
  -webkit-transform: rotate(60deg);
}

.h3 {
  transform: rotate(-60deg);
  -webkit-transform: rotate(-60deg);
}

.gel {
  height: 30px;
  width: 30px;
  transition: all .3s;
  -webkit-transition: all .3s;
  position: absolute;
  top: 50%;
  left: 50%;
}

.center-gel {
  margin-left: -15px;
  margin-top: -15px;
  animation-name: pulse00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  -webkit-animation-name: pulse00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
}

.c1 {
  margin-left: -47px;
  margin-top: -15px;
}

.c2 {
  margin-left: -31px;
  margin-top: -43px;
}

.c3 {
  margin-left: 1px;
  margin-top: -43px;
}

.c4 {
  margin-left: 17px;
  margin-top: -15px;
}

.c5 {
  margin-left: -31px;
  margin-top: 13px;
}

.c6 {
  margin-left: 1px;
  margin-top: 13px;
}

.c7 {
  margin-left: -63px;
  margin-top: -43px;
}

.c8 {
  margin-left: 33px;
  margin-top: -43px;
}

.c9 {
  margin-left: -15px;
  margin-top: 41px;
}

.c10 {
  margin-left: -63px;
  margin-top: 13px;
}

.c11 {
  margin-left: 33px;
  margin-top: 13px;
}

.c12 {
  margin-left: -15px;
  margin-top: -71px;
}

.c13 {
  margin-left: -47px;
  margin-top: -71px;
}

.c14 {
  margin-left: 17px;
  margin-top: -71px;
}

.c15 {
  margin-left: -47px;
  margin-top: 41px;
}

.c16 {
  margin-left: 17px;
  margin-top: 41px;
}

.c17 {
  margin-left: -79px;
  margin-top: -15px;
}

.c18 {
  margin-left: 49px;
  margin-top: -15px;
}

.c19 {
  margin-left: -63px;
  margin-top: -99px;
}

.c20 {
  margin-left: 33px;
  margin-top: -99px;
}

.c21 {
  margin-left: 1px;
  margin-top: -99px;
}

.c22 {
  margin-left: -31px;
  margin-top: -99px;
}

.c23 {
  margin-left: -63px;
  margin-top: 69px;
}

.c24 {
  margin-left: 33px;
  margin-top: 69px;
}

.c25 {
  margin-left: 1px;
  margin-top: 69px;
}

.c26 {
  margin-left: -31px;
  margin-top: 69px;
}

.c27 {
  margin-left: -79px;
  margin-top: -15px;
}

.c28 {
  margin-left: -95px;
  margin-top: -43px;
}

.c29 {
  margin-left: -95px;
  margin-top: 13px;
}

.c30 {
  margin-left: 49px;
  margin-top: 41px;
}

.c31 {
  margin-left: -79px;
  margin-top: -71px;
}

.c32 {
  margin-left: -111px;
  margin-top: -15px;
}

.c33 {
  margin-left: 65px;
  margin-top: -43px;
}

.c34 {
  margin-left: 65px;
  margin-top: 13px;
}

.c35 {
  margin-left: -79px;
  margin-top: 41px;
}

.c36 {
  margin-left: 49px;
  margin-top: -71px;
}

.c37 {
  margin-left: 81px;
  margin-top: -15px;
}

.r1 {
  animation-name: pulse00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-delay: .2s;
  -webkit-animation-name: pulse00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-delay: .2s;
}

.r2 {
  animation-name: pulse00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-delay: .4s;
  -webkit-animation-name: pulse00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-delay: .4s;
}

.r3 {
  animation-name: pulse00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-delay: .6s;
  -webkit-animation-name: pulse00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-delay: .6s;
}

.r1 > .hex-brick {
  animation-name: fade00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-delay: .2s;
  -webkit-animation-name: fade00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-delay: .2s;
}

.r2 > .hex-brick {
  animation-name: fade00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-delay: .4s;
  -webkit-animation-name: fade00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-delay: .4s;
}

.r3 > .hex-brick {
  animation-name: fade00;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-delay: .6s;
  -webkit-animation-name: fade00;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-delay: .6s;
}

@keyframes pulse00 {
  0% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }

  50% {
    -webkit-transform: scale(0.01);
    transform: scale(0.01);
  }

  100% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}

@keyframes fade00 {
  0% {
    background: #218eb9;
  }

  50% {
    background: #000000;
  }

  100% {
    background: #353535;
  }
}
  </style>