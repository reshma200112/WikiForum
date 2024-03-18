<template>
    <div id="body">
      <div class="history-title"><h2>Activity History</h2></div>
      <div class="history-table">
      <div class="table-contents-history">
      <v-table >
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-left">
              Name
            </th>
            <th class="text-left">
              Calories
            </th>
            <th class="text-left">
              Calories
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="item in desserts"
            :key="item.name" class="history-rows"
          >
            <td>{{ item.name }}</td>
            <td>{{ item.calories }}</td> 
            <td>{{ item.name }}</td>
            <td>{{ item.calories }}</td>
          </tr>
        </tbody>
      </v-table>
    </div>
  </div>
    </div>
    </template>
    <style scoped>
    .history-table{
      display: flex;
      justify-content: center;
    }
    .history-title{
      padding-top: 20px;
      padding-bottom: 20px;
      display: flex;
      justify-content: center;
      flex-direction: row;
    }
    .table-contents-history{
      width: 960px;
    }
    .history-rows:hover{
  background-color: rgba(204, 206, 206, 0.295);
    }
  </style>
    <script>
    export default {
      name:'activity-history',
      data () {
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
            },    {
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
            },    {
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