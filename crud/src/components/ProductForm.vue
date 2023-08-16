<template>
    <div>
      <h2>Create Product</h2>
      <form @submit.prevent="createProduct">
        <label>
          Name:
          <input type="text" v-model="name" required />
        </label>
        <br />
        <label>
          Price:
          <input type="number" v-model="price" required />
        </label>
        <br />
        <button type="submit">Create</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        name: '',
        price: 0,
      };
    },
    methods: {
      createProduct() {
        const newProduct = {
          name: this.name,
          price: this.price,
        };
  
        axios.post('http://your-api-url/products', newProduct)
          .then(() => {
            this.name = '';
            this.price = 0;
            this.$emit('productCreated');
          })
          .catch((error) => {
            console.error(error);
          });
      },
    },
  };
  </script>
  