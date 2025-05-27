<template>
  <div class="footer_content">
    <div class="footer_content_data_top">
      <div class="content_data">
        <div class="content_data_item">
          <div class="data_item_top">
            <img src="/images/footer_car.svg"/>
          </div>
          <div class="data_item_bottom" v-html="this.$page.props.language.shipping_yerevan">
            
          </div>
        </div>
        <div class="content_data_item">
          <div class="data_item_top">
            <img src="/images/footer_lock.svg"/>
          </div>
          <div class="data_item_bottom" v-html="this.$page.props.language.secured_payment"></div>
        </div>
        <div class="content_data_item">
          <div class="data_item_top">
            <img src="/images/footer_circle.svg"/>
          </div>
          <div class="data_item_bottom" v-html="this.$page.props.language.return_policy"></div>
        </div>
        <div class="content_data_item">
          <div class="data_item_top" style="    border: 1px solid #939956;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    border-radius: 50%;">
            <img src="/images/phone.svg" style="margin-bottom: 0;"/>
          </div>
          <div class="data_item_bottom" v-html="this.$page.props.language.contact_us"></div>
        </div>
      </div>
    </div>
    <div class="footer_content_data_center">
      <div class="info_data">
        <div class="info_data_item">
          <div class="title"  v-html="this.$page.props.language.contact_us"></div>
          <div class="phone">(010) 534836</div>
          <div class="address" v-html="this.$page.props.language.work_time"></div>
        </div>
        <div class="info_data_item">
          <div class="images">
            <img src="/images/visa.svg"/>
            <img src="/images/mc.svg"/>
            <img src="/images/arca.png"/>
          </div>
        </div>
      </div>
    </div>
     <div class="footer_content_data_bottom">
      <div>{{ this.$page.props.language.follow_us }}</div>
      <a target="_blank" href="https://www.instagram.com/yvesrocherarm1/?hl=en"><img src="/images/instagram.svg"/></a>
      <a target="_blank" href="https://www.facebook.com/p/Yves-Rocher-Armenia-100063517751918/?locale=hy_AM"><img src="/images/facebook.svg"/></a>
      <!-- <a href=""><img src="/images/x.svg"/></a>
      <a href=""><img src="/images/youtube.svg"/></a> -->
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
.footer_content {
  margin-top: 80px;
  .footer_content_data_top{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    background-color: #F0F2E6;
    height: 138px;
    .content_data{
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 1140px;
      .content_data_item{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        .data_item_top{
          img{
            width: 46px;
            height: 46px;
            margin-bottom: 12px;
          }
        }
        .data_item_bottom{

        }
      }
    }
  }
  .footer_content_data_center{
     .info_data{
      display: flex;
      align-items: center;
      justify-content: space-around;
      padding: 50px 0 30px 0;
      background: #F0F0F0;
      .info_data_item{
        background: #F0F0F0;
        border-radius: 5px;
        padding: 25px;
        .title{
          font-size: 14px;
          font-weight: 600;
          margin-bottom: 5px;
          line-height: 100%;
        }
        .phone{
          font-size: 20px;
          line-height: 23px;
          color: #014E2E;
          margin-bottom: 5px;
        }
        .address{
          font-size: 12px;
          font-weight: 400;
        }
        .images{
          display: flex;
          margin-top: 4px;
          align-items: center;
          img{
            margin-left: 18px;
          }
          img:last-child{
            height: 15px;
          }
        }
      }
    }
  }
  .footer_content_data_bottom{
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 35px;
    div{
      font-weight: 600;
      margin-right: 8px;
    }
    img{
      margin-left: 16px;
    }
  }
}
</style>
