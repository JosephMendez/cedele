<div class="container home-introduce">
    <div class="row">
        <div class="introduce-cedele col-md-12">
            <div class="introduce-cedele-main-text">
                Welcome to Cedele!
            </div>
        </div>
    </div>
    <div class="row home-introduce-content">
        <div class="introduce-cedele-left">
            <pre>At the heart of Cedele, we strive to uphold the ethos "Eat
Well, Be Well". We believe that besides physical
nourishment, the food you eat also affects your whole
well-being.

To serve food that is honest and wholesome, we take the
care to select the freshest natural ingredients to craft our
food, giving you the peace of mind when you dine. Our
baked goods are sweetened with organic unrefined sugar
and we do not use artificial, processed ingredients such as
food colouring, improvers or premixes. We also thicken our
soups naturally with vegetables.</pre>

        <div class="introduce-cedele-left-button mt-5">
            <button type="submit" class="btn btn-primary btn-shadow btn-login btn-lg w-100" name="learn-more" value="Learnmore">
                    Learn More
                </button>
        </div>
        </div>
        <div class="introduce-cedele-right d-flex">
            <div class="introduce-cedele-right-left">
                <div class="introduce-img-top">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/welcome-top.svg"/>
                </div>
                <div class="introduce-img-bottom">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/welcome-bottom.png"/>
                </div>
            </div>
            <div class="introduce-cedele-right-right">
                <div class="introduce-img-right">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/welcome-right.svg"/>
                </div>
            </div>
        </div>
    </div>
</div>

<style lang="css">
    .introduce-cedele-main-text {
        /* Heading/H3 */
        font-family: 'Gotham Regular';
        font-style: normal;
        font-weight: bold;
        font-size: 30px;
        line-height: 46px;
        letter-spacing: 0.005em;
        color: #3C1605;
    }

    .home-introduce-content {
        margin-left: 0;
        margin-top: 50px;
    }

    .introduce-cedele-left {
        width: 43%;
    }

    .introduce-cedele-left pre {
        font-family: Plantin MT Pro;
        font-style: normal;
        font-weight: normal;
        font-size: 13px;
        line-height: 24px;

        /* or 150% */
        letter-spacing: 0.44px;

        /* Grayshade/3f3f3f */
        color: #3F3F3F;
    }

    .introduce-cedele-right {
        width: 57%;
    }

    .introduce-img-bottom {
        margin-top: 30px;
    }

    .introduce-img-bottom {
        margin-top: 30px;
    }

    .introduce-cedele-right-right {
        margin-left: auto;
    }

    .introduce-cedele-left-button {
        width: 153px;
    }

    .introduce-cedele-left-button button {
        font-family: 'Gotham Regular',-apple-system,Roboto,Arial;
        font-size: 1.125rem;
        text-transform: uppercase;
    }

    .carousel-slider .fadeInRight {
        animation-name: none;
    }

    .carousel-slider-hero__cell__button__one {
        background: #914204;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        border-radius: 8px;
        height: 100%;
        width: 100%;
    }

    .carousel-slider-hero__cell__buttons {
        width: 152px;
        height: 49px;
        margin-left: 212px;
        display: flex;
    }

    .carousel-slider-hero__cell__buttons > span {
        width: 153px;
    }

    .text-order {
        height: 100%;
        border: none;
        font-size: 18px;
        font-style: normal;
        font-weight: bold;
        font-size: 18px;
        line-height: 17px;
        /* identical to box height */

        text-align: center;
        letter-spacing: 0.75px;
        text-transform: uppercase;
    }

    .text-order-link {
        width: inherit;
        border: none!important;
        font-family: 'Gotham Regular',-apple-system,Roboto,Arial;
        font-style: normal;
        font-weight: bold;
        font-size: 18px;
        line-height: 17px;
        /* identical to box height */

        text-align: center;
        letter-spacing: 0.75px;
        text-transform: uppercase;

        /* Grayshade/White */

        color: #FFFFFF!important;

        /* Inside Auto Layout */

        flex: none;
        order: 0;
        align-self: center;
        margin: 10px 0px;
    }

    .text-order a:hover {
        background-color: transparent !important;
    }

    .owl-dot > span {
        background-color: #fff!important;
    }

    .owl-dot.active > span {
        background-color: #914204!important;
    }

    .carousel-slider-hero__cell__content {
        position: absolute;
    }

    @media only screen and (min-width: 1366px) {
        .carousel-slider-hero__cell__buttons {
            margin-left: 195px;
        }
    }

    @media only screen and (min-width: 1440px) {
        .carousel-slider-hero__cell__buttons {
            margin-left: 173px;
        }
    }

    @media only screen and (min-width: 1600px) {
        .carousel-slider-hero__cell__buttons {
            margin-left: 120px;
        }
    }

    @media only screen and (min-width: 1920px) {
        .carousel-slider-hero__cell__buttons {
            margin-left: 40px;
        }
    }
</style>

<script>
    jQuery(document).ready(function() {
        jQuery('.carousel-slider-hero__cell__button__one').addClass('text-order');
        jQuery('.text-order a').addClass('text-order-link');
    })
</script>
