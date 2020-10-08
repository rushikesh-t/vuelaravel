<template>
  <div class="m-5">
    <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="showWar">
      Product Name Already Taken!
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
      <h5 class="card-header bg-primary text-white">Add Product</h5>

      <template v-if="isLoading">
        <div class="w-100 bg-light text-center" style="height:200px">
          <div class="lds-circle">
            <div></div>
          </div>
        </div>
      </template>

      <template v-if="!isLoading">
        <div class="card-body">
          <form @submit.prevent="submit">
            <div class="row">
              <div class="col form-group">
                <label for="productName">Product Name</label>
                <input
                  type="text"
                  v-model.trim="product.productName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.product.productName.$error }"
                />
                <div
                  v-if="submitted && !$v.product.productName.required"
                  class="invalid-feedback"
                >Product Name is required</div>
                <div
                  v-if="submitted && !$v.product.productName.minLength"
                  class="invalid-feedback"
                >Product Name must have at least {{$v.product.productName.$params.minLength.min}} letters.</div>
                <div
                  v-if="submitted && !$v.product.productName.maxLength"
                  class="invalid-feedback"
                >Product Name must have at most {{$v.product.productName.$params.maxLength.max}} letters.</div>
              </div>
            </div>

            <div class="row">
              <div class="col form-group">
                <label for="categoryId">Product category</label>
                <select
                  class="form-control"
                  v-model="product.categoryId"
                  :class="{ 'is-invalid': submitted && $v.product.categoryId.$error }"
                >
                  <template v-for="category in categories">
                    <option :value="category.id" :key="category.id">{{category.category_name}}</option>
                  </template>
                </select>
                <div
                  v-if="submitted && !$v.product.categoryId.required"
                  class="invalid-feedback"
                >Product Category is required</div>
              </div>
            </div>

            <div class="row">
              <div class="col form-group">
                <label for="productDescription">Product Description</label>
                <textarea
                  v-model.trim="product.productDescription"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.product.productDescription.$error }"
                />
                <div
                  v-if="submitted && !$v.product.productDescription.required"
                  class="invalid-feedback"
                >Product Description is Required</div>
              </div>
            </div>

            <div class="row">
              <div class="col form-group">
                <label for="productPrice">Product Price</label>
                <input
                  type="text"
                  v-model.number="product.productPrice"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.product.productPrice.$error }"
                />
                <div
                  v-if="submitted && !$v.product.productPrice.required"
                  class="invalid-feedback"
                >Product Price is Required</div>
                <div
                  v-if="submitted && !$v.product.productPrice.numeric"
                  class="invalid-feedback"
                >Enter Valid Price</div>
              </div>
            </div>

            <div class="row">
              <div class="col align-self-center">
                <label for="userImage">Product Image</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                  </div>
                  <div class="custom-file">
                    <input
                      type="file"
                      class="custom-file-input form-control"
                      id="userImage"
                      aria-describedby="inputGroupFileAddon01"
                      @change="fileselected"
                    />
                    <label class="custom-file-label" for="userImage">{{selectedImage}}</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-2 mt-2">
              <div class="col-3">
                <button class="btn btn-primary col-11">Add Product</button>
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
  numeric,
  minLength,
  maxLength,
  between,
  sameAs
} from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      product: {
        productName: "",
        categoryId: "",
        productDescription: "",
        productPrice: "",
        productImage: ""
      },
      categories: [],
      isLoading: false,
      submitted: false,
      showWar: false,
      selectedImage: "Choose file"
    };
  },
  validations: {
    product: {
      productName: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(15)
      },
      categoryId: {
        required
      },
      productDescription: {
        required
      },
      productPrice: {
        required,
        numeric
      }
    }
  },
  mounted() {
    this.loadCategory();
  },
  methods: {
    loadCategory() {
      let validToken = localStorage.getItem("token");
      this.isLoading = true;
      axios
        .get("/api/category", {
          headers: {
            Authorization: "Bearer " + validToken
          }
        })
        .then(response => {
          this.categories = response.data;
          this.isLoading = false;
        });
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
            "/api/addproduct",
            {
              product: vm.product
            },
            {
              headers: {
                Authorization: "Bearer " + validToken
              }
            }
          )
          .then(function(response) {
            if (response.data == "success") {
              vm.$router.push("/product");
            } else if (response.data == "productNameTaken") {
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
    },
    clear() {
      this.submitted = false;
      this.product.productName = "";
      this.product.categoryId = "";
      this.product.productDescription = "";
      this.product.productPrice = "";
      this.product.productImage = "";
      this.selectedImage = "Choose file";
    },
    fileselected(event) {
      let imageFile = event.target.value.split("\\").pop();
      if (imageFile != "") {
        this.selectedImage = imageFile;
        let fileReader = new FileReader();
        let file = fileReader.readAsDataURL(event.target.files[0]);
        fileReader.onload = e => {
          this.product.productImage = e.target.result;
        };
      } else {
        this.selectedImage = "Choose file";
      }
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