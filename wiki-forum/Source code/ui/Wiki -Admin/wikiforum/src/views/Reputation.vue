
<template>
  <div>
    <div v-if="screenWidth >= 1268">
      <Header @handeDrawer="drawer = !drawer" />
      <Sidebar :drawer="drawer" />
    </div>
    <div v-else>
      <ResponsiveHeader @handeDrawer="drawerr = !drawerr" />
      <ResponsiveSidebar :drawerr="drawerr" />

    </div>

    <v-subheader class="d-flex justify-space-between align-center">
      <h2>Reputation</h2>
    </v-subheader>



    <v-col lg="12" cols="12">
      <br>
      <div v-if="valuee">
        <p></p>

        <div class="loader"></div>


      </div>
      <div v-else>
        <v-data-table :headers="headers" :items="reputationDetails" hide-default-footer="true" class="elevation-1">


          <template v-slot:[`item.action`]="{ item }">
            <div v-if="item.status == 1">
              <div>

                <v-dialog v-model="dialogg" max-width="600px" persistent>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon v-bind="attrs" v-on="on" @click="editScore(item.id)">mdi-pencil </v-icon>

                  </template>

                  <v-form @submit.prevent="putScore(item.id)" ref="form">
                    <v-card>
                      <v-card-title>
                        <span class="text-h5">Edit score</span>
                      </v-card-title>
                      <v-card-text>
                        <v-container>
                          <div v-if="editId == 2">
                            <v-row>


                              <v-col cols="12" sm="6" md="4">
                                
                                <v-text-field label="Score" hint="" type="number"
                                  onkeydown="return event.keyCode !== 69 && event.keyCode !== 189" :rules="AnsscoreRules"
                                  required v-model.trim="edComments"></v-text-field>
                              </v-col>

                            </v-row>
                          </div>
                          <div v-if="editId == 1">
                            <v-row>


                              <v-col cols="12" sm="6" md="4">
                                <v-text-field label="Score" hint="" type="number"
                                  onkeydown="return event.keyCode !== 69 && event.keyCode !== 189" :rules="scoreRules"
                                  required v-model.trim="edComments"></v-text-field>
                              </v-col>

                            </v-row>
                          </div>
                          <div v-if="editId == 3">
                            <v-row>


                              <v-col cols="12" sm="6" md="4">
                                <v-text-field label="Score" hint="" type="number"
                                  onkeydown="return event.keyCode !== 69 && event.keyCode !== 189" :rules="scoreRules"
                                  required v-model.trim="edComments"></v-text-field>
                              </v-col>

                            </v-row>
                          </div>
                        </v-container>

                      </v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeReputationEdit">
                          Close
                        </v-btn>

                        <div v-if="edComments == ''">
                          <v-btn color="blue darken-1" type="submit" text disabled>
                            Submit
                          </v-btn>
                        </div>
                        <div v-if="edComments != ''">
                          <div v-if="editId == 2">
                            <v-btn color="blue darken-1" type="submit" text :disabled="isAnswerSubmitDisabled">
                              Submit
                            </v-btn>
                          </div>
                          <div v-if="editId == 1">
                            <v-btn color="blue darken-1" type="submit" text :disabled="isSubmitDisabled">
                              Submit
                            </v-btn>
                          </div>
                          <div v-if="editId == 3">

                            <v-btn color="blue darken-1" type="submit" text :disabled="isSubmitDisabled">
                              Submit
                            </v-btn>
                          </div>
                        </div>



                      </v-card-actions>
                    </v-card></v-form>
                </v-dialog>
              </div>
            </div>


          </template>
        </v-data-table>
      </div>
    </v-col>

  </div>
  
</template>


<script>

