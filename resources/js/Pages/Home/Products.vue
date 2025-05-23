<template>
  <div class="categories_content">
    <a :href="/category/+item.id" class="category_item" v-for="item in categories
      .filter(item => item.id !== 1000)
      .slice(0, 4)" :key="item.id">
      <span :href="/category/+item.id">
        {{ item.name_ru }}
      </span>
    </a>
  </div>
  <div class="categories_content categories_content_small">
    <div class="category_item" v-for="item in categoryBanners" :class="{ category_item_active: item.type}" @click="changeProducts(item.id)">
      <div>
        {{item.title}}
      </div>
    </div>
  </div>
  <div class="products_content">
    <div class="products_content_item" v-for="item in products">
      <div class="discount" v-if="item.discount">{{ item.discount }}%</div>
      <div class="image">
        <img :src="item.image" />
      </div>
      <div class="text_cotent">
        <div class="title">
          {{ item.name_ru }}
        </div>
        <div class="rating">
          <img src="/images/stars.png">
          <div>(100)</div>
        </div>
        <div class="size">
          <span>{{ item.size }}</span>
        </div>
        <div class="price">
          <span class="big">{{ item.price }} դ</span>
          <span v-if="item.discount" class="small">{{ item.price+(item.price*item.discount/100) }} դ</span>
        </div>
        
      </div>
      <button>В корзину</button>
    </div>
  </div>
</template>

<script>

import axios from "axios";

export default {
  components: {

  },
  props: {
  },
  mounted(){
    this.changeProducts(1);
  },
  data() {
    return {
      categories:[],
      products:[],
      categoryBanners: [
        {
            id:1,
            title: 'Бестселлеры',
            type:true,
        },
        {
            id:2,
            title: 'НОВИНКИ',
            type:false,
        },
        {
            id:3,
            title: 'Скидки',
            type:false,
        },
      ],
      form: {
      },
    }
  },
  mounted(){
    axios.get("/random-categories").then((response) => {
      this.categories = response.data.categories
    });
    this.changeProducts(1);
  },
  methods: {
    changeProducts(id){
      this.categoryBanners
      this.categoryBanners.forEach((cat) => {
        cat.type = false;
        if(cat.id == id){
          cat.type = true;
        }
    });
    if(id == 1){
      axios.get("/products?is_bestseller=1").then((response) => {
        this.products = response.data.products.data.slice(0, 5);
      });
    }else if(id == 2){
      axios.get("/products?is_new=1").then((response) => {
        this.products = response.data.products.data.slice(0, 5);
      });
    }else{
      axios.get("/products?discount=1").then((response) => {
        this.products = response.data.products.data.slice(0, 5);
      });
    }
    // console.log(this.products)
    },
  },
}
</script>
<style scoped lang="scss">
.categories_content{
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 1100px;
  margin: 0 auto;
  .category_item{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 250px;
    height: 50px;
    background:#D3E1D4 ;
    border-radius: 25px;
    font-size: .75rem;
    font-weight: 700;
    color: #000;
    text-transform: uppercase;
  }
}
.categories_content_small{
  justify-content: center!important;
  .category_item{
    margin: 0 10px;
    width: 150px;
    height: 40px;
    margin-top: 1.5625rem;
    color: #000;
    background: #FFF;
    border: 1px solid #000;
    cursor: pointer;
  }
  .category_item_active{
    background:#D3E1D4 ;
  }
}
.products_content{
  display: flex;
  width: 1100px;
  margin: 1rem auto 0 auto;
  .products_content_item{
    width: 210px;
    height: 420px;
    position: relative;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, .11);
    margin-right: 12.5px;
    border-radius: 5px;
    .discount{
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      background: #143616;
      border-radius: 0 5px 0 0;
      width: 55px;
      height: 33px;
      color: #FFF;
      right: 0;
      top: 0;
      font-size: .875rem;
      font-weight: 600;
    }
    .image{
      margin-top: 20px;
      width: 100%;
      img{
        width: 195px;
        height: 195px;
        margin: 0 auto;
        object-fit: cover;
      }
    }
    .text_cotent{
      display: flex;
      flex-direction: column;
      justify-content: space-evenly;
      height: 135px;
      padding: 0 10px;
      .title{
        padding: 5px 0;
        font-size: 1rem;
      }
      .rating{
        display: flex;
        align-items: center;
        div{
          margin-left: 10px;
        }
      }
      .size{
        font-size: 12px;
        color: #767676;
      }
      .price{
        .big{
          color: #ba1051;
          font-size: 1.3125rem;
          font-weight: 700;
        }
        .small{
          font-size: 12px;
          color: #767676;
          margin-left: 5px;
          text-decoration: line-through;
        }
      }
   
    }
    button{
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        border-radius: 5px;
        font-size: 1.0625rem;
        height: 60px;
        color: #FFF;
        background: #014e2e;
        margin-bottom: 10px;
        font-weight: 700;
        text-transform: uppercase;
        margin-top: 2px;
      }
    
  }
  .products_content_item:nth-child(5n){
    margin-right: 0;
  }
}
</style>
