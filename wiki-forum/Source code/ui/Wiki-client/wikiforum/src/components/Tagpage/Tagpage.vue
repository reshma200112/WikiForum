<template>
  <div class="body">
    <div class="tagpage-container">
    <div class="box">
      <div id="logo" class="google-logo" title="Google">
        <img :src="require('@/assets/wiki.png')" alt="" class="wf-logo" />
      </div>
      <h2 class="namewiki">Wiki Forum</h2>

      <form method="post" @submit.prevent="AdduserTag()">
        <div class="tag-input">
          <p>Select your interested fields<small class="manstar-p">*</small></p>
          <v-autocomplete v-model="Tagid" :items="TagList" chips closable-chips color="blue-grey-lighten-2" 
            item-title="tag_name" item-value="id" multiple variant="filled" :menu-props="{ maxHeight: 195 }"
            @change="Tagid.$touch()" v-bind:class="{
              error: v$.Tagid.$error,
              valid:
                v$.Tagid.$dirty &&
                !v$.Tagid.$invalid,
            }">
            <template v-slot:chip="{ props, item }">
              <v-chip v-bind="props" :text="item.raw.tag_name"></v-chip>
            </template>
            <template v-slot:item="{ props, item }">
              <v-list-item v-bind="props" :title="item?.raw?.tag_name"></v-list-item>
            </template>
          </v-autocomplete>
          <span class="error-show" v-for="error in v$.Tagid.$errors" :key="error.$uid">{{
            error.$message
          }}</span>

          <p class="tagSubmit">
            <button type="submit" id="typetagpage">Submit</button>
          </p>
        </div>
      </form>
    </div>
  </div>
  </div>
</template>
<script>
import http from "../../http-common";

import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";
import { useToast, POSITION } from "vue-toastification";
export default {
  name: "sfdvc-c",
  data() {
    return {
      v$: useVuelidate(),
      TagList: "",
      Tagid: [],
    };
  },
  validations(){
    return{ Tagid: {
        required: helpers.withMessage("Please select atleast one Tag", required),
        maxLength: helpers.withMessage(
          "You can only select upto 5 tags",
          maxLength(5)
        ),
        $autoDirty: true,
      },}
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  created()
  {
    this.getAlltags();
  },
  methods: {
    async AdduserTag() {
      this.v$.$validate();
    
          if (this.v$.Tagid.$error ) {
        console.log();
      } else {
        
        const bodyParameters = {
          tag_id: JSON.parse(JSON.stringify(this.Tagid)),
        };
       await http
          .post("/userTag", bodyParameters)
          .then((res)=>{
            console.log(res.data);
          localStorage.setItem("userTag",JSON.stringify(res.data.hasTag))
          if(res.data.hasTag=='true'){
              window.location.href = "dashboard";
          }
          })
          .catch((error) => {
            let msg = "Unable to process !!" + error.response.data.message;
            this.toast.error(msg, { position: POSITION.TOP_CENTER, timeout: 1700 });
          });
       

      }
    },
    
    getAlltags() {
      http
        .get("/tags")
        .then((response) => {
          this.TagList = response.data;
        })
        .catch(function (error) {
          throw error;
        });
    },
  },
}; // Add to tag array
</script>
<style scopped>
.tagSubmit{
  margin-top:180px
}
@import url("Tagpage.css");
</style>