import http from '../../src/http-common'
import Header from '../../src/components/Header.vue'
import Sidebar from '../../src/components/Sidebar.vue'
import ResponsiveSidebar from '@/components/ResponsiveSidebar.vue'
import ResponsiveHeader from '@/components/ResponsiveHeader.vue'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
export default {
  components: { Sidebar, Header, ResponsiveSidebar, ResponsiveHeader },
  name: "User-list",
  data() {
    return {
      screenWidth: window.innerWidth,
      drawer: true,
      drawerr: false,
      editId: '',
      valuee: false,
      pausedFor2Seconds: false,
      edComments: '',
      RepScore: '',

      name: '',
      score: '',
      dialog: false,
      dialogg: false,
      search: '',


      nameRules: [
        v => !!v || 'Reputation name is required',


      ],
      scoreRules: [
        v => !!v || 'Score is required ',

        // v => v && /^[0-9]+$/.test(v) || 'Only numbers are allowed',
        v => v && parseInt(v) >= 5 && parseInt(v) <= 25 || 'Score must be between 5 and 25',


      ],
      AnsscoreRules: [
        v => !!v || 'Score is required',
        v => v && parseInt(v) >= 10 && parseInt(v) <= 25 || 'Score must be between 10 and 25',


      ],
      cards: ["Today", "Yesterday"],

      reputationDetails: [
        {
          edComments: '',
          id: '',
          name: '',
          score: '',
          status: ''
        }
      ],
      headers: [

        {
          text: "ID",
          color: "red",


          value: "id",
          sortable: false,
          align: "start",
          width: "3%",
        },
        { text: "NAME", sortable: false, value: "name", width: "15%" },
        { text: "SCORE", value: "score", sortable: false, width: "20%" },

        {
          text: "ACTION", value: "action", sortable: false,

          width: "10%"
        },
      ],


    }
  },
  computed: {
    isSubmitDisabled() {
      return this.edComments === '' || this.edComments < 5 || this.edComments > 25


    },
    isAnswerSubmitDisabled() {
      return this.edComments === '' || this.edComments < 10 || this.edComments > 25

    },
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
    },
    closeReputationEdit() {
      console.log("ss")
      this.dialogg = false
    },


    editScore(id) {


      this.editId = id;
      http
        .get("/getReputationId/" + id)
        .then((response) => {

          console.log("aa", response.data.score);
          this.edComments = response.data.score
          console.log("112qq", this.edComments);

        })
        .catch(function (error) {
          throw error;
        });

    },

    async putScore(id) {


      this.valuee = true

      id = this.editId
      const bodyParameters = {
        score: this.edComments,

      }
      await http.put("/reputation/" + id, bodyParameters)
        .then((response) => {
          console.log(response)
          console.log("111111111111", response.data.success)
          this.reputationDetails = response.data
this.valuee  = false
http.get(this.reputation())

let msg = response.data.message
this.$toast.success(msg)
this.dialogg = false


        },

        )

      this.pausedFor2Seconds = true;

   

    },
    async editScoree(id) {
      console.log(id);
      alert(id)
    },
    async reputation() {
      this.valuee = true


      http.get("/getReputation").then((response) => {
        console.log(response)

        this.reputationDetails = response.data


        if (response.status == 401) {
          localStorage.clear('TOKEN')
          window.location.reload()
        }
        this.valuee = false

      });
    

    },

  },

  beforeMount() {
    this.reputation();
  }

}
</script>

<style>
.loader {
  --height-of-loader: 4px;
  --loader-color: #0071e2;
  width: 100%;
  height: var(--height-of-loader);
  border-radius: 30px;
  background-color: rgba(0, 0, 0, 0.2);
  position: relative;
}

.loader::before {
  content: "";
  position: absolute;
  background: var(--loader-color);
  top: 0;
  left: 0;
  width: 0%;
  height: 100%;
  border-radius: 30px;
  animation: moving 1s ease-in-out infinite;
  
}

@keyframes moving {
  50% {
    width: 100%;
  }

  100% {
    width: 0;
    right: 0;
    left: unset;
  }
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;

}
input[type="number"] {
    -moz-appearance: textfield;
}
</style>