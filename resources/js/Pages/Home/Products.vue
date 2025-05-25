<template>
   <div class="categories_content">
      <a
        :href="'/category/' + item.id"
        class="category_item"
        v-for="item in categories.filter(c => c.id !== 1000).slice(0, 4)"
        :key="item.id"
      >
        {{ item.name_ru }}
      </a>
    </div>

    <div class="categories_content categories_content_small">
      <div
        class="category_item"
        v-for="item in categoryBanners"
        :class="{ category_item_active: item.type }"
        @click="changeProducts(item.id)"
        :key="item.id"
      >
        <div>{{ item.title }}</div>
      </div>
    </div>
  <div class="carousel_full_wrapper">
    <!-- категории: оставлены без изменений -->
   

    <!-- карусель товаров -->
    <div class="carousel_wrapper">
      <!-- <h2 class="carousel_title">Популярные товары</h2> -->

      <div class="arrow left">
        <button @click="prevSlide">
          <img src="images/arrow_carusel.svg"/>
        </button>
      </div>

      <div class="products_content_wrapper" ref="carouselWrapper">
        <div
          class="products_content"
          :style="carouselStyle"
          @transitionend="handleTransitionEnd"
        >
          <div
            class="products_content_item"
            v-for="(item, index) in displayProducts"
            :key="index"
          >
            <div class="discount" v-if="item.discount">{{ item.discount }}%</div>
            <div class="image">
              <img :src="item.image" />
            </div>
            <div class="text_cotent">
              <div class="title">{{ item.name_ru }}</div>
              <div class="rating">
                <img src="/images/stars.png" />
                <div>(100)</div>
              </div>
              <div class="size">
                <span>{{ item.size }}</span>
              </div>
              <div class="price">
                <span class="big">{{ item.price }} դ</span>
                <span
                  v-if="item.discount"
                  class="small"
                >{{ item.price + (item.price * item.discount / 100) }} դ</span>
              </div>
            </div>
            <button>В корзину</button>
          </div>
        </div>
      </div>

      <div class="arrow right">
        <button @click="nextSlide">
          <img src="images/arrow_carusel.svg"/>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      products: [],
      displayProducts: [],
      currentIndex: 5,
      itemWidth: 222.5,
      transitionEnabled: true,
      categories: [],
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
    };
  },
  computed: {
    carouselStyle() {
      return {
        transform: `translateX(-${this.currentIndex * this.itemWidth}px)`,
        transition: this.transitionEnabled ? "transform 0.5s ease-in-out" : "none",
      };
    },
  },
  mounted() {
    axios.get("/random-categories").then((response) => {
      this.categories = response.data.categories
    });
    this.loadProducts();
    // this.loadCategories();
    // this.loadCategoryBanners();
  },
  methods: {
    async loadProducts() {
      try {
        const { data } = await axios.get("/products?is_bestseller=1");
        this.products = data.products.data;

        const head = this.products.slice(-5);
        const tail = this.products.slice(0, 5);
        this.displayProducts = [...head, ...this.products, ...tail];
      } catch (err) {
        console.error("Ошибка при загрузке продуктов:", err);
      }
    },
    // async loadCategories() {
    //   try {
    //     const { data } = await axios.get("/categories");
    //     this.categories = data.categories || [];
    //   } catch (err) {
    //     console.error("Ошибка при загрузке категорий:", err);
    //   }
    // },
    // async loadCategoryBanners() {
    //   try {
    //     const { data } = await axios.get("/category-banners");
    //     this.categoryBanners = data || [];
    //   } catch (err) {
    //     console.error("Ошибка при загрузке баннеров категорий:", err);
    //   }
    // },
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
          const head = this.products.slice(-5);
          const tail = this.products.slice(0, 5);
          this.displayProducts = [...head, ...this.products, ...tail];
        });
      }else if(id == 2){
        axios.get("/products?is_new=1").then((response) => {
          this.products = response.data.products.data.slice(0, 5);
          const head = this.products.slice(-5);
          const tail = this.products.slice(0, 5);
          this.displayProducts = [...head, ...this.products, ...tail];
        });
      }else{
        axios.get("/products?discount=1").then((response) => {
            this.products = response.data.products.data.slice(0, 5);
            const head = this.products.slice(-5);
            const tail = this.products.slice(0, 5);
            this.displayProducts = [...head, ...this.products, ...tail];
        });
      }
    },
    nextSlide() {
      this.transitionEnabled = true;
      this.currentIndex++;
    },
    prevSlide() {
      this.transitionEnabled = true;
      this.currentIndex--;
    },
    handleTransitionEnd() {
      const total = this.products.length;
      if (this.currentIndex >= total + 5) {
        this.transitionEnabled = false;
        this.currentIndex = 5;
      }
      if (this.currentIndex < 5) {
        this.transitionEnabled = false;
        this.currentIndex = total + 4;
      }
    },
  },
};
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
  margin-bottom: 25px;
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
.carousel_full_wrapper {
  width: 1100px;
  margin: 1rem auto;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.carousel_wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  position: relative;

  .carousel_title {
    text-align: left;
    width: 100%;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #014e2e;
  }

  .arrow {
    // background: #ffffff;
    color: #014e2e;
    font-size: 24px;
    width: 50px;
    height: 100%;
    position: absolute;
    z-index: 1;

    button {
      display: flex;
      align-items: center;
      justify-content: center;
      background: #F4F3F7;
      cursor: pointer;
      position: absolute;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      top: calc(50% - 20px);
      border: none;
      img{
        width: 24px;
        height: 24px;
      }
    }
    &.left {
      left: 0;
      // background: linear-gradient(to right, white 80%, transparent 100%);
      button{
        right: 68px;
      }
    }

    &.right {
      right: 0;
      // background: linear-gradient(to left, white 80%, transparent 100%);
       button{
        img{
          transform: rotate(180deg);
        }
        left: 68px;
      }
    }
  }
}

.products_content_wrapper {
  overflow: hidden;
  width: 100%;
}

.products_content {
  display: flex;
}

.products_content_item {
  width: 210px;
  margin-right: 12.5px;
  flex-shrink: 0;
  height: 420px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.11);
  border-radius: 5px;
  position: relative;
  background: #fff;

  .image img {
    width: 195px;
    height: 195px;
    margin: 20px auto 0;
    object-fit: cover;
    display: block;
  }

  .text_cotent {
    padding: 0 10px;
    height: 135px;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;

    .title {
      font-size: 1rem;
    }

    .rating {
      display: flex;
      align-items: center;

      img {
        height: 14px;
      }

      div {
        margin-left: 10px;
      }
    }

    .size {
      font-size: 12px;
      color: #767676;
    }

    .price {
      .big {
        font-size: 1.3125rem;
        color: #ba1051;
        font-weight: 700;
      }

      .small {
        font-size: 12px;
        text-decoration: line-through;
        margin-left: 5px;
        color: #767676;
      }
    }
  }

  button {
    width: 100%;
    height: 60px;
    font-size: 1.0625rem;
    background: #014e2e;
    color: #fff;
    font-weight: 700;
    text-transform: uppercase;
    border-radius: 5px;
    margin: 2px 0 10px 0;
    border: none;
  }

  .discount {
    position: absolute;
    top: 0;
    right: 0;
    background: #143616;
    color: white;
    width: 55px;
    height: 33px;
    border-radius: 0 5px 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>
