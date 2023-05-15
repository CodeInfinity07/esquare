/**
 * @module       App 
 * @author       Junqid Khalid
 * @see          https://code.jquery.com/jquery/
 * @license      MIT (jquery.org/license)
 * @version      3.2.1
 */
 jQuery(function($){

  $('.product--promos').slick({
    dots: false,
    speed: 200,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 4000,
    arrows:false,
   
  });

$('.woocommerce-Price-currencySymbol').html('Rs');
 $('.product--slider').slick({
  dots: false,
  infinite: true,
  speed: 100,
  slidesToShow: 5,
  slidesToScroll: 1,
  autoplay: true,
  arrows: true,
  autoplaySpeed: 2000,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: false,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});



$('.slider-brands').slick({
  dots: false,
  infinite: true,
  speed: 200,
  slidesToShow: 8,
  slidesToScroll: 1,
  autoplay: true,
  arrows: true,
  autoplaySpeed: 2000,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: false,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
$('.product--slider-full-width').slick({
  dots: false,
  infinite: true,
  speed: 100,
  slidesToShow: 6,
  slidesToScroll: 1,
  autoplay: true,
  arrows: true,
  autoplaySpeed: 2000,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 3,
        infinite: true,
        dots: false,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

  // add title to reviews widget on left sidebar
  $('.wp-block-woocommerce-all-reviews').prepend('<h3>Featured Reviews</h3>');
 $('.wp-block-woocommerce-product-categories').prepend('<h3>Product Categories</h3>');

/*
this snippets is developed by Junaid kHalid
Flying cart effect

*/

let count  = $('.count-items').html();

//if add to cart btn clicked
$('.add_to_cart_button').on('click', function (){
  let cart = $('.cart-contents');
  //console.log('button clicked')
  $('.header').addClass('sticky');
  $('header.header').css('transform', 'translateY(0)');
  //find the img of that card which button is clicked by user
  let imgtodrag = $(this).parent('.add-to-cart-container').parent('.product--thumbnail').find("img").eq(0);
  if (imgtodrag) {
    // duplicate the img
    var imgclone = imgtodrag.clone().offset({
      top: imgtodrag.offset().top,
      left: imgtodrag.offset().left
    }).css({
      'opacity': '0.8',
      'position': 'absolute',
      'height': '150px',
      'width': '150px',
      'z-index': '999999999999999999999'
    }).appendTo($('body')).animate({
      'top': cart.offset().top + 20,
      'left': cart.offset().left + 30,
      'width': 75,
      'height': 75
    }, 1000, 'easeInOutExpo');

   
    count++;
    $(".count-items").text(count);
    setTimeout(function(){
      cart.addClass('shake');     
    }, 1000);

    
   
    imgclone.animate({
      'width': 0,
      'height': 0
    }, function(){
      $(this).detach();
      cart.removeClass('shake');
    });
  }
});


/*
Custom dropdown select options

*/
$(document).ready(function(){
  $(".cate-dropdown").each(function(){
      $(this).wrap("<span class='select-wrapper'></span>");
      $(this).after("<span class='holder'></span>");
  });

  $(".cate-dropdown").change(function(){
      var selectedOption = $(this).find(":selected").text();
      $(this).next(".holder").text(selectedOption);
  }).trigger('change');
});

/*
*
* // woocommerce plus minus add to cart
*
*/
 
//  jQuery(function ($) {
//   if (!String.prototype.getDecimals) {
//       String.prototype.getDecimals = function () {
//           var num = this,
//               match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
//           if (!match) {
//               return 0;
//           }
//           return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
//       }
//   }
  // Quantity "plus" and "minus" buttons
//   $(document.body).on('click', '.plus, .minus', function () {
//       var $qty = $(this).closest('.quantity').find('.qty'),
//           currentVal = parseFloat($qty.val()),
//           max = parseFloat($qty.attr('max')),
//           min = parseFloat($qty.attr('min')),
//           step = $qty.attr('step');

//       // Format values
//       if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
//       if (max === '' || max === 'NaN') max = '';
//       if (min === '' || min === 'NaN') min = 0;
//       if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

//       // Change the value
//       if ($(this).is('.plus')) {
//           if (max && (currentVal >= max)) {
//               $qty.val(max);
//           } else {
//               $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
//           }
//       } else {
//           if (min && (currentVal <= min)) {
//               $qty.val(min);
//           } else if (currentVal > 0) {
//               $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
//           }
//       }

//       // Trigger change event
//       $qty.trigger('change');
//   });
// });

function enable_update_cart() {
  $('[name="update_cart"]').removeAttr('disabled');
}

function quantity_step_btn() {
  var timeoutPlus;
  $('.plus').on('click', function() {
      $input = $(this).prev('input.qty');
      var val = parseInt($input.val());
      var step = $input.attr('step');
      step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
      $input.val( val + step ).change();

      if( timeoutPlus != undefined ) {
          clearTimeout(timeoutPlus)
      }
      timeoutPlus = setTimeout(function(){
          $('[name="update_cart"]').trigger('click');
      }, 1000);
  });

  var timeoutMinus;
  $('.minus').on('click', function() {
      $input = $(this).next('input.qty');
      var val = parseInt($input.val());
      var step = $input.attr('step');
      step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
      if (val > 1) {
          $input.val( val - step ).change();
      }

      if( timeoutMinus != undefined ) {
          clearTimeout(timeoutMinus)
      }
      timeoutMinus = setTimeout(function(){
          $('[name="update_cart"]').trigger('click');
      }, 1000);
  });

  var timeoutInput;
  jQuery('div.woocommerce').on('change', '.qty', function(){
      if( timeoutInput != undefined ) {
          clearTimeout(timeoutInput)
      }
      timeoutInput = setTimeout(function(){
          $('[name="update_cart"]').trigger('click');
      }, 1000);
  });
}

$(document).ready(function() {
  enable_update_cart();
  quantity_step_btn();
});

jQuery( document ).on( 'updated_cart_totals', function() {
  enable_update_cart();
  quantity_step_btn();
});
/* 
Custom tabs
*
*/

// Click function
// $('.woocommerce--custom-tabs .nav li').click(function(){
//   $('.woocommerce--custom-tabs .nav li').removeClass('active');
//   $(this).addClass('active');
//   $('.tab-pane').hide();
  
//   var activeTab = $(this).find('a').attr('href');
//   $(activeTab).fadeIn();
//   return false;
// });
// // Show the first tab and hide the rest
// $('.woocommerce--custom-tabs .nav li:first-child').addClass('active');
// // $('.tab-pane').not(":first-child").hide();
// // $('#tab-description').fadeIn();

// $( ".woocommerce--custom-tabs .nav li").trigger('click');
// $( '#tab-review' ).hide();
// $( '#tab-description' ).show();

// Show the first tab and hide the rest
$('#tabs-nav li:first-child').addClass('active');
$('.tab-content').hide();
$('#tab-description').show();


// Click function
$('#tabs-nav li').click(function(){
  $('#tabs-nav li').removeClass('active');
  $(this).addClass('active');
  $('.tab-content').hide();
  
  var activeTab = $(this).find('a').attr('href');
  $(activeTab).fadeIn();
  return false;
});

$('.search-toggle').click(function(){
  $(this).toggleClass('search-close');
  $('.search--box').toggleClass('show');
  
  // $('.search').toggleClass('down');
  // $('nav').removeClass('down');
});
    // var count = $(".navbar--main .navbar--ul li.menu-item-has-children .dropdown-menu").find().length;
    // if (count >= '2'){
    //   console.log('Gratgher then tow chiles are tgere')
    // } else {
    //    console.log('Lesst then tow')
    // }

     // click menu
     $(document).on('click', '.bar-open-menu', function () {
      $(this).toggleClass('active');
      $(this).closest('.main-header').find('.header-nav').toggleClass('show-menu');
      return false;
  });
  // vertical-menu
    $('.vertical-menu-toggle').on('click',function(){
      $('.navbar-vertical-nav').toggleClass('active');
      $(this).$('.navbar-vertical-nav').find('.verticalmenu-content').toggleClass('show-up');
      return false;
  });
  
  $(document).on('click', '.bar-open-menu,.vertical-menu-overlay', function () {
      $('body').toggleClass('vertical-menu-open');
      //return false;
  });

  if ($(window).width() < 1024) {
    $('.navbar--main .navbar--ul>li>.dropdown-menu').hide();
  $('.navbar--main .navbar--ul li.menu-item-has-children a').on('click',function(){
   
    
     
      $(this).next('.navbar--main .navbar--ul>li>.dropdown-menu').slideToggle();
   
    // $(this).$('.navbar-vertical-nav').find('.verticalmenu-content').toggleClass('show-up');
    //return false;
});
}
// humburger menu responsive
$(document).ready(function(){
  $(".humburger").click(function(){
      $(this).toggleClass('active');
       $('.header .navbar--main').toggleClass('show');
  });
});


  $('.navbar--main .navbar--ul > li.menu-item-has-children, .navbar-vertical-nav > ul > li.menu-item-has-children').each(function () {
    $(this).children('a').append('<span class="arrow--nav"><i class="icon-g-07"></i></span>');
});




  $(function () {
   
    var setclass_for_icon = $('.navbar-vertical-nav > ul > li> a').html();
    setclass_for_icon = setclass_for_icon.replace(/\s+/g, '-').toLowerCase();

    $('.navbar-vertical-nav > ul > li> a').addClass(setclass_for_icon);
});

  // $( window ).load(function() {
  // });

//sticky menu
    var prevScrollPositionMENU = window.pageYOffset;

    $(window).on('scroll', function () {
        if ($(window).scrollTop() >= $('header.header').height()) {

            var currentScrollPositionMENU = window.pageYOffset;

            if (prevScrollPositionMENU > currentScrollPositionMENU) {
                $('.header').addClass('sticky');
                $('header.header').css('transform', 'translateY(0)');
            }
            else {
                $('.header').removeClass('sticky');
                $('header.header').css('transform', 'translateY(' + $('header.header').height() * -1 + 'px)');
            }
            prevScrollPositionMENU = currentScrollPositionMENU;
        }
        else {
            $('.header').removeClass('sticky');
        }
        //AOS.refresh();
    });
    

    /*
Whatapp message

*/
// popupWhatsApp = () => {
  
//   let btnClosePopup = document.querySelector('.closePopup');
//   let btnOpenPopup = document.querySelector('.whatsapp-button');
//   let popup = document.querySelector('.popup-whatsapp');
//   let sendBtn = document.getElementById('send-btn');

//   btnClosePopup.addEventListener("click",  () => {
//     popup.classList.toggle('is-active-whatsapp-popup')
//   })
  
//   btnOpenPopup.addEventListener("click",  () => {
//     popup.classList.toggle('is-active-whatsapp-popup')
//      popup.style.animation = "fadeIn .6s 0.0s both";
//   })
  
//   sendBtn.addEventListener("click", () => {
//   let msg = document.getElementById('whats-in').value;
//   let relmsg = msg.replace(/ /g,"%20");
//     //just change the numbers "1515551234567" for your number. Don't use +001-(555)1234567     
//    window.open('https://wa.me/923260099 111?text='+relmsg, '_blank'); 
  
//   });

//   setTimeout(() => {
//     popup.classList.toggle('is-active-whatsapp-popup');
//   }, 3000);
// }

// popupWhatsApp();


// preloader
// $(window).on("load", function() {
//   $(".dots").fadeOut();
//   $(".preloader").delay(2000).fadeOut("slow");
//   $("body").delay(2000).css({
//     "overflow": "visible"
//   });   
// });
    /*
  *
  * Woocommerce Snippets
  *
  *
*/
  // forcefull triger count items in minicart
  
  $(document.body).trigger('wc_fragment_refresh');
  $('.woocommerce-product-gallery__trigger').html('<i class="icon-f-85 open"></i>');




  /**
	 * Open modal and close modal
	 *
	 */
  //  $('.cart-contents').on('click', function(e) {
  //   e.preventDefault();
  //   //$('.modal-cart').toggleClass('is-visible');
  //   $('body').toggleClass('modal-open');
  // });

  $('.cart-contents').on('click', function(e) {
    e.preventDefault();
    $('.modal-cart').fadeIn();
    $('body').addClass('modal-open');
	});
	$('.modal-cart-close').on('click', function(e) {
    $('.modal-cart').fadeOut();
		$('body').removeClass('modal-open');
	})

/*
	 * Remove Cart Item
	 */
 function removeCartItem() {
  var $cartModal = $( '.modal-cart' );

  $cartModal.on( 'click', '.remove', function ( e ) {
    e.preventDefault();
    $cartModal.addClass( 'loading' );
    $( document.body ).block( $cartModal );
    var currentURL = $( this ).attr( 'href' );

    $.get( currentURL, function() {
      $( document.body ).trigger( 'wc_fragment_refresh' );
    }, 'html' );
  } );

  $(document.body).on( 'wc_fragments_refreshed', function() {
    $cartModal.removeClass( 'loading' );
    //sober.unblock( $cartModal );
  } );
};
removeCartItem();


// Live persons view
function randomNumber(){
  let div = document.getElementById('live-number');
  let x = Math.floor((Math.random() * 100) + 10);
  setTimeout(randomNumber, 120000);
  div.innerHTML = x;
  console.log(x);
}
randomNumber();
	 
$("a#menu-item-dropdown-100.shop").attr("href","/shop");	 

  }); // document ready function ends here


 

  // jQuery( document.body ).trigger( 'added_to_cart' , function(){
  //   alert("Payment method selected");
    
  // });


//   //flying cart animation
//   const cards = document.querySelectorAll('.product--thumbnail');
//   const shopping_cart = document.querySelector('.cart-contents');
// const cart_btns = document.querySelectorAll('.add_to_cart_button');

// // Fly To Shopping Cart Effect

// for (cart_btn of cart_btns) {
//   cart_btn.onclick = (e) => {

//       shopping_cart.classList.add('active');

//       let product_count = Number(shopping_cart.getAttribute('data-product-count')) || 0;
//       shopping_cart.setAttribute('data-product-count', product_count + 1);

//       // finding first grand parent of target button 
//       let target_parent = e.target.parentNode.parentNode.parentNode;
//       target_parent.style.zIndex = "100";
//       // Creating separate Image
//       let img = target_parent.querySelector('img');
//       let flying_img = img.cloneNode();
//       flying_img.classList.add('flying-img');

//       target_parent.appendChild(flying_img);

//       // Finding position of flying image

//       const flying_img_pos = flying_img.getBoundingClientRect();
//       const shopping_cart_pos = shopping_cart.getBoundingClientRect();

//       let data = {
//           left: shopping_cart_pos.left - (shopping_cart_pos.width / 2 + flying_img_pos.left + flying_img_pos.width / 2),
//           top: shopping_cart_pos.bottom - flying_img_pos.bottom + 30
//       }

//       console.log(data.top);

//       flying_img.style.cssText = `
//                               --left : ${data.left.toFixed(2)}px;
//                               --top : ${data.top.toFixed(2)}px;
//                               `;


//       setTimeout(() => {
//           target_parent.style.zIndex = "";
//           target_parent.removeChild(flying_img);
//           shopping_cart.classList.remove('active');
//       }, 1000);
//   }
// }



// var parent = document.querySelector('#pa_color'),
//     docFrag = document.createDocumentFragment(),
//     list = document.createElement('ul');

// // build list items
// while(parent.firstChild) {
//   // we simultaniously remove and store the node
//   var option = parent.removeChild(parent.firstChild);

//   // not interested in text nodes at this point
//   if(option.nodeType !== 1) continue;

//   // lets build a list item
//   var listItem = document.createElement('li');

//   // we loop through the properties of the node and
//   // apply only the ones that also exist as atributes
//   for(var i in option) {
//     if(option.hasAttribute(i)) listItem.setAttribute(i, option.getAttribute(i));
//   }

//   // loop through the select children to append to the
//   // list item.  We want text nodes this time.
//   while(option.firstChild) {
//     listItem.appendChild(option.firstChild);
//   }

//   // append them to the document fragment for easier
//   // appending later
//   docFrag.appendChild(listItem);
// }

// // build wrapping ul.  Same as above
// for(var i in parent) {
//   if(parent.hasAttribute(i)) list.setAttribute(i, parent.getAttribute(i));
// }

// // add the list items to the list
// list.appendChild(docFrag);

// // lastly replace the select node with the list
// parent.parentNode.replaceChild(list, parent);