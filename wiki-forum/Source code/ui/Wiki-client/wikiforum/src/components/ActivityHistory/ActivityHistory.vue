<template>
  <div id="body" class="history">
    <div class="history-title">
      <h2>Activity History</h2>
    </div>
    <div class="history-table">
      <div class="table-contents-history">
        <v-table class="">
          <thead>
            <tr>
              <th class="text-left">
                What
              </th>
              <th class="text-left">
                Action
              </th>
              <th class="text-left">
                Comments
              </th>
              <!-- <th class="text-left">
                Calories
              </th> -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in desserts" :key="item.name" class="history-rows">
              <td>
                <!-- {{}} -->
                <div v-if="item.name = 'Eclair'">
                  <p>Question Based</p>
                </div>
                <div v-if="a == 2">
                  <p>Answer Based</p>
                </div>
                <div v-if="a == 3">
                  <p>Comment Based</p>
                </div>
              </td>

              <td>
                <div v-if="a == 1">
                  <p>Question Edited</p>
                </div>
                <div v-if="a == 2">
                  <p>Answer Added</p>
                </div>
                <div v-if="a == 3">
                  <p>Answer Edited</p>
                </div>
                <div v-if="a == 4">
                  <p>Answer Deleted</p>
                </div>
                <div v-if="a == 5">
                  <p>Answer Verified</p>
                </div>
                <div v-if="a == 6">
                  <p>Comment Added</p>
                </div>
                <div v-if="a == 7">
                  <p>Comment Edited</p>
                </div>
                <div v-if="a == 8">
                  <p>Comment Deleted</p>
                </div>
                <div v-if="a == 9">
                  <p>Question Deleted</p>
                </div>
                <div v-if="a == 10">
                  <p>Question Closed</p>
                </div>
                {{ item.calories }}
              </td>
              <td>{{ item.name }}</td>
              <!-- <td>{{ item.calories }}</td> -->
            </tr>
          </tbody>
        </v-table>
      </div>
    </div>
  </div>
</template>
<style scoped>
.history-table {
  display: flex;
  justify-content: center;
}

.history-title {
  padding-top: 20px;
  padding-bottom: 20px;
  display: flex;
  justify-content: center;
  flex-direction: row;
}

.table-contents-history {
  width: 960px;
}

.history-rows:hover {
  background-color: rgba(204, 206, 206, 0.295);
}
</style>
<script>
export default {
  name: 'activity-history',
  data() {
    return {
      desserts: [
        {
          name: 'Frozen Yogurt',
          calories: 159,
        },
        {
          name: 'Ice cream sandwich',
          calories: 237,
        },
        {
          name: 'Eclair',
          calories: 262,
        },
        {
          name: 'Cupcake',
          calories: 305,
        },
        {
          name: 'Gingerbread',
          calories: 356,
        }, {
          name: 'Jelly bean',
          calories: 375,
        },
        {
          name: 'Lollipop',
          calories: 392,
        },
        {
          name: 'Honeycomb',
          calories: 408,
        },
        {
          name: 'Donut',
          calories: 452,
        },
        {
          name: 'KitKat',
          calories: 518,
        }, {
          name: 'Jelly bean',
          calories: 375,
        },
        {
          name: 'Lollipop',
          calories: 392,
        },
        {
          name: 'Honeycomb',
          calories: 408,
        },
        {
          name: 'Donut',
          calories: 452,
        },
        {
          name: 'KitKat',
          calories: 518,
        },
        {
          name: 'Jelly bean',
          calories: 375,
        },
        {
          name: 'Lollipop',
          calories: 392,
        },
        {
          name: 'Honeycomb',
          calories: 408,
        },
        {
          name: 'Donut',
          calories: 452,
        },
        {
          name: 'KitKat',
          calories: 518,
        },
      ],
    }
  },
}
</script>
<style scoped></style>