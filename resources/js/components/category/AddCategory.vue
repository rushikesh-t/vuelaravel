<template>
  <div class="m-5">
    <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="showWar">
      Catagory Name Already Taken!
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
      <h5 class="card-header bg-primary text-white">Add Category</h5>

      <template v-if="isLoading">
        <div class="w-100 bg-light text-center" style="height:300px">
          <div class="lds-circle">
            <div></div>
          </div>
        </div>
      </template>

      <template v-else>
        <div class="card-body">
          <form @submit.prevent="submit">
            <div class="row">
              <div class="col form-group">
                <label for="categoryName">Caregory Name</label>
                <input
                  type="text"
                  v-model.trim="categoryName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.categoryName.$error }"
                />
                <div
                  v-if="submitted && !$v.categoryName.required"
                  class="invalid-feedback"
                >Category Name is required</div>
                <div
                  v-if="submitted && !$v.categoryName.minLength"
                  class="invalid-feedback"
                >Category Name must have at least {{$v.categoryName.$params.minLength.min}} letters.</div>
                <div
                  v-if="submitted && !$v.categoryName.maxLength"
                  class="invalid-feedback"
                >Category Name must have at most {{$v.categoryName.$params.maxLength.max}} letters.</div>
              </div>
            </div>

            <div class="row mb-2 mt-2">
              <div class="col-3">
                <button class="btn btn-primary col-11">Add Category</button>
              </div>
              <div class="col-3">
                <button type="button" class="btn btn-danger col-11" @click="clear">Clear</button>
              </div>
            </div>
          </form>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import {
  required,
  minLength,
  maxLength,
  between,
  sameAs
} from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      categoryName: "",
      isLoading: false,
      submitted: false,
      showWar: false
    };
  },
  validations: {
    categoryName: {
      required,
      minLength: minLength(5),
      maxLength: maxLength(15)
    }
  },
  methods: {
    clear() {
      this.submitted = false;
      this.categoryName = "";
    },
    submit() {
      this.submitted = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      } else {
        let validToken = localStorage.getItem("token");
        const vm = this;
        axios
          .post(
            "/api/addcategory",
            {
              categoryName: vm.categoryName
            },
            {
              headers: {
                Authorization: "Bearer " + validToken
              }
            }
          )
          .then(function(response) {
            if (response.data == "success") {
              vm.$router.push("/category");
            } else if (response.data == "categoryNameTaken") {
              vm.submitted = false;
              vm.showWar = true;
              setTimeout(function() {
                vm.showWar = false;
              }, 3000);
            }
          })
          .catch(function(error) {
            vm.submitted = false;
            console.log(error);
          });
      }
    }
  }
};
</script>

<style scoped>
</style>