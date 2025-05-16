<template>
  <div class="footer_content">
    <div class="footer_content_data_top">
      <div class="content_data">
        <div class="content_data_item">
          <div class="data_item_top">
            <img src="/images/footer_car.svg"/>
          </div>
          <div class="data_item_bottom">
            Shipping in <b>Yerevan </b>
          </div>
        </div>
        <div class="content_data_item">
          <div class="data_item_top">
            <img src="/images/footer_lock.svg"/>
          </div>
          <div class="data_item_bottom">
            <b>Secured</b> payment
          </div>
        </div>
        <div class="content_data_item">
          <div class="data_item_top">
            <img src="/images/footer_circle.svg"/>
          </div>
          <div class="data_item_bottom">
            Return  <b>Policy</b>
          </div>
        </div>
        <div class="content_data_item">
          <div class="data_item_top" style="    border: 1px solid #939956;
    display: flex
;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    border-radius: 50%;">
            <img src="/images/phone.svg" style="margin-bottom: 0;"/>
          </div>
          <div class="data_item_bottom">
            <b>Contact 
            </b>US
          </div>
        </div>
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
}
</style>
