<template>
    <div>
      <h2>Product List</h2>
      <ul>
        <li v-for="product in products" :key="product.id">
          <Product :product="product" @deleteProduct="deleteProduct" />
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import Product from './Product.vue';
  
  export default {
    components: {
      Product,
    },
    data() {
      return {
        products: [],
      };
    },
    mounted() {
      this.fetchProducts();
    },
    methods: {
      fetchProducts() {
        axios.get('http://localhost/e_commerce/product/', {headers: {Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiYWRtaW4iLCJ1c2VyaWQiOiIxIiwidXNlcm5hbWUiOiJBZG1pbmlzdHJhdG9yIiwidXNlcm1haWwiOiJhZG1pbkBhZG1pbi5uZXQiLCJleHBpcmVzIjoxNjg5NzMyNDA1fQ.W-UC5sFNK1Mj8hEce_-ATOhZgF0If4uH8SrmGl4M8bg'}})
          .then((response) => {
            this.products = response.data;
          })
          .catch((error) => {
            console.error(error);
          });
      },
      deleteProduct(productId) {
        axios.delete(`http://your-api-url/products/${productId}`)
          .then(() => {
            this.fetchProducts();
          })
          .catch((error) => {
            console.error(error);
          });
      },
    },
  };
  </script>
  