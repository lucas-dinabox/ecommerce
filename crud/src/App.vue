<template>
  <div id="app">
    <ProductForm @productCreated="fetchProducts" />
    <ProductList />
  </div>
</template>

<script>
import ProductList from './components/ProductList.vue';
import ProductForm from './components/ProductForm.vue';

export default {
  components: {
    ProductList,
    ProductForm,
  },
};
</script>
