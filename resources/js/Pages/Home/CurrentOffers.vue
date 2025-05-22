<template>
  <div class="current_content">
    <div class="title">Текущие <b>предложения</b></div>
    <div class="title_small"><span>Войдите в свою учетную запись, чтобы воспользоваться скидкой 35% на 3 продукта + бесплатный подарок!*</span></div>
    <div class="data_content">
      <div class="data" v-for="item in imageBanners.slice().reverse().slice(0, 3)">
        <div class="data_top">
          <img :src="item['image_medium']"/>
        </div>
        <div class="data_middle">
          <div class="title">{{ item.title_ru }}</div>
          <div class="text">{{ item.proposition_ru  }}</div>
        </div>
        <div class="data_bottom">
          <button>Воспользуйтесь преимуществом</button>
        </div>
      </div>
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
  color: #014E2E;
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
      border-radius: 8px;
      background: #6F7E0D1A;
      height: 426px;
      width: 369px;
      margin-right: 9px;
      .data_top{
        img{
          width: 100%;
          height: 202px;
          border-radius: 8px 8px 0 0;
        }
      }
      .data_middle{
        padding: 0 30px;
        .title{
          font-size: 18px;
          text-align: center;
          margin-bottom: 8px;
        }
        .text{
          font-size: 16px;
          color: #767676;
          text-align: center;
        }
      }
      .data_bottom{
        display: flex;
        border-radius: 0 0 8px 8px;
        button{
          margin: 0 auto;
          padding: 10px 24px;
          background: #014E2E;
          color: #FFFFFF;
          border-radius: 20px;
          font-weight: 600;
          margin-bottom: 20px;
        }
      }
    }
    .data:last-child{
      margin-right: 0;
    }
  }
}
</style>
