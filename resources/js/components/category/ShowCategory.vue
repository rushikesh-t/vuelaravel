<template>
  <div class="m-5">
    <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="showAlt">
      Category delete successfully!
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="card">
      <h5 class="card-header bg-success text-white">Category</h5>

      <template v-if="isLoading">
        <div class="w-100 bg-light text-center" style="height:200px">
          <div class="lds-circle">
            <div></div>
          </div>
        </div>
      </template>

      <template v-if="!isLoading">
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Category Name</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(category, index) in categories" :key="category.id">
                <th>{{index + 1}}</th>
                <td>{{category.category_name}}</td>
                <td>
                  <router-link
                    :to="'category/edit/'+ category.id"
                    tag="button"
                    class="btn btn-primary"
                    active-class="active"
                  >Edit</router-link>
                </td>
                <td>
                  <button class="btn btn-danger" @click.prevent="deleteCategory(category.id)">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      categories: [],
      showAlt: false,
      isLoading: false
    };
  },
  mounted() {
    this.loadCategory();
  },
  methods: {
    loadCategory() {
      let validToken = localStorage.getItem('token');
      this.isLoading = true;
      axios.get("/api/category",
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }).then(response => {
        this.categories = response.data;
        this.isLoading = false;
      });
    },
    deleteCategory(id) {
      let validToken = localStorage.getItem('token');
      const vm = this;
      axios
        .post("/api/deletecategory", {
          id: id
        },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          })
        .then(function(response) {
          if (response.data.status == "success") {
            vm.loadCategory();
            vm.showAlt = true;
            setTimeout(function() {
              vm.showAlt = false;
            }, 3000);
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.lds-circle {
  display: inline-block;
  transform: translateZ(1px);
  padding: 70px 0;
  text-align: center;
}
.lds-circle > div {
  display: inline-block;
  width: 64px;
  height: 64px;
  margin: 8px;
  border-radius: 50%;
  background: #777;
  animation: lds-circle 2.4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
@keyframes lds-circle {
  0%,
  100% {
    animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
  }
  0% {
    transform: rotateY(0deg);
  }
  50% {
    transform: rotateY(1800deg);
    animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
  }
  100% {
    transform: rotateY(3600deg);
  }
}
</style>