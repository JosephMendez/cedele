( function() {
  jQuery(document).ready(() => {
    jQuery('.hightlight-categories-carousel').owlCarousel({
      items : 6, 
      margin: 30,
      responsiveClass:true,
      dots: true,
      nav: true,
      responsive: {
        0:{
          items:1,
        },
        360: {
          items: 2,
        },
        768:{
          items:3,
        },
        1140:{
          items:6,
        }
      }
    });
  });  
  jQuery(document).ready(() => {
    jQuery('.products.cedele-carousel').owlCarousel({
      items : 4,
      margin: 25, 
      dots: true,
      nav: true,
      responsiveClass:true,
      responsive: {
        0:{
          items:1,
        },
        768: {
          items: 2,
        },
        996:{
          items:3,
        },
        1140:{
          items:4,
        },
      }
    });
  });  
})();