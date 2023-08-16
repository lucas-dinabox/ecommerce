<template>
    <div>
      <span>{{ product.name }} - ${{ product.price }}</span>
      <button @click="deleteProduct">Delete</button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      product: {
        type: Object,
        required: true,
      },
    },
    methods: {
      deleteProduct() {
        this.$emit('deleteProduct', this.product.id);
      },
    },
  };
  </script>
  