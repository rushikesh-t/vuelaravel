<template>
  <div class="m-5">
    <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="showAlt">
      User Updated Successfully!
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="showWar">
      {{altMsg}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="card">
      <h5 class="card-header bg-primary text-white">Update User</h5>

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
                <label for="userName">User Name</label>
                <input
                  type="text"
                  v-model.number="user.userName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.userName.$error }"
                />
                <div
                  v-if="submitted && !$v.user.userName.required"
                  class="invalid-feedback"
                >User Name is required</div>
                <div
                  v-if="submitted && !$v.user.userName.minLength"
                  class="invalid-feedback"
                >User Name must have at least {{$v.user.userName.$params.minLength.min}} letters.</div>
                <div
                  v-if="submitted && !$v.user.userName.maxLength"
                  class="invalid-feedback"
                >User Name must have at most {{$v.user.userName.$params.maxLength.max}} letters.</div>
              </div>
            </div>

            <div class="row">
              <div class="col form-group">
                <label for="email">Email</label>
                <input
                  type="text"
                  v-model.trim="user.email"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.email.$error }"
                />
                <div
                  v-if="submitted && !$v.user.email.required"
                  class="invalid-feedback"
                >Email is required</div>
                <div
                  v-if="submitted && !$v.user.email.email"
                  class="invalid-feedback"
                >Please enter valid Email Address</div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="firstName">First Name</label>
                  <input
                    type="text"
                    v-model.trim="user.firstName"
                    class="form-control"
                    :class="{ 'is-invalid': submitted && $v.user.firstName.$error }"
                  />
                  <div
                    v-if="submitted && !$v.user.firstName.required"
                    class="invalid-feedback"
                  >First Name is required</div>
                  <div
                    v-if="submitted && !$v.user.firstName.minLength"
                    class="invalid-feedback"
                  >First Name must have at least {{$v.user.firstName.$params.minLength.min}} letters.</div>
                  <div
                    v-if="submitted && !$v.user.firstName.maxLength"
                    class="invalid-feedback"
                  >First Name must have at most {{$v.user.firstName.$params.maxLength.max}} letters.</div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input
                    type="text"
                    v-model.trim="user.lastName"
                    class="form-control"
                    :class="{ 'is-invalid': submitted && $v.user.lastName.$error }"
                  />
                  <div
                    v-if="submitted && !$v.user.lastName.required"
                    class="invalid-feedback"
                  >Last Name is required</div>
                  <div
                    v-if="submitted && !$v.user.lastName.minLength"
                    class="invalid-feedback"
                  >Last Name must have at least {{$v.user.lastName.$params.minLength.min}} letters.</div>
                  <div
                    v-if="submitted && !$v.user.lastName.maxLength"
                    class="invalid-feedback"
                  >Last Name must have at most {{$v.user.lastName.$params.maxLength.max}} letters.</div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col form-group">
                <label for="age">age</label>
                <input
                  type="text"
                  v-model.number="user.age"
                  id="firstName"
                  name="firstName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.age.$error }"
                />
                <div
                  v-if="submitted && !$v.user.age.required"
                  class="invalid-feedback"
                >Age is required</div>
                <div
                  v-if="submitted && !$v.user.age.between"
                  class="invalid-feedback"
                >Age should between {{$v.user.age.$params.between.min}} to {{$v.user.age.$params.between.max}}</div>
              </div>
              <div class="col form-group">
                <label for="Gender">Gender</label>
                <div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      value="Male"
                      v-model="user.gender"
                      :class="{ 'is-invalid': submitted && $v.user.gender.$error }"
                    />
                    <label class="form-check-label" for="Male">Male</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      value="Female"
                      v-model="user.gender"
                      :class="{ 'is-invalid': submitted && $v.user.gender.$error }"
                    />
                    <label class="form-check-label" for="Female">Female</label>
                  </div>
                </div>
                <div
                  v-if="submitted && !$v.user.gender.required"
                  class="invalid-feedback"
                  style="display: block"
                >Gender is required</div>
              </div>
            </div>

            <div class="row">
              <div class="col form-group">
                <label for="oldPassword">Old Password</label>
                <input type="text" v-model.number="user.oldPassword" class="form-control" />
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="password">New Password</label>
                  <input
                    type="password"
                    v-model.trim="user.password"
                    class="form-control"
                    :class="{ 'is-invalid': submitted && $v.user.password.$error }"
                  />
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.password.required"
                  >Password is required.</div>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.password.minLength"
                  >Password must have at least {{ $v.user.password.$params.minLength.min }} letters.</div>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.password.maxLength"
                  >Password must have at most {{ $v.user.password.$params.maxLength.max }} letters.</div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="confirmPassword">Confirm Password</label>
                  <input
                    type="password"
                    v-model.trim="user.confirmPassword"
                    class="form-control"
                    :class="{ 'is-invalid': submitted && $v.user.confirmPassword.$error }"
                  />
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.confirmPassword.sameAsPassword"
                  >Passwords must be identical.</div>
                </div>
              </div>
              <router-link to="/forgot" tag="a" class="col-12">
                <div>Forgot Password?</div>
              </router-link>
            </div>

            <div class="row">
              <div class="col align-self-center">
                <label for="userImage">User Image</label>
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
              <div class="col-2" style="height:100px;">
                <template>
                  <img
                    :src="'/upload/image/user/'+ image"
                    class="align-self-center bg-light rounded-circle"
                    style="height:100px; width:100px;"
                  />
                </template>
              </div>
            </div>

            <div class="row mb-2 mt-2">
              <div class="col-3">
                <button class="btn btn-primary col-11">Update</button>
              </div>
              <template v-if="this.$store.getters.permission == 2">
                <div class="col row">
                  <label style="display: inline" for="Role" class="col-2">Role</label>
                  <select class="col-5 form-control" id="Role" v-model="user.type">
                    <option value="0">User</option>
                    <option value="1">Employee</option>
                    <option value="2">Admin</option>
                  </select>
                </div>
              </template>
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
  email,
  between,
  sameAs
} from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      user: {
        id: "",
        userName: "",
        email: "",
        firstName: "",
        lastName: "",
        age: "",
        gender: "",
        oldPassword: "",
        password: "",
        confirmPassword: "",
        image: "",
        type: 0
      },
      image: "default.png",
      submitted: false,
      showAlt: false,
      showWar: false,
      isLoading: false,
      selectedImage: "Choose file",
      altMsg: ""
    };
  },
  mounted() {
    this.loaduser();
  },
  validations: {
    user: {
      userName: {
        minLength: minLength(5),
        maxLength: maxLength(15)
      },
      email: {
        email
      },
      firstName: {
        minLength: minLength(3),
        maxLength: maxLength(15)
      },
      lastName: {
        minLength: minLength(5),
        maxLength: maxLength(12)
      },
      age: {
        between: between(15, 60)
      },
      gender: {
        required
      },
      password: {
        minLength: minLength(6),
        maxLength: maxLength(15)
      },
      confirmPassword: {
        sameAsPassword: sameAs("password")
      }
    }
  },
  methods: {
    loaduser() {
      if (
        this.$store.getters.permission != 1 ||
        this.$route.params.user == this.$store.getters.userlog
      ) {
        this.isLoading = true;
        let validToken = localStorage.getItem("token");
        const vm = this;
        vm.user.userName = this.$route.params.user;
        axios
          .post(
            "/api/getuser",
            {
              user: vm.user.userName
            },
            {
              headers: {
                Authorization: "Bearer " + validToken
              }
            }
          )
          .then(function(response) {
            let data = response.data;
            vm.user.id = data.id;
            vm.user.userName = data.username;
            let name = data.name;
            vm.user.age = data.age;
            vm.user.email = data.email;
            vm.user.gender = data.gender;
            name = name.split(" ");
            vm.user.firstName = name[0];
            vm.user.lastName = name[1];
            if (data.avatar != null) {
              vm.image = data.avatar;
            }
            vm.user.type = data.type;
            vm.isLoading = false;
          })
          .catch(function(error) {
            console.log(error);
            vm.isLoading = false;
          });
      } else {
        this.$router.push({ path: "/home" });
      }
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
            "/api/edituser",
            {
              user: this.user
            },
            {
              headers: {
                Authorization: "Bearer " + validToken
              }
            }
          )
          .then(function(response) {
            console.log(response.data);
            vm.loaduser();
            if (response.data.status == "updated") {
              vm.$route.params.user = vm.user.userName;
              vm.submitted = false;
              vm.user.password = "";
              vm.user.confirmPassword = "";
              vm.showAlt = true;
              setTimeout(function() {
                vm.showAlt = false;
              }, 3000);
            }
            if (response.data == "userNameTaken") {
              vm.altMsg = "User Name Already Taken!";
              vm.showWar = true;
              setTimeout(function() {
                vm.showWar = false;
              }, 3000);
            }
            if (response.data == "emailTaken") {
              vm.altMsg = "Email Address Already Taken!";
              vm.showWar = true;
              setTimeout(function() {
                vm.showWar = false;
              }, 3000);
            }
            if (response.data == "passwordNotMatch") {
              vm.altMsg = "Old Password Doesn't Match (User Not Updated)";
              vm.showWar = true;
              setTimeout(function() {
                vm.showWar = false;
              }, 3000);
            }
            if (response.data.status == "success") {
              vm.submitted = false;
              vm.loaduser();
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    fileselected(event) {
      let imageFile = event.target.value.split("\\").pop();
      if (imageFile != "") {
        this.selectedImage = imageFile;

        let fileReader = new FileReader();
        let file = fileReader.readAsDataURL(event.target.files[0]);
        fileReader.onload = e => {
          this.user.image = e.target.result;
        };
      } else {
        this.selectedImage = "Choose file";
        this.user.image = "";
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