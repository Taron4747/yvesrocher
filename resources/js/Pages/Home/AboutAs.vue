<template>
  <div class="about_content">
    <div class="title" v-html="this.$page.props.language.about_us"></div>
    <div class="content">
      <div class="content_item content_item_1">
        <div class="title">Our product, our expertise</div>
        <div class="text">Routines, collections and diagnosis</div>
        <div class="link">Discover</div>
      </div>
      <div class="content_item content_item_2">
        <div class="title">Our history</div>
        <div class="text">Pioneer of Botanical Beauty</div>
        <div class="link">Learn More</div>
      </div>
      <div class="content_item content_item_3">
        <div class="title">Our Commitments</div>
        <div class="text">Our love acts speak louder than words</div>
        <div class="link">Learn More</div>
      </div>
      <div class="content_item content_item_4">
        <div class="title">Our Blog</div>
        <div class="text">To inspire, share and engage</div>
        <div class="link">Learn More</div>
      </div>

    </div>
  </div>
</template>

<script>


export default {
  components: {

  },
  props: {
  },
  mounted(){
    
  },
  data() {
    return {
      form: {
      },
    }
  },
  
  methods: {
    
  },
}
</script>
<style scoped lang="scss">
.about_content{
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin: 80px auto 0 auto;
  width: 1140px;
  color: #014E2E;
  .title{
    font-size: 28px;
    margin-bottom: 40px;
  }
  .content{
    display: flex;
    justify-content: space-between;
    width: 100%;
    .content_item{
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      width: 267px;
      height: 360px;
      text-align: center;
      color: #FFFFFF;
      padding: 24px;
      border-radius: 15px;
      background-position: top;
      .title{
        font-size: 18px;
        margin-bottom: 4px;
        line-height: 100%;
      }
      .text{
        font-weight: 400;
        font-size: 16px;
        margin-bottom: 8px;
        line-height: 100%;
      }
      .link{
        font-weight: 500;
        line-height: 100%;
        text-decoration: underline;
      }
    }
    .content_item_1{
      background-image: linear-gradient(to bottom, rgba(0,0,0, 0),rgba(0,0,0, 100)),url("/images/about_us_1.webp");
    }
    .content_item_2{
      background-image: linear-gradient(to bottom, rgba(0,0,0, 0),rgba(0,0,0, 100)),url("/images/about_us_2.webp");
    }
    .content_item_3{
      background-image: linear-gradient(to bottom, rgba(0,0,0, 0),rgba(0,0,0, 100)),url("/images/about_us_3.webp");
    }
    .content_item_4{
      background-image: linear-gradient(to bottom, rgba(0,0,0, 0),rgba(0,0,0, 100)),url("/images/about_us_4.webp");
    }
  }
 
}
</style>
