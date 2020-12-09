( function() {
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