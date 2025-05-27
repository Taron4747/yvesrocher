<template>
  <div class="current_content">
    <div class="title">Текущие <b>предложения</b></div>
    <div class="title_small"><span>Войдите в свою учетную запись, чтобы воспользоваться скидкой 35% на 3 продукта + бесплатный подарок!*</span></div>
    <div class="data_content">
      <a :href="item.link" class="data" v-for="item in imageBanners.slice(0, 3)">
        <div class="data_top">
          <img :src="item['image_medium']"/>
        </div>
        <div class="data_bottom">
          <div class="title">{{ item[`title_${$page.props.locale}`]}}</div>
          <div class="text"  v-html="item[`proposition_${$page.props.locale}`]" />
          <button>{{ this.$page.props.language.shop_now }}</button>
        </div>
      </a>
      <!-- <div class="data">
        <div class="data_top">
          <img :src="images[1]['image_medium']"/>
        </div>
        <div class="data_middle">
          <div class="title">праздничная коллекция</div>
          <div class="text">Познакомьтесь с нашими праздничными наборами, которые идеально подходят для знакомства с нашими лимитированными ароматами как раз перед сезоном подарков!</div>
        </div>
        <div class="data_bottom">
          <button>Обнаружить</button>
        </div>
      </div>
      <div class="data">
        <div class="data_top">
          <img :src="images[2]['image_medium']"/>
        </div>
        <div class="data_middle">
          <div class="title">3-х этапная программа ухода за телом</div>
          <div class="text">Потратьте $70+ и получите бесплатный ритуал по уходу за телом в дорожном формате! Включает гель для душа, скраб для тела и питательный лосьон для тела.</div>
        </div>
        <div class="data_bottom">
          <button>Узнать больше</button>
        </div>
      </div> -->
    </div>
  </div>
</template>

<script>


export default {
  components: {

  },
  props: {
    imageBanners:Object
  },
  created(){
    this.images =  Object.values(this.imageBanners);
    // console.log(this.images[1]['image_medium'])
    // console.log(this.images[2]['image_medium'])
  },
  data() {
    return {
      images:[],
      form: {
      },
    }
  },
  
  methods: {
    
  },
}
</script>
<style scoped lang="scss">
.current_content{
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin: 50px auto 0 auto;
  width: 1140px;
  // color: #014E2E;
  .title{
    font-size: 28px;
  }
  .title_small{
    display: flex;
    align-items: center;
    justify-content: center;
    background: #6F7E0D1A;
    padding: 10px 24px;
    border-radius: 10px;
    margin: 16px 0;
  }
  .data_content{
    display: flex;
    .data{
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-radius: 20px;
      background: #6F7E0D1A;
      height: 554px;
      width: 364px;
      margin-right: 24px;
      .data_top{
        img{
          width: 100%;
          height: 304px;
          border-radius: 20px 20px 0 0;
        }
      }
      .data_bottom{
        display: flex; 
        flex-direction: column; 
        justify-content: flex-start; 
        padding: 22px;
        height: 250px;
        background-color: #D3E1D4;
        border-radius: 0 0 20px 20px;
        .title{
            font-size: 20px;
            margin-bottom: 20px;
            // color: #000;
            text-transform: uppercase;
            font-weight: 600;
        }
        .text{
          text-align: left;
          // font-size: 16px;
          // color: #767676;
        }
        button{
          padding: 10px 15px;
          border-radius: 5px;
          float: left;
          display: block;
          box-sizing: border-box;
          min-width: 135px;
          margin-bottom: 5px;
          font-weight: 700;
          width: max-content;
          margin-top: auto;
          text-transform: uppercase;
          background-color: #000;
          color: #fff;
        }
      }
    }
    .data:last-child{
      margin-right: 0;
    }
  }
}
</style>
