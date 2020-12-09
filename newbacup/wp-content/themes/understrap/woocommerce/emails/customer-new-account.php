<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8 ;">
	<meta http-equiv="Content-Security-Policy" content="default-src *;
   img-src * 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;
   style-src  'self' 'unsafe-inline' *;font-src 'self';">
    <style>
        body, html {
            padding: 0;
            margin: 0;
        }
        @font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Light.eot');
			src: url('fonts/Gotham-Light.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Light.woff2') format('woff2'),
			url('fonts/Gotham-Light.woff') format('woff'),
			url('fonts/Gotham-Light.ttf') format('truetype'),
			url('fonts/Gotham-Light.svg#Gotham-Light') format('svg');
			font-weight: 300;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Book.eot');
			src: url('fonts/Gotham-Book.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Book.woff2') format('woff2'),
			url('fonts/Gotham-Book.woff') format('woff'),
			url('fonts/Gotham-Book.ttf') format('truetype'),
			url('fonts/Gotham-Book.svg#Gotham-Book') format('svg');
			font-weight: normal;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Medium.eot');
			src: url('fonts/Gotham-Medium.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Medium.woff2') format('woff2'),
			url('fonts/Gotham-Medium.woff') format('woff'),
			url('fonts/Gotham-Medium.ttf') format('truetype'),
			url('fonts/Gotham-Medium.svg#Gotham-Medium') format('svg');
			font-weight: 500;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Bold.eot');
			src: url('fonts/Gotham-Bold.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Bold.woff2') format('woff2'),
			url('fonts/Gotham-Bold.woff') format('woff'),
			url('fonts/Gotham-Bold.ttf') format('truetype'),
			url('fonts/Gotham-Bold.svg#Gotham-Bold') format('svg');
			font-weight: bold;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Bold';
			src: url('fonts/Gotham-Bold.eot');
			src: url('fonts/Gotham-Bold.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Bold.woff2') format('woff2'),
			url('fonts/Gotham-Bold.woff') format('woff'),
			url('fonts/Gotham-Bold.ttf') format('truetype'),
			url('fonts/Gotham-Bold.svg#Gotham-Bold') format('svg');
			font-weight: bold;
			font-style: normal;
			font-display: swap;
		}
        .wpmail-container {
            width: 100%;
            background-color: #F3F3F3;
        }
        .wpmail-container p,
        .wpmail-container h5 {
            margin: 0px;
        }

        /* HEADER */
        .wpmail-logo {
            padding: 8px 0px 16px 0px;
            text-align: center;
        }
        .wpmail-logo img {
            width: 174px;
            height: 76px;
        }
        .wpmail-line {
            display: block;
            position: relative;
        }
        .wpmail-line:before {
            position: absolute;
            content: "";
            width: 370px;
            border-top: 1px solid #914204;
            margin-left: -185px;
        }

        /* CONTENT */
        .wpmail-content {
            position: relative;
            padding: 24px;
            width: 568px;
            height: 519px;
            margin: auto;
            background-color: #FFFFFF;
            border-radius: 4px;
            box-shadow: 0 4px 4px rgba(0,0,0,.25);
            box-sizing: border-box;
        }
        .wpmail-content .wpmail-text-header {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-weight: bold;
            font-size: 24px;
            line-height: 23px;
            text-align: center;
            letter-spacing: 0.5px;
            color: #3F3F3F;
            margin-bottom: 8px;
        }
        .wpmail-content .wpmail-text-header span {
            color: #3C1605;
        }
        .wpmail-content .wpmail-pharse-title {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            letter-spacing: 0.15px;
            color: #3C1605;
        }
        .wpmail-content .wpmail-text-regular {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 22px;
            letter-spacing: -0.25px;
            color: #3F3F3F;
        }
        /* discount */
        .wpmail-discount {
            height: 36px;
            margin-bottom: 36px;
            line-height: 36px;
        }
        .wpmail-discount-sub {
            width: 180px;
            height: 36px;
            border: 1px solid #F3F3F3;
            box-sizing: border-box;
            border-radius: 4px;
            overflow: hidden;
            margin: auto;
            display: flex;
        }
        .wpmail-discount-sub-icon {
            width: 36px;
            height: 36px;
            background-color: #AD8850;
        }

        .wpmail-discount-sub-text {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: bold;
            text-align: center;
            letter-spacing: 0.75px;
            text-transform: uppercase;
            color: #914204;
            font-size: 16px;
            width: 100%;
            line-height: 36px;
        }
        .wpmail-shopping {
            text-align: center;
            z-index: 999;
            position: relative
        }
        .wpmail-button-shopping-text {
            margin: 24px auto;
            width: 181px;
            height: 49px;
            /*padding: 16px 31px;*/
            padding: 16px 4px;
            /* Primary/Brown */
            background: #914204;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 4px;
            box-sizing: border-box;
            color: white !important;
            text-decoration: none;
            cursor: pointer;
            display: block;
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-weight: bold;
            font-size: 16px;
            line-height: 17px;
            /* identical to box height */
            letter-spacing: 0.75px;
            text-transform: uppercase;
        }
        .wpmail-section-footerbg {
            position: absolute;
            bottom: 0px;
            width: 100%;
            height: 139px;
            margin: 0px -24px;
            background-image: url(<?php echo get_template_directory_uri(); ?>/images/emails/fruits.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: 0;
        }

        /* Footer */
        .wpmail-footer {
            text-align: center;
            padding: 58px 0px 36px 0px;
        }
        .wpmail-followus {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            /* identical to box height, or 140% */
            text-align: center;
            letter-spacing: 0.15px;
            margin-bottom: 12px !important;
            color: #000000;
        }
         .wpmail-social {
            width: 140px;
            display: inline-block;
            height: 36px;
            margin-bottom: 16px;
        }
		.wpmail-social a{
			float: left;
			padding: 0px 5px;
		}
        .wpmail-info {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-style: normal;
            font-weight: 300;
            font-size: 12px;
            line-height: 16px;
            text-align: center;
            color: #3F3F3F;
        }
        .wpmail-info-fax {
            margin-left: 37px;
        }
		a{
			color: #fff;
		}
    </style>
</head>
<body>
    <div class="wpmail-container">
        <div class="wpmail-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/images/emails/logo.png">
            <span class="wpmail-line"></span>
        </div>
        <div class="wpmail-content">
            <p class="wpmail-text-header">Hi <span><?php echo $firstname; ?>,</span></p>
            <p class="wpmail-text-header" style="margin-bottom: 24px;">Welcome to Cedele!</p>
            <p class="wpmail-text-regular"  style="margin-bottom: 16px;">Your account registration has been confirmed.</p>
            <p class="wpmail-text-regular"  style="margin-bottom: 16px;">You’ll be the first to know about our freshest bakes and artisanal food, and have exclusive discounts and deals delivered straight to you!</p>
<!--            <p class="wpmail-pharse-title">Here’s a treat for you</p>-->
            <p class="wpmail-text-regular" style="margin-bottom: 24px;">Ready to order? Please log in for faster checkout. Happy shopping!</p>
<!--            <div class="wpmail-discount">-->
<!--                <div class="wpmail-discount-sub">-->
<!--                    <div class="wpmail-discount-sub-icon">-->
<!--                        <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                            <g clip-path="url(#clip0)">-->
<!--                            <path d="M14.1716 9.92893L15.5858 11.3431" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
<!--                            <path d="M18.4142 14.1716L19.8284 15.5858" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
<!--                            <path d="M22.6569 18.4142L24.0711 19.8284" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
<!--                            <path d="M7.1005 17L17 7.10051C17.3751 6.72543 17.8838 6.51472 18.4142 6.51472C18.9446 6.51472 19.4533 6.72543 19.8284 7.10051L21.9497 9.22183C21.5747 9.5969 21.364 10.1056 21.364 10.636C21.364 11.1665 21.5747 11.6752 21.9497 12.0503C22.3248 12.4253 22.8335 12.636 23.364 12.636C23.8944 12.636 24.4031 12.4253 24.7782 12.0503L26.8995 14.1716C27.2746 14.5466 27.4853 15.0554 27.4853 15.5858C27.4853 16.1162 27.2746 16.6249 26.8995 17L17 26.8995C16.6249 27.2746 16.1162 27.4853 15.5858 27.4853C15.0553 27.4853 14.5466 27.2746 14.1716 26.8995L12.0502 24.7782C12.4253 24.4031 12.636 23.8944 12.636 23.364C12.636 22.8335 12.4253 22.3248 12.0502 21.9497C11.6752 21.5747 11.1665 21.364 10.636 21.364C10.1056 21.364 9.59689 21.5747 9.22182 21.9497L7.1005 19.8284C6.72543 19.4534 6.51471 18.9446 6.51471 18.4142C6.51471 17.8838 6.72543 17.3751 7.1005 17" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
<!--                            </g>-->
<!--                            <defs>-->
<!--                            <clipPath id="clip0">-->
<!--                            <rect width="24" height="24" fill="white" transform="translate(0.0294342 17) rotate(-45)"/>-->
<!--                            </clipPath>-->
<!--                            </defs>-->
<!--                        </svg>-->
<!--                    </div>-->
<!--                    <div class="wpmail-discount-sub-text">WELCOME10</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <p class="wpmail-pharse-title">Ready to order?</p>-->
<!--            <p class="wpmail-text-regular" style="margin-bottom: 24px;">-->
<!--				Enter your promo code at checkout and enjoy the goodness of Cedele!-->
<!--			</p>-->
            <div class="wpmail-shopping">
                <a class="wpmail-button-shopping-text" target="_blank" href="<?php echo get_permalink(woocommerce_get_page_id('shop')); ?>">SHOP NOW</a>
            </div>
            <div class="wpmail-section-footerbg"></div>
        </div>
        <div class="wpmail-footer">
            <p class="wpmail-followus">Follow Us</p>
            <div class="wpmail-social">
                <a href="https://www.facebook.com/cedelesingapore" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/emails/social1.png">
                </a>
                <a href="https://www.instagram.com/cedelesingapore" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/emails/social2.png">
                </a>
				<a href="https://t.me/cedeletelegram" target="_blank">
					<img src="<?php echo get_template_directory_uri(); ?>/images/emails/social3.png">
				</a>
            </div>
            <p class="wpmail-info">1 Kaki Bukit Road 1, #02-41, Enterprise One, Singapore 415934</p>
            <p class="wpmail-info">
                Tel: 6922 9700 <span class="wpmail-info-fax">Fax: 6448 0035</span>
            </p>
        </div>
    </div>
</body>
</html>
